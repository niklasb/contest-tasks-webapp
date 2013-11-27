<?php

require_once("includes/preamble.php");

$task = get_task($_REQUEST["id"]);
$solved = $_REQUEST["solved"] == '1';
if ($solved) {
  $stmt = $db->prepare("insert into user_tasks set user_name = ?, task_id = ?");
} else {
  $stmt = $db->prepare("delete from user_tasks where user_name = ? and task_id = ?");
}
$stmt->execute(array(user(), $task["id"]));
redirect("");
