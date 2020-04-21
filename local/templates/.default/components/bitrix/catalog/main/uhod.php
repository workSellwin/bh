<?php
$code = explode('/', $APPLICATION->GetCurPage())[3];
//PR( $code );
switch($code){
    case 'ukhod-za-volosami':

        $arResult['VARIABLES'] = [
            'SECTION_CODE_PATH' => 'professionalnyy-uhod-za-volosami/ukhod-za-volosami',
            'SECTION_ID' => 414,
            'SECTION_CODE' => 'ukhod-za-volosami',
        ];
        break;
    case 'stailing':

        $arResult['VARIABLES'] = [
            'SECTION_CODE_PATH' => 'professionalnyy-uhod-za-volosami/stailing',
            'SECTION_ID' => 115,
            'SECTION_CODE' => 'stailing',
        ];
        break;
    case 'okrashivanie':

        $arResult['VARIABLES'] = [
            'SECTION_CODE_PATH' => 'professionalnyy-uhod-za-volosami/okrashivanie',
            'SECTION_ID' => 116,
            'SECTION_CODE' => 'okrashivanie',
        ];
        break;
    case 'oksidenty-proyaviteli-aktivatory':

        $arResult['VARIABLES'] = [
            'SECTION_CODE_PATH' => 'professionalnyy-uhod-za-volosami/oksidenty-proyaviteli-aktivatory',
            'SECTION_ID' => 117,
            'SECTION_CODE' => 'oksidenty-proyaviteli-aktivatory',
        ];
        break;
    default:
        $arResult['VARIABLES'] = [
            'SECTION_CODE_PATH' => 'professionalnyy-uhod-za-volosami',
            'SECTION_ID' => 114,
            'SECTION_CODE' => 'professionalnyy-uhod-za-volosami',
        ];
        break;
}

include "section.php";
//PR($arResult, false, true);