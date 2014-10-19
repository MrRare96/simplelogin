<?php
session_start();
/* 
    Setup file php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */

//database connection setup
$pw = 'Negami-Senpai';
$dburl = 'mysql.grendelhosting.com';
$uname = 'u478222806_rare';
$dbname = 'u478222806_user';

$returnpage = 'http://managonereader.acoxi.com';

$con = mysqli_connect($dburl,$uname,$pw,$dbname);
//verification email containments, change whatever you need, I am using it for my own project.
function veremail($email, $nick, $vercode){
    //verification email setup
    $emailfrom = 'no-reply@managonereader.acoxi.com';
    $headers = 'From: ' . $emailfrom . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1';
    $creator = 'RareAMV';
    $webname = 'MangaOnEreader - beta!';
    
    $mail = '<html><body>Welcome to ' . $webname . ' ' . $nick . '.<p> '
                        . 'To finish your registration, you will need to verify your account using this link:<br>
                            <a href="http://managonereader.acoxi.com/verify.php?verifycode=' . $vercode . ' "> Verify </a><p>
                         This login system is currently under development, but it is safe. But since its in development, it might happen that you lose your account.<br>
                         There will be an e-mail send if this happens. The login system is not yet in use, but lateron you will be able to keep track of what you are reading<br>
                         and you will be able to resume reading if you suddenly left! Als a MAL implementation will be added in the future!<p>
                         Greetings from the creator of ' . $webname . ' : ' . $creator . ' :).</body></html>';
                 
    mail($email, 'Verify your account!', $mail, $headers);
}
//My db exists out of 6 columns, 1 column is an auto increment primery key called ID <- might need this in future
$table = 'reg_user'; // table naam
$column1 = 'nick'; //var_char(15) unique, but do whatever you need 
$column2 = 'password';//tiny text, can also be var_char, but I got some problems creating my db, so i used this
$column3 = 'email';// var_char (255) <- unique, should be enough for email, could also use tiny text, but i am random
$column4 = 'verificationurl';//tiny text <- should be unique, i should use var_char here since this is random generated but with a max char of 10 XD, wups
$column5 = 'verified';//var_char(3) <- is null if not verified, if it is verified, it will change into "yes", but you can set that to whatever you want

//inserts the data into the database when you register (used in register.php)
function regsql($nick, $pass, $email, $randomString){
    global $column1, $column2, $column3, $column4, $column5, $table, $con;
    //$regsql = 'INSERT INTO ' . $table . ' (' . $column1 . ', ' . $column2 . ', ' . $column3 . ', ' . $column4 . ') VALUES("' . $nick . '", "' . $pass . '", "' . $email . '", "' . $randomString . '");';
    $sql = 'INSERT INTO ' . $table . ' (' . $column1 . ', ' . $column2 . ', ' . $column3 . ', ' . $column4 . ') VALUES (?,?,?,?);';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $nick, $pass, $email, $randomString);
    return mysqli_stmt_execute($stmt);


    
}

//checks if email or nickname exists in database (used in login.php)
function checksql($nick){
    global $column1, $column2, $column3, $column4, $column5, $table, $con;
    //$checksql = "SELECT * FROM  `" . $table . "` WHERE " . $column1 . " =  '" . $nick . "' OR " . $column3 . " = '" . $nick . "'";
    $sql = "SELECT " . $column1 . "," . $column2 . "," . $column4 . " FROM  `" . $table . "` WHERE " . $column1 . " =? OR " . $column3 . "=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nick, $nick);
    $query = mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nickname, $password, $verified);
    mysqli_stmt_fetch($stmt);
    $userar = array("nick" => $nickname, "password" => $password, "verified" => $verified);
    return $userar;
}
//searches for the row where the given verification code is given. (used in verify.php)
function verifysql($code){
    global $column1, $column2, $column3, $column4, $column5, $table, $con;
    //$verifysql = "SELECT * FROM `" . $table . "` WHERE " . $column4 . " = '" . $code . "'";
    $sql = "SELECT " . $column1 . "," . $column3 . ", " . $column4 . " FROM  `" . $table . "` WHERE " . $column4 . "=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nick, $email, $verurl);
    mysqli_stmt_fetch($stmt);
    $userar = array("nick" => $nick, "email" => $email, "verurl" => $verurl);
    return $userar;
    
    
}
//updates verified column to yes in row where row contains the verification code (used in verify.php)
function updateverifysql($code){
    global $column1, $column2, $column3, $column4, $column5, $table, $con;
   // $sql2 = "UPDATE " . $table . " SET " . $column5 . " = 'yes' WHERE " . $column4 . " = '" . $code . "';";
    $sql = "UPDATE `" . $table . "` SET " . $column5 . "= 'yes' WHERE " . $column4 . "=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $code);
    $query = mysqli_stmt_execute($stmt);
    return $query;
}


    
//function that generates a string with random numbers and characters
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}    

//i use this to hash a form so only the form verified by the script can launch sql codes
function form_hasher(){
    $random = rand(1, 20);
    $randomstring = generateRandomString($random);
    $postver = password_hash($randomstring, PASSWORD_BCRYPT);
    $_SESSION['formhash'] = $postver;
    if($_POST['hash'] == ""){
        $_SESSION['randomstring'] = $randomstring;
        //echo $_SESSION['randomstring'] . " created";
    } else {
        $_SESSION['randomstring'] = $_SESSION['randomstring'];
        //echo $_SESSION['randomstring'] . " reused";
    }
}

//checks if email address is real and not used for spam, got this from http://www.w3schools.com/php/php_secure_mail.asp
function spamcheck($field) {
    // Sanitize e-mail address
    $field=filter_var($field, FILTER_SANITIZE_EMAIL);
    // Validate e-mail address
    if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return TRUE;
    } else {
        return FALSE;
    }
}


?>