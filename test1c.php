<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>

<?
\Bitrix\Main\Loader::includeModule("sale");
\Bitrix\Main\Loader::includeModule("catalog");
\Bitrix\Main\Loader::includeModule("iblock");
$export =  new CSaleExport2();
$APPLICATION->RestartBuffer();
header("Content-Type: application/xml; charset=utf-8");
$arResultStat = $export::ExportOrders2Xml([],1);
//PR($arResultStat);
die();
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
