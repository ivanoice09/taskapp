<?php
header('Content-Type: application/json');

// Ensure no output is sent before headers
if (ob_get_level()) ob_end_clean();

require_once 'config.php';

try {
    // Get input data consistently
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null && json_last_error() !== JSON_ERROR_NONE) {
        $input = [];
    }
    
    $action = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? ($input['action'] ?? $_POST['action'] ?? '')
        : ($_GET['action'] ?? '');

    switch ($action) {
        case 'getAll':
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY date, created_at");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
            break;

        case 'create':
            // Validate required fields
            if (empty($input['title']) || empty($input['date'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Title and date are required']);
                break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, date, completed, created_at) 
                                 VALUES (:title, :description, :date, :completed, NOW())");
            $stmt->execute([
                ':title' => $input['title'],
                ':description' => $input['description'] ?? '',
                ':date' => $input['date'],
                ':completed' => isset($input['completed']) ? ($input['completed'] ? 1 : 0) : 0
            ]);
            echo json_encode(['id' => $pdo->lastInsertId()] + $input);
            break;

        case 'updateStatus':
            if (empty($input['id']) || !isset($input['completed'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID and completed status are required']);
                break;
            }
            
            $stmt = $pdo->prepare("UPDATE tasks SET completed = :completed WHERE id = :id");
            $stmt->execute([
                ':id' => $input['id'],
                ':completed' => $input['completed'] ? 1 : 0
            ]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}