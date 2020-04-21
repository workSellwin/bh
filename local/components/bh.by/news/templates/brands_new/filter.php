<div class="product__sort">
    <div>
        <? if (!empty($_GET["sort"])): ?>
            <style>
                <?if($_GET["sort"] == 'shows'):?>
                .product__sort [data-sort-type="popularity"] {
                    font-weight: bold;
                }

                <?endif;?>
                <?if($_GET["sort"] == 'property_newproduct'):?>
                .product__sort [data-sort-type="new"] {
                    font-weight: bold;
                }

                <?endif;?>
                <?if($_GET["sort"] == 'PROPERTY_PRICE_SORT_2'):?>
                .product__sort [data-sort-type="price"] {
                    font-weight: bold;
                }

                <?endif;?>
                <?if($_GET["sort"] == 'PROPERTY_PRICE_SORT_2_DISCOUNT'):?>
                .product__sort [data-sort-type="discount"] {
                    font-weight: bold;
                }

                <?endif;?>
            </style>
        <? endif; ?>
        <? if ($_GET["sort"] == "shows" && $_GET["method"] == "desc"): ?>
            <a class="product__sort-lnk product__sort-lnk_active"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=shows&method=asc" data-sort-type="popularity">
                По популярности
            </a>
        <? else: ?>
            <a class="product__sort-lnk" href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=shows&method=desc"
               data-sort-type="popularity">
                По популярности
            </a>
        <? endif; ?>
        <? if ($_GET["sort"] == "property_newproduct" && $_GET["method"] == "desc"): ?>
            <a class="product__sort-lnk product__sort-lnk_active"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=property_newproduct&method=asc"
               data-sort-type="new">
                По новинкам</a>
        <? else: ?>
            <a class="product__sort-lnk"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=property_newproduct&method=desc"
               data-sort-type="new">
                По новинкам</a>
        <? endif; ?>
        <? /*if($_GET["sort"] == "catalog_PRICE_1" && $_GET["method"] == "desc"):?>
			<a class="product__sort-lnk product__sort-lnk_active" href="<?=$arResult["SECTION_PAGE_URL"]?>?sort=catalog_PRICE_1&method=asc">По цене</a>
		<?else:?>
			<a class="product__sort-lnk" href="<?=$arResult["SECTION_PAGE_URL"]?>?sort=catalog_PRICE_1&method=desc">По цене</a>
		<?endif;*/ ?>
        <?
        $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_2';
        $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_2_DISCOUNT';
        $arGroup = $USER->GetUserGroup($USER->GetID());
        if (in_array(14, $arGroup) !== false) {
            $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_14';
            $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_14_DISCOUNT';
        } elseif (in_array(10, $arGroup) !== false) {
            $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_10';
            $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_10_DISCOUNT';
        }
        ?>
        <? if ($_GET["sort"] == $SORT_PRICE_CODE && $_GET["method"] == "desc"): ?>
            <a class="product__sort-lnk product__sort-lnk_active"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_PRICE_CODE ?>&method=asc"
               data-sort-type="price">
                По цене
            </a>
        <? else: ?>
            <a class="product__sort-lnk"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_PRICE_CODE ?>&method=desc"
               data-sort-type="price">
                По цене
            </a>
        <? endif; ?>

        <? if ($_GET["sort"] == $SORT_DISCOUNT_CODE && $_GET["method"] == "desc"): ?>
            <a class="product__sort-lnk product__sort-lnk_active"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_DISCOUNT_CODE ?>&method=asc"
               data-sort-type="discount">
                По скидке
            </a>
        <? else: ?>
            <a class="product__sort-lnk"
               href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_DISCOUNT_CODE ?>&method=desc"
               data-sort-type="discount">
                По скидке
            </a>
        <? endif; ?>
    </div>
</div>
<div style="clear: right"></div>


<? if ($_GET["sort"] == "shows" || $_GET["sort"] == "property_newproduct" || $_GET["sort"] == $SORT_PRICE_CODE || $_GET["sort"] == $SORT_DISCOUNT_CODE) {
    $arParams["ELEMENT_SORT_FIELD"] = $_GET["sort"];
    $arParams["ELEMENT_SORT_ORDER"] = $_GET["method"];
    //PROPERTY_MAXIMUM_PRICE
} ?>