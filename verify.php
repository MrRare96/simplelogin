<?php

/* 
    Verify script for php inlog system, made by Eldin Zenderink from scratch. 
 * I am to lazy to look up the license stuff, but: 
 * these scripts are free for ever, I would like to see some credit, hidden on a page for example, but if you dont want to, thats fine
 * Also if you want to show me your project with this little login scriptie, post it here: ask.fm/rareamv
 */

//location of setup parameters
 include('setup.php');
//connects to database
 $con = mysqli_connect($dburl,$uname,$pw,$dbname);
//retrieves verification code from url
$code = $_GET['verifycode'];
//prevents sql injection attacks
$code = mysqli_real_escape_string($con, $code);

 
    //executes query
    $query = mysqli_query($con, verifysql($code));
    // if something goes wrong:
    if(!$query){
        echo 'Something went wrong while verifying with the database, try again later.';
    } else {
        //gets information out the database, verfifcation url = unique, so 1 row will return
        while($row = mysqli_fetch_array($query)) { 
            $dbverify = $row['verificationurl'];
            $email = $row['email'];
            $nick = $row['nick'];
        } 
        //checks if verify code from database is the same as from the url
        if($code == $dbverify){
            //updates the database to set verified to yes
            $query2 = mysqli_query($con, updateverifysql($code));
            //if something went wrong with the query, this happens
            if(!$query2 || $query2 == NULL){
                echo 'Something went wrong while updating database, when this happens, its a fault at our end!<br> If this happens, tell me here: <p> <iframe src="http://ask.fm/widget/e2dbfcc21e0cade2a632fbf1a5211430b175ca65?stylesheet=small&fgcolor=%23000000&bgcolor=%23EFEFEF&lang=42" frameborder="0" scrolling="no" width="120" height="275" style="border:none;"></iframe>';
            } else {
                //this will happen when everything goes right.
                echo '<b>You are verified, you can now login to your account! Thanks for registering!<br>
                    Your nickname = ' . $nick . ' and your email = ' . $email . ', if this is not correct, warn the creator using:<p>
                        <iframe src="http://ask.fm/widget/e2dbfcc21e0cade2a632fbf1a5211430b175ca65?stylesheet=small&fgcolor=%23000000&bgcolor=%23EFEFEF&lang=42" frameborder="0" scrolling="no" width="120" height="275" style="border:none;"></iframe><p>
                        he will try to figure things out on his end!<br>
                You will be redirected to the homepage, where you can login within 5 seconds!</b>';
                 echo '<meta http-equiv="refresh" content="5;url=' . $returnpage . '">';
            }
            
        }
        
    }



