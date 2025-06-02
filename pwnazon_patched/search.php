<?php
include "utils.php";

make_header("Search");

function search_row($item, $i, $search, $found) {
  if ($item["restricted"] == False) {
    if (stristr($item["name"], $search) || stristr($item["desc"], $search)) {
      if (!$found) {
        echo "<table><tr><th style='width:10%'>Name</th><th style='width:7%'>Price</th><th style='width:30%'>Description</th><th style='width:7%'>Purchase</th></tr>";
      }
      $s = "<tr><td>".$item["name"]."</td><td>\$".$item["price"]."</td><td>".$item["desc"]."</td><td onclick='add_item($i)'>Add to cart!<br><img src='assets/add_to_cart.png'></td></tr>\n";
      echo preg_replace("($search)", "<b style='color:#04f'>$0</b>", $s);
      return True;
    }
  }
  return False;
}

?>

<form style="margin: 0 auto; width:20%;" action="search.php" method="POST">
  <input type="text" name="search"> <input type="submit" value="Search">
</form>
<br>
<?php
if (isset($_POST) && isset($_POST['search'])) {
  $found = False;
  foreach ($items as $key => $item) {
    $found |= search_row($item, $key, $_POST['search'], $found);
  }
  if (!$found) {
    echo "<h3>No results found</h3>";
  }
}

?>
