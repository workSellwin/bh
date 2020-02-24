<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
// global $USER;
// //if($USER->IsAuthorized()){
// if($_GET["login"]=="yes"){
// 	LocalRedirect("/auth/?error_auth=Y");
// }elseif($USER->IsAuthorized()){
// 	LocalRedirect("/personal/");
// }
use Bitrix\Main\Application;

global $USER;
if (!is_object($USER)) $USER = new CUser;

$_POST["USER_REMEMBER"] = 'Y';
$arAuthResult = $USER->Login($_POST["USER_LOGIN"], $_POST["USER_PASSWORD"], $_POST["USER_REMEMBER"]);
$APPLICATION->arAuthResult = $arAuthResult;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$uriString = $request->getRequestUri();

$pattern = "/^auth/";
$script = (preg_match($pattern, $uriString)) ? "window.location.href='" . SITE_DIR . "/auth/';" : "window.location.reload();";

if ($arAuthResult == 1) {
    echo '<div class="complite">Успешно авторизованы, страница будет перезагружена!<script>' . $script . '</script></div>';
} else {
    echo '<div class="err_mess">Неверный логин, или пароль!</div>';
}
?>
