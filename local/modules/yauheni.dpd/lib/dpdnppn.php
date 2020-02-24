<?php

namespace Yauheni\Dpd;
use \Bitrix\Sale;
class dpdNppN extends dpdBase
{
    public $ID_CITY = 674; //Беларусь, обл Минская, Минский, г Минск
    public $PICKUP_DATE= '';

    public function Run($arOrderParams){
        //устонавливаем адрес отпровителя ID_CITY из таблицы location
        $this->setShipmentSender($this->ID_CITY);
        //получаем адрес получателя через яндекс
        $arAddres = $this->getYandexAddress($arOrderParams['RESPONSE_YANDEX']);
        if ($arAddres['ID']) {
            //устонавливаем адрес получателя  ID_CITY
            $this->setShipmentReceiver($arAddres['ID']);
        }
        //Отправка от (false - от двери , true  - от терминала)
        $this->setShipmentSelfPickup(true);
        //Отправка до (false - от двери , true  - от терминала)
        $this->setShipmentSelfDelivery(false);
        //устонавливаем данные items для DPD
        $this->ITEMS = [
            'NAME' => 'косметические товары',
            'QUANTITY' => 1,
            'PRICE' => $arOrderParams['PRICE'],
            'VAT_RATE' => 'Без НДС',
            'WEIGHT' => $arOrderParams['ORDER_WEIGHT'] <= 1000 ? 1000 : $arOrderParams['ORDER_WEIGHT'],
        ];
        //init данных item в ob_shipment
        $this->setShipmentItems();
        //ob_shipment пехнём в ob_order
        $this->OB_ORDER->setShipment($this->OB_SHIPMENT);
        // -------------------- Отправитель -----------------------------------------------------------
        $this->SENDER = [
            'NAME' => 'ООО "Сэльвин-Логистик"',
            'FIO' => 'Березнева Анна Александровна',
            'PHONE' => '+375447480824',
            'STREET' => 'Купревича',
            'HOUSE' => '28',
            'KORPUS' => '2',
            'OFFICE' => '40',
            'NEED_PASS' => 'Y',
        ];
        //init данных отправителя в ob_order
        $this->setSenderOrder();
        //---------------------- Получатель -----------------------------------------------------------
        $ROOM = $arOrderParams['ROOM'] ? ', кв. ' . $arOrderParams['ROOM'] : '';
        $this->RECEIVER = [
            'NAME' => $arOrderParams['FIO'],
            'FIO' => $arOrderParams['FIO'],
            'PHONE' => $arOrderParams['PHONE'],
            'STREET' => $this->YANDEX['Components']['street'], //улица
            'HOUSE' => $this->YANDEX['Components']['house'] . $ROOM, //номер дома,
        ];
        //init данных получатель в ob_order
        $this->setReceiverOrder();
        //---------------------------------------------------------------------------------------------
        $OPTIONS = [
            'PICKUP_DATE' => $this->PICKUP_DATE ? $this->PICKUP_DATE : date('Y-m-d'),
            'PICKUP_TIME_PERIOD' => '9-18',
            'SERVICE_CODE' => 'CSM',
            'SERVICE_VARIANT' => 'ТД',
            'SENDER_TERMINAL_CODE' => 'MSQ',
            'ORDER_ID' => $arOrderParams['ID'],
            'CARGO_CATEGORY' => 'косметические товары',
        ];
        if(!empty($this->ORDER_OPTIONS)){
            $this->ORDER_OPTIONS = array_merge($this->ORDER_OPTIONS, $OPTIONS);
        }else{
            $this->ORDER_OPTIONS = $OPTIONS;
        }
        //устонавливаем дополнительные пораметры для ob_order
        $this->setOptionsOrder();
        //create order в dpd
        $result = $this->createOrderDpd();
        return $result;
    }
}