<?php

namespace Yauheni\Dpd;
use \Bitrix\Sale;
class dpdBase
{

    public $OPTIONS=[
        'KLIENT_NUMBER'   => '',
        'KLIENT_NUMBER_BY'   => '',
        'KLIENT_KEY'      => '',
        'KLIENT_CURRENCY' => 'BYN',
        'IS_TEST'         => false,
        'DB' => [    'DSN' => 'mysql:dbname=sitemanager0;host=localhost',
            'PASSWORD' => 'W-)xph?L2g89PBnzygSk',
            'USERNAME' => 'bitrix0',
        ],
    ];
    public $ITEMS = [
        'NAME' => '',
        'QUANTITY' => '',
        'PRICE' => '',
        'NPP' =>  '',
        'VAT_RATE' => '',
        'WEIGHT' => '',
    ];
    public $SENDER=[
        'NAME' => '', //Название компании
        'FIO' => '', //Контактные лица
        'PHONE' => '', //Контактные телефоны
        'STREET' => '', //Улица
        'HOUSE' => '', //номер дома
        'KORPUS' => '',  //корпус
        'OFFICE' => '', //Офис
        'NEED_PASS' => '', //Пропуск (Y , N)
    ];
    public $RECEIVER = [
        'NAME' => '',
        'FIO' => '',
        'PHONE' => '',
        'STREET' => '',
        'HOUSE' => '',
    ];
    public $ORDER_OPTIONS = [
        'PICKUP_DATE' => '',
        'PICKUP_TIME_PERIOD' => '',
        'SERVICE_CODE' => '',
        'SERVICE_VARIANT' => '',
        'SENDER_TERMINAL_CODE' => '',
        'ORDER_ID' => '',
        'CARGO_CATEGORY' => '',
    ];
    public $OB_CONFIG;
    public $OB_SHIPMENT;
    public $OB_ORDER;
    public $YANDEX;
    /**
     * Устоновить KLIENT_NUMBER и KLIENT_KEY
     * @param $KLIENT_NUMBER
     * @param $KLIENT_KEY
     */
    public function __construct($KLIENT_NUMBER, $KLIENT_KEY)
    {
        $this->OPTIONS['KLIENT_NUMBER'] = $KLIENT_NUMBER;
        $this->OPTIONS['KLIENT_NUMBER_BY'] = $KLIENT_NUMBER;
        $this->OPTIONS['KLIENT_KEY'] = $KLIENT_KEY;
        $this->setOb();
    }

    public function setOb(){
        $this->OB_CONFIG = new \Ipol\DPD\Config\Config($this->OPTIONS);
        $this->OB_SHIPMENT = new \Ipol\DPD\Shipment($this->OB_CONFIG);
        $this->OB_ORDER = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('order')->makeModel();
    }

    public function setShipmentSender($SENDER_CITY_ID){
        $this->OB_SHIPMENT->setSender($SENDER_CITY_ID);
    }

    public function setShipmentReceiver($RECEIVER_CITY_ID){
        $this->OB_SHIPMENT->setReceiver($RECEIVER_CITY_ID);
    }

    public function setShipmentSelfPickup($SELF_PICKUP = false){
        $this->OB_SHIPMENT->setSelfPickup($SELF_PICKUP);
    }

    public function setShipmentSelfDelivery($SELF_DELIVERY = false){
        $this->OB_SHIPMENT->setSelfDelivery($SELF_DELIVERY);
    }

    public function setShipmentItems(){
        $this->OB_SHIPMENT->setItems([$this->ITEMS]);
    }

    public function getYandexAddress($q){
        define('yandex_apikey', '91227d27-4cba-45e6-9179-7f0dc075d31d');
        $ob = new \Lui\Delivery\YandexApi();
        $arYandex = $ob->GetDataYandex($q);
        $this->YANDEX = $arYandex;
        $arAddres = $this->OB_SHIPMENT->getDB()->getTable('location')->getAddress($arYandex);
        return $arAddres;
    }

    public function setSenderOrder (){
        foreach ($this->SENDER as $key => $value){
            switch ($key){
                case 'NAME';
                    $this->OB_ORDER->senderName = $value;
                    break;
                case 'FIO';
                    $this->OB_ORDER->senderFio = $value;
                    break;
                case 'PHONE';
                    $this->OB_ORDER->senderPhone = $value;
                    break;
                case 'STREET';
                    $this->OB_ORDER->senderStreet = $value;
                    break;
                case 'HOUSE';
                    $this->OB_ORDER->senderHouse = $value;
                    break;
                case 'KORPUS';
                    $this->OB_ORDER->senderKorpus = $value;
                    break;
                case 'OFFICE';
                    $this->OB_ORDER->senderOffice = $value;
                    break;
                case 'NEED_PASS';
                    $this->OB_ORDER->senderNeedPass = $value;
                    break;
            }
        }
    }

    public function setReceiverOrder (){
        foreach ($this->RECEIVER as $key => $value){
            switch ($key){
                case 'NAME';
                    $this->OB_ORDER->receiverName = $value;
                    break;
                case 'FIO';
                    $this->OB_ORDER->receiverFio = $value;
                    break;
                case 'PHONE';
                    $this->OB_ORDER->receiverPhone = $value;
                    break;
                case 'STREET';
                    $this->OB_ORDER->receiverStreet = $value;
                    break;
                case 'HOUSE';
                    $this->OB_ORDER->receiverHouse = $value;
                    break;
            }
        }
    }

    public function setOptionsOrder(){
        foreach ($this->ORDER_OPTIONS as $key => $value){
            switch ($key){
                case 'PICKUP_DATE';
                    $this->OB_ORDER->pickupDate = $value;
                    break;
                case 'PICKUP_TIME_PERIOD';
                    $this->OB_ORDER->pickupTimePeriod = $value;
                    break;
                case 'SERVICE_CODE';
                    $this->OB_ORDER->serviceCode = $value;
                    break;
                case 'SERVICE_VARIANT';
                    $this->OB_ORDER->serviceVariant = $value;
                    break;
                case 'SENDER_TERMINAL_CODE';
                    $this->OB_ORDER->senderTerminalCode = $value;
                    break;
                case 'ORDER_ID';
                    $this->OB_ORDER->orderId = $value;
                    break;
                case 'CARGO_CATEGORY';
                    $this->OB_ORDER->cargoCategory = $value;
                    break;
                case 'SET_NPP'; //включить НПП
                    $this->OB_ORDER->setNpp($value);
                    break;
                case 'PRICE_DELIVERY';
                    $this->OB_ORDER->setPriceDelivery($value);
                    break;
                case 'USE_CARGO_VALUE';
                    $this->OB_ORDER->setUseCargoValue($value);
                    break;
            }
        }
    }

    public function createOrderDpd(){
        $result = $this->OB_ORDER->dpd()->create();
        return $result;
    }

}