<?php

class MySqlClient
{
	private $host;
	private $user;
	private $password;
	private $database;
	private $socket;
	private $port;
	private $query;
	private $queryType;
	private $result;
	private $adapter;
	
	function __construct($host, $user, $pswrd, $db, $port = null, $sckt = null)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $pswrd;
		$this->database = $db;
		$this->socket = (isset($sckt)) ? $sckt : null ;
		$this->port = (isset($port)) ? $port : null ;
		$this->query = null;
		$this->result = null;
		$this->adapter = mysqli_init();
		if(!$this->adapter){
			throw new Exception("MySqli Initailization failed");
		}
	}
	
	
	function __destruct()
	{
		$this->disconnect();	
	}	
	
	private function isConnected()
	{
		if(@$this->adapter->get_connection_stats()){	
			return true;	
		} else {
			return false;
		}
	}
	
	private function getAffectedRows()
	{
		return $this->adapter->affected_rows;
	}
	
	private function getNumRows()
	{
		return $this->result->num_rows;
	}
	
	public function escape($str)
	{
		return $this->adapter->real_escape_string($str);
	}
	
	public function connect()
	{	
		try {
			if(!$this->isConnected()) {
				if(@$this->adapter->real_connect($this->host, $this->user, $this->password, $this->database))
					return true;
				else
					return false;
			} else
				return true;
		} catch (Exception $ex) {	
			throw new Exception($ex->getMessage());
		}
	}
	
	public function disconnect()
	{
		if($this->isConnected()){	
			$this->adapter->close();	
		}			
	}	
	
	
	public function prepareQuery($queryString)
	{
		$queryType = substr($queryString, 0, 6);
		switch(strtolower($queryType)){
			case 'insert':
				$this->queryType = 'insert';
				break;
			case 'update':
				$this->queryType = 'update';
				break;
			case 'delete':
				$this->queryType = 'delete';
				break;
			case 'select':
				$this->queryType = 'select';
				break;
			default:
				$this->queryType = 'invalid';
				throw new Exception('Invalid Query Type: ' . $queryType);
		}
		$this->query = escape($queryString);			
	}
	
	public function executeQuery()
	{
		$this->connect();		
		if($this->query == null){
			$msg = "Query String not set";
			throw new NullQueryException($msg);
		}	
		try {
			
			$this->result = @$this->adapter->query($this->escape($this->query));
		} catch (Exception $ex) {
			$this->disconnect();
			throw new Exception($ex->getMessage());
		}		
		$this->query = null;
		$this->queryType = null;
		if($this->adapter->errno){
			throw new Exception($this->adapter->error);
		}
		switch($this->queryType){
			case 'insert':				
			case 'update':				
			case 'delete':
				return $this->getAffectedRows();				
			case 'select':				
				return $this->getNumRows();
		}
		$this->disconnect();		
	}
	
	
	public function prepareInsertQuery($table, $fields, $values)
	{
		if(is_array($fields) && is_array($values)) {
			$numFields = count($fields);
			$numValues = count($values);			
			if($numFields == $numValues){
				$preparedFields = "(";
				$preparedValues = " VALUES (";
				for($counter = 0; $counter < $numFields; $counter++){
					$preparedFields .= "$fields[$counter]"; 
					$preparedValues .= "'$values[$counter]'";
					if($counter < ($numFields - 1)){
						$preparedFields .= ", ";
						$preparedValues .= ", ";
					}
				}				
				$preparedFields .= ")";
				$preparedValues .= ")";
				$this->queryType = "insert";
				$this->query = "INSERT INTO $table " . $preparedFields . $preparedValues;				
			} else {
				throw new Exception("The number of fields not equal to the number of values");
			}
		} else {
			throw new Exception("Invalid parameter! array expected in parameter 2 and 3");
		}
	}
	
	public function prepareUpdateQuery($table, $fields, $values, $criteria = null)
	{
		if(is_array($fields) && is_array($values)) {
			$numFields = count($fields);
			$numValues = count($values);
			if($numFields == $numValues){
				$preparedStatement = "UPDATE $table SET ";				
				for($counter = 0; $counter < $numFields; $counter++){
					$preparedStatement .= "$fields[$counter] = '$values[$counter]'";					
					if($counter < ($numFields - 1)){
						$preparedStatement .= ", ";						
					}
				}
				$preparedStatement = ($criteria == null) ? $preparedStatement : $preparedStatement . " WHERE " . $criteria;				
				$this->queryType = "update";
				$this->query = $preparedStatement;				
			} else {
				throw new Exception("The number of fields not equal to the number of values");
			}
		} else {
			throw new Exception("Invalid parameter! array expected in parameter 2 and 3");
		}
	}
	
	public function prepareDeleteQuery($table, $criteria = null)
	{
		$this->queryType = "delete";
		$this->query = ($criteria == null) ? "DELETE FROM $table" : "DELETE FROM $table WHERE $criteria";
	}

	public function prepareSelectQuery($table, $fields = null, $criteria = null)
	{
		$preparedStatement = "SELECT ";
		if($fields != null){
			if(is_array($fields)) {
				$numFields = count($fields);
				$preparedFields = "";
				for($counter = 0; $counter < $numFields; $counter++){
					$preparedFields .= "$fields[$counter]";				
					if($counter < ($numFields - 1)){
						$preparedFields .= ", ";					
					}
				}
				$preparedStatement .= $preparedFields . " FROM $table";
			} else {
				throw new Exception("Invalid parameter! array expected in parameter 1");
			}			
		} else {
			$preparedStatement .= "* FROM $table"; 
		}	
		$this->queryType = "select";
		$this->query = ($criteria == null) ? $preparedStatement : $preparedStatement . " WHERE $criteria";
	}
	
	public function fetch($type = 'array')
	{
		$type = strtolower($type);
		if(!$this->result){
			$msg = "Data set empty";
			throw new EmptyResultException($msg);
		}
		switch($type)
		{			
			case 'array':
				{					
					$index = -1;
					while($row = $this->result->fetch_array(MYSQLI_ASSOC))
					{
						$array[++$index] = $row; 						
					}
					return $array;
				}
				break;
			case 'object':	
				{
				}
				break;				
			case 'string':			
				{
				}
				break;

			default: 
			{
				$msg = "Invalid result type: " . $type;
				throw new InvalidResultTypeException($msg);
			}
		}
	}	
}


/**********************************************************************************/
class MySqlClientException extends Exception
{
	function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
		
	protected function GetErrorMessage()
	{				
		$stack = $this->getTrace();
		$file = null;
		$line = null;
		$function = null;
		if($stack)
		{
			$file = $stack[0]['file'];
			$line = $stack[0]['line'];
			$function = $stack[0]['function'];
		}
		else
		{
			$file = $this->getFile();
			$line = $this->getLine();
			$function = "-";
		}			
		return 	"<br />[File].............\"".$file. "\"".
				"<br />[Line]............".$line. 
				"<br />[Function].....".$function.
				"<br />[Code].........".$this->code.
				"<br />[Error]..........<b>".$this->message. "</b>".
				"<br />";	
	}
}

class ConnectionException extends MySqlClientException
{
	public function __construct($message, $code = 1001, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}
}

class DatabaseSelectException extends MySqlClientException
{
	public function __construct($message, $code = 1002, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}
}

class NullConnectionException extends MySqlClientException
{
	public function __construct($message, $code = 1003, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}
}

class NullQueryException extends MySqlClientException
{
	public function __construct($message, $code = 1004, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}
}

class InvalidResultTypeException extends MySqlClientException
{
	public function __construct($message, $code = 1005, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}
}

class EmptyResultException extends MySqlClientException
{
	public function __construct($message, $code = 1006, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
	public function GetErrorMessage()
	{
		return get_class() . parent::GetErrorMessage();
	}	
}



?>