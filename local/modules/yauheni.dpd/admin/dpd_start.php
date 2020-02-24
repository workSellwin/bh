<?
/** @global CMain $APPLICATION */

use \Bitrix\Main,
    \Bitrix\Main\Application,
    \Bitrix\Main\Loader,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\SiteTable,
    \Bitrix\Main\UserTable,
    \Bitrix\Main\Config\Option,
    \Bitrix\Sale;
use Yauheni\Dpd\dpd;
use Yauheni\Dpd\dpdNppN;
use Yauheni\Dpd\dpdNppY;


if (isset($_REQUEST['work_start'])) {
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/modules/yauheni.dpd/sdk/src/autoload.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("yauheni.dpd");
CModule::IncludeModule("lui.delivery");


$POST_RIGHT = $APPLICATION->GetGroupRight("main");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm("Доступ запрещен");

$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">' .
    '<tr class="heading">' .
    '<td>Текущее действие</td>' .
    '<td width="1%">&nbsp;</td>' .
    '</tr>' .
    '</table>';

$aTabs = array(array("DIV" => "edit1", "TAB" => "Обработка"));
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle("DPD");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');
ini_set('display_errors', 1);
ini_set('max_execution_time', 30);



if(isset($_REQUEST['SEND_ORDER_DPD']) && isset($_REQUEST['ORDER_ID'])){
    $time = time() - 3600 * 24 * 1;
    $dpd = new dpd();
    if(is_numeric ($_REQUEST['ORDER_ID'])){
        $arOrder = $dpd->getArOrders($time, ['ID'=>$_REQUEST['ORDER_ID']]);
        if(isset($_REQUEST['NPP']) && $_REQUEST['NPP'] == 'Y'){
            $dpdOrder = new dpdNppY('1104009153', '9CCE8C68288349CBFE56E5D420CFA807268B6845');
        }else{
            $dpdOrder = new dpdNppN('1104009121', 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF');
        }
        if(!empty($arOrder)) {
            foreach ($arOrder as $k => $value) {
                //Записываем результат в заказ ("статус DPD")
                $dpdOrder->PICKUP_DATE = date('Y-m-d');
                $result = $dpdOrder->Run($value);
                if ($result) {
                    $arRes = $result->getErrorMessages();
                    if (empty($arRes)) {
                        $arDataStatus = $result->getData();
                        $ORDER_NUM = $arDataStatus['ORDER_NUM'] ? $arDataStatus['ORDER_NUM'] : ' - ';
                        $ORDER_STATUS = $arDataStatus['ORDER_STATUS'] ? $arDataStatus['ORDER_STATUS'] : ' - ';
                        echo 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID'];
                        AddOrderProperty(45, 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID']);
                    } else {
                        echo 'ERROR: ' . $arRes[0], $value['ID'];
                        AddOrderProperty(45, 'ERROR: ' . $arRes[0], $value['ID']);
                    }
                }
            }
        }else{
            echo 'ERROR: Токого заказа с ID '.$_REQUEST['ORDER_ID'].' нет';
        }
    }else{
        echo 'ERROR: ID заказа должно быть числом';
    }
}

?>

    <form enctype="multipart/form-data" action="" method="POST">
        <table>
            <tr>
                <td>ID заказа:</td>
                <td>
                    <input name="ORDER_ID" type="text"/>
                </td>
            </tr>
            <tr>
                <td>С НПП</td>
                <td>
                    <input name="NPP" type="checkbox" value="Y"/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="SEND_ORDER_DPD" value="Y">Создать заказ В DPD</button></td>
            </tr>
        </table>
    </form>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>