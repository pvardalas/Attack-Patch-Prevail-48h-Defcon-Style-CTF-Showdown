<?php
include "utils.php";

function make_row($item, $i) {
  if ($item["restricted"] === False) {
    echo "<tr><td>".$item["name"]."</td><td>\$".$item["price"]."</td><td>".$item["desc"]."</td><td onclick='add_item($i)'>Add to cart!<br><img src='assets/add_to_cart.png'></td></tr>\n";
  } else {
    //this is a restricted item...
    if (is_admin()) {
      echo "<tr><td>".$item["name"]."</td><td>\$".$item["price"]."</td><td>".$item["desc"]."</td><td onclick='add_item($i)'>Add to cart!<br><img src='assets/add_to_cart.png'></td></tr>\n";
    } else {
      echo "<tr><td><s>".$item["name"]."</s></td><td><s>\$".$item["price"]."</s></td><td>I'm sorry, this is a special item subject to restrictions for purchase.</td><td><s>Add to cart!</s></td></tr>\n";
    }
  }
}

make_header("Browse");

echo "<table><tr><th style='width:10%'>Name</th><th style='width:7%'>Price</th><th style='width:30%'>Description</th><th style='width:7%'>Purchase</th></tr>";
$i = 0;
foreach ($items as $key => $item) {
  make_row($item, $key);
}
