<?define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("График доставки");
?>

<?include($_SERVER["DOCUMENT_ROOT"]."/local/include/delivery_time.php");?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>