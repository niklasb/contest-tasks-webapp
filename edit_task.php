<?php

require_once("includes/preamble.php");

$edit = isset($_REQUEST["id"]);
if ($edit) {
  $task_id = intval($_REQUEST["id"]);
  $task = get_task($task_id);
}
if ($edit && user() != $task["owner"]) {
  add_session_error("This is not your task!");
  redirect("");
}
if (!$edit && !user()) {
  add_session_error("Not logged in!");
  redirect("");
}

function save_task() {
  global $db;
  global $edit;
  global $task_id;
  if (!isset($_POST["name"]) || !isset($_POST["url"]) || !isset($_POST["tags"]))
    return false;
  $name = $_POST["name"];
  $url = $_POST["url"];
  $tags = normalize_tags($_POST["tags"]);
  $owner = user();
  $error = false;
  if (empty($name)) {
    add_error("Invalid name");
    $error = true;
  }
  if (!validate_http_url($url)) {
    add_error("Invalid URL (nice try)");
    $error = true;
  }
  if ($error) return false;
  if ($edit) {
    $stmt = $db->prepare(
       "update tasks ".
       "set name = :name, url = :url, tags = :tags, owner = :owner ".
       "where id = :id");
  } else {
    $stmt = $db->prepare("insert into tasks set name = :name, url = :url, tags = :tags, owner = :owner");
  }
  if ($edit) $stmt->bindParam(":id", $task_id);
  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":url", $url);
  $stmt->bindParam(":tags", $tags);
  $stmt->bindParam(":owner", $owner);
  $stmt->execute();
  return true;
}

if (save_task()) {
  add_session_message("Task saved successfully.");
  redirect("");
}

$preset_name = "";
$preset_url = "";
$preset_tags = "";
if ($edit) {
  $task = get_task($task_id);
  $preset_name = $task["name"];
  $preset_url = $task["url"];
  $preset_tags = $task["tags"];
}
html_header($edit ? "Edit Task" : "Add Task");
?>
  
<form action="edit_task.php<?php if ($edit) h("?id=" . $task_id); ?>"
      method="post">
  <p>Name: 
    <input type="text" id="name" name="name" 
           value="<?php h($preset_name); ?>" />
  </p>
  <p>URL: 
    <input type="text" id="url" name="url" 
           value="<?php h($preset_url); ?>" />
  </p>
  <p>Tags (comma-separated): 
    <input type="tags" id="tags" name="tags" 
           value="<?php h($preset_tags); ?>" />
  </p>
  <p><input type="submit" name="submit" id="submit" value="Save" /></p>
</form>

<?  html_footer();
