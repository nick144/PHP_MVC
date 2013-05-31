<?php

class Controller{
	protected $_controller;
	protected $_model;
	protected $_action;
	protected $_template;
	
	
	function __contruct($model, $controller, $action){
		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;
		
		$this->$model = &amp; new $model;
		$this->_template = &amp; new Template($controller, $action);
	
	}
	
	function set($name, $value){
		$this-_template->set($name, $value);
	}
	
	function __destruct(){
		$this->_template->render();
	}

}