<?php

session_start();
error_reporting(E_ALL);
require_once("includes/util.php");
require_once("includes/db.php");
require_once("includes/model.php");
require_once("includes/disqus.php");

$messages = array();
function add_message($msg, $error=false) {
  global $messages;
  array_push($messages, array("msg" => $msg, "error" => $error));
}
function add_error($msg) {
  add_message($msg, true);
}
function add_session_message($msg, $error=false) {
  $msg = array("msg" => $msg, "error" => $error);
  if (isset($_SESSION["messages"])) {
    array_push($_SESSION["messages"], $msg);
  } else {
    $_SESSION["messages"] = array($msg);
  }
}
function add_session_error($msg) {
  add_session_message($msg, true);
}

if (isset($_SESSION["messages"])) {
  foreach($_SESSION["messages"] as $msg) {
    add_message($msg["msg"], $msg["error"]);
  }
}
$_SESSION["messages"] = array();

function user() {
  if (isset($_SESSION["name"])) return $_SESSION["name"];
  return null;
}

function html_footer() {
  echo "</body></html>";
}
function html_header($title) {
  global $messages;
?>
<html>
  <head>
    <title>Contest Tasks - <?php h($title) ?></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <?php if (!empty($messages)): ?>
      <ul class="messages">
        <?php foreach($messages as $msg): ?>
          <li class="<?php h($msg["error"] ? "error" : "info") ?>"><?php h($msg["msg"]); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <h1><?php h($title); ?></h1>
<?php }
