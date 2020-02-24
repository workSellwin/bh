<?php


function updateProductCountAgent()
{
	ini_set('max_execution_time', '600');
	
    Cmodule::IncludeModule('catalog');
    //$json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstore/json');
    $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorefull/json');
    $jsonRes = json_decode($json, true);

    // ob_start();
    // print_r($jsonRes);
    // $res99 = ob_get_clean();
    // AddMessage2Log("\n\n".$res99."\n");


    $today = date("H:i:s d-m-Y");
    $count = 0;
    $count2 = 0;
    if (count($jsonRes["dataStore"]) > 0) {
        //ob_start();
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
            // print_r($value);
            // print_r($ob);
            // echo "\n\n------------------------";
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

function CreateProductDpd(){
    /*CModule::IncludeModule("yauheni.dpd");
    CModule::IncludeModule("lui.delivery");
    $time = time() - 3600 * 24 * 1;
    $dpd = new dpd();
    $arOrder = $dpd->getArOrders($time, ['ID'=>$_REQUEST['ORDER_ID']]);
    if(isset($_REQUEST['NPP']) && $_REQUEST['NPP'] == 'Y'){
        $dpdOrder = new dpdNppY('1104009153', '9CCE8C68288349CBFE56E5D420CFA807268B6845');
    }else{
        $dpdOrder = new dpdNppN('1104009121', 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF');
    }
    if(!empty($arOrder)) {
        foreach ($arOrder as $k => $value) {
            PR($value);
            //Доставка курьером (РБ) = 19
            if ($value['DELIVERY']['ID'] == 19 && $value['PERSON_TYPE_ID'] == 1) {
                //Записываем результат в заказ ("статус DPD")
                $dpdOrder->PICKUP_DATE = date('Y-m-d');
                $result = $dpdOrder->Run($value);
                if ($result) {
                    $arRes = $result->getErrorMessages();
                    if (empty($arRes)) {
                        $arDataStatus = $result->getData();
                        $ORDER_NUM = $arDataStatus['ORDER_NUM'] ? $arDataStatus['ORDER_NUM'] : ' - ';
                        $ORDER_STATUS = $arDataStatus['ORDER_STATUS'] ? $arDataStatus['ORDER_STATUS'] : ' - ';
                        echo 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID'];
                        AddOrderProperty(45, 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID']);
                    } else {
                        echo 'ERROR: ' . $arRes[0], $value['ID'];
                        AddOrderProperty(45, 'ERROR: ' . $arRes[0], $value['ID']);
                    }
                }
            }
        }
    }*/
    PR('345345');
}