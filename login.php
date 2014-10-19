<?php
/* 
 * login script php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */
//here i start everything, both needed files that contains functions are retrieved here, also the form hasher launches using this.
include "setup.php";
require 'password.php'; 
session_start();
//form hasher which might not properly work, since after one failed attempt to login, it says you are hacking, smart, isnt it :?
form_hasher();
?>
<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            Nickname/E-mail:
            <input type="text" name="nick" /> <br>
            Password:
            <input type="password" name="pass" /><br>
            <input type="hidden" name="hash" value="<?php echo $_SESSION['formhash']; ?>" />

            <input type="submit" name="submit" value="Login" /> 
        </form>
    </body>
</hmtl>
<?php 
//variables retrieved from a session and form, to check if your using a verified form
$formhash = $_POST['hash'];
$randomstring = $_SESSION['randomstring'];   
// if your form is verified by this script, you are able to continue
if(password_verify($randomstring, $formhash)){  
    //checks if there is a connection problem
    if(!$con){     
        echo 'failed to connect to db: ' . mysqli_error($con);   
    }
    //stops possible injection attacks
    $nick = mysqli_real_escape_string($con, $_POST['nick']);
    //executes query
    $query = checksql($nick);
    // checks for possible problems with query
    if($query["nick"] == ""){
        echo 'Propably your nick name/ email that you entered, is wrong, please try again.';
    } 
    // compares pw if there is a password inputted <- fu english
    if(isset($_POST['pass']) || $_POST['pass'] != ""){
        $pass = $_POST['pass'];
        //compares pw from db and inputted pw
        if(password_verify($pass, $query['password'])){
            //sends you back to home page, or says you didnt verify
            if($query['verified'] == NULL){
                echo 'You did not verify your account, please check your email inbox!';
            } else {
                $_SESSION['nick'] = $query['nick'];
                echo 'You are logged in, you will be send back to the homepage within 2 seconds!';
                unset($_SESSION['formhash']);
                unset($_SESSION['randomstring']);
                 echo '<meta http-equiv="refresh" content="5;url=' . $returnpage . '">';
            }    
        } else {
            //if you inputted the wrong pw or nickname/email, this will be shown:
            echo '<br>Try again or registere <a href="register.php"> here. </a><br>';
            //var_dump($query);
            unset($_SESSION['formhash']);
            unset($_SESSION['randomstring']);  
        }
    }
} else {
    //on first launch of script, hash will always be wrong, so to prevent the warning from showing on first launch:
    if($_POST['hash'] != ""){
        echo 'Something funny is going on, are you doing it?';
    } else {
        echo 'Go ahead, log in!';
    }
}
?>
