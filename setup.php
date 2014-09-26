<?php

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
    global $column1, $column2, $column3, $column4, $column5, $table;
    $regsql = 'INSERT INTO ' . $table . ' (' . $column1 . ', ' . $column2 . ', ' . $column3 . ', ' . $column4 . ') VALUES("' . $nick . '", "' . $pass . '", "' . $email . '", "' . $randomString . '");';
    return $regsql;
}

//checks if email or nickname exists in database (used in login.php)
function checksql($nick){
    global $column1, $column2, $column3, $column4, $column5, $table;
    $checksql = "SELECT * FROM  `" . $table . "` WHERE " . $column1 . " =  '" . $nick . "' OR " . $column3 . " = '" . $nick . "'";
    return $checksql;
}
//searches for the row where the given verification code is given. (used in verify.php)
function verifysql($code){
    global $column1, $column2, $column3, $column4, $column5, $table;
    $verifysql = "SELECT * FROM `" . $table . "` WHERE " . $column4 . " = '" . $code . "'";
    return $verifysql;
}
//updates verified column to yes in row where row is contains the verification code (used in verify.php)
function updateverifysql($code){
    global $column1, $column2, $column3, $column4, $column5, $table;
    $sql2 = "UPDATE " . $table . " SET " . $column5 . " = 'yes' WHERE " . $column4 . " = '" . $code . "';";
    return $sql2;
}

?>
