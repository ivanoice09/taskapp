<?php

session_start();

// Database connection
include("db.php");
$conn = connect_to_db();

// Get form data
$title = $_POST['title'];
$description = $_POST['description'] ?? null;
$due_date = $_POST['due_date'] ?? null;
$id_creator = $_SESSION['user_id'];

// Insert new task
$stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, id_creator) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $title, $description, $due_date, $id_creator);
$stmt->execute();

// Redirect back to the main page   
header("Location: create_task.php");
exit();
?>