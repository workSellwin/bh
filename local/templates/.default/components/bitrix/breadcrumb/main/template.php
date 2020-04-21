<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult) || !empty($_GET["q"]))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
//$css = $APPLICATION->GetCSSArray();

$strReturn .= '<div class="bx-breadcrumb_wrp"><div class="bx-breadcrumb  cl" itemscope itemtype="http://schema.org/BreadcrumbList">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	//if($arResult[$index]["LINK"] != "/catalog/"){
		$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
		if($title == 'Косметика и средства для ухода за лицом' && $index > 2)
			$title = 'Средства для окрашивания бровей и ресниц';

		$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' ' : '');
		$child = ($index > 0? ' ' : '');

		if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		{
			$new_index = $index+1;
			$strReturn .= '
				<div class="bx-breadcrumb-item" id="bx_breadcrumb_'.$new_index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" '.$child.$nextRef.'>
					<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item">
						<span itemprop="name">'.$title.'</span>
						<meta itemprop="position" content="'.$new_index.'" />
					</a>
				</div>';
		}
		else
		{
			$new_index = $index+1;
			$strReturn .= '
			<div class="bx-breadcrumb-item" id="bx_breadcrumb_'.$new_index.'"  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" '.$child.$nextRef.'>
				<a class="active-crumb" href="'.$arResult[$index]["LINK"].'#content" title="'.$title.'" itemprop="item">
					<span itemprop="name">'.$title.'</span>
					<meta itemprop="position" content="'.$new_index.'" />
				</a>
			</div>';
		}
	//}
}

$strReturn .= '<div style="clear:both"></div></div></div>';

return $strReturn;
