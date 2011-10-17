<?php
	require 'raslib.php';
	class DbAccess
	{
		private $dbClient;
		
		function __construct($host, $user, $pswrd, $db, $port = null, $sckt = null)
		{
			try {
				$this->dbClient = new MySqlClient($host, $user, $pswrd, $db, $port, $sckt);
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function executeInsertQuery($table, $fields, $values)
		{
			try {
				$this->dbClient->prepareInsertQuery($table, $fields, $values);
				return $this->dbClient->executeQuery();
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function executeUpdateQuery($table, $fields, $values, $criteria = null)
		{
			try {
				$this->dbClient->prepareUpdateQuery($table, $fields, $values, $criteria);
				return $this->dbClient->executeQuery();
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function executeDeleteQuery($table, $criteria = null)
		{
			try {
				$this->dbClient->prepareDeleteQuery($table, $criteria);
				return $this->dbClient->executeQuery();				
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function executeSelectQuery($table, $fields = null, $criteria = null)
		{
			try {
				$this->dbClient->prepareSelectQuery($table, $fields, $criteria);
				return $this->dbClient->executeQuery();
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		
		public function getResults($format = "array")
		{
			try {
				return $this->dbClient->fetch($format);				
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
	}

?>