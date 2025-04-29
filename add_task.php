<?php
// Database connection
include("db.php");
$conn = connect_to_db();

// Get form data
$title = $_POST['title'];
$description = $_POST['description'] ?? null;
$due_date = $_POST['due_date'] ?? null;

// Insert new task
$stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $description, $due_date);
$stmt->execute();

// Redirect back to the main page   
header("Location: index.php");
exit();
?>