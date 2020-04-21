<?php

namespace Yauheni\Dpd;
use \Bitrix\Sale;
class dpd
{

    public $arOrder = [];
    public $catalogID = 2;
    public $date_to;
    public $date_from;
    public $PERSON_TYPE_ID = 1;

    public function __construct()
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("iblock");
    }

    public function getArOrders($TIME, $Filter = [])
    {
        global $DB;
        $this->date_to = date($DB->DateFormatToPHP(\CSite::GetDateFormat("SHORT")), $TIME).' 10:00:00';
        $this->date_from = date('d.m.Y').' 10:00:00';
        $this->getListOrders($Filter);
        return $this->arOrder;
    }

    //получить список заказов
    protected function getListOrders($Filter = [])
    {
        //echo date('d.m.Y', strtotime($this->date . ' + ' . $this->interval . ' days'));
        $arFilter = Array(
            '>=DATE_INSERT' => $this->date_to,
            '<=DATE_INSERT' => $this->date_from,
            'PERSON_TYPE_ID' => $this->PERSON_TYPE_ID,
        );
        if($Filter['ID']){
            unset($arFilter['>=DATE_INSERT']);
            unset($arFilter['<=DATE_INSERT']);
        }
        if(!empty($Filter)){
            $arFilter = array_merge($arFilter, $Filter);
        }

        $rsSales = \CSaleOrder::GetList(array("DATE_INSERT" => "desc"), $arFilter);
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
        $arKey = ['FIO', 'PHONE', 'EMAIL', 'CITY', 'LOCATION', 'STREET', 'ROOM', 'HOME', 'RESPONSE_YANDEX', 'DATE', 'TERMINAL_CITY', 'TERMINAL_ADDRES'];
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

}