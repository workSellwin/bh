<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	$flag = "N";
	global $USER;
	if(!empty($_POST['ID']))
	{
		$itemID = $_POST['ID'];   
					 
		if(!$USER->IsAuthorized()){
			// $arElements = unserialize($APPLICATION->get_cookie('favorites'));
			// if(!in_array($itemID, $arElements))
			// 	$arElements[] = $itemID;
			// else
			// 	unset($arElements[array_search($itemID,$arElements)]);
			// $APPLICATION->set_cookie("favorites",serialize($arElements));
		}
		else{
			$idUser = $USER->GetID();
			$rsUser = CUser::GetByID($idUser);
			$arUser = $rsUser->Fetch();
			$arElements = unserialize($arUser['UF_FAVORITES_' . strtoupper(SITE_ID)]);
			if(!in_array($itemID, $arElements)){ 
				$arElements[] = $itemID;
				$flag = "Y";
			}else{
				unset($arElements[array_search($itemID,$arElements)]);
			}
			$USER->Update($idUser, Array('UF_FAVORITES_' . strtoupper(SITE_ID) =>serialize($arElements)));
		}
	}

	echo $flag;
?>