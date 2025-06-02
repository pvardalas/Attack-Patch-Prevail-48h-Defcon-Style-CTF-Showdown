<?php

include 'utils.php';

make_header('Cart');

$obj = unserialize($_COOKIE["STATE"]);

if (empty($obj["cart"])) {
  echo "<h3>Your cart is empty!</h3>";
} else {
  echo "<table><tr><th>Name</th><th>Price</th><th>Remove from Cart</th></tr>";
  $total = 0;
  foreach ($obj["cart"] as $key => $item) {
    $total += $item["price"];
    $flag = '';
    if ($item["name"] === "FLAG") {
      $flag_file = fopen("key","r") or die ("NO KEY FILE");
      $flag = " - ".fread($flag_file, filesize("key"));
    }
    echo "<tr><td>".$item["name"].$flag."</td><td>\$".$item["price"]."</td><td onclick='remove_item($key)'>Remove</td>";
  }
  echo "<tr style='outline: solid black'><td>Total (before tax and shipping)</td><td>\$$total</td></table>";
}
?>
