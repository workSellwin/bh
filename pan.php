<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("pan");
?>
<?

CModule::IncludeModule("iblock");


$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>3, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
    $arFieldsOffers[] = $ob->GetFields();

}


foreach ($arFieldsOffers as $arItem)
{

    $db_props = CIBlockElement::GetProperty(3, $arItem['ID'], array("sort" => "asc"), Array("CODE"=>"CML2_LINK"));
    if($ar_props = $db_props->Fetch())
        $product = IntVal($ar_props["VALUE"]);
    else
        $product = false;

    if($product)
    {
        $db_old_groups = CIBlockElement::GetElementGroups($product, true);
        $ar_new_groups = Array();
        while($ar_group = $db_old_groups->Fetch())
            $ar_new_groups[] = $ar_group["ID"];
    }

    $nav = CIBlockSection::GetNavChain(false,$ar_new_groups[0]);
    if($arSectionPath = $nav->GetNext()){

        CIBlockElement::SetPropertyValuesEx($arItem['ID'], false, array('SECTION_MAIN' => $arSectionPath['NAME']));
    }

}




?>
<pre>
    <?print_r($arFieldsOffers);?>
</pre>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>