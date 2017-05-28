<?php
session_start();
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");

if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "add":
            if(!empty($_POST["quantity"])) {
                $food_id = $_GET["id"];
                $product = mysql_fetch_array(mysql_query("select * from foods where id = '$food_id'"));
                $itemArray = array($product["id"]=>array('name'=>$product["name"], 'id'=>$product["id"], 'quantity'=>$_POST["quantity"], 'price'=>$product["price"]));

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($product["id"],$_SESSION["cart_item"])) {
                        foreach($_SESSION["cart_item"] as $k => $v) {
                            if($product["id"] == $k)
                                $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["id"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/flexbox.css">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.0/build/pure-min.css">
    <script src="https://use.fontawesome.com/91287065d2.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Menu</li>
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
            <li class="active"><a href="menu.php">Menu</a></li>
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
    <?php
    if(isset($_SESSION["cart_item"])){
        $item_total = 0;
        $item_quantity_total = 0;
        ?>
        <h3>Shopping Cart</h3>
        <table class="pure-table pure-table-horizontal">
            <tbody>
            <tr>
                <th align="center"><strong>Name</strong></th>
                <th align="center"><strong>Quantity</strong></th>
                <th align="center"><strong>Price</strong></th>
                <th align="center"><strong>Action</strong></th>
            </tr>
            <?php
            foreach ($_SESSION["cart_item"] as $item){
                ?>
                <tr>
                    <td><strong><?php echo $item["name"]; ?></strong></td>
                    <td align="center"><?php echo $item["quantity"]; ?></td>
                    <td align=right><?php echo "Rp ".number_format($item["price"]); ?></td>
                    <td><a href="menu.php?action=remove&id=<?php echo $item["id"]; ?>" class="pure-button pure-button-primary" style="background: #d50000">Remove Item</a></td>
                </tr>
                <?php
                $item_total += ($item["price"]*$item["quantity"]);
                $item_quantity_total += $item["quantity"];
            }
            ?>

            <tr>
                <td align="left"><strong>Total</strong></td>
                <td align="center"><strong><?php echo $item_quantity_total; ?></strong></td>
                <td align="right"><strong><?php echo "Rp ".number_format($item_total); ?></strong></td>
                <td><a href="checkout.php" class="pure-button pure-button-primary"><i class="fa fa-shopping-cart fa-lg"></i> Checkout</a></td>
            </tr>
            </tbody>
        </table>
        <?php
    }
    else {
        echo '<h2>Nothing in the cart. Try buy some foods!</h2>';
    }
    ?>
    <form method="get" action="menu.php" class="pure-form" style="margin-top: 25px">
        <input type="text" name="s" id="s" placeholder="Search..."/>&nbsp<input type="submit" value="go" class="pure-button pure-button-primary" style="background: #d50000"/>
    </form>
    <?php
    if (isset($_GET['s'])) {
        $search = $_GET['s'];
        $search_query = "SELECT * FROM foods WHERE name LIKE '%"."$search"."%'";
        $foods=mysql_query($search_query);
        $total_search = mysql_num_rows($foods);
        if ($total_search > 0) {
            echo "<p>Search result for <strong>".$search."</strong>. <a href='menu.php' style='text-decoration: none'>Clear search</a></p>";
        } else {
            echo "<p>Your search <strong>".$search."</strong> did not match any foods here. <a href='menu.php' style='text-decoration: none'>Clear search</a></p>
                  <p>Suggestion:</p>
                  <ul>
                  <li>Make sure that all words are spelled correctly</li>
                  <li>Try different keywords</li>
                  <li>Try fewer keywords</li>
                  </ul>
            ";
        }
    } else {
        $foods=mysql_query('SELECT * FROM foods ORDER BY imagepath DESC');
    }
    while ($food=mysql_fetch_array($foods))
    {
        echo'
                 <div class="container">
                    <div class="item">
                        <img class="center-cropped" src="' . $food["imagepath"] . '" alt="' . $food["name"] . '">
                        <h3>'.$food["name"].'</h3>
                        <p>Provided by <strong>'.$food["seller"].'</strong><p>
                        <p><strong>Rp '.number_format($food["price"]).'</strong><p>
                        <form class="pure-form" method="post" action="menu.php?action=add&id='.$food["id"].'">
                            <input type="number" name="quantity" style="width: 60px; height: auto;" value="1" maxlength="10">&nbsp';
                            if (isset($_SESSION['seller'])) {
                                echo '<input class="pure-button pure-button-primary" type="submit" value="Only for Buyer Account" disabled>';
                            } else {
                                echo '<input class="pure-button pure-button-primary" type="submit" value="Add to Cart">';
                            }
                            echo '
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