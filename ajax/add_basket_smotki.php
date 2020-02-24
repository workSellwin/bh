<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Sale;

?>


<?
if(isset($_REQUEST['prod_id_1']) && isset($_REQUEST['prod_id_2'])){
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("catalog");
    CModule::IncludeModule("sale");
    $arId = array($_REQUEST['prod_id_1'], $_REQUEST['prod_id_2']);
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    foreach ($arId as $val){
        if ($item = $basket->getExistsItem('catalog', $val)) {
            $item->setField('QUANTITY', $item->getQuantity() + 1);
        }
        else {
            $item = $basket->createItem('catalog', $val);
            $item->setFields(array(
                'QUANTITY' => 1,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ));
        }
        $basket->save();
    }
    echo 1;
}else{
    echo 0;
}


?>