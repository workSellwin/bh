<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	$name="не указано";

	$PROP = array();
	if(isset($_POST['rating']))
		$PROP[65] =  filter_var($_POST['rating'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if(isset($_POST['id_product'])) 
		$PROP[66] = filter_var($_POST['id_product'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if(isset($_POST['email'])) 
		$PROP[67] = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if(isset($_POST['name'])) 
		$name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if(isset($_POST['reviews'])) 
		$reviews = filter_var($_POST['reviews'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if(CModule::IncludeModule('iblock')){
		$arLoadProductArray = Array(
			"MODIFIED_BY"    => 1, 
			"IBLOCK_SECTION_ID" => false,
			"IBLOCK_ID"      => 8,
			"PROPERTY_VALUES"=> $PROP,
			"NAME"           => $name,
			"ACTIVE"         => "N",
			"PREVIEW_TEXT"   => $reviews,
			); 
		$el = new CIBlockElement;
		
		$strEmail = COption::GetOptionString('main','email_from');
		
		if($PRODUCT_ID = $el->Add($arLoadProductArray)){
			$arSend = array(
				"AUTHOR" => $name,
				"AUTHOR_EMAIL" => $PROP[67],
				"TEXT" => $reviews,
				"EMAIL_TO" => $strEmail,
				"ID_PRODUCT" => $PROP[66],
				"RATING" => $PROP[65],
				"URL" => $_SERVER['SERVER_NAME']."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=8&type=catalog&ID=".$PRODUCT_ID
			);
			
			CEvent::SendImmediate('FEEDBACK_FORM', 's1', $arSend,"Y",47);
			echo '<div class="complite">Отзыв отправлен!</div>';
			
		}else{
			echo '<div class="err_mess">Повторите ввод!</div>';
		}
	}else{
		echo '<div class="err_mess">Повторите ввод!</div>';
	}
?>