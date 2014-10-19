<?php
//this class contains the code to connect to the database
class dbconnect{

	function __construct($variables){
		$this->vars = $variables;
	}
	
	public $dbcon;

	public function connect(){
		$this ->dbcon = mysqli_connect($this->vars['dburl'],$this->vars['uname'],$this->vars['pw'],$this->vars['dbname']);	
	}

	public function disconnect($connection){
		$this ->dbcon = mysqli_close($connection);
	}
}

?>