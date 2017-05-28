<?php
session_start();
if (!isset($_SESSION['seller'])) {
    header("location: sellsignin.php");
}

$email=$_SESSION['seller'];
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");

if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "update":
            if(!empty($_POST["name"]) && !empty($_POST["price"])) {
                $food_id = $_GET["id"];
                $food_name = $_POST["name"];
                $food_price = $_POST["price"];
                if (mysql_query("update foods set name = '$food_name', price = '$food_price' where id = '$food_id'")) {
                    echo "<script language='javascript' type='text/javascript'>";
                    echo "alert('Food updated!');";
                    echo "</script>";
                }
                else {
                    echo "<script language='javascript' type='text/javascript'>";
                    echo "alert('Problem in updating food. Please contact support!');";
                    echo "</script>";
                }
            }
            break;
        case "delete":
            $food_id = $_GET["id"];
            if (mysql_query("delete from foods where id = '$food_id'")) {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Food deleted!');";
                echo "</script>";
            }
            else {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Problem in deleting food. Please contact support!');";
                echo "</script>";
            }
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Food | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/flexbox.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">My Food</li>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <?php
        if (isset($_SESSION['seller'])) {
            echo '
            <li><a href="addfood.php">Add Food</a></li>
            <li class="active"><a href="myfood.php">My Food</a></li>
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
    <h2>Your Listing</h2>
    <?php
    $foods=mysql_query("SELECT * FROM foods WHERE email ='$email' ORDER BY imagepath DESC");
    if (mysql_num_rows($foods) < 1) {
        echo '<p>You did not post any food yet. Try add food <a href="addfood.php" style="text-decoration: none">here</a>.</p>';
    }
    while ($food=mysql_fetch_array($foods))
    {
        echo'
                 <div class="container">
                    <div class="item">
                        <img class="center-cropped" src="' . $food["imagepath"] . '" alt="' . $food["name"] . '"><br><br>
                        <form class="pure-form" method="post" action="myfood.php?action=update&id='.$food["id"].'">
                        <input name="name" type="text" value="'.$food["name"].'" maxlength="30"><br>
                        <input name="price" type="number" value="'.$food["price"].'" maxlength="10"><br><br>
                        <a href="myfood.php?action=delete&id='.$food["id"].'" class="pure-button pure-button-primary" style="background: #d50000">Delete Food</a>
                        <input class="pure-button pure-button-primary" type="submit" value="Update">
                        </form>
                    </div>
                 </div>
                 <div class="container" style="background: transparent; box-shadow: none;">
                 </div>';
    }
    ?>
</main>
</body>
</html>