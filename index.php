<?php
session_start();
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
</head>
<body style="background: white; overflow: hidden">
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Kantin Online</li>
        <li class="active"><a href="index.php">Home</a></li>
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
    <div class="row">
        <div class="primary col">
            <h2>Our freshest foods!</h2>
            <?php
                $last_items_query = mysql_query("SELECT * FROM foods ORDER BY imagepath DESC LIMIT 2");
                while ($last_items = mysql_fetch_array($last_items_query))
                {
                    $path = $last_items["imagepath"];
                    $title = $last_items["name"];
                    echo'
                    <a href="menu.php"><img class="center-cropped" style="width: 100%;" src="'.$path.'" alt="'.$title.'"></a>
                    ';
                }
            ?>
        </div><!--/.primary-->

        <div class="secondary col" style="text-align: right">
            <div>
                <p style="font-size: 300%; margin-bottom: -20px;"><strong><span style="color:#d50000;">Kantin</span></strong> Online</p>
                <h2>Grab our best food now!</h2>
                <p>We provide more than 20 menus of best food around town</p>
                <p>Served by experienced  & talented chef.</p>
                <a href="menu.php" class="pure-button pure-button-primary" style="background: #d50000">View Menu</a>
            </div>
            <a href="menu.php"><img class="center-cropped" style="width: 100%; height: 50%; padding-top: 27%" src="images/home.png"></a>
        </div><!--/.secondary-->
    </div>
</main>
</body>
</html>
}