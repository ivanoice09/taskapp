<?php
include("db.php");
$conn = connect_to_db();

$stmt = $conn->query("SELECT * FROM tasks ORDER BY is_completed, due_date, created_at");
$tasks = $stmt;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">My Tasks</h3>
                    </div>

                    <div class="card-body">
                        <!-- Add Task Form -->
                        <form action="add_task.php" method="POST" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="title" class="form-control" placeholder="Add a new task..." required>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                            <div class="mt-2">
                                <textarea name="description" class="form-control" placeholder="Description (optional)"></textarea>
                            </div>
                            <div class="mt-2">
                                <input type="date" name="due_date" class="form-control">
                            </div>
                        </form>

                        <!-- Task List -->
                        <ul class="list-group">
                            <?php while ($task = $tasks->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center <?= $task['is_completed'] ? 'completed' : '' ?>">
                                    <div>
                                        <form action="complete_task.php" method="POST" class="d-inline">
                                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                            <input type="checkbox" name="is_completed" <?= $task['is_completed'] ? 'checked' : '' ?>
                                                onchange="this.form.submit()" class="form-check-input me-2">
                                        </form>
                                        <span class="task-title"><?= htmlspecialchars($task['title']) ?></span>
                                        <?php if ($task['description']): ?>
                                            <small class="d-block text-muted"><?= htmlspecialchars($task['description']) ?></small>
                                        <?php endif; ?>
                                        <?php if ($task['due_date']): ?>
                                            <small class="d-block <?= (strtotime($task['due_date']) < time() && !$task['is_completed']) ? 'text-danger' : 'text-muted' ?>">
                                                Due: <?= date('M j, Y', strtotime($task['due_date'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <form action="delete_task.php" method="POST" class="d-inline">
                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>