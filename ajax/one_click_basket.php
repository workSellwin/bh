<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	$PROP = $_POST["PROP"];

	if(CModule::IncludeModule('iblock') && !empty($PROP['PHONE'])){
		$arLoadProductArray = Array(
			"IBLOCK_SECTION_ID"	=> false,
			"IBLOCK_ID"			=> 9,
			"PROPERTY_VALUES"	=> $PROP,
			"NAME"				=> $_POST["NAME"],
		);
		$el = new CIBlockElement;
		
		if($PRODUCT_ID = $el->Add($arLoadProductArray)){

			$city = $PROP['MINSK'] === 'Y' ? 'Minsk' : '';
			$oneClickObj = new OrderOneClick();
			$oneClickObj->Order($PROP['PRODUCT_ID'], $_POST['NAME'], $PROP['PHONE'], $city);

//			$arSend = array(
//				"AUTHOR" => $_POST["NAME"],
//				"URL" => $_SERVER['SERVER_NAME']."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=9&type=catalog&ID=".$PRODUCT_ID
//			);
			//$arSend = array_merge($arSend,$PROP);
			//CEvent::Send('FEEDBACK_FORM', 's1', $arSend,"Y",56);//Immediate
			//CEvent::CheckEvents();
			echo '<div class="complite"><script>$(".one-click-form__inner").addClass("hide");$(".one-click-form__result").removeClass("hide");</script></div>';
			
		}else{
			echo '<div class="err_mess">Повторите ввод!</div>';
		}
	}else{
		echo '<div class="err_mess">Повторите ввод!</div>';
	}
?>
