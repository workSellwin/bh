<?php
use Yauheni\Dpd\dpd;
use Yauheni\Dpd\dpdBase;
use Yauheni\Dpd\dpdNppN;
use Yauheni\Dpd\dpdNppY;
use Yauheni\Dpd\dpdPvz;

function updateProductCountAgent()
{
	ini_set('max_execution_time', '600');
	
    Cmodule::IncludeModule('catalog');
    $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstore/json');//new service url
    //$json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorefull/json');
    $jsonRes = json_decode($json, true);

    // ob_start();
    // $res99 = ob_get_clean();

    $today = date("H:i:s d-m-Y");
    $count = 0;
    $count2 = 0;
    if (count($jsonRes["dataStore"]) > 0) {
        //ob_start();
        $arIds = [43461, 43462, 43463, 44429, 44430, 44432, 44433, 44434, 44436, 44437, 44438, 18207];
        foreach ($jsonRes["dataStore"] as $key => $value) {
            $res = CCatalogProduct::GetList(array(), array("ELEMENT_XML_ID" => $value["good"]), false, array(), array());
            if ($ob = $res->GetNext()) {
                $arIds[] = $ob["ID"];
                if ($ob["QUANTITY"] != $value["count"]) {
                    if (CCatalogProduct::Update($ob["ID"], array('QUANTITY' => $value["count"]))) {
                        $count++;
                    }
                }
            }
        }
        //$res99 = ob_get_clean();

        $res2 = CCatalogProduct::GetList(array(), array("!ID" => $arIds, "!QUANTITY" => 0), false, false);
        while ($ob = $res2->GetNext()) {
            if (CCatalogProduct::Update($ob["ID"], array('QUANTITY' => 0))) {
                $count2++;
            }
        }
        //AddMessage2Log($res99);
        AddMessage2Log("\n\n" . $today . " Обновленно " . $count . " товаров, и для " . $count2 . " проставлен остаток 0 шт.\n");
    }
    return "updateProductCountAgent();";
}


function CreatGoogleFile(){
    $obGoogleFile = new creatGoogleFile();
    $obGoogleFile->GetGoogleFile();
    return "CreatGoogleFile();";
}

function UpdataExportFileOnliner (){
    $onliner = new exportOnlinerNew();
    $onliner -> process();
    return "UpdataExportFileOnliner();";
}

function UpdataExportFileFacebook (){
    $EX_FACEBOOK = new exportFacebook();
    $EX_FACEBOOK->process();
    return "UpdataExportFileFacebook();";
}

function CreateProductDpd(){
    CModule::IncludeModule("yauheni.dpd");
    CModule::IncludeModule("lui.delivery");
    $time = time() - 3600 * 24 * 1;
    $dpd = new dpd();
    $arOrder = $dpd->getArOrders($time );
    if(!empty($arOrder)) {
        foreach ($arOrder as $k => $value) {
            //PR($value);
            //Доставка курьером (РБ) = 19  и оплачен
            if ($value['DELIVERY']['ID'] == 19 && $value['IS_PAID'] == 'Y') {
                //оплачен на сайте
                if($value['IS_PAID'] == 'Y') {
                    $dpdOrder = new dpdNppN();
                    if($value['DATE']){
                        $arData = explode('.',  $value['DATE']);
                        $dpdOrder->PICKUP_DATE = $arData[2].'-'.$arData[1].'-'.$arData[0];
                        $result = $dpdOrder->Run($value);
                        //$dpdOrder->ShowResult($result, $dpdOrder, $value);
                    }
                }
            }
            //Доставка курьером (РБ) = 19  и не оплачен
            if ($value['DELIVERY']['ID'] == 19 && $value['IS_PAID'] == 'N') {
                //не оплачен на сайте
                if($value['IS_PAID'] == 'N') {
                    $dpdOrder = new dpdNppY();
                    if($value['DATE']){
                        $arData = explode('.',  $value['DATE']);
                        $dpdOrder->PICKUP_DATE = $arData[2].'-'.$arData[1].'-'.$arData[0];
                        $result = $dpdOrder->Run($value);
                        $dpdOrder->ShowResult($result, $dpdOrder, $value);
                    }
                }
            }
            //Самовывоз  из ПВЗ (РБ) и оплачен
            if ($value['DELIVERY']['ID'] == 44 && $value['IS_PAID'] == 'Y') {
                //оплачен на сайте
                if($value['IS_PAID'] == 'Y') {
                    $dpdOrder = new dpdPvz();
                    if($value['DATE']){
                        $arData = explode('.',  $value['DATE']);
                        $dpdOrder->PICKUP_DATE = $arData[2].'-'.$arData[1].'-'.$arData[0];
                        $result = $dpdOrder->Run($value);
                        $dpdOrder->ShowResult($result, $dpdOrder, $value);
                    }
                }
            }
        }
    }
    return "CreateProductDpd();";
}

function updateTerminalDpd(){
    CModule::IncludeModule("yauheni.dpd");
    CModule::IncludeModule("lui.delivery");
    $dpd = new dpdBase();
    $dpd->loadingTerminal();
}

function updateTerminalFile(){
    CModule::IncludeModule("yauheni.dpd");
    CModule::IncludeModule("lui.delivery");
    $dpd = new dpdBase();
    $dpd->updateTerminalFile();
}

function getTerminalDpd(){
    CModule::IncludeModule("yauheni.dpd");
    CModule::IncludeModule("lui.delivery");
    $dpd = new dpdBase();
    return $dpd->getTerminal();
}