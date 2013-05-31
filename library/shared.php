<?php

/** Check if the eviroment is development and display error **/

function setReporting(){
		if(DEVELOPMENT_ENVIROMENT == true){
			error_reporting(E_ALL);
			ini_set('display_errors', 'on');
		}else{
			error_reporting(E_ALL);
			ini_set('display_errors', 'off');
			ini_set('log_errors', 'on');
			ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log')
		}
}


/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value){
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripcslashes($value);
	return $value;

}

function removeMagicQuotes(){
	if(get_magic_quotes_gpc()){
		$_GET 	 = stripcSlashesDeep($_GET 	 );
		$_POST 	 = stripcSlashesDeep($_POST  );
		$_COOKIE = stripcSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals(){
	if(ini_get('register_globals')){
		$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		
		foreach($array as $value){
			foreach($GLOBALS[$value] as $key => $var){
				if($var === $GLOBALS[$key]){
					unset($GLOBALS[$key]);
				}
			}
		
		}
	}
}


/** Main call Function **/

function callHook(){
	global $url;
	
	$urlArray = array();
	$urlArray = explode("/", $url);
	
	$controller = $urlArray[0];
	array_shift($urlArray);
	$action = $urlArray[0];
	array_shift($urlArray);
	$queryString = $urlArray;
	
	$controllerName = $controller;
	$controller = ucwords($controller);
	$model = rtrim($controller, 's');
	$controller .= 'controller';
	$dispatch  = new $controller($model, $controllerName, $action);
	
	if((int)method_exists($controller, $action)){
		call_user_func_array(array($dispatch, $model), $queryString);
	}else{
	
	/*Error Generator Code */
	}
}

/** Autoload any class that are required **/


function __autoload($classname){
	if(file_exists(ROOT.DS.'library'.DS.strtolower($classname).'class.php')){
		require_once(ROOT.DS.'library'.DS.strtolower($classname).'class.php');
	}elseif(file_exists(ROOT.DS.'application'.DS.'controllers'.DS.strtolower($classname).'class.php')){
		require_once(ROOT.DS.'application'.DS.'controllers'.DS.strtolower($classname).'class.php');
	}elseif(file_exists(ROOT.DS.'application'.DS.'models'.DS.strtolower($classname).'class.php')){
		require_once(ROOT.DS.'application'.DS.'models'.DS.strtolower($classname).'class.php');
	}else{
	
	/*Error Generator Code */
	}
}


setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();