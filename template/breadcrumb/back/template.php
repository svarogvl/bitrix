<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';




$itemSize = count($arResult);
for($index = $itemSize-1; $index >= 0; $index--)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	if($index != 0)
	{
		$strReturn .= $title." - ";
	}
	else
	{
		$strReturn .= $title;
	}
}



return $strReturn;
