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
        case "finish":
            $order_id = $_GET["id"];
            if(mysql_query("delete from foodorder where id = '$order_id'")) {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Order Finished!');";
                echo "</script>";
            } else {
                echo "<script language='javascript' type='text/javascript'>";
                echo "alert('Error finishing order! Please contact support!');";
                echo "</script>";
            }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incoming Order | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/flexbox.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Incoming Order</li>
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
    <h2>Order to Your Store</h2>
    <div class="container">
        <table class="pure-table pure-table-horizontal" style="width: 100%">
            <tbody>
            <tr>
                <th align="center"><strong>Name</strong></th>
                <th align="center"><strong>Quantity</strong></th>
                <th align="center"><strong>Price</strong></th>
                <th align="center"><strong>Buyer</strong></th>
                <th align="center"><strong>Phone</strong></th>
                <th align="center"><strong>Address</strong></th>
                <th align="center"><strong>Note</strong></th>
                <th align="center"><strong>Date</strong></th>
                <th align="center"><strong>Action</strong></th>
            </tr>
            <?php
            $item_total = 0;
            $item_quantity_total = 0;
            $orders=mysql_query("SELECT * FROM foodorder WHERE store_mail = '$email'");
            while ($order=mysql_fetch_array($orders)){
                ?>
                <tr>
                    <td><strong><?php echo $order["name"]; ?></strong></td>
                    <td align="center"><?php echo $order["quantity"]; ?></td>
                    <td align=right><?php echo "Rp ".number_format($order["price"]); ?></td>
                    <td align="center"><?php echo $order["buyer"]; ?></td>
                    <td align="center"><?php echo $order["phone"]; ?></td>
                    <td align="center"><?php echo $order["address"]; ?></td>
                    <td align="center"><?php echo $order["note"]; ?></td>
                    <td align="center"><?php echo substr($order["id"], -17, 4)."/".implode("/", str_split(substr($order["id"], -13, 4), 2)); ?></td>
                    <td><a href="order_in.php?action=finish&id=<?php echo $order["id"]; ?>" class="pure-button pure-button-primary" style="background: #d50000">Finish Order</a></td>
                </tr>
                <?php
                $item_total += ($order["price"]*$order["quantity"]);
                $item_quantity_total += $order["quantity"];
            }
            ?>

            <tr>
                <td align="center"><strong>Total</strong></td>
                <td align="center"><strong><?php echo $item_quantity_total; ?></strong></td>
                <td align="right"><strong><?php echo "Rp ".number_format($item_total); ?></strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
}
}