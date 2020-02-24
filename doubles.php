<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


$arSelect = Array("ID", "NAME", "CODE", "IBLOCK_SECTION_ID");
$arFilter = Array("IBLOCK_ID"=>2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$resProds = array();
while($ob = $res->GetNext())
{
	$resProds[$ob['CODE']][] = array("name" => $ob['NAME'], "id" => $ob["ID"], "section_id" => $ob["IBLOCK_SECTION_ID"], 'code' => $ob['CODE']);
}
//var_dump($resProds);
foreach($resProds as $code=>$prod){
	if(count($prod) > 1){
		echo '<strong style="font-size:20px; display:block; clear:left">Алиас - '.$code.'</strong><br/>';
		echo 'Дубли:<hr/><br/>';
		foreach($prod as $double){
			echo $double['name'].', id = '.$double['id'].', <a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=2&type=catalog&ID='.$double['id'].'">Ссылка в системе администрирования</a><hr/><br/>';
		}
	}
}