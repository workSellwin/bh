<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$USER_ID = $USER->GetID();
if ($USER_ID and $_REQUEST['ID'] and $_REQUEST['ACTION']) {
    \CModule::IncludeModule("catalog");
    $arPrice = \CCatalogProduct::GetOptimalPrice((int)$_REQUEST['ID'], 1, [2], 'N', SITE_ID);
    $price = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];

    $data = [
        'UF_PRODUCT_ID' => (int)$_REQUEST['ID'],
        'UF_USER_ID' => $USER_ID,
        'UF_PRICE' => $price,
    ];
    $obj = PriceMonitor::getInstance();
    switch ($_REQUEST['ACTION']) {
        case 'ADD':
            $obj->add($data);
            break;
        case 'DELETE':
            $obj->delete($data);
            break;
        case 'VIEW':
            echo $obj->view($data);
            break;
    }
}
