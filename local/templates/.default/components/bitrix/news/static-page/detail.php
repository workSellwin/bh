<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); ?>


<? $ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "META_KEYWORDS" => $arParams["META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
        "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "MESSAGE_404" => $arParams["MESSAGE_404"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "SHOW_404" => $arParams["SHOW_404"],
        "FILE_404" => $arParams["FILE_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
        "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
        "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
        "USE_SHARE" => $arParams["USE_SHARE"],
        "SHARE_HIDE" => $arParams["SHARE_HIDE"],
        "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
        "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
        "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
        "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
        "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
        'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
    ),
    $component
);
global $arrFilter;
$GLOB_FILTER = [];
$FILTER_PROP = [];
$arSelect = Array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_*');
$arFilter = Array("IBLOCK_ID" => 38, "ID" => $ElementID, "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while ($res1 = $res->GetNextElement()) {
    $reOnl = $res1->GetFields();
    $reProp = $res1->GetProperties();

    $GLOB_FILTER = array_column($reProp, 'VALUE', 'CODE');

    foreach ($reProp as $key => $val) {

        if ($val['VALUE'] && $key != 'SECTION') {
            $arrFilter['PROPERTY_'.$key] = $val['VALUE'];
        }

    }


}





?>
    <div class="catalog stock cl">
        <div class="catalog-left">
            <? /*
			<div class="catalog-left__item filter">
				<div class="js-left-nav-btn">фильтры<span></span></div>
				<div class="bx-filter js-left-nav">
					<form action="" method="get">
						<div class="bx-filter-parameters-box">
							<div class="bx-filter-block">
								<?foreach ($arSectionFiler as $key => $value):?>
									<div class="checkbox">
										<input class="radio-field" type="radio" id="filter<?=$value["ID"]?>" name="filter" <?=($filter==$value["ID"])?"checked":""?> value="<?=$value["ID"]?>">
										<label class="radio__label" for="filter<?=$value["ID"]?>"><?=$value["NAME"]?></label>
									</div>
								<?endforeach?>
							</div>
						</div>
						<div class="bx-filter-button-box">
							<div class="bx-filter-block">
								<input class="btn btn-themes" type="submit" id="set_filter" name="set_filter" value="Подобрать">
								<input class="btn btn-link" type="submit" id="del_filter" name="del_filter" value="Сбросить">
							</div>
						</div>
					</form>
				</div>
			</div>*/
            ?>
            <?
            $APPLICATION->IncludeComponent(
                "bh.by:brands_smart_filter",
                "main",
                array(
                    "IBLOCK_TYPE" => "",
                    "IBLOCK_ID" => "2",
                    "FILTER_NAME" => "arrFilter",
                    "PRICE_CODE" => array(
                        0 => "Trade Price",
                        1 => "RTL",
                    ),
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => "N",
                    "SAVE_IN_SESSION" => "N",
                    "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                    "XML_EXPORT" => "N",
                    "SECTION_TITLE" => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    "HIDE_NOT_AVAILABLE" => "Y",
                    "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                    "CONVERT_CURRENCY" => "N",
                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                    "SEF_MODE" => "N",
                    "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                    "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "COMPONENT_TEMPLATE" => "main",
                    "SECTION_ID" => $GLOB_FILTER['SECTION'] ? $GLOB_FILTER['SECTION'] : '',
                    "SECTION_CODE" => "",
                    "DISPLAY_ELEMENT_COUNT" => "Y",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "BRANDS_ELEM_FILTER" => $GLOB_FILTER['BRANDS'] ? $GLOB_FILTER['BRANDS'] : '',
                    "FILTER_PROP" => $arrFilter,
                ),
                false
            );
            ?>
        </div>
        <div class="product">
            <div id="MY_AJAX_FILTER">
                <?
                if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
                    $GLOBALS['APPLICATION']->RestartBuffer();
                endif; ?>

                <div class="schema-tags">
                    <? $APPLICATION->ShowViewContent('prop_filter'); ?>
                </div>


                <?

                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "cat",
                    array(
                        "ACTION_VARIABLE" => "action",
                        "ADD_PICT_PROP" => "-",
                        "DESCRIPTION" => $arResult["SECTION"]["DESCRIPTION"],
                        "ADD_PROPERTIES_TO_BASKET" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "ADD_TO_BASKET_ACTION" => "ADD",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "BACKGROUND_IMAGE" => "-",
                        "BASKET_URL" => "/personal/cart/",
                        "BROWSER_TITLE" => "NAME",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COMPATIBLE_MODE" => "Y",
                        "COMPONENT_TEMPLATE" => "cat",
                        "CONVERT_CURRENCY" => "N",
                        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                        "DETAIL_URL" => "",
                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_COMPARE" => "N",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_SORT_FIELD" => "sort",
                        "ELEMENT_SORT_FIELD2" => "id",
                        "ELEMENT_SORT_ORDER" => "asc",
                        "ELEMENT_SORT_ORDER2" => "desc",
                        "ENLARGE_PRODUCT" => "PROP",
                        "ENLARGE_PROP" => "-",
                        "FILTER_NAME" => "arrFilter",
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
                        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
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
                            0 => "",
                            1 => "COLOR_REF_2",
                            2 => "COLOR_REF",
                            3 => "",
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
                        "PAGE_ELEMENT_COUNT" => "12",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRICE_CODE" => array(
                            0 => "BASE",
                            1 => "OPT",
                            2 => "SELLWIN",
                            3 => "b2b Activ",
                            4 => "b2b pro loreal pro/matrix",
                            5 => "b2b Kerastase",
                            6 => "b2b Redken",
                            7 => "RTL",
                            8 => "Trade Price",
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
                        "SECTION_ID" => $GLOB_FILTER['SECTION'] ? $GLOB_FILTER['SECTION'] : '',
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "SECTION_URL" => "",
                        "SECTION_USER_FIELDS" => array(
                            0 => "",
                            1 => "",
                        ),
                        "SEF_MODE" => "Y",
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_STATUS_404" => "Y",
                        "SET_TITLE" => "Y",
                        "SHOW_404" => "Y",
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
                        "SECTION_CODE_PATH" => ""
                    ),
                    false,
                    array(
                        "ACTIVE_COMPONENT" => "Y"
                    )
                ); ?>
                <script>
                    var i = 1;
                    $('body').on('click', '.schema-tags__item', function () {

                        if (i == 1) {
                            prop_click($(this));
                        }
                        i++;
                    });


                    function prop_click($this) {
                        var data_prop_id = $($this).attr('data-prop-id');
                        var data_price = $($this).attr('data-price');
                        if (data_price == 'Y') {
                            var data_price_value = $($this).attr('data-price-value');
                            setTimeout(function () {
                                $('input#' + data_prop_id).val(data_price_value);
                                $('input#' + data_prop_id).trigger("click");
                                $('input#' + data_prop_id).trigger("keyup");
                            }, 1);
                        } else {
                            setTimeout(function () {
                                $('input#' + data_prop_id).trigger("click");
                            }, 10);
                        }
                    }
                </script>

                <?
                if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
                    die();
                endif;
                ?>
            </div>
        </div>
    </div>


<?
//$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
//$IPROPERTY = $ipropValues->getValues();

//$APPLICATION->AddChainItem($arResult["SECTION"]["NAME"], "");
//$APPLICATION->SetTitle($IPROPERTY["SECTION_PAGE_TITLE"]);
//$APPLICATION->SetPageProperty("title", $IPROPERTY["SECTION_META_TITLE"]);
//$APPLICATION->SetPageProperty("keywords", $IPROPERTY["SECTION_META_KEYWORDS"]);
//$APPLICATION->SetPageProperty("description", $IPROPERTY["SECTION_META_DESCRIPTION"]);
?>