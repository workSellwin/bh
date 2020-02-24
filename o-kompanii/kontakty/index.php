<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><?$APPLICATION->IncludeComponent(
	"Mitlab:contacts",
	".default",
	Array(
		"ADDRESS" => "223049, Республика Беларусь, Минская область, Щомыслицкий с/с, Торгово-логистический центр, Щомыслица, 28/3",
		"COMPONENT_TEMPLATE" => ".default",
		"EMAIL" => array(0=>"info@bh.by",1=>"",),
		"FAX" => array(),
		"PHONE" => array(0=>"7577",1=>"",)
	)
);?>
<div style="margin-top: -8%;">
<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A485d625eb88898d060331d44612ad3dcca9cdeda996a2fc2cf100f622e686fb6&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
</div>
<div style="text-align:center; padding-top:35px;">
	<p>
		 ООО «Сэльвин-Логистик»<br>
		 Юридический адрес – Минская обл, Минский р-н, Щомыслицкий с/с, ТЛЦ "Щомыслица" 28/2, к. 40<br>
		 Тел\факс: +375 (17) 269-33-33/34<br>
		 р/с BY08PJCB30120292331000000933 (BYN)<br>
		 ОАО «Приорбанк», ЦБУ 115, BIC PJCBBY2X<br>
		 Адрес банка: 220002, г. Минск, ул. Кропоткина, д. 91
	</p>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>