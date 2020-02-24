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


if (isset($_REQUEST['work_start']))
{
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

$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущее действие</td>'.
    '<td width="1%">&nbsp;</td>'.
    '</tr>'.
    '</table>';

$aTabs = array(array("DIV" => "edit1", "TAB" => "Обработка"));
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle("DPD");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");



//------------------------------------------------------------------------------------------------------------------------


require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('kocmo.exchange');
//phpinfo();
//error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('max_execution_time', 30);





$time = time() - 3600 * 24 * 1;
$dpd = new dpd();

$arOrder = $dpd->getArOrders($time);


foreach ($arOrder as $k => $value) {

    //Доставка курьером (РБ) = 19
    //if ($value['DELIVERY']['ID'] == 19 && $value['PERSON_TYPE_ID'] == 1) {
        //оплачен на сайте
        $IS_NPP = true; //true - с NPP ; false - без NPP
        if(/*$value['IS_PAID'] == 'Y'*/ !$IS_NPP){
            //отпровляеться в DPD по РБ без НПП (1104009121)
            $dpd->options['KLIENT_NUMBER'] = '1104009121';
            $dpd->options['KLIENT_NUMBER_BY'] = '1104009121';
            $dpd->options['KLIENT_KEY'] = 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF';
        }//не оплачен
        else{
            //отпровляеться в DPD по РБ с НПП (1104009153)
            $dpd->options['KLIENT_NUMBER'] = '1104009153';
            $dpd->options['KLIENT_NUMBER_BY'] = '1104009153';
            $dpd->options['KLIENT_KEY'] =  '9CCE8C68288349CBFE56E5D420CFA807268B6845';
            $IS_NPP = true;
        }

        define('yandex_apikey', '91227d27-4cba-45e6-9179-7f0dc075d31d');
        $ob = new \Lui\Delivery\YandexApi();
        $q = $value['RESPONSE_YANDEX'];
        $arYandex = $ob->GetDataYandex($q);

        $config = new \Ipol\DPD\Config\Config($dpd->options);
        $shipment = new \Ipol\DPD\Shipment($config);

        if(!$IS_NPP){
            $ID_CITY = 674; //Беларусь, обл Минская, Минский, г Минск
        }else{
            $ID_CITY = 2588; //Беларусь, обл Минская, Минский, д Щомыслица
        }

       // $shipment->setSender($ID_CITY);
        $shipment->setSender(5505);

        $arAddres = $shipment->getDB()->getTable('location')->getAddress($arYandex);
        if($arAddres['ID']){
            $shipment->setReceiver($arAddres['ID']);
        }
        /**
         * Отправка от
         * false - от двери
         * true  - от терминала
         */
        $shipment->setSelfPickup(true);

        /**
         * Отправка до
         * false - от двери
         * true  - от терминала
         */
        $shipment->setSelfDelivery(false);

//  5505 2900769247
    $shipment->setItems([
            [
                'NAME' => 'косметические товары',
                'QUANTITY' =>  1,
                'PRICE' => $value['PRICE'],
                'NPP' => $IS_NPP ? $value['PRICE']: '',
                'VAT_RATE' => 'Без НДС',
                'WEIGHT' => $value['ORDER_WEIGHT'] <= 1000 ? 1000 : $value['ORDER_WEIGHT'],
                /*'DIMENSIONS' => [
                    'LENGTH' => 200,
                    'WIDTH' => 100,
                    'HEIGHT' => 50,
                ]*/
            ],
        ]);



        $order = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order')->makeModel();
        $order->setShipment($shipment);
        // -------------------- Отправитель -----------------------------------------------------------
        $order->senderName = 'ООО "Сэльвин-Логистик" - TEST TEST TEST'; //Название компании
        $order->senderFio = $IS_NPP ? 'BH.BY ALL.BH.BY' : 'Березнева Анна Александровна'; //Контактные лица
        $order->senderPhone = '+375447480824'; //Контактные телефоны
        $order->senderStreet = $IS_NPP ?'Щомыслица' : 'Купревича';
        $order->senderHouse = '28'; //номер дома
        $order->senderKorpus = '2'; //корпус
        if(!$IS_NPP) {
            $order->senderOffice = '40'; //номер офиса
        }
        $order->senderNeedPass = $IS_NPP ? 'N' : 'Y';
        //---------------------- Получатель -----------------------------------------------------------
        $order->receiverName = $value['FIO'];
        $order->receiverFio = $value['FIO'];
        $order->receiverPhone = $value['PHONE'];
        $ROOM = $value['ROOM'] ? ', кв. '.$value['ROOM'] : '';
        $order->receiverStreet = $arYandex['Components']['street']; //улица
        $order->receiverHouse = $arYandex['Components']['house'].$ROOM; //номер дома
        //---------------------------------------------------------------------------------------------
        $order->pickupDate = /*date('Y-m-d');*/ date('Y-m-d', time() + 86400);
        $order->pickupTimePeriod = '9-18';

        if(!$IS_NPP){
            $order->setUnitLoads($value['PRICE']);
        }else{
            //$order->setNpp('Y'); //включить НПП
            //$order->setPriceDelivery(0);
            //$order->serviceVariant = 'ТД';
        }

        $order->orderId = 2268; // $value['ID'];
        // указываем тариф отправки
        $order->serviceCode = 'CSM';
        // в нашем случае это терминал - дверь
        $order->senderTerminalCode = 196058326; //105
        //$order->serviceVariant = ['SELF_PICKUP' => true, 'SELF_DELIVERY' => false];

        $order->cargoCategory = 'косметические товары';

        $result = $order->dpd()->create();

        PR($order);
        PR($result);

        if($result){
            $arRes = $result->getErrorMessages();
            if(empty($arRes)){
                $arDataStatus = $result->getData();
                $ORDER_NUM = $arDataStatus['ORDER_NUM'] ? $arDataStatus['ORDER_NUM'] : ' - ' ;
                $ORDER_STATUS = $arDataStatus['ORDER_STATUS']? $arDataStatus['ORDER_STATUS'] : ' - ' ;
                AddOrderProperty(45, 'ORDER NUM: '.$ORDER_NUM.'; ORDER STATUS: '.$ORDER_STATUS, $value['ID']);
            }else{
                AddOrderProperty(45, 'Error: '.$arRes[0], $value['ID']);
            }
        }
        


        //die();
    //}

}
//pr($order->dpd(), 14);




/*$PriceType = array();
$dbPriceType = CCatalogGroup::GetList(
    array("ID" => "ASC"),
    array(),
    false,
    false,
    $arSelectFields = array()
);
while ($arPriceType = $dbPriceType->Fetch())
{
    $PriceType[$arPriceType['ID']] = $arPriceType;
}*/

//PR($PriceType);



?>



    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
        <?
        echo bitrix_sessid_post();

        //$tabControl->Begin();
        //$tabControl->BeginNextTab();
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <?
        //$arSiteID = array('s1', 's2');
        ?>
        <form enctype="multipart/form-data" action="" method="POST">
            <table>
                <tr>
                    <td>Пользователь ID:</td>
                    <td>
                        <input name="USER_ID" type="text"/>
                    </td>
                </tr>
                <tr>
                    <td>Группа пользователя:</td>
                    <td>
                        <input name="USER_GROUPS" type="text" required />
                    </td>
                </tr>
                <tr>
                    <td>Префикс к названию скидки:</td>
                    <td>
                        <input name="pref" type="text" required/>
                    </td>
                </tr>
                <tr>
                    <td>Приоритет скидки:</td>
                    <td>
                        <input name="priority" type="text" required/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" name="sendfile" value="Send File">Создать скидки</button></td>
                </tr>
            </table>
        </form>
        <!--<script>
            $(document).on("click",".show_calendar",function(){
                BX.calendar({node: this, value: new Date(), field: this, bTime: false});
            });
        </script>-->
        <?
        //$tabControl->End();
        ?>
    </form>





<?
//1104009121  ||  CA461909F1DFED320BFBCA5B90A002AD5756D6BF - без наложенного плотежа (на сайте расплотились картой - статус оплачено)
//1104009153 - c наложенным плотежём (оплата при получении - статус не оплачено)

/*$options  = [
    'KLIENT_NUMBER'   => '1104009121',
    'KLIENT_NUMBER_BY'   => '1104009121',//1104009153
    'KLIENT_KEY'      => 'CA461909F1DFED320BFBCA5B90A002AD5756D6BF',
    'KLIENT_CURRENCY' => 'BYN',
    'IS_TEST'         => false,
    'DB' => [    'DSN' => 'mysql:dbname=sitemanager0;host=localhost',
        'PASSWORD' => 'W-)xph?L2g89PBnzygSk',
        'USERNAME' => 'bitrix0',
    ],
];
$config  = new \Ipol\DPD\Config\Config($options);*/

//--------------------- Загрузка location ---------------------------------------------------------------------
/*
$ob = new \PDO('mysql:dbname=sitemanager0;host=localhost', 'bitrix0', 'W-)xph?L2g89PBnzygSk');

$config  = new \Ipol\DPD\Config\Config($options);
$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('location');
$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

$loader = new \Ipol\DPD\DB\Location\Agent($api, $table);

$step   = isset($_GET['step']) ? $_GET['step'] : 1;
$pos    = isset($_GET['pos']) ? $_GET['pos'] : null;

if ($step < 2) {
    $ret = $loader->loadAll($pos, ['BY']);

    if ($ret === true) {
        print 'LOAD LOCATIONS STEP 1: FINISH';
        print '<a href="?step=2" id="continue">continue</a><br>';
        print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
    } else {
        print sprintf('LOAD LOCATIONS STEP 1: %s%%<br>', round($ret[0] / $ret[1] * 100));
        print '<a href="?step=1&pos='. $ret[0] .'" id="continue">continue</a><br>';
        print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
    }
}
elseif ($step < 3) {
    $ret = $loader->loadCashPay($pos, ['BY']);

    if ($ret === true) {
        print 'LOAD LOCATIONS STEP 2: FINISH';
    } else {
        $pos = explode(':', $ret[0]);

        print sprintf('LOAD LOCATIONS STEP 2: %s%%<br>', round(end($pos) / $ret[1] * 100));
        print '<a href="?step=2&pos='. $ret[0] .'" id="continue">continue</a><br>';
        print '<br>'. $ret[1];
        print '<script>setTimeout(function(){document.getElementById("continue").click();}, 2000)</script>';
    }
}
*/
//------------------------------------- Загрузка terminal ----------------------------------------------------------------------------------
/*
$ob = new \PDO('mysql:dbname=sitemanager0;host=localhost', 'bitrix0', 'W-)xph?L2g89PBnzygSk');
$config  = new \Ipol\DPD\Config\Config([
    'KLIENT_NUMBER'   => '1104009153',
    'KLIENT_KEY'      => '9CCE8C68288349CBFE56E5D420CFA807268B6845',
    'KLIENT_CURRENCY' => 'BYN',
    'IS_TEST'         => false,
]);
$table   = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('terminal');
$api     = \Ipol\DPD\API\User\User::getInstanceByConfig($config);

$loader = new \Ipol\DPD\DB\Terminal\Agent($api, $table);

$step   = isset($_GET['step']) ? $_GET['step'] : 1;
$pos    = isset($_GET['pos'])  ? $_GET['pos']  : null;

if ($step < 2) {
    $ret = $loader->loadUnlimited($pos);
    //$ret = $loader->loadAll($pos, ['BY']);

    if ($ret === true) {
        print 'LOAD TERMINALS STEP 1: FINISH';
        print '<a href="?step=2" id="continue">continue</a><br>';
        print '<script>document.getElementById("continue").click()</script>';
    } else {
        print sprintf('LOAD TERMINALS STEP 1: %s%%<br>', round($ret[0] / $ret[1] * 100));
        print '<a href="?step=1&pos='. $ret[0] .'" id="continue">continue</a><br>';
        print '<script>document.getElementById("continue").click()</script>';
    }
} elseif ($step < 3) {
    $ret = $loader->loadLimited($pos);

    if ($ret === true) {
        print 'LOAD TERMINALS STEP 2: FINISH';
    } else {
        $pos = explode(':', $ret[0]);

        print sprintf('LOAD TERMINALS STEP 2: %s%%<br>', round(end($pos) / $ret[1] * 100));
        print '<a href="?step=2&pos='. $ret[0] .'" id="continue">continue</a><br>';
        print '<script>document.getElementById("continue").click()</script>';
    }
}
//step=2&pos=BY:0


*/

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>