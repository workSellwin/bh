<?php
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(!$GLOBALS['USER']->IsAdmin()) LocalRedirect(SITE_DIR);

$message = false;

$request = Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

if($request->isPost() && check_bitrix_sessid() && isset($_FILES['FILE']) && (!($_FILES["FILE"]["error"] > 0)))
{
	$fileId = \CFile::SaveFile($_FILES['FILE']);

	if($fileId > 0)
	{
		\Bitrix\Main\Loader::includeModule('iblock');

		$arFilter = [
			'IBLOCK_TYPE' => 'catalog',
			'IBLOCK_ID' => 2,
			'!PROPERTY_DISCOUNT_PERCENT' => false
		];
		$res = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID']);
		while($row = $res->GetNext())
		{
			\CIBlockElement::SetPropertyValuesEx($row['ID'], $row['IBLOCK_ID'], ['DISCOUNT_PERCENT' => '']);
		}

		$file = $_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($fileId);
		if (($fp = fopen($file, "r")) !== FALSE)
		{
			$arSku = [];
			$arDiscount = [];
			while (($data = fgetcsv($fp, 0, ";")) !== FALSE)
			{
				$sku = $data[0];
				$arSku[] = $sku;
				$arDiscount[$sku] = $data[1];
			}
			fclose($fp);
			\CFile::Delete($fileId);

			if(!empty($arDiscount))
			{
				$arFilter = [
					'IBLOCK_TYPE' => 'catalog',
					'IBLOCK_ID' => 2,
					'XML_ID' => $arSku
				];
				$res = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID', 'XML_ID']);
				while($row = $res->GetNext())
				{
					if($arDiscount[ $row['XML_ID'] ])
					{
						\CIBlockElement::SetPropertyValuesEx($row['ID'], $row['IBLOCK_ID'], ['DISCOUNT_PERCENT' => $arDiscount[ $row['XML_ID'] ]]);
					}
				}
			}

			$message = 'Импорт завершен';
		}
	}


}
?>
<?if($message):?>
	<div class=""><?=$message?></div>
<?endif?>
<div class="add-file-csv-wrp">
	<form action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data" method="post">
		<?=bitrix_sessid_post()?>
		<button type="submit" class="btn">Добавить</button>
		<div class="add-file-csv">
			<span class="note-file">Выберите файл в формате CSV</span>
			<input name="FILE" class="field cart-search-select-file" value="" placeholder="Выберите файл в формате CSV" type="file" accept=".csv">
		</div>
	</form>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>