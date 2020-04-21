<?php

namespace Yauheni\Dpd;
use \Bitrix\Sale;
class dpdNppY extends dpdBase
{

    public $ID_CITY = 2588; //Беларусь, обл Минская, Минский, д Щомыслица
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
        $this->setShipmentSelfPickup(false);
        //Отправка до (false - от двери , true  - от терминала)
        $this->setShipmentSelfDelivery(false);
        //устонавливаем данные items для DPD
        $this->ITEMS = [
            'NAME' => 'косметические товары',
            'QUANTITY' => 1,
            'PRICE' => $arOrderParams['PRICE'],
            'NPP' => $arOrderParams['PRICE'],
            'VAT_RATE' => '',
            'CARGO' => 1,
            'WEIGHT' => $arOrderParams['ORDER_WEIGHT'] <= 1000 ? 1000 : $arOrderParams['ORDER_WEIGHT'],
        ];
        //init данных item в ob_shipment
        $this->setShipmentItems();
        //ob_shipment пехнём в ob_order
        $this->OB_ORDER->setShipment($this->OB_SHIPMENT);
        // -------------------- Отправитель -----------------------------------------------------------
        $this->SENDER = [
            'NAME' => 'ООО "Сэльвин-Логистик"',
            'FIO' => 'BH.BY ALL.BH.BY',
            'PHONE' => '+375447480824',
            'STREET' => 'Щомыслица',
            'HOUSE' => '28',
            'KORPUS' => '2',
            'NEED_PASS' => 'N',
        ];
        //init данных отправителя в ob_order
        $this->setSenderOrder();
        //---------------------- Получатель -----------------------------------------------------------
        $HOUSE =  $arOrderParams['HOME']  ? $arOrderParams['HOME'] : $this->YANDEX['Components']['house']; //номер дома,
        $house = $this->NormalizNumHouse($HOUSE);
        $this->RECEIVER = [
            'NAME' => $arOrderParams['FIO'],
            'FIO' => $arOrderParams['FIO'],
            'PHONE' => $arOrderParams['PHONE'],
            'STREET' => $arOrderParams['STREET'] ? $arOrderParams['STREET'] : $this->YANDEX['Components']['street'], //улица
            'HOUSE' =>  $house['HOUSE'], //номер дома,
            'KORPUS' => $house['KORPUS'],
            'STR' => $house['STR'],
            'FLAT' => $arOrderParams['ROOM'],
        ];
        //init данных получатель в ob_order
        $this->setReceiverOrder();
        //---------------------------------------------------------------------------------------------
        $this->ORDER_OPTIONS = [
            'PICKUP_DATE' => $this->PICKUP_DATE ? $this->PICKUP_DATE : date('Y-m-d'),
            'PICKUP_TIME_PERIOD' => '9-18',
            'SERVICE_CODE' => 'CSM',
            'SERVICE_VARIANT' => 'ТД',
            'SENDER_TERMINAL_CODE' => 'MSQ',
            'SET_NPP' => 'Y',
            'PRICE_DELIVERY' => 0,
            'ORDER_ID' => $arOrderParams['ID'],
            'CARGO_CATEGORY' => 'косметические товары',
            'USE_CARGO_VALUE' => 'Y',
        ];
        //устонавливаем дополнительные пораметры для ob_order
        $this->setOptionsOrder();
        //create order в dpd
        $result = $this->createOrderDpd();
        return $result;
    }
}