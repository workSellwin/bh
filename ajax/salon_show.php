<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	CModule::IncludeModule("iblock");

		$res2 = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 10,"IBLOCK_SECTION_ID" => $_REQUEST["brand"]), false, Array(), array("NAME","ID","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_COORD","PROPERTY_ADDR"));
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
						//"iconContent" => $ob2["ID"]
					),
					/*"options" => array(
						"iconImageHref" => SITE_TEMPLATE_PATH . '/images/icon_dostaprim.svg',
						"iconLayout" => 'default#image',
						"iconImageSize" => 	array(34, 54),
						"iconImageOffset" => array(-17, -54),
						"iconColor" => '#000'
					)*/
				);
			}
		}
	$objectManager = \Bitrix\Main\Web\Json::encode(Array(
		"type"	=> "FeatureCollection",
		"features" => $points
	));
	//style="max-width: 700px;background: #fff"
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
			//console.log(<?=$objectManager?>);
			objectManager.objects.options.set('preset', 'islands#blackIcon');
			objectManager.clusters.options.set('preset', 'islands#invertedBlackClusterIcons');
			// objectManager.clusters.options.set({
			// 	clusterIconLayout: 'default#pieChart',
			// 	clusterIconPieChartRadius: 25,
			// 	clusterIconPieChartCoreRadius: 16,
			// 	clusterIconPieChartStrokeWidth: 4,
			// 	hasBalloon: false,
			// 	//preset: 'islands#invertedBlackClusterIcons'
			// });

			myMap.geoObjects.add(objectManager);
			myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true, zoomMargin: 40});
		}
	</script>
</div>
