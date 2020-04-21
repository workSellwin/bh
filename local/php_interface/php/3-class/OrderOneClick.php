<?php

use Bitrix\Main\Context;
use Bitrix\Sale;

class OrderOneClick
{
    protected $name;
    protected $phone;
    protected $city;
    protected $freeDeliveryLimit = 30;
    protected $errors = [];
    protected $order = false;
    protected $basket = false;

    /**
     * OrderOneClick constructor.
     */
    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");
    }

    /**
     * @param $id
     * @param $name
     * @param $phone
     * @return mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     */
    public function Order($id, $name, $phone, $city = 'Minsk', $quantity = 1)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->city = mb_strtolower($city);

        $this->isUser();
        $basket = $this->CreateBasket([$this->GetItem($id, $quantity)]);
        $deliveryId = $this->getDeliveryId($basket);
        $order = $this->AddOrder($basket);
        $this->order = $order;
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(
            \Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)
        );
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        /** @var \Bitrix\Sale\BasketItem $basketItem */
        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        // Создание оплаты
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem(
            \Bitrix\Sale\PaySystem\Manager::getObjectById(1)
        );
        $payment->setField("SUM", $order->getPrice());
        $payment->setField("CURRENCY", $order->getCurrency());
        $result = $order->save();
        //return $result;
        return $order->getId();
    }

    public function fastOrder(array $param)
    {
        if( empty($param['name']) || empty($param['phone']) ){
            return false;
        }
        $this->name = $param['name'];
        $this->phone = $param['phone'];
        
        if( empty($param['city']) ){
            $this->city = 'Minsk';
        }
        else{
            $this->city = mb_strtolower($param['city']);
        }

        try{
            $this->isUser();
            $basket = $this->getBasket();
            $deliveryId = $this->getDeliveryId($basket);
            $order = $this->AddOrder($basket);
            $this->order = $order;
            $shipmentCollection = $order->getShipmentCollection();
            $shipment = $shipmentCollection->createItem(
                \Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId)
            );

            $shipmentItemCollection = $shipment->getShipmentItemCollection();
            /** @var \Bitrix\Sale\BasketItem $basketItem */
            foreach ($basket as $basketItem) {
                $item = $shipmentItemCollection->createItem($basketItem);
                $item->setQuantity($basketItem->getQuantity());
            }

            // Создание оплаты
            $paymentCollection = $order->getPaymentCollection();
            $payment = $paymentCollection->createItem(
                \Bitrix\Sale\PaySystem\Manager::getObjectById(1)
            );
            $payment->setField("SUM", $order->getPrice());
            $payment->setField("CURRENCY", $order->getCurrency());
            $result = $order->save();
            return $order->getId();

        } catch (Exception $e){
            $this->errors[] = $e;
            return false;
        }
    }

    public function getBasketSum(){

        try {

            $basket = $this->getBasket();
            return $basket->getPrice();

        } catch (Exception $e) {

            $this->errors[] = $e;
            return false;
        }
    }

    public function getOrderSum(){
        if(!empty($this->order)){
            return $this->order->getPrice();
        }
        else{
            return 0;
        }
    }

    /**
     * @param $basket
     * @return \Bitrix\Sale\Order
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    protected function AddOrder($basket)
    {
        global $USER;

        $site = SITE_ID !== 's1' ? 's2' : 's1';//для админки

        $userId = $USER->GetID();
        $order = \Bitrix\Sale\Order::create($site, $userId);
        $order->setPersonTypeId(1);
        $order->setField('USER_DESCRIPTION', 'Покупка в 1 клик');
        $propertyCollection = $order->getPropertyCollection();
        $phonePropValue = $propertyCollection->getPhone();
        $phonePropValue->setValue($this->phone);
        $order->setBasket($basket);
        return $order;
    }

    /**
     *
     */
    protected function isUser()
    {
        global $USER;
        if (!is_object($USER) or !$USER->GetID()) {
            $id = $this->AddUser($this->name, $this->phone);
            $USER->Authorize($id);
        }
    }

    /**
     * @param $id
     * @param int $quantity
     * @return array
     */
    protected function GetItem($id, $quantity = 1)
    {
        global $USER;
        $arPrice = CCatalogProduct::GetOptimalPrice($id, $quantity, $USER->GetUserGroupArray());
        $res = \CIBlockElement::GetByID($id);
        $ar_res = $res->GetNext();
        $arItem = [
            'PRODUCT_ID' => $id,
            'NAME' => $ar_res['NAME'],
            'PRICE' => $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'],
            'CURRENCY' => 'BYN',
            'QUANTITY' => $quantity
        ];
        return $arItem;
    }

    protected function GetItems(){

        return [];


    }

    protected function getBasket(){

        if(!empty($this->basket)){
            return $this->basket;
        }

        $siteId = Bitrix\Main\Context::getCurrent()->getSite();

        if(!in_array($siteId, ['s1', 's2'])){//для админки
            $siteId = 's1';
        }
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);

        if(empty($basket)){
            throw new Exception('basket empty');
        }

        return $basket;
    }

    /**
     * @param $name
     * @param $phone
     * @return mixed
     */
    protected function AddUser($name, $phone)
    {
//        $tempId = Sale\Fuser::getId();//if the f_user is already substantial
//
//        if(intval($tempId) > 0){
//            $ID = $tempId;
//        }
//        else {
            $new_password = randString(7);
            $email = randString(6);
            $user = new \CUser;
            $email = $email . '@gmail.com';
            $arFields = Array(
                "NAME" => $name,
                "EMAIL" => $email,
                "LOGIN" => $email,
                "PHONE" => $phone,
                "ACTIVE" => "Y",
                "GROUP_ID" => array(3, 5, 4),
                "PASSWORD" => $new_password,
                "CONFIRM_PASSWORD" => $new_password,
            );
            $ID = $user->Add($arFields);
        //}
        return $ID;
    }

    /**
     * BASKET
     */

    /**
     * @param $arItems
     * @return mixed
     */
    protected function CreateBasket($arItems)
    {
        // Создаем и наполняем корзину
        $basket = \Bitrix\Sale\Basket::create(SITE_ID);
        foreach ($arItems as $i => $arItem) {
            $basketItem = $basket->createItem("catalog", $arItem['PRODUCT_ID']);
            $basketItem->setFields($arItem);
        }
        /*global $USER;
        if ($USER->IsAdmin()) {
            PR($basket);
            die();
        }*/
        return $basket;
    }

    protected function getDeliveryId(\Bitrix\Sale\BasketBase $basket){

        $sum = $basket->getPrice();

        if($this->city == 'minsk' || $this->city == 'минск'){
            if($this->freeDeliveryLimit > $sum){
                return 7;
            }
            else{
                return 2;
            }
        }
        else{
            if($this->freeDeliveryLimit > $sum){
                return 9;
            }
            else{
                return 19;
            }
        }
    }
}