<?
define("STOP_STATISTICS", true);
define("NO_AGENT_CHECK", true);

require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$serviceID = (int) $request->getQuery("service");

if($serviceID > 0 && Bitrix\Main\Loader::IncludeModule("iblock"))
{

	$arFilter = Array("IBLOCK_TYPE" => "news", "IBLOCK_ID" => 19, "ID" => $serviceID);
	$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_LOCATION");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount" => 1), $arSelect);
	if($row = $res->GetNextElement())
	{
		$prop = $row->GetProperties();
		$arIDs = $prop["LOCATION"]["VALUE"];

		if(!empty($arIDs))
		{
			$arFilter = Array("IBLOCK_ID" => 10, "ID" => $arIDs, "IBLOCK_TYPE" => "news");
			$arSelect = Array("NAME","ID","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_COORD","PROPERTY_ADDR", "IBLOCK_ID");

			$res2 = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while($ob2 = $res2->GetNext())
			{
				if($ob2["PROPERTY_COORD_VALUE"]){
					$img = "";
					if($ob2["PREVIEW_PICTURE"]){
						$img = '<img src="'.CFile::GetPath($ob2["PREVIEW_PICTURE"]).'"><br>';
					}
					$coord = explode(",",$ob2["PROPERTY_COORD_VALUE"]);
					$points[] = array(
						"type" => "Feature",
						"id" => $ob2["ID"],
						"geometry"=> array(
							"type"=> "Point",
							"coordinates" => $coord
						),
						"properties" => array(
							"clusterCaption" => $ob2["NAME"],
							"balloonContentBody" => '<div style="max-width:250px;"><b>'.$ob2["NAME"].'</b><br>'.$img.$ob2["PROPERTY_ADDR_VALUE"].$ob2["PREVIEW_TEXT"].'</div>',
						),
					);
				}
			}
			$objectManager = \Bitrix\Main\Web\Json::encode(Array(
				"type"	=> "FeatureCollection",
				"features" => $points
			));
			?>
			<div class="popup_salon" >
				<script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU" ></script>
				<div id="map" style="height: 100%"></div>
				<script>
					ymaps.ready(init);
					function init () {
						var myMap = new ymaps.Map('map', {
								center: [55.76, 37.64],
								zoom: 10,
								controls: ['zoomControl']
							}),
							objectManager = new ymaps.ObjectManager({
								clusterize: true
							});

						objectManager.add(<?=$objectManager?>);
						objectManager.objects.options.set('preset', 'islands#blackIcon');
						objectManager.clusters.options.set('preset', 'islands#invertedBlackClusterIcons');

						myMap.geoObjects.add(objectManager);
						myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true, zoomMargin: 40});
					}
				</script>
			</div>
			<?
		}
	}
}
