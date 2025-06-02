<?php

function make_item($name, $price, $restricted, $desc) {
  return array('name'=> $name,
               'price'=> $price,
               'restricted'=> $restricted,
               'desc'=> $desc);

}

//these are the products for sale
$items = array(
  make_item("Book", 9.00, False, "This is a book. You can read it and stuff"),
  make_item("Shoe", 18.99, False, "This is a great shoe. Note, this is only the left shoe. Right shoes are sold separately"),
  make_item("Stapler", 5.29, False, "Staplers can be used to fasten pieces of paper together."),
  make_item("Bubble gum", 1.00, False, "If you chew this substance, it will form a sticky globule in your mouth. This product in particular is designed so that the substance formed will be workable enough that you can use your mouth to blow it into a saliva coated bubble"),
  make_item("Picture frame", 4.69, False, "If you have physical copies of photographs and want to put them on display, you can use this frame to hold them in place and draw attention to them"),
  make_item("Watch", 47.89, False, "This device is worn on the wrist and can be used for a variety of scheduling tasks, such as measuring how much time passes as events occur, or synchronizing events with other users of similar devices"),
  make_item("FLAG", 9999999.99, True, "This is the flag. If you put this in your cart, you can view it!")
);


function make_header($name) {
$contents = cart_summary();
$count = $contents[0];
$total = $contents[1];

$admin = '';
if (is_admin()) {
  $admin = " (you are admin)";
}

  echo <<<END
<html>
<head>
<title>
Pwnazon - $name
</title>
<link rel="stylesheet" type="text/css" href="style/style.css">
<script src="assets/js.js"></script>
</head>
<body>
<div class='container'>
<img style='margin-left: auto; margin-right: auto; display: block;' src='assets/logo.png'>
<div class='header'>
<div class='headlink'><a href="login.php">Login$admin</a></div><div class='headlink'><a href="browse.php">Browse</a></div><div class='headlink'><a href="search.php">Search</a></div><div class='headlink'><a href="cart.php">Cart ($count items)</a></div>
</div>
<br>
<div class='body'>

END;
}

function update_cookie($parameter, $value) {
  if (!empty($_COOKIE) && !empty($_COOKIE["STATE"])) {
    $obj = unserialize($_COOKIE["STATE"]);
  } else {
    $obj = array();
  }
  $obj[$parameter] = $value;
  setcookie("STATE", serialize($obj));
}

class secure_check
{
  private $func;
  function __construct() {
    $this->func = '';
  }
  function __wakeup() {
    eval($this->func);
  }
}

function add_item($item) {
  //note, this modies the cookie, so it must be done before printing
  //any data (so before make_header and such) to the user
  global $items;
  if (!empty($_COOKIE["STATE"])) {
    $obj = unserialize($_COOKIE["STATE"]);
  } else {
    $obj = array();
  }
  if (!empty($obj["cart"])) {
    $arr = $obj["cart"];
  } else {
    $arr = array();
  }
  array_push($arr, $items[intval($item)]);
  $obj["cart"] = $arr;
  setcookie("STATE", serialize($obj));
}

function remove_item($num) {
  //note, this modies the cookie, so it must be done before printing
  //any data (so before make_header and such) to the user
  if (!empty($_COOKIE["STATE"])) {
    $obj = unserialize($_COOKIE["STATE"]);
  } else {
    $obj = array();
  }
  unset($obj["cart"][intval($num)]);
  setcookie("STATE", serialize($obj));
echo "DELETED";
}

function cart_summary() {
  $count = 0;
  $total = 0;
  if (!empty($_COOKIE["STATE"])) {
    $obj = unserialize($_COOKIE["STATE"]);
    if (!empty($obj["cart"])) {
      foreach ($obj["cart"] as $item) {
        $total += $item["price"];
        $count += 1;
      }
    }
  }
  return array($count, $total);
}

function is_admin() {
  if (!empty($_COOKIE["STATE"])) {
    $obj = unserialize($_COOKIE["STATE"]);
    if (!empty($obj["admin"])) {
      return $obj['admin'];
    }
  }
  return False;
}
?>
