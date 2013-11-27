<?php

require_once("includes/preamble.php");
if (!isset($_POST["name"]) || !isset($_POST["password"])) 
  redirect("");

$name = $_POST["name"];
$password = $_POST["password"];
$stmt = $db->prepare("select * from users where name = ?");
$stmt->execute(array($name));
$row = $stmt->fetch();
$hash = $row["hash"];
if (crypt($password, $hash) == $hash) {
  $_SESSION["name"] = $name;
  add_session_message("You have been logged in successfully.");
} else {
  add_session_error("Invalid user name or password!");
}
redirect("");
