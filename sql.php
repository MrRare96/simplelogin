<?php

class sqlfunctions{

	private $table;
	private $column1;
	private $column2;
	private $column3;
	private $column4;
	private $column5;
	private $con;
	private $querystate;
	private $dataar;

	function __construct($variables){
		//$this-> = $variables;
		$this->table = $variables['table'];
		$this->column1 = $variables['column1'];
		$this->column2 = $variables['column2'];
		$this->column3 = $variables['column3'];
		$this->column4 = $variables['column4'];
		$this->column5 = $variables['column5'];
		$this->con = $variables['con'];
	}


	public function reg_sql($nick, $pass, $email, $randomString){
	    $sql = 'INSERT INTO ' . $this->table . ' (' . $this->column1 . ', ' . $this->column2 . ', ' . $this->column3 . ', ' . $this->column4 . ') VALUES (?,?,?,?);';
	    $stmt = mysqli_prepare($this->con, $sql);
	    mysqli_stmt_bind_param($stmt, "ssss", $nick, $pass, $email, $randomString);
	    $this->querystate = mysqli_stmt_execute($stmt);  
	    $returner = array("sqldata" => "none", "querychecker" => $this->querystate);
	    return $returner;
	}

	public function check_sql($nick){
	    $sql = "SELECT " . $this->column1 . "," . $this->column2 . "," . $this->column4 . " FROM  `" . $this->table . "` WHERE " . $this->column1 . " =? OR " . $this->column3 . "=?";
	    $stmt = mysqli_prepare($this->con, $sql);
	    mysqli_stmt_bind_param($stmt, "ss", $nick, $nick);
	    $this->querystate = mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $nickname, $password, $verified);
	    mysqli_stmt_fetch($stmt);
	    $this->dataar = array("nick" => $nickname, "password" => $password, "verified" => $verified);  
	    $returner = array("sqldata" => $this->dataar, "querychecker" => $this->querystate);
	    return $returner;  
	}

	public function verify_sql($code){
	    $sql = "SELECT " . $this->column1 . "," . $this->column3 . ", " . $this->column4 . " FROM  `" . $this->table . "` WHERE " . $this->column4 . "=?";
	    $stmt = mysqli_prepare($this->con, $sql);
	    mysqli_stmt_bind_param($stmt, "s", $code);
	    $this->querystate = mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $nick, $email, $verurl);
	    mysqli_stmt_fetch($stmt);
	    $this->dataar = array("nick" => $nick, "email" => $email, "verurl" => $verurl);
	    $returner = array("sqldata" => $this->dataar, "querychecker" => $this->querystate);
	    return $returner;  
	}

	public function updateverify_sql($code){
	    $sql = "UPDATE `" . $this->table . "` SET " . $this->column5 . "= 'yes' WHERE " . $this->column4 . "=?";
	    $stmt = mysqli_prepare($this->con, $sql);
	    mysqli_stmt_bind_param($stmt, "s", $code);
	    $this->querystate = mysqli_stmt_execute($stmt);
	    $returner = array("sqldata" => "none", "querychecker" => $this->querystate);
	    return $returner;
	}


}


?>