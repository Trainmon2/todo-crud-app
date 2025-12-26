<?php
session_start();

// Initialize arrays
if (!isset($_SESSION['tasks'])) $_SESSION['tasks'] = [];
if (!isset($_SESSION['completed'])) $_SESSION['completed'] = [];

$message = "";
$username = $_SESSION['username'] ?? "";

// Add Task
if (isset($_POST['add'])) {
    $username = trim($_POST['username']);
    $task = trim($_POST['task']);

    if (empty($username)) {
        $message = "Please enter your name.";
    } elseif (empty($task)) {
        $message = "Task cannot be empty.";
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['tasks'][] = $task;
        $_SESSION['completed'][] = false;
        $message = "Task added!";
    }
}

// Mark Task Done
if (isset($_POST['done'])) {
    $i = $_POST['done'];
    $_SESSION['completed'][$i] = true;
}

// Clear All
if (isset($_POST['clear'])) {
    $_SESSION['tasks'] = [];
    $_SESSION['completed'] = [];
    $message = "All tasks cleared!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Todo App</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- NAVBAR -->
  <div class="navbar">
    <a class="active" href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="login.php">Login</a>
    <a href="#" id="themeToggle">🌙 Dark Mode</a>
  </div>

  <div class="container">
    <div class="card">

      <!-- Greeting -->
      <?php if (!empty($_SESSION['username'])): ?>
        <h2>Hello, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
      <?php endif; ?>

      <!-- Add Task Form -->
      <form method="POST">
        <input type="text" name="username" placeholder="Enter your name" />
        <input type="text" name="task" placeholder="Enter a task" />
        <button type="submit" name="add">Add Task</button>
      </form>

      <form method="POST">
        <button type="submit" name="clear">Clear All</button>
      </form>

      <!-- Feedback -->
      <div class="feedback">
        <?php if (!empty($message)): ?>
          <p style="color: <?= strpos($message, 'added') !== false ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Tasks List -->
      <?php if (!empty($_SESSION['tasks'])): ?>
        <h3>Here are Your Tasks:</h3>
        <ul>
          <?php foreach ($_SESSION['tasks'] as $index => $t): ?>
            <?php
              $isDone = $_SESSION['completed'][$index] ?? false;
              $class = $isDone ? "completed animate" : "";
              $check = $isDone ? "✅" : "";
            ?>
            <li class="<?= $class ?>">
              <?= htmlspecialchars($t) ?> <?= $check ?>
              <form method="POST" style="display:inline;">
                <button name="done" value="<?= $index ?>">Mark Done</button>
              </form>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <script>
// ✅ Fade + Glow animation for completed tasks
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("li.completed").forEach(li => {
    li.style.transition = "opacity 1.2s ease, text-shadow 1.2s ease";
    li.style.textShadow = "0 0 6px #27ae60, 0 0 12px #2ecc71";
    li.style.opacity = "0.85";
    setTimeout(() => li.style.opacity = "1", 800);
  });
});

// ✅ Dark Mode Toggle
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("themeToggle");
  const body = document.body;
  
  // Load saved preference
  if (localStorage.getItem("theme") === "dark") {
    body.classList.add("dark-mode");
    toggle.textContent = "☀️ Light Mode";
  }

  toggle.addEventListener("click", (e) => {
    e.preventDefault();
    body.classList.toggle("dark-mode");
    const darkEnabled = body.classList.contains("dark-mode");
    toggle.textContent = darkEnabled ? "☀️ Light Mode" : "🌙 Dark Mode";
    localStorage.setItem("theme", darkEnabled ? "dark" : "light");
  });
});
</script>
</body>
</html>