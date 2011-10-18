<?php
require 'MainView.php';
class OrderView extends MainView
{
	function __construct($view = null)
	{		
		try{
			parent::__construct('./views/');
			if($view !== null){
				parent::setView($view);
			}			
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
	
	public function setView($view)
	{
		parent::setView($view);
	}
	
	public function assign($variable, $value)
	{
		parent::assign($variable, $value);
	}
	
	public function render($directOutput = true)
	{
		parent::render($directOutput);
	}
	
	
}


	
?>