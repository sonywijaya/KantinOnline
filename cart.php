<?php
$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");

if(isset($_POST["add_to_cart"]))
{
    if(isset($_SESSION["shopping_cart"]))
    {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if(!in_array($_GET["id"], $item_array_id))
        {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id'             =>     $_GET["id"],
                'item_name'           =>     $_POST["hidden_name"],
                'item_price'          =>     $_POST["hidden_price"],
                'item_quantity'       =>     $_POST["quantity"]
            );
            $_SESSION["shopping_cart"][$count] = $item_array;
        }
        else
        {
            echo '<script>alert("Item Already Added")</script>';
            echo '<script>window.location="cart.php"</script>';
        }
    }
    else
    {
        $item_array = array(
            'item_id'             =>     $_GET["id"],
            'item_name'           =>     $_POST["hidden_name"],
            'item_price'          =>     $_POST["hidden_price"],
            'item_quantity'       =>     $_POST["quantity"]
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}
if(isset($_GET["action"]))
{
    if($_GET["action"] == "delete")
    {
        foreach($_SESSION["shopping_cart"] as $keys => $values)
        {
            if($values["item_id"] == $_GET["id"])
            {
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>alert("Item Removed")</script>';
                echo '<script>window.location="cart.php"</script>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
    <script type="text/javascript" src="js/simpleCart.js"></script>
</head>
<body>
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Kantin Online</li>
        <li class="active"><a href="cart.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <?php
        session_start();
        if (isset($_SESSION['seller'])) {
            echo '
            <li><a href="addfood.php">Add Food</a></li>
            <li><a href="myfood.php">My Food</a></li>
            ';
        }
        else {
            echo'
            <li><a href="menu.php">Menu</a></li>
            <li><a href="sellsignin.php">Sell Food</a></li>
            ';
        }
        ?>
        <li class="dropdown">
            <?php
            if (isset($_SESSION['seller'])) {
                echo '
                <a href="#" class="dropbtn">Seller Account &#x25BC</a>
            <div class="dropdown-content">
                <a href="sellerprofile.php">Profile</a>
                <a href="order.php">Order</a>
                <a href="signout.php">Sign Out</a>
            </div>';
            }
            elseif (isset($_SESSION['buyer'])) {
                echo '
            <a href="#" class="dropbtn">Account &#x25BC</a>
            <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="signout.php">Sign Out</a>
            </div>';
            }
            else {
                echo '
            <a href="#" class="dropbtn">Account &#x25BC</a>
            <div class="dropdown-content">
                <a href="signin.php">Sign In</a>
            </div>';
            }
            ?>
        </li>
    </ul>
</div>
<main>
<div class="container" style="width:700px;">
    <h3 align="center">Simple PHP Mysql Shopping Cart</h3><br />
    <?php
    $result = mysql_query("SELECT * FROM foods");
    if(mysql_num_rows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            ?>
            <div class="col-md-4">
                <form method="post" action="cart.php?action=add&id=<?php echo $row["email"]; ?>">
                    <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">
                        <img src="<?php echo $row["imagepath"]; ?>" class="img-responsive" /><br />
                        <h4 class="text-info"><?php echo $row["name"]; ?></h4>
                        <h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>
                        <input type="text" name="quantity" class="form-control" value="1" />
                        <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
                        <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
                        <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
                    </div>
                </form>
            </div>
            <?php
        }
    }
    ?>
    <div style="clear:both"></div>
    <br />
    <h3>Order Details</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th width="40%">Item Name</th>
                <th width="10%">Quantity</th>
                <th width="20%">Price</th>
                <th width="15%">Total</th>
                <th width="5%">Action</th>
            </tr>
            <?php
            if(!empty($_SESSION["shopping_cart"]))
            {
                $total = 0;
                foreach($_SESSION["shopping_cart"] as $keys => $values)
                {
                    ?>
                    <tr>
                        <td><?php echo $values["item_name"]; ?></td>
                        <td><?php echo $values["item_quantity"]; ?></td>
                        <td>$ <?php echo $values["item_price"]; ?></td>
                        <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                        <td><a href="cart.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
                    </tr>
                    <?php
                    $total = $total + ($values["item_quantity"] * $values["item_price"]);
                }
                ?>
                <tr>
                    <td colspan="3" align="right">Total</td>
                    <td align="right">$ <?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<br />
</body>
</html>