<?php
// Database connection
include("db.php");
$conn = connect_to_db();

// Get task ID
$task_id = $_POST['task_id'];

// Delete task
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();

// Redirect back to the main page
header("Location: index.php");
exit();
?>