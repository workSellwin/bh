<?php

function updateSortIndex(){

    \Bitrix\Main\Loader::includeModule('iblock');
    $res = CIBlockElement::GetList(
        [],
        ["IBLOCK_ID" => 2, "PROPERTY_SALON_VALUE" => 'Да', '!SORT' => 5000],
        false,
        false,
        ['SORT', 'ID', 'NAME', 'PROPERTY_SALON_VALUE']
    );

    $oElement = new \CIBlockElement();

    while($fields = $res->fetch()){
        $oElement->update($fields["ID"], ['SORT' => 5000]);
    }

    return "updateSortIndex();";
}
