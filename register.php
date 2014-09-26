<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            Nick name:
            <input type="text" name="nick" /> <br>
            E-mail:
            <input type="text" name="email" /> <br>
            Password:
            <input type="password" name="pass" /><br>
            Retype Password:
            <input type="password" name="pass2" /><br>
            <input type="submit" name="submit" value="Register" /> <p><br>
                </form>
               
<?php

/* 
    register script php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */

//loction of every setup parameter
include('setup.php');
require 'password.php';

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

//checks if nick name is set and email is real/not used for spamming
    if(isset($_POST['nick'])&& spamcheck($_POST['email']) == TRUE){
        //checks if you didnt make a mistake in your password
        if($_POST['pass'] == $_POST['pass2']){
       
            
            
            //encrypt password 
            $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

            //connects to database
            $con = mysqli_connect($dburl,$uname,$pw,$dbname);
            
            //checks if there are no errors in connection with database
            if(!$con){
                echo 'failed to connect to db: ' . mysqli_error($con);
            }
            //stops possible sql injection attacks
            $nick = mysqli_real_escape_string($con, $_POST['nick']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            //creates part of verification url
            $length = 10;
            $verificationurl = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                   
            //executes query
            $query = mysqli_query($con, regsql($nick, $pass, $email, $verificationurl));

            //checks if everything goes right
            if(!$query){
                //most likely the only error would be that email is wrong or nick is already in use
                echo '<br> E-mail is already in use, please register using another email adress<p>';   
            } else {
                //sends email with verification link to registered user
                veremail($_POST['email'], $_POST['nick'], $verificationurl);
                echo '<br><b> An email is send to ' . $_POST['email'] . ', it contains a link to verify your account!</b><p>';
                echo '<br><b> Thank you for registering! You will be redirected to the home/search page within 5 seconds!</b><p>';
                echo '<meta http-equiv="refresh" content="5;url=' . $returnpage . '">';
                
            }
            //closes connection with database
            mysqli_close($con);
            
        } else {
            echo 'You mistyped your password 2e time.<br> try again<p>';
        }
    } else {
        echo 'You either didnt fill in a nickname, or your email is invalid<p>';
    }
  
?>
        Remember, this is still in development, changes to the system can occur frequently, so you might lose your account without notice!<br>
        E-mail verification almost finished.
        Registration is free for ever. Also just in general: never use the same password over and over again. Everything can be hacked! <br>
        Functions like save your reading process and continue from where you left will be added. In the future there will<br>
        be an MyAnimeList(MAL) implementation, however, since its quit difficult, it will take a while, or in worst case scenario, it will never happen! 
        
    </body>
</hmtl>
