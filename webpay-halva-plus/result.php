<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("Прием информации об оплате");
file_put_contents('log.json', json_encode ($_REQUEST));
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment.receive", 
	"", 
	array(
		//"PAY_SYSTEM_ID" => "16",
		"PAY_SYSTEM_ID_NEW" => "16"
		//"PERSON_TYPE_ID" => "1"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>