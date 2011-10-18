<?php
class OrderController
{
	private $model;
	private $view;
	
	function __construct()
	{
		$this->model = new OrderModel();
		$this->view = new OrderView('orders_all.php');
	}
	public function addOrder()
	{
		
	}
	
	public function getOrders()
	{
		$result = $this->model->getOrders();		
		$this->view->buildOrdersTable($result);		
		$this->view->render();
	}
}
?>