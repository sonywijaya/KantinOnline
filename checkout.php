<?php
session_start();

$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");
$success = false;

if(isset($_POST['finalize'])){
    if($_POST['shipping']=="delivery") {
        if (empty($_POST['address'])) {
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Empty Address!');";
            echo "</script>";
        }
        else {
            $email = $_SESSION['buyer'];
            $grabdata = mysql_query("select name, password, phonenumber from signupuser  where email = '$email'");
            $currentdata = mysql_fetch_array($grabdata);
            $buyer_name = $currentdata["name"];
            $buyer_phone = $currentdata["phonenumber"];
            $address = $_POST['address'];
            $note = $_POST['note'];
            foreach ($_SESSION["cart_item"] as $item) {
                $item_id = $item["id"];
                $item_name = $item["name"];
                $item_quantity = $item["quantity"];
                $item_price = $item["price"];
                $store_email_query = mysql_query("select email from foods  where id = '$item_id'");
                $fetch_store_mail = mysql_fetch_array($store_email_query);
                $store_email = $fetch_store_mail["email"];
                if (mysql_query("insert into foodorder values('$item_id', '$store_email', '$item_name', '$item_quantity','$item_price',
                                                          '$buyer_name', '$email', '$buyer_phone', '$address', '$note')")) {
                    $success = true;
                } else {
                    echo "<script language='javascript' type='text/javascript'>";
                    echo "alert('Problem in Checkout. Please contact support!');";
                    echo "</script>";
                    $success = false;
                }
            }
            if($success) {
                unset($_SESSION["cart_item"]);
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Checkout Success!');";
                echo "</script>";
                $URL="order.php";
                echo "<script>location.href='$URL'</script>";
            }
        }
    }
    else {
        $email = $_SESSION['buyer'];
        $grabdata = mysql_query("select name, password, phonenumber from signupuser  where email = '$email'");
        $currentdata = mysql_fetch_array($grabdata);
        $buyer_name = $currentdata["name"];
        $buyer_phone = $currentdata["phonenumber"];
        $note = $_POST['note'];
        foreach ($_SESSION["cart_item"] as $item) {
            $item_id = $item["id"];
            $item_name = $item["name"];
            $item_quantity = $item["quantity"];
            $item_price = $item["price"];
            $store_email_query = mysql_query("select email from foods  where id = '$item_id'");
            $fetch_store_mail = mysql_fetch_array($store_email_query);
            $store_email = $fetch_store_mail["email"];
            if (mysql_query("insert into foodorder values('$item_id', '$store_email', '$item_name', '$item_quantity','$item_price',
                                                          '$buyer_name', '$email', '$buyer_phone', '', '$note')")) {
                $success = true;
            } else {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Problem in Checkout. Please contact support!');";
                echo "</script>";
                $success = false;
            }
        }
        if($success) {
            unset($_SESSION["cart_item"]);
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Checkout Success!');";
            echo "</script>";
            $URL="order.php";
            echo "<script>location.href='$URL'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <script src="https://use.fontawesome.com/91287065d2.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Checkout</li>
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
    <div class="row">
        <div class="primary col">
            <?php
            if(isset($_SESSION["cart_item"])){
            $item_total = 0;
            $item_quantity_total = 0;
            ?>
                <h3>Finalize Your Order</h3>
                <div class="container">
                    <table class="pure-table pure-table-horizontal" style="width: 100%">
                        <tbody>
                        <tr>
                            <th align="center"><strong>Name</strong></th>
                            <th align="center"><strong>Quantity</strong></th>
                            <th align="center"><strong>Price</strong></th>
                        </tr>
                        <?php
                        foreach ($_SESSION["cart_item"] as $item){
                            ?>
                            <tr>
                                <td><strong><?php echo $item["name"]; ?></strong></td>
                                <td align="center"><?php echo $item["quantity"]; ?></td>
                                <td align=right><?php echo "Rp ".number_format($item["price"]); ?></td>
                            </tr>
                            <?php
                            $item_total += ($item["price"]*$item["quantity"]);
                            $item_quantity_total += $item["quantity"];
                        }
                        ?>

                        <tr>
                            <td align="center"><strong>Total</strong></td>
                            <td align="center"><strong><?php echo $item_quantity_total; ?></strong></td>
                            <td align="right"><strong><?php echo "Rp ".number_format($item_total); ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                    <p>Want to modify your cart? <a href="menu.php" style="text-decoration: none">Back to menu</a></p>
                    <form action="" class="pure-form pure-form-stacked" method="post">
                        <label class="control control--radio">Delivery (<span style="color: green">+ Rp 5,000</span>)
                            <input type="radio" name="shipping" value="delivery" checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Pickup
                            <input type="radio" name="shipping" value="pickup by Myself"/>
                            <div class="control__indicator"></div>
                        </label>
                        <br>
                        <?php
                        if (!isset($_SESSION['buyer'])) {
                            echo "<script language='javascript' type='text/javascript'>
                                   alert('You need to login before finalize checkout!');
                                   </script>";
                            echo '
                            <input type="text" name="address" style="width: 100%" placeholder="If you select delivery, please fill in the address here." disabled>
                            <input type="text" name="note" style="width: 100%" placeholder="Additional note." disabled>
                            <br>
                            <input class="pure-button pure-button-primary" type="submit" name="finalize" value="You need to login" disabled>
                            <p class="message">Click <a href="signin.php" style="text-decoration: none">here</a> to login</p>
                            ';
                        }
                        else {
                            echo '
                            <input type="text" name="address" style="width: 100%" placeholder="If you select delivery, please fill in the address here." maxlength="50">
                            <input type="text" name="note" style="width: 100%" placeholder="Additional note." maxlength="200">
                            <br>
                            <input class="pure-button pure-button-primary" type="submit" name="finalize" value="Finalize">
                            ';
                        }
                        ?>
                    </form>
                </div>
            <?php
            }
            else {
                echo '<h2>Nothing in the cart. Try buy some foods!</h2>';
            }
            ?>
        </div>
        <div class="secondary col">
            <h3>Information</h3>
            <ul>
                <li>All transactions payment is using cash</li>
                <li>You pay when you receive item</li>
                <li>You cannot cancel your order using the website, you need to contact the seller directly</li>
                <li>All foods here are ready to eat</li>
                <li>You can pick up directly to our "offline" canteen</li>
                <li>We will deliver your food no longer than 30 minutes</li>
                <li>Further information plase contact <a href="mailto:cs@kantinonline.com" style="text-decoration: none">Customer Service</a></li>
            </ul>
        </div>
    </div>

</main>
</body>
</html>