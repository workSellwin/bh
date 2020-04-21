<?if($_REQUEST['add_to_free_delivery'] == 'y'){
    $APPLICATION->RestartBuffer();
}?>
<div id="add_to_free_delivery">
<?
global $add_to_free_filter;
$cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
$cacheId = 'add_to_free_' . $arResult['allSum'];
$idArr = [];
$diffPrice = 30 - $arResult['allSum'];

if ($diffPrice > 0) {
    if ($cache->read(3600, $cacheId)) {
        $idArr = $cache->get($cacheId);
    } else {
        if (\Bitrix\Main\Loader::includeModule('catalog')) {
            $res = CIBlockElement::GetList(
                [],
                ['IBLOCK_ID' => 40, 'ID' => 42746],
                false,
                false,
                ['PROPERTY_PRODUCTS']
            );

            while ($fields = $res->fetch()) {
                $idArr[] = $fields['PROPERTY_PRODUCTS_VALUE'];
            }
            $cache->set($cacheId, $idArr);
        }
    }

    $userGroup = $USER->GetUserGroupArray();

    /*$idArr = array_filter($idArr, function($id) use ($userGroup, $diffPrice){

        $optimalPrice = CCatalogProduct::GetOptimalPrice($id, 1, $userGroup);
        if($optimalPrice['DISCOUNT_PRICE'] < $diffPrice){
            return true;
        }
        return false;
    });*/
//pr($idArr, 1);
    if (count($idArr)) {

        //$idArr = array_slice($idArr, 0, 10);
        $add_to_free_filter["ID"] = $idArr;
        $add_to_free_filter['>PRICE'] = $diffPrice;
        $add_to_free_filter['=PRICE_TYPE'] = 15;

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "add_to_free",
            array(
                "TITLE" => "Добавить до бесплатной доставки",
                "freeDelivery" => 30,
                "SLIDER_NUM" => 10,
                "ACTION_VARIABLE" => "action",
                "ADD_PICT_PROP" => "-",
                "ADD_PROPERTIES_TO_BASKET" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "ADD_TO_BASKET_ACTION" => "ADD",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "BACKGROUND_IMAGE" => "-",
                "BASKET_URL" => "/ajax/add2basket.php",
                "BROWSER_TITLE" => "NAME",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "CACHE_TIME" => "3600",
                "CACHE_TYPE" => "A",
                "COMPATIBLE_MODE" => "Y",
                "CONVERT_CURRENCY" => "N",
                "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                "DETAIL_URL" => "",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_COMPARE" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_SORT_FIELD" => "CATALOG_PRICE_15",
                "ELEMENT_SORT_FIELD2" => "CATALOG_PRICE_1",
                "ELEMENT_SORT_ORDER" => "desc",
                "ELEMENT_SORT_ORDER2" => "desc",
                "ENLARGE_PRODUCT" => "PROP",
                "ENLARGE_PROP" => "-",
                "FILTER_NAME" => "add_to_free_filter",
                "HIDE_NOT_AVAILABLE" => "Y",
                "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                "IBLOCK_ID" => "2",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_TYPE_ID" => "catalog",
                "INCLUDE_SUBSECTIONS" => "A",
                "LABEL_PROP" => array(
                    0 => "NEWPRODUCT",
                    1 => "SALELEADER",
                    2 => "SPECIALOFFER",
                    3 => "SALE",
                ),
                "LABEL_PROP_MOBILE" => array(
                    0 => "NEWPRODUCT",
                    1 => "SALELEADER",
                    2 => "SPECIALOFFER",
                    3 => "SALE",
                ),
                "LABEL_PROP_POSITION" => "top-left",
                "LAZY_LOAD" => "N",
                "LINE_ELEMENT_COUNT" => "3",
                "LOAD_ON_SCROLL" => "N",
                "MESSAGE_404" => "",
                "MESS_BTN_ADD_TO_BASKET" => "Добавить",
                "MESS_BTN_BUY" => "Купить",
                "MESS_BTN_DETAIL" => "Подробнее",
                "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                "MESS_BTN_SUBSCRIBE" => "Подписаться",
                "MESS_NOT_AVAILABLE" => "Нет в наличии",
                "META_DESCRIPTION" => "UF_META_DESCRIPTION",
                "META_KEYWORDS" => "UF_KEYWORDS",
                "OFFERS_CART_PROPERTIES" => array(
                    0 => "COLOR_REF,SIZES_SHOES,SIZES_CLOTHES",
                ),
                "OFFERS_FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "OFFERS_LIMIT" => "0",
                "OFFERS_PROPERTY_CODE" => array(
                    0 => "COLOR_REF_2",
                    1 => "COLOR_REF",
                    2 => "",
                ),
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_FIELD2" => "id",
                "OFFERS_SORT_ORDER" => "desc",
                "OFFERS_SORT_ORDER2" => "desc",
                "OFFER_ADD_PICT_PROP" => "-",
                "OFFER_TREE_PROPS" => array(),
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => "main",
                "PAGER_TITLE" => "Товары",
                "PAGE_ELEMENT_COUNT" => "10",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRICE_CODE" => array(
                    0 => "SELLWIN",
                    1 => "Trade Price",
                    2 => "RTL",
                    3 => "b2b Activ",
                    4 => "b2b pro loreal pro/matrix",
                    5 => "b2b Kerastase",
                    6 => "b2b Redken",
                    7 => "BASE",
                    8 => "OPT",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                "PRODUCT_DISPLAY_MODE" => "Y",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPERTIES" => array(),
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "",
                "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false}]",
                "PRODUCT_SUBSCRIPTION" => "N",
                "PROPERTY_CODE" => array(
                    0 => "NEWPRODUCT",
                    1 => "SALELEADER",
                    2 => "SPECIALOFFER",
                    3 => "SALE",
                    4 => "",
                ),
                "PROPERTY_CODE_MOBILE" => array(
                    0 => "NEWPRODUCT",
                    1 => "SALELEADER",
                    2 => "SPECIALOFFER",
                    3 => "SALE",
                ),
                "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                "RCM_TYPE" => "personal",
                "SECTION_CODE" => "",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SECTION_URL" => "",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "SEF_MODE" => "Y",
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SHOW_ALL_WO_SECTION" => "N",
                "SHOW_CLOSE_POPUP" => "N",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "SHOW_FROM_SECTION" => "N",
                "SHOW_MAX_QUANTITY" => "N",
                "SHOW_OLD_PRICE" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "SHOW_SLIDER" => "N",
                "SLIDER_INTERVAL" => "3000",
                "SLIDER_PROGRESS" => "N",
                "TEMPLATE_THEME" => "site",
                "USE_ENHANCED_ECOMMERCE" => "N",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "FILE_404" => "",
                "SEF_RULE" => "",
                "SECTION_CODE_PATH" => "",
            ),
            false
        );
    }
}
?>
</div>
<?if($_REQUEST['add_to_free_delivery'] == 'y'){
    exit;
}?>

