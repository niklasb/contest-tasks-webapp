<?php

require_once("includes/preamble.php");
$task_id = $_REQUEST["id"];
$task = get_task($task_id);
$users = implode(", ", task_users($task_id));
$tags = implode(", ", parse_tags($task["tags"]));

html_header("Task '" . $task["name"] . "'");
?>
<table>
  <tr>
    <td>URL:</td>
    <td><a href="<?php h($task["url"]); ?>"><?php h($task["url"]); ?></a></td>
  </tr>
  <tr>
    <td>Tags:</td>
    <td><?php h($tags); ?></td>
  </tr>
  <tr>
    <td>Solved by:</td>
    <td><?php h($users); ?></td>
  </tr>
</table>

<?php if (user() === $task["owner"]): ?>
<p>
  <a href="edit_task.php?id=<?php h($task["id"]) ?>">Edit task</a>
  <a href="delete_task.php?id=<?php h($task["id"]) ?>">Delete task</a>
</p>
<?php endif; ?>

<?php show_disqus("task".$task_id); ?>
<?php html_footer(); 
