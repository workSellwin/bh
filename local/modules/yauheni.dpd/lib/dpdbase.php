<?php

namespace Yauheni\Dpd;
use \Bitrix\Sale;
class dpdBase
{

    public $OPTIONS=[
        'KLIENT_NUMBER'   => '1104009121',
        'KLIENT_NUMBER_BY'   => '1104009121',
        'KLIENT_KEY'      => 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF',
        'KLIENT_CURRENCY' => 'BYN',
        'IS_TEST'         => false,
        'DB' => [
            'DSN' => 'mysql:dbname=sitemanager0;host=localhost',
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
        'STREET' => '', //улица
        'HOUSE' => '', //номер дома,
        'KORPUS' => '',
        'STR' => '',
        'FLAT' =>'',
    ];
    public $ORDER_OPTIONS = [
        'PICKUP_DATE' => '',
        'PICKUP_TIME_PERIOD' => '',
        'SERVICE_CODE' => '',
        'SERVICE_VARIANT' => '',
        'SENDER_TERMINAL_CODE' => '',
        'RECEIVER_TERMINAL_CODE' => '',
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
    public function __construct($KLIENT_NUMBER = false, $KLIENT_KEY = false)
    {
        if($KLIENT_NUMBER){
            $this->OPTIONS['KLIENT_NUMBER'] =  $KLIENT_NUMBER;
            $this->OPTIONS['KLIENT_NUMBER_BY'] = $KLIENT_NUMBER;
        }
        if($KLIENT_KEY){
            $this->OPTIONS['KLIENT_KEY'] = $KLIENT_KEY;
        }
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
            switch ($key) {
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
                case 'KORPUS';
                    $this->OB_ORDER->receiverKorpus = $value;
                    break;
                case 'HOUSE';
                    $this->OB_ORDER->receiverHouse = $value;
                    break;
                case 'STR';
                    $this->OB_ORDER->receiverStr = $value;
                    break;
                case 'FLAT';
                    $this->OB_ORDER->receiverFlat = $value;
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
                case 'RECEIVER_TERMINAL_CODE';
                    $this->OB_ORDER->receiverTerminalCode = $value;
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

    public function loadingTerminal(){
        //$connection = \Bitrix\Main\Application::getConnection();
        //$connection->dropTable('b_ipol_dpd_order');

        $table   = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('terminal');
        $api     = \Ipol\DPD\API\User\User::getInstanceByConfig($this->OB_CONFIG);
        $loader  = new \Ipol\DPD\DB\Terminal\Agent($api, $table);
        $step    = isset($_GET['step']) ? $_GET['step'] : 1;
        $pos     = isset($_GET['pos'])  ? $_GET['pos']  : 'BY:0';

        if ($step < 2) {
            $ret = $loader->loadLimited($pos);
            if ($ret === true) {
                print 'LOAD TERMINALS STEP 1: FINISH';
                print '<a href="?LOUD_TAB=edit6&step=2" id="continue">continue</a><br>';
                print '<script>document.getElementById("continue").click()</script>';
            } else {
                print sprintf('LOAD TERMINALS STEP 1: %s%%<br>', round($ret[0] / $ret[1] * 100));
                print '<a href="?LOUD_TAB=edit6&step=1&pos='. $ret[0] .'" id="continue">continue</a><br>';
                print '<script>document.getElementById("continue").click()</script>';
            }
        } elseif ($step < 3) {
            $ret = $loader->loadLimited($pos);

            if ($ret === true) {
                print 'LOAD TERMINALS STEP 2: FINISH';
            } else {
                $pos = explode(':', $ret[0]);

                print sprintf('LOAD TERMINALS STEP 2: %s%%<br>', round(end($pos) / $ret[1] * 100));
                print '<a href="?LOUD_TAB=edit6&step=2&pos='. $ret[0] .'" id="continue">continue</a><br>';
                print '<script>document.getElementById("continue").click()</script>';
            }
        }
    }

    public function loadingLocation(){
        $table   = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('location');
        $api     = \Ipol\DPD\API\User\User::getInstanceByConfig($this->OB_CONFIG);
        $loader  = new \Ipol\DPD\DB\Location\Agent($api, $table);
        $step    = isset($_GET['step']) ? $_GET['step'] : 1;
        $pos     = isset($_GET['pos']) ? $_GET['pos'] : null;

        if ($step < 2) {
            $ret = $loader->loadAll($pos, ['BY']);

            if ($ret === true) {
                print 'LOAD LOCATIONS STEP 1: FINISH';
                print '<a href="?LOUD_TAB=tab_mail&step=2" id="continue">continue</a><br>';
                print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
            } else {
                print sprintf('LOAD LOCATIONS STEP 1: %s%%<br>', round($ret[0] / $ret[1] * 100));
                print '<a href="?LOUD_TAB=tab_mail&step=1&pos='. $ret[0] .'" id="continue">continue</a><br>';
                print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
            }
        }
        elseif ($step < 3) {
            $ret = $loader->loadCashPay($pos, ['BY']);

            if ($ret === true) {
                print 'LOAD LOCATIONS STEP 2: FINISH';
            } else {
                $pos = explode(':', $ret[0]);

                print sprintf('LOAD LOCATIONS STEP 2: %s%%<br>', round(end($pos) / $ret[1] * 100));
                print '<a href="?LOUD_TAB=tab_mail&step=2&pos='. $ret[0] .'" id="continue">continue</a><br>';
                print '<br>'. $ret[1];
                print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
            }
        }
    }


    public function getTerminal(){
        $arTerminals = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('terminal')->findModels();
        $terminalCity = [];
        foreach ($arTerminals as $terminal){
            $arTermin = $terminal->jsonSerialize();
            if($terminalCity[$arTermin['CITY']]){
                $terminalCity[$arTermin['CITY']][$arTermin['ID']] = $arTermin;
            }else{
                $terminalCity[$arTermin['CITY']][$arTermin['ID']] = $arTermin;
            }
        }
        return $terminalCity;
    }

    public function NormalizNumHouse($NumHouse){
        $Components = [
            'HOUSE'=>'',
            'KORPUS' => '',
            'STR' => '',
        ];
        if(is_numeric($NumHouse)){
            $Components['HOUSE'] = $NumHouse;
        }else{
            $HOUSE = preg_split("/[A-Za-zА-яА-Я\/\-\|\\\,]+/", $NumHouse);
            $Components['HOUSE'] = $HOUSE[0];
            $KORPUS = preg_split("/[0-9\/\-\|\\\,]+/", $NumHouse);
            $Components['KORPUS'] = $KORPUS[1];
            $STR = preg_split("/[\/\-\|\\\,]+/", $NumHouse);
            $Components['STR'] = $STR[1];
        }
        return $Components;
    }

    public function ShowResult($result, $dpdOrder, $value){
        if ($result) {
            $arRes = $result->getErrorMessages();
            $log = [
                'SEND' => 'Отправка заказа по АГЕНТУ',
                'ORDER_ID' =>$value['ID'],
                'KLIENT_NUMBER' => $dpdOrder->OPTIONS['KLIENT_NUMBER'],
                'RESPONSE' => '',
            ];
            if (empty($arRes)) {
                $arDataStatus = $result->getData();
                $ORDER_NUM = $arDataStatus['ORDER_NUM'] ? $arDataStatus['ORDER_NUM'] : ' - ';
                $ORDER_STATUS = $arDataStatus['ORDER_STATUS'] ? $arDataStatus['ORDER_STATUS'] : ' - ';
                $log['RESPONSE'] = 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS;
                echo 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS;
                AddOrderProperty(45, 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID']);
            } else {
                $log['RESPONSE'] = 'ERROR: ' . $arRes[0];
                echo 'ERROR: ' . $arRes[0];
                AddOrderProperty(45, 'ERROR: ' . $arRes[0], $value['ID']);
            }
            AddMessage2Log($log);
        }
    }


    public function updateTerminalFile(){
        $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/TerminalesDPD/PVZ-CSV4.csv";
        if (file_exists($path)) {
            $poles_name = [
                'Описание',
                'Код подразделения',  //
                'Код страны',
                'Город расположения',
                'Адрес',              //
                'Телефон',
                'Прием посылок',
                'Выдача посылок',
                'Оплата наличными Отправителем',
                'Оплата банковской картой Отправителем',
                'Обед',
                'Наложенный платеж',
                'ТРМ',
                'Примерка',
                'Доступная услуга на приём',
                'Доступная услуга на выдачу',
                'Услуга',
                'Ограничения по габаритам посылки',
                'Максимальное количество мест',
                'Максимальный вес 1-го грузового места',
                'Тип подразделения',
                'Ограничение суммы НПП',
            ];
            //$dataArray['FIELDS'] = \CsvLib::CsvToArrayNew($path, $poles_name);



           /* foreach ($dataArray['FIELDS'] as $key => $val){
                if($val['Код подразделения']){
                    $exists = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('terminal')->getByCode($val['Код подразделения']);
                    if($exists){
                        $result = \Ipol\DPD\DB\Connection::getInstance($this->OB_CONFIG)->getTable('terminal')->update($exists['ID'], ['ADDRESS_DESCR'=>$val['Адрес']]);
                    }

                }
            }*/
        }else{
            echo 'Путь к файлу не верен';
        }
    }

}