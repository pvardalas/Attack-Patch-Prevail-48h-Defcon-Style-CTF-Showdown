<?php
include "utils.php";

if (!empty($_POST)) {
  if (isset($_POST["login"])) {
    if ($_POST["login"] == "admin") {
      $flag_file = fopen("key","r") or die ("NO KEY FILE");
      $flag = fread($flag_file, filesize("key"));
      if ($_POST["password"] === trim($flag)) {
        update_cookie("admin", True);
      }
    }
  } else {
    foreach ($_POST as $key => $value) {
      call_user_func($key, $value);
    }
  }
}

echo "OK";
?>
