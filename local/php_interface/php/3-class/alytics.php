<?php


use \Bitrix\Sale;
class Alytics
{
    public $arOrder = [];
    public $catalogID = 2;
    public $date_to;
    public $date_from;
    public $PERSON_TYPE_ID = 1;
    protected $FIELDS_ALYTICS = [
        'id',                   //Идентификатор транзакции
        'number',               //Номер транзакции (заказа) из CRM, если в вашей CRM он есть.
        'revenue',              //Выручка по сделке из CRM. Если придет пустое, сохраним как 0.
        'profit',               //Прибыль по сделке из CRM.
        'count',                //Количество.
        'client_id',            //Google client id (cid) из CRM, client_id клиента, полученный из куки (google client id (cid))
        'href',                 //Ссылка на страницу транзакции в CRM.
        'creation_date',        //Дата и время создания сделки из CRM. Если нет информации по секундам, то передавайте вместо SS значение 59.
        'utm_source',           //Рекламный источник. Метка utm_source из сделки.
        'utm_medium',           //Рекламный канал. Метка utm_medium из сделки.
        'utm_content',          //Содержание рекламного объявления. Метка utm_content из сделки.
        'utm_campaign',         //Рекламная кампания. Метка utm_campaign из сделки.
        'utm_term',             //Ключевое слово. Метка utm_term из сделки.
    ];

    public function __construct()
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("iblock");
    }

    public function getArOrders($DATE)
    {
        global $DB;
        $DATE = explode('-', $DATE);
        $this->date_to = $DATE[2].'.'.$DATE[1].'.'.$DATE[0].' 00:00:00';
        $this->date_from = $DATE[2].'.'.$DATE[1].'.'.$DATE[0].' 23:59:00';
        $this->getListOrders();
        return $this->arOrder;
    }

    //получить список заказов
    protected function getListOrders()
    {
        //echo date('d.m.Y', strtotime($this->date . ' + ' . $this->interval . ' days'));
        $arFilter = Array(
            '>=DATE_INSERT' => $this->date_to,
            '<=DATE_INSERT' => $this->date_from,
            'PERSON_TYPE_ID' => $this->PERSON_TYPE_ID,
        );

        $rsSales = \CSaleOrder::GetList(array("DATE_INSERT" => "asc"), $arFilter);
        while ($arSales = $rsSales->Fetch()) {
            $order = Sale\Order::load($arSales['ID']);
            //ID заказа
            $this->arOrder[$arSales['ID']]['ID'] = $arSales['ID'];
            //тип доставки
            $arDeliv = \CSaleDelivery::GetByID($arSales['DELIVERY_ID']);
            if ($arDeliv) {
                $this->arOrder[$arSales['ID']]['DELIVERY']['NAME'] = $arDeliv['NAME'];
                $this->arOrder[$arSales['ID']]['DELIVERY']['ID'] = $arDeliv['ID'];
            }
            $this->arOrder[$arSales['ID']]['DATE_INSERT'] = $arSales['DATE_INSERT'];
            //Стоимость заказа.
            $this->arOrder[$arSales['ID']]['PRICE']=$arSales['PRICE'];
            //Оплачен, не оплачен
            $this->arOrder[$arSales['ID']]['IS_PAID'] = $order->isPaid() ? 'Y' : 'N';
            // Вес заказа
            $this->arOrder[$arSales['ID']]['ORDER_WEIGHT'] = $order->getBasket()->getWeight() ? $order->getBasket()->getWeight() : 0 ;
            //свойства заказа
            $this->setOrderPropsValue($arSales['ID']);
            //товар заказа
            $this->arOrder[$arSales['ID']]['PROD'] = $this->BasketOrder($arSales['ID']);
        }
    }

    protected function setOrderPropsValue($ORDER_ID)
    {
        $db_props = \CSaleOrderPropsValue::GetOrderProps($ORDER_ID);
        $arProps = [];
        while ($arPropOrder = $db_props->Fetch()) {
            $arProps[$arPropOrder['CODE']] = $arPropOrder['VALUE'];
        }
        $arKey = ['FIO', 'PHONE', 'EMAIL', 'CITY', 'LOCATION', 'STREET', 'ROOM', 'HOME', 'RESPONSE_YANDEX', 'DATE', 'TERMINAL_CITY', 'TERMINAL_ADDRES', 'GOOGLE_CLIENT_ID', "DISCOUNT"];
        foreach ($arKey as $key) {
            $this->arOrder[$ORDER_ID][$key] = $arProps[$key];
        }
    }

    protected function BasketOrder($ORDER_ID)
    {
        if($ORDER_ID){
            $dbBasket = \CSaleBasket::GetList(Array("ID" => "ASC"), Array("ORDER_ID" => $ORDER_ID));
            $elem_id = [];
            while ($arBasket = $dbBasket->Fetch()) {
                $elem_id[] = $arBasket['PRODUCT_ID'];
            }
            return $this->getListElement($elem_id, $ORDER_ID);
        }
    }

    protected function getListElement($ELEM_ID, $ORDER_ID)
    {

        $arElements = [];
        if(!empty($ELEM_ID)) {
            $arSelect = Array("ID", "NAME", "IBLOCK_ID", "CODE", "PROPERTY_BRANDS", "PROPERTY_SERIES", "PROPERTY_productype", "XML_ID");
            $arFilter = Array("IBLOCK_ID" => $this->catalogID, 'ID' => $ELEM_ID);
            $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while ($ob = $res->GetNext()) {
                $arElements[$ORDER_ID][$ob['ID']]['XML_ID'] = $ob['XML_ID'];
                $arElements[$ORDER_ID][$ob['ID']]['NAME'] = $ob['NAME'];
                $arElements[$ORDER_ID][$ob['ID']]['BRANDS'] = $ob['PROPERTY_BRANDS_VALUE'];
                $arElements[$ORDER_ID][$ob['ID']]['SERIES'] = $ob['PROPERTY_SERIES_VALUE'];
                $arElements[$ORDER_ID][$ob['ID']]['PRODUCTYPE'] = $ob['PROPERTY_PRODUCTYPE_VALUE'];
            }
        }
        return $arElements;
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function getFormatJsonOrder($arOrder){
        if(!empty($arOrder)){
            $data = [];
            foreach ($arOrder as $key => $val){
                $creation_date = ConvertDateTime($val['DATE_INSERT'], "YYYY-MM-DD HH:MM:SS", "ru");

                $data['data']['transactions'][$val['ID']]['id'] =  $val['ID'];
                $data['data']['transactions'][$val['ID']]['number'] =  '';
                $data['data']['transactions'][$val['ID']]['revenue'] =  $val['PRICE'];
                $data['data']['transactions'][$val['ID']]['profit'] =  '';
                $data['data']['transactions'][$val['ID']]['count'] =  1;
                $data['data']['transactions'][$val['ID']]['client_id'] =  $val['GOOGLE_CLIENT_ID'] && $val['GOOGLE_CLIENT_ID']!=null ? $val['GOOGLE_CLIENT_ID'] : '' ;                       //
                $data['data']['transactions'][$val['ID']]['href'] =  '';
                $data['data']['transactions'][$val['ID']]['creation_date'] =  $creation_date;
                $data['data']['transactions'][$val['ID']]['utm_source'] =  '';
                $data['data']['transactions'][$val['ID']]['utm_medium'] =  '';
                $data['data']['transactions'][$val['ID']]['utm_content'] =  '';
                $data['data']['transactions'][$val['ID']]['utm_campaign'] =  '';
                $data['data']['transactions'][$val['ID']]['utm_term'] =  '';
            }
        }
        $data['data']['transactions'] = array_values($data['data']['transactions']);
        $data = json_encode($data);
        return $data;
    }

}