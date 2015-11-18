<?

#######################################session############################
if (!function_exists("addFavoritesSession")){
	function addFavoritesSession($nameSession, $valueSession, $separator){
		$session = $_SESSION[$nameSession];
		$sessionSet = false;
// var_dump($session);
		if (isset($session) && !empty($session))
		{
			$valuesInSession = explode($separator, $session);
// var_dump($valuesInSession);
			if (is_array($valuesInSession) && !empty($valuesInSession) && !in_array($valueSession, $valuesInSession))
			{
				$valuesInSession[] = $valueSession;
				$_SESSION[$nameSession] = implode(",", $valuesInSession);
				// var_dump($valuesInSession);
				// var_dump($separator);

				// var_dump(implode($separator, $valuesInSession));
				$sessionSet = true;
			}
			elseif(in_array($valueSession, $valuesInSession))
			{
				$sessionSet = true;
			}
		}
		// var_dump($sessionSet);

		if (!$sessionSet)
			$_SESSION[$nameSession] = $valueSession;
	}
}

if (!function_exists("delFavoritesSession")){
	function delFavoritesSession($nameSession, $valueSession, $separator){

		$session = $_SESSION[$nameSession];

		if (isset($session) && !empty($session))
		{
			$favorites = explode($separator, $session);

			if (is_array($favorites) && !empty($favorites)){
				deleteElementByValue($favorites, $valueSession);

				if (is_array($favorites) && !empty($favorites))
					$_SESSION[$nameSession] = implode($separator, $favorites);			
				else
					$_SESSION[$nameSession] = "";			
			}
		}
	}
}

if (!function_exists("getFavoritesSession")){
	function getFavoritesSession($nameSession, $separator){
		$session = $_SESSION[$nameSession];

		if (isset($session) && !empty($session))
			return explode($separator, $session);
		else
			return array();
	}
}


######################################cookie################################
if (!function_exists("addFavoritesCookie")){
	function addFavoritesCookie($nameCookie, $valueCookie, $separator, $cookieExp=3600){
		$cookie = $_COOKIE[$nameCookie];
		$cookieSet = false;

		if (isset($cookie) && !empty($cookie))
		{
			$valuesInCookie = explode($separator, $cookie);

			if (is_array($valuesInCookie) && !empty($valuesInCookie) && !in_array($valueCookie, $valuesInCookie))
			{
				$valuesInCookie[] = $valueCookie;
				setcookie($nameCookie, implode($separator, $valuesInCookie), time()+$cookieExp);
				$cookieSet = true;
			}
			elseif(in_array($valueCookie, $valuesInCookie))
			{
				$cookieSet = true;
			}
		}

		if (!$cookieSet)
			setcookie($nameCookie, $valueCookie, time()+$cookieExp);
	}
}

if (!function_exists("delFavoritesCookie")){
	function delFavoritesCookie($nameCookie, $valueCookie, $separator, $cookieExp=3600){

		$cookie = $_COOKIE[$nameCookie];

		if (isset($cookie) && !empty($cookie))
		{
			$favorites = explode($separator, $cookie);

			if (is_array($favorites) && !empty($favorites)){
				deleteElementByValue($favorites, $valueCookie);

				if (is_array($favorites) && !empty($favorites))
					setcookie($nameCookie, implode($separator, $favorites), time()+$cookieExp);			
				else
					setcookie($nameCookie, "", time()-$cookieExp);			
			}
		}
	}
}

if (!function_exists("getFavoritesCookie")){
	function getFavoritesCookie($nameCookie, $separator){
		$cookie = $_COOKIE[$nameCookie];

		if (isset($cookie) && !empty($cookie))
			return explode($separator, $cookie);
		else
			return array();
	}
}


#####################################functions###############################
if (!function_exists("deleteElementByValue")){
	function deleteElementByValue(&$ar, $value){
		while (($i = array_search($value, $ar)) !== false) {
		    unset($ar[$i]);
		} 
	}
}
//SESSION, COOKIE, UF, IBLOCK
$arParams["SAVE_IN"] = (strlen($arParams["SAVE_IN"]) > 0) ? trim($arParams["SAVE_IN"]) : "SESSION";//способы хранения избранного: сессия, куки, пользовательское свойство, инфоблок.для неавторизованных только сессия и куки
$arParams["SEPARATOR"] = (strlen($arParams["SEPARATOR"]) > 0) ? trim($arParams["SEPARATOR"]) : ",";//разделитель, используется для хранения в сессии, куках, пользовательском свойстве
$arParams["IBLOCK_ID"] = (intval($arParams["IBLOCK_ID"]) > 0) ? intval($arParams["IBLOCK_ID"]) : "";//идентификатор ИБ, если выбран способ хранения в инфоблоках
$arParams["OBJECT"] = (strlen($arParams["OBJECT"]) > 0) ? trim($arParams["OBJECT"]) : "object";		//значение параметра в котором приходит значение для добавления/удаления
$arParams["PARAM"] = (strlen($arParams["PARAM"]) > 0) ? trim($arParams["PARAM"]) : "favorite";	//название свойства
$arParams["ACTION"] = (strlen($arParams["ACTION"]) > 0) ? trim($arParams["ACTION"]) : "action";	//название параметра в котором приходит код действия
$arParams["VALUE"] = (strlen($arParams["VALUE"]) > 0) ? trim($arParams["VALUE"]) : "";	

if (empty($arParams["OBJECT"])){
	ShowError("Объект не задан");
	return;
}

if ($arParams["SAVE_IN"] == "IBLOCK" && intval($arParams["IBLOCK_ID"]) <= 0){
	ShowError("Не задан идентификатор информационного блока");
	return;
}

if (($arParams["SAVE_IN"] == "UF" || $arParams["SAVE_IN"] == "SESSION" || $arParams["SAVE_IN"] == "COOKIE") && strlen($arParams["PARAM"]) <= 0){
	ShowError("Не задан идентификатор свойства");
	return;
}


$add = "addFavorites".ucfirst(strtolower($arParams["SAVE_IN"]));
$del = "delFavorites".ucfirst(strtolower($arParams["SAVE_IN"]));
$get = "getFavorites".ucfirst(strtolower($arParams["SAVE_IN"]));

// var_dump($add);
// var_dump($del);
// var_dump($get);

$key = $arParams["PARAM"];
$value = isset($_REQUEST[$arParams["OBJECT"]]) && !empty($_REQUEST[$arParams["OBJECT"]]) ? $_REQUEST[$arParams["OBJECT"]] : $arParams["VALUE"];


//action
if ($_REQUEST[$arParams["ACTION"]] == "add"){
	$add($key, $value, $arParams["SEPARATOR"]);
}

if ($_REQUEST[$arParams["ACTION"]] == "del"){
	$del($key, $value, $arParams["SEPARATOR"]);
}

$arResult["ITEMS"] = $get($key, $arParams["SEPARATOR"]);

$arResult["ADD_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION"]."=add&".$arParams["OBJECT"]."=".$arParams["VALUE"], array($arParams["ACTION"], $arParams["OBJECT"]));
$arResult["DEL_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION"]."=del&".$arParams["OBJECT"]."=".$arParams["VALUE"], array($arParams["ACTION"], $arParams["OBJECT"]));
$arResult["SELECTED"] = (in_array($value, $arResult["ITEMS"])) ? true : false;

// echo '<pre>';
// var_dump($_REQUEST);
// var_dump($arResult);
// var_dump($_SESSION);
// echo '</pre>';

$this->IncludeComponentTemplate();
//todo iblock
//todo uf
