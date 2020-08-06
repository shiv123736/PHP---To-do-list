<?php

session_start();
if(isset($_SESSION['loggedin'])) {
    header("location:to_do_list.php");
}

$showalert = false;
$showerror = false;
$display="none";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $username = $password = $con_password = "";
    if( !empty($_POST['email']) && !empty($_POST['uname']) && !empty($_POST['pass']) && !empty($_POST['con_pass'])) {
        include "component/db_connection.php";
        $email = test_input($_POST['email']);
        $username = test_input($_POST['uname']);
        $password = test_input($_POST['pass']);
        $con_password = test_input($_POST['con_pass']);

        if( $password == $con_password ) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "insert into to_do_list (email, username, password, date) values('$email', '$username', '$hash', CURRENT_TIMESTAMP())";
            $result = $conn->query($sql);
            if( $result == true) {
                if( $result == true) {
                    $showalert = true;
                }
            }     
            else {
                $showerror = true;
                $showerror = ' This Username is already registered, Use another username.';
            }
            
        }
        else {
        $showerror = true;
        $showerror = ' Password do not match.';
        }
    }
    else {
        $showerror = true;
        $showerror = 'ops!, Fill required details.';
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="styles/log_in_css.css" type="text/css" media="all"> -->
    <link href="styles/log_in_css.css?<?=filemtime("styles/log_in_css.css")?>" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
</head>

<body>
    
    <?php require "component/_navbar.php"; ?>

    <?php
    if($showerror)
    echo '<div id="alert_danger">
        <p id="warning" style="display:inline;"><strong>ops!</strong> '.$showerror.'</p>
        <input type=button value="close" id="close_danger" onclick="alert_danger.style.display='.$display.'">
    </div> ';
    ?>

    <?php
    if($showalert)
    echo '<div id="alert_success">
        <p id="warning" style="display:inline;"><strong>Success!</strong> Account created successfully... you can log in now</p>
        <input type=button value="close" id="close_success" onclick="close_alert("alert_success")">
    </div> ';
    ?>
    <div class="container">
        <h1 id="heading" style="margin-bottom: 30px; text-align: center; opacity: 0; ">Sign up</h1>
        <div id="box" class="center" style="opacity: 0;">

            <form action="signup.php" method="post" name="form">
                <label class="lab">email id:</label><br>
                <input type="email" class="inpu" id="email" name="email" ><br>
                <label class="lab">username:</label><br>
                <input type="text" class="inpu" id="uname" name="uname" ><br>
                <label class="lab">password:</label><br>
                <input type="password" class="inpu" id="pass" name="pass" ><br>
                <label class="lab">confirm password:</label><br>
                <input type="password" class="inpu" id="con_pass" name="con_pass" ><br>
                <center>
                    <input class="btn" id="create" type="submit" value="sign up">
                    <input class="btn" id="cancel" type="button" value="cancel">
                </center>
            </form>
            <p id="new_acc">already have account ?</p><a href="index.php"> log in</a>
        </div>
    </div>
    <script>
        <?php
    if($showerror)
    echo  'alert_danger.addEventListener("click", function (){
           alert_danger.style.display="none";
         })';

    if($showalert)
         echo 'alert_success.addEventListener("click", function (){
                 alert_success.style.display="none";
            })';
    ?>


        function close_alert(element) {
            form.pass.value="";
            form.con_pass.value="";
            // form.email.value="";
            console.log("close");
            al = document.getElementById(element);
            al.style.display = "none";
        }

        window.onload = function () {
            fade_out();
        };
        function fade_out() {
            to_do = document.getElementById('heading');
            box = document.getElementById('box');
            // tab = document.getElementById('tab');

            o_todo = 0;
            o_box = 0;
            // o_tab = 0;

            to_do.style.opacity = o_todo;
            box.style.opacity = o_box;
            // tab.style.opacity = o_tab;
            
            clr_to_do = setInterval(() => {
                to_do.style.opacity = o_todo;
                box.style.opacity = o_box;
                // tab.style.opacity = o_tab;

                if (o_todo < 1.10){
                    o_todo = o_todo + 0.09;
                    o_box = o_box + 0.09;
                    // o_tab = o_tab + 0.09;
                }
                else {
                    clearInterval(clr_to_do);
                    // fade_out_box();
                }
            }, 50);
            
        }


    </script>
</body>

</html>