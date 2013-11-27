<?php

require_once("includes/preamble.php");

$users = array();
$user_sql = 
  "select u.name as name, ".
  "  (select count(*) from user_tasks where user_name = u.name) as cnt ".
  "from users u ".
  "order by cnt desc";
foreach($db->query($user_sql) as $user)
  array_push($users, $user);

$tasks = array();
foreach($db->query("select * from tasks") as $task) {
  $task["users"] = task_users($task["id"]);
  array_push($tasks, $task);
}

html_header("Tasks");
?>

<table class="tasks">
  <thead>
    <tr>
      <td>Task</td>
      <?php foreach($users as $user): ?>
        <td>
          <?php if ($user["name"] == user()) echo "<b>" ?>
          <?php h($user["name"]) ?> (<?php h($user["cnt"]) ?>)
          <?php if ($user["name"] == user()) echo "</b>" ?>
        </td>
      <?php endforeach; ?>
      <?php if (user()): ?>
        <td>Actions</td>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($tasks as $task): ?>
    <tr>
      <td>
        <a href="task.php?id=<?php h($task["id"]) ?>"><?php h($task["name"]) ?></a>
        (<a href="<?php h($task["url"]) ?>">statement</a>)
      </td>
      <?php foreach($users as $user): ?>
        <?php $solved = in_array($user["name"], $task["users"]) ?>
        <td class="<?php h($solved ? "solved" : "unsolved") ?>">&nbsp;</td>
      <?php endforeach; ?>
      <?php if (user()): ?>
        <td>
          <?php if (in_array(user(), $task["users"])): ?>
            <a href="solved_task.php?id=<?php h($task["id"]) ?>&solved=0">Didn't solve it</a>
          <?php else: ?>
            <a href="solved_task.php?id=<?php h($task["id"]) ?>&solved=1">Solved it!</a>
          <?php endif; ?>
          <?php if (user() === $task["owner"]): ?>
            <a href="edit_task.php?id=<?php h($task["id"]) ?>">Edit task</a>
            <a href="delete_task.php?id=<?php h($task["id"]) ?>">Delete task</a>
          <?php endif; ?>
        </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p>
<?php if (user()): ?>
  <p><a href="edit_task.php">Add a task</a></p>
  <a href="logout.php">Logout</a>
<?php else: ?>
  <a href="register.php">Register</a>
  <form action="login.php" method="post">
    <input type="text" name="name" id="name" placeholder="Name" />
    <input type="password" name="password" id="password" />
    <input type="submit" name="submit" id="submit" value="Login" />
  </form>
<?php endif; ?>
</p>

<?php show_disqus("index"); ?>
<?php html_footer(); 
