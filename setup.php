<?php
/* 
    Setup file php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */

include_once("db.php");

//database connection setup
$pw = 'Negami-Senpai';
$dburl = 'mysql.grendelhosting.com';
$uname = 'u478222806_rare';
$dbname = 'u478222806_user';
//contains all things needed to connect to an database
$dbsetup = array("pw" => $pw, "dburl" => $dburl, "uname" => $uname, "dbname" => $dbname);

//connects to database
$dbcon = new dbconnect($dbsetup);
$con = $dbcon-> connect();


//My db exists out of 6 columns, 1 column is an auto increment primery key called ID <- might need this in future
$table = 'reg_user'; // table naam
$column1 = 'nick'; //var_char(15) unique, but do whatever you need 
$column2 = 'password';//tiny text, can also be var_char, but I got some problems creating my db, so i used this
$column3 = 'email';// var_char (255) <- unique, should be enough for email, could also use tiny text, but i am random
$column4 = 'verificationurl';//tiny text <- should be unique, i should use var_char here since this is random generated but with a max char of 10 XD, wups
$column5 = 'verified';//var_char(3) <- is null if not verified, if it is verified, it will change into "yes", but you can set that to whatever you want
//contains all vars needed to connect to an database, including the connecting data
$tablesetup = array("table" => $table, "column1" => $column1, "column2" => $column2, "column3" => $column3, "column4" => $column4, "column5" => $column5, "con" => $con);

//this is the link to where some scripts could return to
$returnpage = 'http://managonereader.acoxi.com';

//this is the mail what will be send when registered
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

?>