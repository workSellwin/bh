<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Sale;
use Bitrix\Main\Application;
use \Bitrix\Main\Loader;

include $_SERVER['DOCUMENT_ROOT'] . '/1c_exchange/Service1cUpdateStatus.php';
global $APPLICATION;

$request = Application::getInstance()->getContext()->getRequest();

$arResponse = ['status' => 'ok', 'error' => [],];
if (!$json = json_decode($request->get("param"), true)) $arResponse['error'][] = 'No Json!';
if (!$login = (string)trim($request->get("login"))) $arResponse['error'][] = 'No login!';
if (!$password = (string)trim($request->get("password"))) $arResponse['error'][] = 'No password!';

if (empty($arResponse['error'])) {

    $USER = new \CUser;
    $arAuthResult = $USER->Login($login, $password, "N");
    $APPLICATION->arAuthResult = $arAuthResult;

    if (!$USER->IsAuthorized()) {
        $arResponse['error'][] = 'Authorization failed';
    } else {

        $ob = new Service1cUpdateStatus();
        if (is_object($ob) and $ob instanceof Service1cOrder) {

            if (!$id = (int)$json["id"]) $arResponse['error'][] = 'No id!';

            Loader::includeModule('sale');
            $order = Sale\Order::load($id);
            if (is_object($order)) {
                try {
                    $arResponse = $ob->run($json, $order);
                    $order->refreshData();
                    $order->save();
                } catch (\Exception $e) {
                    $arResponse['Exception'][] = $e->getMessage();
                }

            } else {
                $arResponse['error'][] = 'No order ID';
            }
        } elseif (is_object($ob) and $ob instanceof Service1cUsers) {
            $arResponse = $ob->run($json);
        } else {
            $arResponse['error'][] = 'No Action';
        }
        $USER->Logout();
    }
}

if ($arResponse['error']) {
    $arResponse['status'] = 'no';
}
header('Content-type: application/json');
echo json_encode($arResponse);
