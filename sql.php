<?php

//all sql functions can be found here

class sqlfunctions{

	function __construct($variables){
		$this->vars = $variables;
	}

	public $querystate;
	public $dataar;
	
	public function reg_sql($nick, $pass, $email, $randomString){
	    $sql = 'INSERT INTO ' . $this->vars['table'] . ' (' . $this->vars['column1'] . ', ' . $this->vars['column2'] . ', ' . $this->vars['column3'] . ', ' . $this->vars['column4'] . ') VALUES (?,?,?,?);';
	    $stmt = mysqli_prepare($this->vars['con'], $sql);
	    mysqli_stmt_bind_param($stmt, "ssss", $nick, $pass, $email, $randomString);
	    $this->querystate = mysqli_stmt_execute($stmt);  
	}

	public function check_sql($nick){
	    $sql = "SELECT " . $this->vars['column1'] . "," . $this->vars['column2'] . "," . $this->vars['column4'] . " FROM  `" . $this->vars['table'] . "` WHERE " . $this->vars['column1'] . " =? OR " . $this->vars['column3'] . "=?";
	    $stmt = mysqli_prepare($this->vars['con'], $sql);
	    mysqli_stmt_bind_param($stmt, "ss", $nick, $nick);
	    $this->querystate = mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $nickname, $password, $verified);
	    mysqli_stmt_fetch($stmt);
	    $this->dataar = array("nick" => $nickname, "password" => $password, "verified" => $verified);    
	}

	public function verify_sql($code){
	    $sql = "SELECT " . $this->vars['column1'] . "," . $this->vars['column3'] . ", " . $this->vars['column4'] . " FROM  `" . $this->vars['table'] . "` WHERE " . $this->vars['column4'] . "=?";
	    $stmt = mysqli_prepare($this->vars['con'], $sql);
	    mysqli_stmt_bind_param($stmt, "s", $code);
	    $this->querystate = mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $nick, $email, $verurl);
	    mysqli_stmt_fetch($stmt);
	    $this->dataar = array("nick" => $nick, "email" => $email, "verurl" => $verurl);
	}

	public function updateverify_sql($code){
	    $sql = "UPDATE `" . $this->vars['table'] . "` SET " . $this->vars['column5'] . "= 'yes' WHERE " . $this->vars['column4'] . "=?";
	    $stmt = mysqli_prepare($this->vars['con'], $sql);
	    mysqli_stmt_bind_param($stmt, "s", $code);
	    $this->querystate = mysqli_stmt_execute($stmt);
	}
}


?>