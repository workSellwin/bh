<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Программа лояльности");
?>


<?
global $USER;
if ($USER->IsAdmin()):?>


    <?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"programma_loyalnosti",
	array(
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "37",
		"TEMPLATE_THEME" => "site",
		"NEWS_COUNT" => "10",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"NUM_NEWS" => "20",
		"NUM_DAYS" => "180",
		"YANDEX" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "N",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/pokupatelyam/programma-loyalnosti/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"DISPLAY_PANEL" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "arrows",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "main",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SLIDER_PROPERTY" => "PICS_NEWS",
		"COMPONENT_TEMPLATE" => "news",
		"SET_LAST_MODIFIED" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"USE_SHARE" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"FILE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);?>



    <?else:?>




    <h2 style="text-align: center;">&nbsp; Интернет магазин Beauty House участвует в программе лояльности «Моцная картка»! </h2>
<p>
 <b>«Моцная картка»</b> – республиканская программа лояльности, позволяющая получать скидки и бонусы в магазинах-партнерах. <b>Скидка предоставляется держателям карточек «Моцная картка» на весь ассортимент товаров в размере 10%</b> при условии ввода всех цифр штрих-кода, расположенного на оборотной стороне «Моцнай карткi», во время оформления заказа на сайтах <a href="https://bh.by/">https://bh.by/</a> и <a href="https://all.bh.by/">https://all.bh.by/</a>.<br>
</p>
<p>
 <br>
</p>
<p>
 <img alt="Screenshot_2019-07-25 Моя корзина.jpg" src="/upload/medialibrary/519/51986ef5d9245b73a3b25187ea8a3289.jpg" title="Screenshot_2019-07-25 Моя корзина.jpg" width="960" height="895"><br>
</p>
<p>
 <br>
</p>
<ul>
	<li>
	Скидка не суммируется с иными дисконтами, не предоставляется на акционные товары. </li>
</ul>
<div>
 <br>
</div>
<ul>
	<li>
	Скидка распространяется при оплате банковскими картами рассрочки. </li>
</ul>
<div>
 <br>
</div>
<ul>
	<li>
	Подробнее с условиями можно ознакомиться на сайте <a href="http://www.bestcard.by" rel="nofollow">www.bestcard.by</a>. </li>
</ul>
 <br>
<p>
	 «Моцную картку» можно оформить в одном из банков-участников программы лояльности (подробности на <a href="http://www.bestcard.by" rel="nofollow">www.bestcard.by</a> в разделе «Банки-участники»).
</p>

    <?endif;?>

    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>