<?php
// Avvia la sessione per gestire il login
session_start();

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
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validazione
    if (empty($email) || empty($password)) {
        throw new Exception('Email e password sono obbligatori');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Formato email non valido');
    }

    // Cerca l'utente nel database
    $sql = "SELECT id, nome, cognome, email, password FROM utenti WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Errore nella preparazione della query: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Email o password non validi');
    }

    $user = $result->fetch_assoc();

    // Verifica la password
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Email o password non validi');
    }

    // Login riuscito - crea la sessione
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['logged_in'] = true;

    // Prepara la risposta
    $response['status'] = 'success';
    $response['message'] = 'Login effettuato con successo! Benvenuto, ' . $user['nome'] . '!';
    $response['redirect'] = 'index.php'; // Pagina a cui reindirizzare dopo il login

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
