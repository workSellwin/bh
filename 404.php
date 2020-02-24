<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?><div class="error404">
	<div class="error404__ttl">
 <img width="500" alt="404_web.jpg" src="/upload/medialibrary/d1b/d1b3aeda1bf0689ab1b7f467ebf7551d.jpg" height="166" title="404_web.jpg" align="middle"><br>
	</div>
	<div class="error404__txt">
 <b>Данной страницы не существует =(</b><br>
		 Попробуйте начать Ваш поиск со страницы <a href="https://bh.by/catalog/professionalnyy-ukhod-za-volosami/">Каталог</a> или с <a href="https://bh.by/">Главной</a> страницы нашего сайта
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>