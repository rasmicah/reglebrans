<?php

class MainView
{
	protected $data;
	protected $viewsLocation;
	protected $view;
	
	function __construct($location)
	{
		$this->data = array();
		
		//if(substr($location, 0, 1) !== '/' || substr($location, -1) !== '/'){
			//throw new Exception(
				//"Invalid location: <b>$location</b>"
				//. " [location must start and end with '/'"
				//. " eg /root_dir/modules/views/]"
			//);
		//}
		
		$this->viewsLocation = $location;
		$this->view = null;
	}
	
	protected function assign($variable, $value)
	{
		$this->data[$variable] = $value;
	}
	
	protected function setView($_view)
	{
		$this->view = $this->viewsLocation . $_view;
		if(!file_exists($this->view)){
			$temp = $this->view;
			$this->view = null;
			throw new Exception("File does not exists: $temp");
		} 
	}
	
	protected function render($directOutput = true)
	{
		if($this->view !== null){
			if($directOutput !== true)
			{
				ob_start();
			}
			
			$data = $this->data;
			include $this->view;
			
			if($directOutput !== true)
			{
				return ob_get_clean();
			}
		} else {
			throw new Exception('View not set');
		}
	}
}


	
?>