<?php
	function __autoload($className)
	{
		$types = array('Controller', 'Model', 'View', 'Helper', 'Exception');
		for($i = 0; $i < count($types); $i++){
			$parts = explode($types[$i], $className);
			if(isset($parts[1])){
				switch($i){
					case 0: $file = './modules/controllers/' . ucfirst($parts[0]) . $types[$i] . '.php';
						break;
					case 1: $file = './modules/models/' . ucfirst($parts[0]) . $types[$i] . '.php';
						break;
					case 2: $file = './modules/views/' . ucfirst($parts[0]) . $types[$i] . '.php';
						break;
					case 3: $file = './library/' . ucfirst($parts[0]) . $types[$i] . '.php';
						break;
					case 4: break;
					case 5: break;
					case 6: break;
					case 7: break;
				}
				
				if(file_exists($file)){
					require $file;				
				} else {
					throw new Exception('File Not Found');
				}				
				break;			
			}		
		}		
	}
?>