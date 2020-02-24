<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
	const MY_HL_BLOCK_ID = 3;
	CModule::IncludeModule('highloadblock');
	function GetEntityDataClass($HlBlockId){
		if (empty($HlBlockId) || $HlBlockId < 1)
		{
			return false;
		}
		$hlblock = HLBT::getById($HlBlockId)->fetch();
		$entity = HLBT::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		return $entity_data_class;
	}
	$entity_data_class = GetEntityDataClass(MY_HL_BLOCK_ID);
	$rsData = $entity_data_class::getList(array("select" => array("*"),"filter" => array("UF_STREET" => "%".$_REQUEST["NAME_STREET"]."%","UF_CODE_CITY"=>$_REQUEST["CODE_CITY"])));
	$i=0;
	while($el = $rsData->fetch()){
		$i++;if($i==10) break;
		$arResult[] = $el["UF_STREET"];
	}
	if(!empty($arResult)){
		ob_start();?>
		<ul>
			<?foreach ($arResult as $value):?>
				<li><?=$value?></li>
			<?endforeach?>
		</ul>
		<?$output = ob_get_clean();
	}
	echo $output;
?>