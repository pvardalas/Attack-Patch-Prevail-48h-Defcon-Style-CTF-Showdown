<?php
define('STATE_HMAC_KEY', 'asdckljnasdckljnsakljdcnsakljdcnaslkjdcnaslkdcn');

// Helper to read STATE safely
function get_state(): array {
    if (empty($_COOKIE['STATE']) || empty($_COOKIE['STATE_SIG'])) {
        return [];
    }
    $data = $_COOKIE['STATE'];
    $sig  = $_COOKIE['STATE_SIG'];
    // verify integrity
    if (!hash_equals(hash_hmac('sha256', $data, STATE_HMAC_KEY), $sig)) {
        // tampered—reset
        return [];
    }
    // unserialize but forbid any objects
    $obj = @unserialize(
      $data,
      ['allowed_classes' => false]
    );
    return is_array($obj) ? $obj : [];
}

// Helper to write STATE + HMAC
function set_state(array $obj): void {
    $data = serialize($obj);
    $sig  = hash_hmac('sha256', $data, STATE_HMAC_KEY);
    setcookie('STATE',     $data, 0, '/', '', false, true);
    setcookie('STATE_SIG', $sig,  0, '/', '', false, true);
}
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
    $obj = get_state();
  } else {
    $obj = array();
  }
  $obj[$parameter] = $value;
  set_state($obj);
}

class secure_check
{
  private $func;
  function __construct() {
    $this->func = '';
  }
  // function __wakeup() {
 //   eval($this->func);
  // }
}

function add_item($item) {
  //note, this modies the cookie, so it must be done before printing
  //any data (so before make_header and such) to the user
  global $items;
  if (intval($item) === 6 && !is_admin()) {
    header('Content-Type: text/plain');
    header('Transfer-Encoding: chunked');
    header('Connection: keep-alive');

    function send_chunk($chunk) {
        $len = dechex(strlen($chunk));
        echo $len . "\r\n";
        echo $chunk . "\r\n";
        flush();
    }

    set_time_limit(60);

    for ($i = 0; $i < 300; $i++) { // 300 * 0.1s = ~30s delay
        send_chunk(str_repeat(" ", 1024)); // 1 KB spaces
        usleep(100000);
    }

    // Send zero-length chunk to indicate end of response
    echo "0\r\n\r\n";
    flush();
    http_response_code(403);
    exit('Access denied.');
  }
  if (!empty($_COOKIE["STATE"])) {
    $obj = get_state();
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
  set_state($obj);
}

function remove_item($num) {
  //note, this modies the cookie, so it must be done before printing
  //any data (so before make_header and such) to the user
  if (!empty($_COOKIE["STATE"])) {
    $obj = get_state();
  } else {
    $obj = array();
  }
  unset($obj["cart"][intval($num)]);
  set_state($obj);
echo "DELETED";
}

function cart_summary() {
  $count = 0;
  $total = 0;
  if (!empty($_COOKIE["STATE"])) {
    $obj = get_state();
    if (!empty($obj["cart"])) {
#      foreach ($obj["cart"] as $item) {
#        $total += $item["price"];
#        $count += 1;
#      }
      foreach ($obj["cart"] as $item) {
        if ($item["restricted"] && !is_admin()) {
          continue; // skip restricted items for non-admins
        }
        $total += $item["price"];
        $count += 1;
      }
    }
  }
  return array($count, $total);
}

function is_admin() {
  if (!empty($_COOKIE["STATE"])) {
    $obj = get_state();
    if (!empty($obj["admin"])) {
      return $obj['admin'];
    }
  }
  return False;
}
?>
