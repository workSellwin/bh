<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$s = json_decode(file_get_contents('php://input'), true);
$phone = $s['phone'];
$href = $s['href'];
$vendorCode = $s['vendor_code'];

$response = ["STATUS" => "", "ERRORS" => []];

if (strlen($phone) > 7 && strlen($href) > 4 && CModule::IncludeModule('iblock')) {

    $PROP = [
        "PHONE" => $phone,
        "HREF" => $href,
        "VENDOR_CODE" => $vendorCode,
    ];

    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => 39,
        "PROPERTY_VALUES" => $PROP,
        "NAME" => $phone,
    );

    $el = new CIBlockElement;

    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        $response["STATUS"] = 'OK';

        echo json_encode($response);
    } else {
        $response["STATUS"] = 'ERROR';
        echo json_encode($response);
    }
} else {
    $response["STATUS"] = 'ERROR';
   echo json_encode($response);;
}
