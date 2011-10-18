<?php
class UserController
{
	private $model;
	private $view;
	
	function __construct()
	{
		$this->model = new UserModel();
		$this->view = new UserView('users_all.php');
	}
	public function addUser()
	{
		
	}
	
	public function getUsers()
	{
		$result = $this->model->getUsers();		
		$this->view->buildUsersTable($result);		
		$this->view->render();
	}
}
?>