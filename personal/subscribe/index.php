<?define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписки");
?><?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "main", Array(
	"COMPONENT_TEMPLATE" => ".default",
		"USE_PERSONALIZATION" => "Y",	// Определять подписку текущего пользователя
		"SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
		"PAGE" => "/personal/subscribe/subscr_edit.php",	// Страница редактирования подписки (доступен макрос /)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>