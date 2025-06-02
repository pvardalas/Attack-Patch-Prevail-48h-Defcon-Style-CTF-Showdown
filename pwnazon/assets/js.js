function add_item(val) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4) {
      location.reload();
    }
  }
  xhttp.open("POST", "actions.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("add_item="+val);
}

function remove_item(val) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4) {
      location.reload();
    }
  }
  xhttp.open("POST", "actions.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("remove_item="+val);
}

function login() {
  var user = document.forms[0].elements["user"].value;
  var pass = document.forms[0].elements["password"].value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4) {
      location.reload();
    }
  }
  xhttp.open("POST", "actions.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("login="+user+ "&password="+pass);
}
