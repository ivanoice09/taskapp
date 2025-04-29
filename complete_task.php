<?php
// Database connection
include("db.php");
$conn = connect_to_db();

// Get task ID and completion status
$task_id = $_POST['task_id'];
$is_completed = isset($_POST['is_completed']) ? 1 : 0;

// Update task completion status
$stmt = $conn->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
$stmt->bind_param("ii", $is_completed, $task_id);
$stmt->execute();

// Redirect back to the main page
header("Location: index.php");
exit();
?>