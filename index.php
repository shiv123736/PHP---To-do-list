<?php

session_start();
if(isset($_SESSION['loggedin'])) {
    header("location:to_do_list.php");
}

$login = false;
$showerror = false;
$display="none";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "component/db_connection.php";
    $username = $password = "";
    if( !empty($_POST['uname']) && !empty($_POST['pass'])) {
        $username = test_input($_POST['uname']); 
        $password = test_input($_POST['pass']);
            // $sql = "select * from to_do_list where username='$username' AND password='$password'";
            $sql = "select * from to_do_list where username='$username'";
            $result = $conn->query($sql);
            if($result->num_rows == 1) { 
                while( $row = $result->fetch_assoc()) {
                    if( password_verify($password, $row['password'])) {
                        $login = true;
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        header("location:to_do_list.php");
                    }
                    else {
                        $showerror = true;
                        $showerror = ' Invalid Credentials.';
                    }
                }
            }
            else {
                $showerror = true;
                $showerror = ' Invalid Credentials.';
            }
    }
    else {
        $showerror = true;
        $showerror = ' Fill required details.';
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
    <title>Log in</title>
</head>

<body>
    


<?php require "component/_navbar.php"; ?>
<?php
    if($showerror)
    echo '<div id="alert_danger">
        <p id="warning" style="display:inline;"><strong>ops! </strong>'.$showerror.'</p>
        <input type=button value="close" id="close_danger" >
    </div> ';
    ?>

    <?php
    if($login)
    echo '<div id="alert_success">
        <p id="warning" style="display:inline;"><strong>Success! </strong> Account created successfully... you can log in now</p>
        <input type=button value="close" id="close_success" >
    </div> ';
    ?>
    <div class="container">
        <h1 id="heading" style="margin-bottom: 30px; text-align: center; opacity: 0; ">log in</h1>
        <div id="box" class="center" style="opacity: 0;">

        <form action="index.php" method="post" name="form" >
            <label class="lab">username:</label><br>
            <input type="text" class="inpu" id="uname" name="uname" ><br>
            <label class="lab">password:</label><br>
            <input type="password" class="inpu" id="pass" name="pass" ><br>
            <center>
                <input class="btn" id="submit_note" type="submit" value="log in" >
            </center>
        </form>
        <p id="new_acc">need an account ?</p><a href="signup.php" >  sign up</a> 
        </div>
    </div>
    <script>

    // alert_success.addEventListener('click', function (){
    //     alert_success.style.display='none';
    // });
    // alert_danger.addEventListener('click', function (){
    //     alert_danger.style.display='none';
    // });
    <?php
    if($showerror)
    echo  'alert_danger.addEventListener("click", function (){
           alert_danger.style.display="none";
         })';

    if($login)
         echo 'alert_success.addEventListener("click", function (){
                 alert_success.style.display="none";
            })';
    ?>

    

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