<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header("location:index.php");
    exit;
}
$name = $_SESSION['username'];
$update = false;
$display = 'none';
$alert=FALSE;
$showerror="";
$title = $description = $id = "";
// if($_SERVER["REQUEST_METHOD"]=="POST")    
if(isset($_POST['save']))
{
    include "component/db_connection.php";
   
    $title=test_input($_POST["titlee"]);
    $description=test_input($_POST["description"]); 
    if(empty($title) && empty($description))
    {
        $showerror = true;
        $showerror = ' Add Some Information into columns.';
    }
    else
    {     
        //insertion into database
        $sql="insert into `notes` (username, title, description) values('$name', '$title', '$description')";
        $result=$conn->query($sql);
        if($result==FALSE) {
            $showerror = true;
            $showerror = "Try another note";
        }
        else { 
            $alert=TRUE;    //for ALERT MESSAGE
            $alert = ' Your Note is submitted successfully.';
        }
    }
    
}

if(isset($_GET['delete'])) {
    include "component/db_connection.php";
    $id = $_GET['delete'];
    $sql = "delete from `notes` where sno=$id";
    $result = $conn->query($sql);
    if($result == true ) {
        $alert=TRUE;    //for ALERT MESSAGE
        $alert = ' Your Note has been deleted successfully.';
    }
}

if(isset($_GET['edit'])) {
    include "component/db_connection.php";
    $id = $_GET['edit'];
    $sql = "select * from `notes` where sno=$id";
    $result = $conn->query($sql);
    if($result->num_rows == 1) {
        $update = true;
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $description = $row['description'];
    }
}

if(isset($_POST['update'])) {
    include "component/db_connection.php";
    $id = test_input($_POST["sno"]);
    $title=test_input($_POST["titlee"]);
    $description=test_input($_POST["description"]); 
    if(empty($title) && empty($description))
    {
        $showerror = true;
        $showerror = ' Add Some Information into columns.';
    }
    else {
        // $sql = "update notes set title='$title', description='$description' where sno=$id";
        $sql = "update `notes` set title='$title', description='$description' where sno=$id";
        $result = $conn->query($sql);
        if($result == true ) {
            $alert=TRUE;    //for ALERT MESSAGE
            $alert = ' Your Note has been update successfully.';
        }
    }
}

if(isset($_POST['clear'])) {
    $title = $description = "";
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
    <!-- <link rel="stylesheet" href="styles/to_do_list_css.css"> -->
    <link href="styles/to_do_list_css.css?<?=filemtime("styles/to_do_list_css.css")?>" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - <?php echo $_SESSION['username'] ?></title>
</head>

<body>
<?php require "component/_navbar.php"; ?>
    <?php
    if($showerror)
    echo '<div id="alert_danger">
        <p id="warning" style="display:inline;"><strong>ops! </strong>'.$showerror.'</p>
        <input type=button value="close" id="close_danger" onclick="alert_danger.style.display='.$display.'">
    </div> ';
    ?>

    <?php
    if($alert)
    echo '<div id="alert_success">
        <p id="warning" style="display:inline;"><strong>Success!</strong> '.$alert.'</p>
        <input type=button value="close" id="close_success" onclick="close_alert("alert_success")">
    </div> ';
    ?>

    <div class="container">
        <h1 id="heading" style="margin-bottom: 30px; text-align: center; opacity: 0; ">welcome - <?php echo $_SESSION['username'] ?> to your list:</h1>
        <div id="box" class="center" style="opacity: 0;">
        <form action="to_do_list.php" method="post" >
            <label class="lab">title:</label><br>
            <input type="text" class="inpu" id="titlee" name="titlee" value="<?php echo $title;?>"><br>
            <input type="hidden" class="inpu" id="sno" value="<?php echo $id?>" name="sno"><br>
            <label class="lab">Description:</label><br>
            <textarea style="height: 100px" class="inpu" id="description" name="description"><?php echo $description;?></textarea><br>
            <center>
            <?php 
            if(!$update) {
                echo '<input class="btn-primary" id="submit_note" type="submit" value="add note" name="save">
                      <input class="btn-danger" id="clear_note" type="submit" value="clear" name="clear"> ';
            }
            
            if($update) {
                echo '<button class="btn-info" id="submit_note" type="submit" name="update">update</button>
                      <input class="btn-danger" id="clear_note" type="submit" value="cancel" name="clear"> ';
            }
            ?>
            </center>
        </form>
        </div>
    </div>
    <div id="tab" class="table" style="overflow-x: auto; opacity: 0;">
        <table class="tab">
            <tr>
                <th>s.no</th>
                <th>title</th>
                <th>description</th>
                <th>date & time</th>
                <th style="width:200px;">action</th>
            </tr>
            <tbody id="fetch_data">
            <?php
            include "component/db_connection.php";
            $sql = "select * from notes where username='$name'";
            $result = $conn->query($sql);
            $index = 0;
            if( $result->num_rows > 0) {
                while ( $row = $result->fetch_assoc()) {
                    $index++;
                 echo '<tr>
                        <td>'.$index.'</td>
                        <td>'.$row['title'].'</td>
                        <td>'.$row['description'].'</td>
                        <td>'.$row['date'].'</td>
                        <td>
                            <a href="to_do_list.php?delete='.$row['sno'].'" class="btn-danger btn-responsive">Delete</a>
                            <a href="to_do_list.php?edit='.$row['sno'].'" class="btn-info btn-responsive">Edit</a>
                        </td>
                    </tr>';
                }
            }
            

            ?>
            </tbody>

        </table>

    </div>
    <script>
        <?php
    if($showerror)
    echo  'alert_danger.addEventListener("click", function (){
           alert_danger.style.display="none";
         })';

    if($alert)
         echo 'alert_success.addEventListener("click", function (){
                 alert_success.style.display="none";
            })';
    ?>

        window.onload = function () {
            fade_out();
        };
       

        //fade in effect on TO DO LIST
        function fade_out() {
            to_do = document.getElementById('heading');
            box = document.getElementById('box');
            tab = document.getElementById('tab');

            o_todo = 0;
            o_box = 0;
            o_tab = 0;

            to_do.style.opacity = o_todo;
            box.style.opacity = o_box;
            tab.style.opacity = o_tab;
            
            clr_to_do = setInterval(() => {
                to_do.style.opacity = o_todo;
                box.style.opacity = o_box;
                tab.style.opacity = o_tab;

                if (o_todo < 1.10){
                    o_todo = o_todo + 0.09;
                    o_box = o_box + 0.09;
                    o_tab = o_tab + 0.09;
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