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
    <title>About Us | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
</head>
<body>
    <div class="main-nav">
        <ul class="nav">
            <li class="pageName">About</li>
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="about.php">About</a></li>
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
        <img class="center-cropped" style="width: 100%" src="images/about.jpg">
        <h2 style="color: #d50000">Kantin Online</h2>
        <p>We provide more than 20 menus of  best food around town. Served by experienced  & talented chef.</p>
        <h2 style="color: #d50000">Contact Us</h2>
        <ul class="contact-info">
            <li class="phone"><a href="tel:62211234567">+62-(21)-1234-567</a></li>
            <li class="mail"><a href="mailto:cs@kantinonline.com">cs@kantinonline.com</a></li>
            <li class="location"><a href="https://www.google.co.id/maps/place/L'Avenue+Office+and+Residence+Jakarta/@-6.2486049,106.8438862,18.98z/data=!4m5!3m4!1s0x0:0x3d49f83a4bd8ff3e!8m2!3d-6.2486693!4d106.8441667?hl=id">L'Avenue Office, Jakarta Selatan</a></li>
        </ul>
    </main>

</body>
</html>