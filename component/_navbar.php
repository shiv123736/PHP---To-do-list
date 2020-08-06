<?php
$show = false;
if(isset($_SESSION['loggedin'])) {
    $show = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="styles/log_in_css.css" type="text/css" media="all"> -->
    <link href="styles/navbar.css?<?=filemtime("styles/log_in_css.css")?>" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
</head>

<div class="mob-dropdown">
    <a class="mob-link" href="#">Menu</a>
    <div class="navbar">
        <ul>
            <li><a class="link" href="to_do_list.php">to do list</a></li>
            <?php 
            if(!$show) {
              echo' <li><a class="link" href="signup.php">sign up</a></li>
                    <li><a class="link" href="index.php">log in</a></li>';
            }
 
            if($show) 
            echo '<li><a class="link" href="logout.php">log out</a></li>'; 
            ?>

            <li id="about"><a class="link" href="#">about us</a></li>
        </ul>
    </div>
</div>
