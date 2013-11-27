<?php

require_once("includes/preamble.php");

function have_user($name) {
  global $db;
  $stmt = $db->prepare("select * from user where name = ?");
  $stmt->execute(array($name));
  return $stmt->rowCount() != 0;
}

function register() {
  global $db;
  if (!isset($_POST["name"]) || !isset($_POST["password"]))
    return false;
  $name = $_POST["name"];
  $password = $_POST["password"];
  $error = false;
  if (empty($name)) {
    add_error("Invalid name");
    $error = true;
  }
  if (empty($password)) {
    add_error("Invalid password");
    $error = true;
  }
  if (have_user($name)) {
    add_error("User already exists"); 
    $error = true;
  }
  if ($error) return false;
  $hash = crypt($password);
  $stmt = $db->prepare("insert into users set name = ?, hash = ?");
  $stmt->execute(array($name, $hash));
  $_SESSION["name"] = $name;
  return true;
}

if (register()) {
  add_session_message("You were registered and logged in successfully.");
  redirect("");
}

html_header("Register");
?>
  
<form action="register.php" method="post">
  <p>Name: 
    <input type="text" id="name" name="name" 
           value="<?php if (isset($_POST["name"])) h($_POST["name"]); ?>" />
  </p>
  <p>Password: 
    <input type="password" id="password" name="password" 
           value="<?php if (isset($_POST["password"])) h($_POST["password"]); ?>" />
  </p>
  <p><input type="submit" name="submit" id="submit" value="Register" /></p>
</form>

<? html_footer();
