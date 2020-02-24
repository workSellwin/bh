<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ошибка при оплате");
?>
Ошибка при оплате счета <?=htmlspecialcharsbx($_REQUEST['wsb_order_num'])?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>