<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата произведена успешно");
?>
Счет <?=htmlspecialcharsbx($_REQUEST['wsb_order_num'])?> оплачен. <br/>
Код транзакции: <?=htmlspecialcharsbx($_REQUEST['wsb_tid'])?>.

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>