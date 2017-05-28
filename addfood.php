<?php
session_start();
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");

if (!isset($_SESSION['seller'])) {
    header("location: sellsignin.php");
}

if(isset($_POST['add']))
{
    $email=$_SESSION['seller'];
    $grabname = mysql_query("select name from seller where email = '$email'");
    $sellername = mysql_fetch_array($grabname);
    $seller = $sellername['name'];

    $foodname = $_POST['foodname'];
    $foodprice = $_POST['foodprice'];
    $uploaddir = "images/seller_foods/";

    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    $file = $_FILES['imagefile']['tmp_name'];
    $timestamp = date("Ymd-H-i-s");
    $food_id = str_replace(' ', '', $seller).$timestamp;
    $destination = $uploaddir."IMG".$timestamp.".".pathinfo($_FILES['imagefile']['name'], PATHINFO_EXTENSION);
    if(is_uploaded_file($file) && !empty($_POST['foodname']) && !empty($_POST['foodprice'])) {
        if(move_uploaded_file($file, $destination)) {
            if (mysql_query("insert into foods values('$email', '$seller','$food_id', '$foodname', '$foodprice', '$destination')")) {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Added Successfully!');";
                echo "</script>";
                $URL="myfood.php";
                echo "<script>location.href='$URL'</script>";
            }
            else {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Could not add food. Please contact support!');";
                echo "</script>";
            }
        }
        else {
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Upload file failed!');";
            echo "</script>";
        }
    }
    else {
        echo "<script language='javascript' type='text/javascript'>";
        echo "alert('Do not send empty form!');";
        echo "</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Food | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/flexbox.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Add Food</li>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <?php
        if (isset($_SESSION['seller'])) {
            echo '
            <li class="active"><a href="addfood.php">Add Food</a></li>
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
    <form class="pure-form pure-form-stacked" enctype="multipart/form-data" action="" method="post">
        <fieldset>
            <label for="foodname">Food Name</label>
            <input type="text" name="foodname" placeholder="Food Name" maxlength="30">

            <label for="price">Price</label>
            <input class="form-control" type="number" name="foodprice" placeholder="Food Price (e.g. 10000)" maxlength="10">

            <label for="image">Image</label>
            <input type="file" name="imagefile" accept="image/*" /> <p class="message"><i>Pick the best and real image, because you can't replace it.</i></p>
            <input type="submit" name="add" class="pure-button pure-button-primary" value="Add">
        </fieldset>
    </form>
</main>
</body>
</html>