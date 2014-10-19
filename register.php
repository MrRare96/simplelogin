<?php
/* 
 * Register script php inlog system, made by Eldin Zenderink from scratch(except password.php). 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
*/
//here i start everything, both needed files that contains functions are retrieved here, also the form hasher launches using this.
include "setup.php";
include "sql.php";
require 'password.php'; 
session_start();
//form hasher which might not properly work, since after one failed attempt to login, it says you are hacking, smart, isnt it :?
form_hasher();
//launches sql function class
$sqldata = new sqlfunctions($tablesetup);
?>

<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            Nick name:
            <input type="text" name="nick" /> <br>
            E-mail:
            <input type="email" name="email" /> <br>
            Password:
            <input type="password" name="pass" /><br>
            Retype Password:
            <input type="password" name="pass2" /><br>
            <input type="hidden" name="hash" value="<?php echo $_SESSION['formhash']; ?>" />
            <input type="submit" name="submit" value="Register" /> <p><br>
        </form>
               
<?php

//variables retrieved from a session and form, to check if your using a verified form
$formhash = $_POST['hash'];
$randomstring = $_SESSION['randomstring'];
// if your form is verified by this script, you are able to continue
if(password_verify($randomstring, $formhash)){
    //checks if nick name is set and email is real/not used for spamming
    if(isset($_POST['nick'])&& spamcheck($_POST['email']) == TRUE){
        //checks if you didnt make a mistake in your password
        if($_POST['pass'] == $_POST['pass2']){  
            //encrypt password 
            $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);   
            //stops possible sql injection attacks
            $nick = $_POST['nick'];
            $email = $_POST['email'];
            //creates part of verification url
            $length = 10;
            //generates random string
            $verificationurl = generateRandomString($length);
            //executes query from sqlfunctions class
            $sqldata-> reg_sql($nick, $pass, $email, $verificationurl);
            $query = $sqldata->querystate;
            //checks if everything goes right
            if($query == false || $query == NULL){
                //most likely the only error would be that email is wrong or nick is already in use
                echo '<br> E-mail or Nick is already in use, please register using another email adress<p>';   
            } else {
                //sends email with verification link to registered user
                veremail($_POST['email'], $_POST['nick'], $verificationurl);
                echo '<br><b> An email is send to ' . $_POST['email'] . ', it contains a link to verify your account!</b><p>';
                echo '<br><b> Thank you for registering! You will be redirected to the home/search page within 5 seconds!</b><p>';
                unset($_SESSION['formhash']);
                unset($_SESSION['randomstring']);
                echo '<meta http-equiv="refresh" content="5;url=' . $returnpage . '">';                 
            }                   
        } else {
            echo 'You mistyped your password 2e time.<br> try again<p>';
        }
    } else {
         echo 'You either didnt fill in a nickname, or your email is invalid<p>';
    }
} else {
    //at first launch the hash stuff is never right, this checks if it is the first launch
     if($_POST['hash'] != ""){
        echo 'Something funny is going on, are you doing it?';
    } else {
        echo 'Go ahead, log in!';
    }
}
  
?>
        Remember, this is still in development, changes to the system can occur frequently, so you might lose your account without notice!<br>
        E-mail verification almost finished.
        Registration is free for ever. Also just in general: never use the same password over and over again. Everything can be hacked! <br>
        Functions like save your reading process and continue from where you left will be added. In the future there will<br>
        be an MyAnimeList(MAL) implementation, however, since its quit difficult, it will take a while, or in worst case scenario, it will never happen! 
        
    </body>
</hmtl>