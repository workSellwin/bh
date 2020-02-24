<?

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetAdditionalCSS("/bitrix/panel/main/export_onliner_menu.css");

if ($APPLICATION->GetGroupRight("Yauheni.dpd") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_store",
        //"section" => "phpdevorg.exportonliner",
        "sort" => 500,
        "icon" => "export_onliner_menu_icon",
        "text" => GetMessage("YAUHENI_DPD"),
        "title" => GetMessage("YAUHENI_DPD"),
        "url" => "dpd_start.php?lang=" . LANGUAGE_ID,
        "items_id" => "yauheni.dpd",
        "items" => array()

    );
    /*$aMenu["items"][] = array(

        "text" => GetMessage("YAUHENI_DPD_DPD"),
        "title" => GetMessage("FORM_RESULTS_ALT"),
        "url" => "dpd_start.php?lang=" . LANGUAGE_ID,
        "icon" => "export_onliner_menu_icon",
        "page_icon" => "export_onliner_menu_icon",
        "items" => array()
    );*/


    return $aMenu;

}
return false;
?>