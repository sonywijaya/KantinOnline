<?php
session_start();
if (!isset($_SESSION['buyer'])) {
    header("location: signin.php");
}

$email=$_SESSION['buyer'];
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");
$grabdata = mysql_query("select name, password, phonenumber from signupuser  where email = '$email'");
$currentdata = mysql_fetch_array($grabdata);

if(isset($_POST['submit'])){
    if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['password'])) {
        echo "<script language='javascript' type='text/javascript'>
               alert('All form must be filled!');
               </script>";
    } else {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        if (mysql_query("update signupuser set name = '$name', phonenumber = '$phone',password = '$password' where email = '$email'")) {
            echo "<script language='javascript' type='text/javascript'>
               alert('Profile updated!');
               location.href='profile.php'
               </script>";
        } else {
            echo "<script language='javascript' type='text/javascript'>
               alert('There is problem in updating profile, please contact support!');
               </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Profile</li>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <?php
        if (isset($_SESSION['seller'])) {
            echo '
            <li><a href="addfood.php">Add Food</a></li>
            <li><a href="myfood.php">My Food</a></li>
            ';
        }
        else {
            echo'
            <li><a href="menu.php">Menu</a></li>
            <li><a href="help.php">Help</a></li>
            ';
        }
        ?>
        <li class="dropdown">
            <?php
            if (isset($_SESSION['seller'])) {
                $email = $_SESSION['seller'];
                $username = mysql_fetch_array(mysql_query("select name from seller  where email = '$email'"));
                $order_num=mysql_num_rows(mysql_query("SELECT * FROM foodorder WHERE store_mail = '$email'"));
                if ($order_num > 0) {
                    echo '<a href="sellerprofile.php" class="dropbtn">'.$username["name"].' (<span style="color: yellow">'.$order_num.'</span>) &#x25BC</a>';
                } else {
                    echo '<a href="sellerprofile.php" class="dropbtn">'.$username["name"].' &#x25BC</a>';
                }
                echo '
                <div class="dropdown-content">
                    <a href="sellerprofile.php">Profile</a>';
                if ($order_num > 0) {
                    echo '<a href="order_in.php">Incoming Order (<span style="color: #d50000">'.$order_num.'</span>)</a>';
                } else {
                    echo '<a href="order_in.php">Incoming Order</a>';
                }
                echo '
                    <a href="help.php">Help</a>
                    <a href="signout.php">Sign Out</a>
                </div>';
            }
            elseif (isset($_SESSION['buyer'])) {
                $email = $_SESSION['buyer'];
                $username = mysql_fetch_array(mysql_query("select name from signupuser  where email = '$email'"));
                $order_num=mysql_num_rows(mysql_query("SELECT * FROM foodorder WHERE buyer_mail = '$email'"));
                if ($order_num > 0) {
                    echo '<a href="profile.php" class="dropbtn">'.$username["name"].' (<span style="color: yellow">'.$order_num.'</span>) &#x25BC</a>';
                } else {
                    echo '<a href="profile.php" class="dropbtn">'.$username["name"].' &#x25BC</a>';
                }
                echo '
                <div class="dropdown-content">
                    <a href="profile.php">Profile</a>';
                if ($order_num > 0) {
                    echo '<a href="order.php">My Order (<span style="color: #d50000">'.$order_num.'</span>)</a>';
                } else {
                    echo '<a href="order.php">My Order</a>';
                }
                echo '
                    <a href="signout.php">Sign Out</a>
                </div>';
            }
            else {
                echo '
            <a href="#" class="dropbtn">Login &#x25BC</a>
            <div class="dropdown-content">
                <a href="signin.php">Buyer Login & Registration</a>
                <a href="sellsignin.php">Seller Login & Registration</a>
            </div>';
            }
            ?>
        </li>
    </ul>
</div>
<main>
    <form class="pure-form pure-form-stacked" action="" method="post">
        <fieldset>
            <label for="name">Name</label>
            <?php
            echo '<input name="name" type="text" value="'.$currentdata["name"].'" maxlength="30">';
            ?>

            <label for="phone">Phone</label>
            <?php
            echo '<input name="phone" type="tel" value='.$currentdata["phonenumber"].' maxlength="15">';
            ?>

            <label for="password">Password</label>
            <?php
            echo '<input name="password" type="password" value='.$currentdata["password"].' maxlength="30">';
            ?>

            <br>
            <input type="submit" class="pure-button pure-button-primary" name="submit" value="Update">
        </fieldset>
    </form>
</main>
</body>
</html>



