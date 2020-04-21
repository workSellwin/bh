<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
if(isset($_REQUEST['date']) && !empty($_REQUEST['date'])){
    $DATA =  $_REQUEST['date'];
    $Alytics = new Alytics();
    if($Alytics->validateDate($DATA)){
        $arOrder = $Alytics->getArOrders($DATA);
        $dataJson = $Alytics->getFormatJsonOrder($arOrder);
        echo $dataJson;
    }else{
        $error = [
             'status' => 'error',
             'message' => 'Не верный формат даты',
        ];
        $error = json_encode($error);
        header("Content-Type: application/json; charset=utf-8");
        echo $error;
    }
}else{
    $error = [
        'status' => 'error',
        'message' => 'Не указана дата',
    ];
    header("Content-Type: application/json; charset=utf-8");
    $error = json_encode($error);
    echo $error;
}
?>