<?php
class ProductController
{
	private $model;
	private $view;
	
	function __construct()
	{
		$this->model = new ProductModel();
		$this->view = new ProductView('products_all.php');
	}
	public function addProduct()
	{
		
	}
	
	public function getProducts()
	{
		$result = $this->model->getProducts();		
		$this->view->buildProductsTable($result);		
		$this->view->render();
	}
}
?>