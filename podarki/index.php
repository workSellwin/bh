<?
if($_SERVER['REQUEST_URI'] == '/podarki/')
	header( 'Location: https://bh.by/catalog/podarki/', true, 301 );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подарочные наборы");

?><br>
 <br>
<?
Cmodule::IncludeModule('catalog');
Cmodule::IncludeModule('iblock');
global $USER;

// $ORDER_ID = 90;
// if (!($arOrder = CSaleOrder::GetByID($ORDER_ID)))
// {
//    echo "Заказ с кодом ".$ORDER_ID." не найден";
// }
// else
// {
//    echo "<pre>";
//    print_r($arOrder);
//    echo "</pre>";
// }
	// $url = "http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorecount/json?good=9280290001";
	// $ch = curl_init();
	// //curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	// curl_setopt($ch, CURLOPT_HEADER, 0);
	// //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)");
	// curl_setopt($ch, CURLOPT_URL, $url);
	// //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	// $data = curl_exec($ch);
	// curl_close($ch);

	// GmiPrint($data);



//CCatalogProduct::GetByID($ID);
// $PRODUCT_ID = 51;
// CCatalogProduct::Update($PRODUCT_ID, array('QUANTITY' => 0));







// $db_res = CCatalogProduct::GetList(
//         array("TIMESTAMP_X" => "ASC"),
//         array(),//"TYPE"=>1
//         false,
//         array("nTopCount" => 100)
//     );
// while ($ar_res = $db_res->Fetch())
// {
// 	//[ELEMENT_XML_ID]
// 	//GmiPrint($ar_res);
// 	$json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorecount/json?good='.$ar_res["ELEMENT_XML_ID"]);
// 	$jsonRes = json_decode($json,true);
// 	//GmiPrint($jsonRes);
// 	// if($jsonRes["dataStore"][0]["good"] == $ar_res["ELEMENT_XML_ID"] && !empty($jsonRes["dataStore"][0]["count"])){
// 	// 	CCatalogProduct::Update($ar_res["ID"], array('QUANTITY' => $jsonRes["dataStore"][0]["count"]));
// 	// 	// GmiPrint($ob["XML_ID"]);
// 	// 	// GmiPrint($jsonRes["dataStore"][0]);
// 	// }
// }





// $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstore/json');
// $jsonRes = json_decode($json,true);
// foreach ($jsonRes["dataStore"] as $key => $value) {
// 	$res = CIBlockElement::GetList(array(), array("XML_ID"=>$value["good"]), false, array(),array());
// 	if($ob = $res->GetNext())
// 	{
// 		CCatalogProduct::Update($ob["ID"], array('QUANTITY' => $value["count"]));
// 	}
// 	// else{
// 	//	GmiPrint($value["good"]);
// 	// }
// }
// echo "URYA!!!";
// $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstore/json');
// $jsonRes = json_decode($json,true);
// GmiPrint($jsonRes);
//updateProductCountAgent();
//echo data("Y:m:d");
// $today = date("H:i:s d-m-Y");
// echo $today;
// echo "URYA!!!";
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>