<?// if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?

use \Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Internals\DiscountCouponTable;

$returnVal = [
    "ERRORS" => [],
    "VALUES" => [],
    "SUCCESS" => 0
];

$allowedDiscount = [//key - discount_id
    's1' => [
        '322' => [
            "VALUE" => 30,
        ],
        '315' => [
            "VALUE" => 50,
        ],
        '312' => [
            "VALUE" => 100,
        ],
        '323' => [
            "VALUE" => 150,
        ],
//        '3038' => [
//            "VALUE" => 30,
//            "CUSTOM" => 'Y'
//        ],
    ]
];

try {

    if (empty($_GET['coupon']) || empty($_GET['site']) || empty($_GET['action'])) {
        throw new \Exception("error passing parameters");
    }

    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
    \Bitrix\Main\Loader::includeModule('sale');

    //$arCoupon = DiscountCouponsManager::getData($_GET['coupon']);

    $arCoupon = Bitrix\Sale\Internals\DiscountCouponTable::getList([
        'select' => ['ID', 'DISCOUNT_ID', 'DESCRIPTION', 'ACTIVE', 'COUPON', 'TYPE', 'MAX_USE', 'USE_COUNT'],
        'filter' => ['COUPON' => trim($_GET['coupon'])],
        'limit' => 1
    ])->fetch();
    //$returnVal["VALUES"][] = $arCoupon;
    if( empty($arCoupon) || intval($arCoupon['ID']) == 0 || $arCoupon['ACTIVE'] !== 'Y' ){
        throw new \Exception("coupon empty!");
    }

    if (is_array($arCoupon)) {

        if ($_GET['action'] == 'get-sum' || $_GET['action'] == 'apply-coupon') {

            if (isset($allowedDiscount[$_GET['site']]) && isset($allowedDiscount[$_GET['site']][$arCoupon['DISCOUNT_ID']])) {

              //  if ($allowedDiscount[$_GET['site']][$arCoupon['DISCOUNT_ID']]["CUSTOM"] !== 'Y') {
                    $returnVal["VALUES"][] = $allowedDiscount[$_GET['site']][$arCoupon['DISCOUNT_ID']]["VALUE"];
                    $returnVal["SUCCESS"] = 1;
//                }
//                elseif( intval($arCoupon['DESCRIPTION']) > 0){
//                    $returnVal["VALUES"][] = intval($arCoupon['DESCRIPTION']);
//                    $returnVal["SUCCESS"] = 1;
//                }

                if( $_GET['action'] == 'apply-coupon' && intval($returnVal["VALUES"][0]) > 0 ){

                    global $USER;
                    $UserAccount = new CSaleUserAccount();
                    $accountId = $UserAccount->UpdateAccount(
                        $USER->GetId(),
                        $returnVal["VALUES"][0],
                        'BYN'
                    );

                    $returnVal["VALUES"][] = $accountId;

                    if( intval($accountId) > 0){
                        Bitrix\Sale\Internals\DiscountCouponTable::update($arCoupon['ID'], [
                            "ACTIVE" => 'N',
                            "USE_COUNT" => $arCoupon['USE_COUNT'] + 1,
                        ]);
                    }
                    //$returnVal["VALUES"]['USER_ID'] = $USER->GetId();
                }
            }
            elseif( intval($arCoupon['DESCRIPTION']) > 0 ){

                $returnVal["VALUES"][] = intval($arCoupon['DESCRIPTION']);
                $returnVal["SUCCESS"] = 1;

                if( $_GET['action'] == 'apply-coupon' && $returnVal["VALUES"][0] > 0 ){

                    global $USER;
                    $UserAccount = new CSaleUserAccount();
                    $accountId = $UserAccount->UpdateAccount(
                        $USER->GetId(),
                        $returnVal["VALUES"][0],
                        'BYN'
                    );

                    $returnVal["VALUES"][] = $accountId;

                    if( intval($accountId) > 0){
                        Bitrix\Sale\Internals\DiscountCouponTable::update($arCoupon['ID'], [
                            "ACTIVE" => 'N',
                            "USE_COUNT" => $arCoupon['USE_COUNT'] + 1,
                        ]);
                    }
                    //$returnVal["VALUES"]['USER_ID'] = $USER->GetId();
                }
            }
        }
        else{
            throw new \Exception("action incorrect");
        }
    } else {
        throw new \Exception("coupon incorrect");
    }


} catch (\Exception $e) {
    $returnVal["ERRORS"][] = $e->getMessage();
} finally {
    echo json_encode($returnVal);
}
