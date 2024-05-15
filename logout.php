<?php
session_start();

// Elimina tutte le variabili di sessione
$_SESSION = array();

// Elimina il cookie di sessione se presente
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Distrugge la sessione
session_destroy();

// Reindirizza l'utente alla pagina di login
header("Location: login.php");
exit;
?>
