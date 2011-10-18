<?php
require 'MainView.php';
class ProductView extends MainView
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
	
	public function buildProductsTable($cats)
	{
		$catHtml = "";
		if(isset($cats) && is_array($cats)){
		foreach($cats as $cat){			
				$catHtml .= "
				<tr>
					<td>$cat[category_id]</td>
					<td><a href='" . UrlHelper::generateUrl('item', 'getitems', 
						array('category'=>$cat['category_name'])). "'>$cat[category_name]</a></td>					
				</tr>";
			}
		} else {
			$catHtml = "<tr><td colspan='4'>No Categorys Exists</td></tr>";
		}
		parent::assign('cats', $catHtml);
	}
}


	
?>