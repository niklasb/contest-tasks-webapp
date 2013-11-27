<?php

function parse_tags($tags) {
  return preg_split("@\s*,\s*@", trim($tags));
}
function normalize_tags($tags) {
  $tags = parse_tags($tags);
  asort($tags);
  return implode(", ", $tags);
}

function get_task($task_id) {
  global $db;
  $stmt = $db->prepare("select * from tasks where id = ?");
  $stmt->execute(array($task_id));
  return $stmt->fetch();
}

function task_users($task_id) {
  global $db;
  $stmt = $db->prepare("select * from user_tasks where task_id = ?");
  $stmt->execute(array($task_id));
  $users = array();
  foreach ($stmt->fetchAll() as $row) 
    array_push($users, $row["user_name"]);
  return $users;
}
