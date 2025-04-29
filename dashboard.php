<?php
// Avvia la sessione
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Reindirizza alla pagina di login se l'utente non è loggato
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .welcome-title {
            margin-bottom: 30px;
            color: #0d6efd;
        }
        /* Stile per il modal personalizzato */
        .custom-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1040;
        }
        .custom-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            display: none;
            z-index: 1050;
        }
        .custom-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .custom-modal-title {
            margin: 0;
            font-size: 1.25rem;
        }
        .custom-modal-body {
            margin-bottom: 20px;
        }
        .custom-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .modal-icon {
            color: #dc3545;
            margin-right: 10px;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="welcome-title">Benvenuto, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <button id="logoutBtn" class="btn btn-outline-danger">Logout</button>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">I tuoi dati</h5>
                    <p class="card-text">Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                    <p class="card-text">ID Utente: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                </div>
            </div>
            
            <div class="mt-4">
                <h4>Cosa puoi fare</h4>
                <ul class="list-group">
                    <li class="list-group-item">Visualizzare le tue informazioni</li>
                    <li class="list-group-item">Modificare il tuo profilo</li>
                    <li class="list-group-item">Gestire le tue impostazioni</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal di conferma per il logout -->
    <div class="custom-modal-backdrop" id="logoutModalBackdrop"></div>
    <div class="custom-modal" id="logoutModal">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="fas fa-sign-out-alt modal-icon"></i>
                Conferma Logout
            </h5>
        </div>
        <div class="custom-modal-body">
            <p>Sei sicuro di voler uscire? La tua sessione verrà terminata.</p>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelLogout">Annulla</button>
            <button type="button" class="btn btn-danger" id="confirmLogout">Conferma Logout</button>
        </div>
    </div>

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script per gestire il logout con conferma -->
    <script>
    // Elementi del DOM
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const logoutModalBackdrop = document.getElementById('logoutModalBackdrop');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');
    
    // Funzione per mostrare il modal
    const showModal = () => {
        logoutModal.style.display = 'block';
        logoutModalBackdrop.style.display = 'block';
        
        // Aggiungi una piccola animazione
        logoutModal.style.opacity = '0';
        setTimeout(() => {
            logoutModal.style.transition = 'opacity 0.3s ease';
            logoutModal.style.opacity = '1';
        }, 10);
    };
    
    // Funzione per nascondere il modal
    const hideModal = () => {
        logoutModal.style.opacity = '0';
        setTimeout(() => {
            logoutModal.style.display = 'none';
            logoutModalBackdrop.style.display = 'none';
        }, 300);
    };
    
    // Evento click sul pulsante logout
    logoutBtn.addEventListener('click', showModal);
    
    // Evento click sul pulsante annulla
    cancelLogout.addEventListener('click', hideModal);
    
    // Evento click sul backdrop per chiudere il modal
    logoutModalBackdrop.addEventListener('click', hideModal);
    
    // Evento click sul pulsante conferma
    confirmLogout.addEventListener('click', () => {
        // Reindirizza alla pagina di logout
        window.location.href = 'logout.php';
    });
    </script>
</body>
</html>