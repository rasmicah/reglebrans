<?php

class ProductModel
{
	private $_db;	
	
	function __construct($db_server, $db_username, $db_password, $db_database)
	{
		try {
			$this->_db = new MySqlClient($db_server, db_username, $db_password, $db_database);
		} catch (Exception $ex) {
			throw $ex;
		}		
	}
	
	public function addProduct($name, $category, $desciption, $quantity, $price)
	{
		try {
			$this->_db->connect();
			$queryStr = "INSERT INTO products product_name, description, category_id, quantity, price) ";
			$queryStr = $queryStr & "VALUES('$name', '$description', $category, $quantity, $price)";
			$this->_db->prepareQuery();
			$result = $this->_db->executeQuery();
			if($result > 0)
				return true;
			else
				return false;
		} catch {
			exit("Error: addProduct-ProductModel");
		}
	}

	public function adjustQuantity($productId)
	{
		try {
			$this->_db->connect();
			$queryStr = "UPDATE product SET qunatity = $quantity WHERE product_id = $productId";			
			$this->_db->prepareQuery();
			$result = $this->_db->executeQuery();
			if($result > 0)
				return true;
			else
				return false;
		} catch {
		
		}
	}
}

	

	
?>