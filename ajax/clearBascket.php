<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
if(check_bitrix_sessid() && $_REQUEST['CLEAR'] == 'Y'){
	CModule::IncludeModule("sale");
	CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
	echo '<div class="complite"><script>location.reload();</script></div>';
}
?>