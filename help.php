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
    <title>Help | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Help</li>
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
            <li class="active"><a href="help.php">Help</a></li>
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
    <img class="center-cropped" style="width: 100%" src="images/help.png">
    <h2 style="color: #d50000">Kantin Online Help</h2>
    <h3>How to Login?</h3>
    <p>You can login by hover your mouse to <strong>Login</strong> menu in the top right of website.
        Then click <a href="signin.php" style="text-decoration: none"> Buyer Login & Registration</a>. You cannot login without registering first. In order to register, you can simply
     click <strong>Create an Account</strong> link in the bottom of the form. Fill in your information, then you can login.</p>
    <h3>How to Buy Food?</h3>
    <p>You can choose the foods in <a href="menu.php" style="text-decoration: none">Menu</a> page. Add to the cart food(s) that you want to buy. If you finish adding your wanted food to the cart,
    click <strong>Checkout</strong>. In order to finalize your order, you need to login first. After you login, you can finalize your order. Do not
    forget to put delivery address if you choose delivery service.</p>
    <h3>How to Sell Food?</h3>
    <p>Remember that we use different account in buying and selling food. If you did not create seller account yet, you can have it by registering
    to our system. The step of registering as seller are basically the same with buyer account, but it has different page. Go to
        <a href="sellsignin.php" style="text-decoration: none">Seller Login & Registration</a> to register as a seller.</p>
    <h3>Additional Information</h3>
    <ul>
        <li>All transactions payment is using cash</li>
        <li>You pay when you receive item</li>
        <li>You cannot cancel your order using the website, you need to contact the seller directly</li>
        <li>All foods here are ready to eat</li>
        <li>You can pick up directly to our "offline" canteen</li>
        <li>We will deliver your food no longer than 30 minutes</li>
        <li>Further information plase contact <a href="mailto:cs@kantinonline.com" style="text-decoration: none">Customer Service</a></li>
    </ul>
</main>
</body>
</html>