<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
if(empty($arResult))
	return "";
$strReturn = '';

$strReturn .= '<ul class="breadcrumb breadcrumb__t"><li><a href="/">Главная</a></li><li class="divider"></li>';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++){
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
	$child = ($index > 0? ' itemprop="child"' : '');
	$arrow = ($index > 0? '<li class="divider"></li>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1){
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'">'.$title.'</a></li><li class="divider"></li>';
	}else{
		$strReturn .= '<li>'.$title.'</li>';
	}
}

$strReturn .= '</ul>';

return $strReturn;
