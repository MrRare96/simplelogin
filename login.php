<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            Nickname/E-mail:
            <input type="text" name="nick" /> <br>
            Password:
            <input type="password" name="pass" /><br>
            <input type="submit" name="submit" value="Login" /> 
        </form>
    </body>
</hmtl>
<?php

    /* 
    login script php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */

    //setup parameters location
    include('setup.php');
    //starts a session-> http://nl1.php.net/manual/en/function.session-start.php
    session_start();
    //connects to database -> http://nl1.php.net/manual/en/book.mysqli.php //
    $con = mysqli_connect($dburl,$uname,$pw,$dbname);
    //checks if there is a connection problem
    if(!$con){     
        echo 'failed to connect to db: ' . mysqli_error($con);   
    }
    //stops possible injection attacks
    $nick = mysqli_real_escape_string($con, $_POST['nick']);
    
    //executes query
    $query = mysqli_query($con, checksql($nick));
    // checks for possible problems with query
    if(!$query){
        echo 'Propably your nick name/ email that you entered, is wrong, please try again.';
    } else {
        //gets data from database, if everything goes right, 1 row will return
        while($row = mysqli_fetch_array($query)) { 
            $nick = $row["nick"];
            $password = $row["password"];
            $verified = $row["verified"];
        } 
        //decrypts password
        $outpass = mcrypt_ecb(MCRYPT_RIJNDAEL_256, $_POST['pass'], base64_decode($password), MCRYPT_DECRYPT);
    }
    //closes connection to database
    mysqli_close($con);
    //had some problems with comparing input pw with the decrypted pw, this solved it
    $inpass = mcrypt_ecb(MCRYPT_RIJNDAEL_256, 'key', $_POST['pass'], MCRYPT_ENCRYPT);
    $inpass = mcrypt_ecb(MCRYPT_RIJNDAEL_256, 'key', $inpass, MCRYPT_DECRYPT);
    // compares pw if there is a password inputted <- fu english
    if(isset($_POST['pass'])){
        //compares pw from db and inputted pw
        if($inpass == $outpass){
            //temporary solution for saving nickname temporary
            $_SESSION['nick'] = $nick;
            //sends you back to home page, or says you didnt verify
            if($verified == NULL){
                echo 'You did not verify your account, please check your email inbox!';
            } else {
                echo 'You are logged in, you will be send back to the homepage within 2 seconds!';
                 echo '<meta http-equiv="refresh" content="5;url=' . $returnpage . '">';
            }
            
        } else {
            //if you inputted the wrong pw or nickname/email, this will be shown:
            echo '<br>Try again or registere <a href="register.php"> here. </a>';
        }
    }
?>

