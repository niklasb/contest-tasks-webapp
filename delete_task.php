<?php

require_once("includes/preamble.php");

$task_id = intval($_REQUEST["id"]);
$task = get_task($task_id);
if (user() != $task["owner"]) {
  add_session_error("This is not your task!");
  redirect("");
  return;
}
$stmt = $db->prepare("delete from tasks where id = ?");
$stmt->execute(array($task_id));
$stmt = $db->prepare("delete from user_tasks where task_id = ?");
$stmt->execute(array($task_id));
add_session_message("Task successfully deleted!");
redirect("");
