<?php
// Disabilita l'output diretto
header('Content-Type: application/json');

// Array per la risposta
$response = array();

try {
    // Verifica che la richiesta sia POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metodo di richiesta non valido');
    }
    
    include("db.php");
    $conn = connect_to_db();
    
    // Recupero dei dati dal form
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $cognome = isset($_POST['cognome']) ? trim($_POST['cognome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validazione
    if (empty($nome) || empty($cognome) || empty($email) || empty($password)) {
        throw new Exception('Tutti i campi sono obbligatori');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Formato email non valido');
    }
    
    if (strlen($password) < 8) {
        throw new Exception('La password deve contenere almeno 8 caratteri');
    }
    
    // Verifica se l'email esiste già
    $check_sql = "SELECT id FROM utenti WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt === false) {
        throw new Exception("Errore nella preparazione della query: " . $conn->error);
    }
    
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        throw new Exception('Questa email è già registrata');
    }
    $check_stmt->close();
    
    // Hashing della password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Preparazione della query di inserimento
    $sql = "INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)";
    
    // Preparazione dello statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Errore nella preparazione dello statement: " . $conn->error);
    }
    
    // Binding dei parametri e esecuzione
    $stmt->bind_param("ssss", $nome, $cognome, $email, $password_hash);
    
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Registrazione completata con successo! Sarai reindirizzato alla pagina di login.';
        $response['redirect'] = 'login.html';
    } else {
        throw new Exception("Errore durante l'inserimento: " . $stmt->error);
    }
    
    // Chiusura dello statement e della connessione
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Restituisce la risposta come JSON
echo json_encode($response);
exit;
?>