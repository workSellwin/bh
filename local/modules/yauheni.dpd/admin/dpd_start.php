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
use Yauheni\Dpd\dpdNppY;
use Yauheni\Dpd\dpdNppN;
use Yauheni\Dpd\dpdPvz;
use Yauheni\Dpd\dpdOrder;


if (isset($_REQUEST['work_start'])) {
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');

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

if (isset($_REQUEST['SEND_ORDER_DPD']) && isset($_REQUEST['ORDER_ID'])) {
    $time = time() - 3600 * 24 * 1;
    $dpd = new dpd();
    if (is_numeric($_REQUEST['ORDER_ID'])) {
        $arOrder = $dpd->getArOrders($time, ['ID' => $_REQUEST['ORDER_ID']]);

        if (!empty($arOrder)) {
            foreach ($arOrder as $k => $value) {
                if ($value['DELIVERY']['ID'] == 19 || $value['DELIVERY']['ID'] == 44) {
                    //устонавливаем options для варианта без НПП

                    if (isset($_REQUEST['PRIZN']) && $_REQUEST['PRIZN'] == 'NPP_N') {
                        $dpdOrder = new dpdNppN();
                    }//устонавливаем options для варианта с НПП
                    elseif (isset($_REQUEST['PRIZN']) && $_REQUEST['PRIZN'] == 'NPP_Y') {
                        $dpdOrder = new dpdNppY();
                    }//устонавливаем options для варианта с ПВЗ
                    elseif (isset($_REQUEST['PRIZN']) && $_REQUEST['PRIZN'] == 'PVZ' && $_REQUEST['CODE_PVZ']) {
                        $value['TERMINAL_ADDRES'] = $_REQUEST['CODE_PVZ'];
                        $dpdOrder = new dpdPvz();
                    }

                    if ($dpdOrder) {
                        $dpdOrder->PICKUP_DATE = isset($_REQUEST['DATA1']) ? $_REQUEST['DATA1'] : date('Y-m-d');
                        //Записываем результат в заказ ("статус DPD")
                        $result = $dpdOrder->Run($value);
                    } else {
                        echo 'ERROR: Обьект dpd не создан';
                    }

                    if ($result) {
                        $arRes = $result->getErrorMessages();
                        $log = [
                            'SEND' => 'Отправка заказа через модуль DPD',
                            'ORDER_ID' => $value['ID'],
                            'KLIENT_NUMBER' => $dpdOrder->OPTIONS['KLIENT_NUMBER'],
                            'RESPONSE' => '',
                        ];
                        if (empty($arRes)) {
                            $arDataStatus = $result->getData();
                            $ORDER_NUM = $arDataStatus['ORDER_NUM'] ? $arDataStatus['ORDER_NUM'] : ' - ';
                            $ORDER_STATUS = $arDataStatus['ORDER_STATUS'] ? $arDataStatus['ORDER_STATUS'] : ' - ';
                            $log['RESPONSE'] = 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS;
                            echo 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS;
                            //AddOrderProperty(45, 'ORDER NUM: ' . $ORDER_NUM . '; ORDER STATUS: ' . $ORDER_STATUS, $value['ID']);
                        } else {
                            $log['RESPONSE'] = 'ERROR: ' . $arRes[0];
                            echo 'ERROR: ' . $arRes[0];
                            //AddOrderProperty(45, 'ERROR: ' . $arRes[0], $value['ID']);
                        }
                        AddMessage2Log($log);
                    }
                } else {
                    echo 'ERROR: В заказе ID ' . $_REQUEST['ORDER_ID'] . ' не верная служба доставки. Должна быть доставка с ID 44 или 19';
                }
            }
        } else {
            echo 'ERROR: Токого заказа с ID ' . $_REQUEST['ORDER_ID'] . ' нет';
        }
    } else {
        echo 'ERROR: ID заказа должно быть числом';
    }
}

if(!isset($_REQUEST['LOUD_TAB'])){
    $_REQUEST['LOUD_TAB'] = 'edit1';
}
//юрий васильевич кретов

//Логин 221022by, Пароль S9PHOO в DPD
?>


    <div class="adm-detail-block" id="tabControl_layout" style="margin-top: 10px">
        <div class="adm-detail-tabs-block" id="tabControl_tabs" style="left: 0px;">
            <span id="tab_cont_edit1" class="adm-detail-tab  <?=$_REQUEST['LOUD_TAB'] == 'edit1' ? 'adm-detail-tab-active' : '' ?>" onclick="tabControl('edit1');">Добавление заказа</span>
            <?/*
            <span id="tab_cont_tab_mail" class="adm-detail-tab <?=$_REQUEST['LOUD_TAB'] == 'tab_mail' ? 'adm-detail-tab-active' : '' ?>"  onclick="tabControl('tab_mail');">Загрузить location</span>
            <span id="tab_cont_edit6" class="adm-detail-tab <?=$_REQUEST['LOUD_TAB'] == 'edit6' ? 'adm-detail-tab-active' : '' ?>" onclick="tabControl('edit6');">Загрузить Terminal</span>
            */?>
        </div>
        <div class="adm-detail-content-wrap">

            <div class="adm-detail-content" id="edit1" style="<?=$_REQUEST['LOUD_TAB'] == 'edit1' ? 'display: block;' : 'display: none;' ?>">
                <div class="adm-detail-title">Добавление заказа в DPD</div>
                <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <input type="hidden" name="LOUD_TAB" value="edit1">
                        <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                            <tbody>
                            <tr>
                                <td class="adm-detail-content-cell-l">ID заказа:</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="ORDER_ID" required type="text" value="<?= $_REQUEST['ORDER_ID'] ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Дата (в формате YY-mm-dd):</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="DATA1" placeholder="Дата в формате YY-mm-dd" required type="text"
                                           value="<?= $_REQUEST['DATA1'] ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Пункт получения ПВЗ</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="CODE_PVZ" type="text" placeholder="Код в формате 308M"
                                           value="<?= $_REQUEST['CODE_PVZ'] ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Без НПП</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="PRIZN"
                                           checked="checked"
                                           type="radio" <?= $_REQUEST['PRIZN'] == 'NPP_N' ? 'checked' : '' ?>
                                           value="NPP_N"/ >
                                </td>
                            </tr>
                            <tr >
                                <td class="adm-detail-content-cell-l">С НПП</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="PRIZN"
                                           type="radio" <?= $_REQUEST['PRIZN'] == 'NPP_Y' ? 'checked' : '' ?>
                                           value="NPP_Y"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Пункт ПВЗ</td>
                                <td class="adm-detail-content-cell-r">
                                    <input name="PRIZN" type="radio" <?= $_REQUEST['PRIZN'] == 'PVZ' ? 'checked' : '' ?>
                                           value="PVZ"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <div class="adm-info-message">
                                            Обратите внимание, чтобы создать заказ для пункта самовывоза необходимо: 1-укозать код ПВЗ, 2-поставить галочку `Пункт ПВЗ`.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <button type="submit" style="height: 35px; border-radius: 5px;" class="adm-btn-save" name="SEND_ORDER_DPD" value="Y">Создать заказ В DPD</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <div class="adm-detail-content" id="tab_mail" style="<?=$_REQUEST['LOUD_TAB'] == 'tab_mail' ? 'display: block;' : 'display: none;' ?>">
                <div class="adm-detail-title">Загрузить таблицу location</div>
                <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <input type="hidden" name="LOUD_TAB" value="tab_mail">
                        <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                            <tbody>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <?if(isset($_REQUEST['LOUD_LOCATION_BTN']) && $_REQUEST['LOUD_LOCATION_BTN'] == 'Y'){
                                            $location = new \Yauheni\Dpd\dpdBase();
                                            $location->loadingLocation();
                                        }?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <div class="adm-info-message">
                                            Загрузка таблицы Loacation из сервеса DPD
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <button type="submit" style="height: 35px; border-radius: 5px;" class="adm-btn-save" name="LOUD_LOCATION_BTN" value="Y">Загрузить</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <div class="adm-detail-content" id="edit6" style="<?=$_REQUEST['LOUD_TAB'] == 'edit6' ? 'display: block;' : 'display: none;' ?>">
                <div class="adm-detail-title">Загрузить таблицу Terminal</div>
                <div class="adm-detail-content-item-block" style="height: auto; overflow-y: visible;">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <input type="hidden" name="LOUD_TAB" value="edit6">
                        <table class="adm-detail-content-table edit-table" id="edit1_edit_table" style="opacity: 1;">
                            <tbody>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <?if(isset($_REQUEST['LOUD_TERMINAL_BTN']) && $_REQUEST['LOUD_TERMINAL_BTN'] == 'Y'){
                                            $terminal = new \Yauheni\Dpd\dpdBase();
                                            $terminal->loadingLocation();
                                        }?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <div class="adm-info-message">
                                            Загрузка таблицы Terminal из сервеса DPD
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div class="adm-info-message-wrap" align="center">
                                        <button type="submit" style="height: 35px; border-radius: 5px;" class="adm-btn-save" name="LOUD_TERMINAL_BTN" value="Y">Загрузить</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <div class="adm-detail-content-btns-wrap" id="tabControl_buttons_div" style="left: 0px;">
                <div class="adm-detail-content-btns"></div>
            </div>
        </div>
    </div>

<?

CJSCore::RegisterExt('visual_discounts_js', array(
    'js' => array(
        '/bitrix/js/yauheni.discount/jquery.min.js',
        '/bitrix/js/yauheni.discount/jquery-ui.min.js',
        '/bitrix/js/yauheni.discount/scripts.js',
    ),
    'css' => array(
        '/bitrix/css/yauheni.discount/jquery-ui.css',
        '/bitrix/css/yauheni.discount/style.css',
    ),
    'rel' => array('jquery'),
));

CJSCore::Init(array("visual_discounts_js"));

?>
    <script>
        function tabControl(tab) {
            $('.adm-detail-content-wrap .adm-detail-content').each(function () {
                $(this).hide();
            });
            $('.adm-detail-tabs-block span').each(function () {
                $(this).removeClass('adm-detail-tab-active');
            });

            $('.adm-detail-tabs-block span#tab_cont_'+tab).addClass('adm-detail-tab-active');

            $('#'+tab).show();
        }
    </script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>