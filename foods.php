<?php

$user="id894518_kantin_online";
$pass_db="kantinonline123";
$database="id894518_kantin_online";
$connect=mysql_connect('localhost', $user, $pass_db);
mysql_select_db($database, $connect) or die("unable to select database");

function get_list_view_html($food_id, $food) {

    $output = "";
    $output = $output . '<div class="container">';
    $output = $output . '<div class="item">';
    $output = $output . '<img class="center-cropped" src="' . $food["imagepath"] . '" alt="' . $food["name"] . '">';
    $output = $output . '<h3>'.$food["name"].'</h3>';
    $output = $output . '<p>Provided by <strong>'.$food["seller"].'</strong><p>';
    $output = $output . '<p><strong>Rp'.$food["price"].'</strong><p>';
    $output = $output . '<form class="pure-form" method="post action="menu.php?action=add&id='.$food_id.'">';
    $output = $output . '<input type="hidden" name="hidden_name" value="'.$food["name"].'">';
    $output = $output . '<input type="hidden" name="hidden_price" value="'.$food["price"].'">';
    $output = $output . '<input type="text" name="quantity" style="width: 50px; height: auto;" value="1">&nbsp';
    $output = $output . '<input class="pure-button pure-button-primary" type="submit" name="add_cart" value="Add to Cart">';
    $output = $output . '</form>';
    $output = $output . '</div>';
    $output = $output . '<div class="container" style="background: transparent; box-shadow: none;">';
    $output = $output . '</div>';

    return $output;
}

$fetchfoods = mysql_query("SELECT * FROM foods");

if($fetchfoods === FALSE) {
    die(mysql_error());
}

$id=1;
while($food = mysql_fetch_array($fetchfoods))
{
    $foods[$id] = $food;
    $id=$id+1;
}