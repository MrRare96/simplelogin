<?php
//this class contains the code to connect to the database
class dbconnect{

	private $dburl;
	private $uname;
	private $pw;
	private $dbname;

	function __construct($variables){
		$this->dburl = $variables['dburl'];
		$this->uname = $variables['uname'];
		$this->pw = $variables['pw'];
		$this->dbname = $variables['dbname'];
	}

	private $dbcon;

	public function connect(){
		return $this ->dbcon = mysqli_connect($this->dburl,$this->uname,$this->pw,$this->dbname);	
	}

	public function disconnect($connection){
		return $this ->dbcon = mysqli_close($connection);
	}
}

?>