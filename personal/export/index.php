<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Прайс");
?>
<p><a href="<?=SITE_DIR?>local/export/?type=quantity_products" target="_blank" rel="nofollow" title="">Скачать прайс: товары (.xlsx)</a></p>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>