<?php
session_start();

if (isset($_SESSION['buyer'])) {
    header("location: menu.php");
}

$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);

mysql_select_db($database, $connect) or die("unable to select database");

if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $query=mysql_query("select email, password from signupuser where email = '$email' AND  password = '$password'");

    $loginCheck=mysql_num_rows($query);

    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script language='javascript' type='text/javascript'>
               alert('All form must be filled!');
               </script>";

    } else if ($loginCheck == 1) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['buyer'] = $email;
        $_SESSION['password'] = $password;
        header("location: menu.php");
    }
    else if ($loginCheck != 1) {
        echo "<script language='javascript' type='text/javascript'>
               alert('Your username or password is incorrect!');
               </script>";
    }
}

if(isset($_POST['register'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $phone=$_POST['phone'];

    $user="Kantin_Online";
    $pass_db="kantinonline123";
    $database="Kantin_Online";
    $connect=mysql_connect('localhost', $user, $pass_db);

    mysql_select_db($database, $connect) or die("unable to select database");

    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['name']) || empty($_POST['phone'])) {
        echo "<script language='javascript' type='text/javascript'>
               alert('Your username or password is incorrect!');
               </script>";
    }
    else {
        if(mysql_query("insert into signupuser values('$name', '$email', '$password', '$phone')")) {
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('You have registered successfully. Now you need to login!');";
            echo "</script>";
            $URL="signin.php";
            echo "<script>location.href='$URL'</script>";
        }
        else {
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Problem in registering account. Please contact support.');";
            echo "</script>";
            $URL="about.php";
            echo "<script>location.href='$URL'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Login | Kantin Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Roboto" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="bg-login">
<div class="main-nav">
    <ul class="nav">
        <li class="pageName">Buyer Login</li>
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
    <div class="login-page">
        <div class="form">
            <form class="register-form" action="" method="post">
                <center><h2>Buyer Registration</h2></center>
                <?php
                if (isset($_SESSION['buyer'])) {
                    echo '
                    <input type="text" name="name" placeholder="Please Sign Out From Seller Account" disabled>
                    <input type="password" name="password" placeholder="Please Sign Out From Seller Account" disabled>
                    <input type="email" name="email" placeholder="Please Sign Out From Seller Account." disabled>
                    <input type="tel" name="phone" placeholder="Please Sign Out From Seller Account" disabled>
                    <input type="submit" name="register" value="Please Sign Out From Seller Acc." class="button" style="background: #b3b3b3" disabled>
                    ';
                }
                else echo '
                    <input type="text" name="name" placeholder="name" maxlength="30">
                    <input type="password" name="password" placeholder="password" maxlength="30">
                    <input type="email" name="email" placeholder="email address" maxlength="30">
                    <input type="tel" name="phone" placeholder="phone number" maxlength="15">
                    <input type="submit" name="register" value="Create" class="button">';
                ?>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
            <form class="login-form" action="" method="post">
                <center><h2>Buyer Login</h2></center>
                <?php
                if (isset($_SESSION['buyer'])) {
                echo '
                    <input type="email" name="email" placeholder="Please Sign Out From Seller Account" disabled>
                    <input type="password" name="password" placeholder="Please Sign Out From Seller Account" disabled>
                    <input type="submit" name="register" value="Please Sign Out From Seller Acc." class="button" style="background: #b3b3b3" disabled>
                    ';
                }
                else {
                    echo '
                    <input type="email" name="email" placeholder="email address" maxlength="30">
                    <input type="password" name="password" placeholder="password" maxlength="30">
                    <input type="submit" name="login" value="Login" class="button">
                    ';
                }
                ?>
                <p class="message">Not registered? <a href="#">Create an account</a></p>
            </form>
        </div>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script type="text/javascript" src="js/toggle.js"></script>
</main>
</body>
</html>

