<?php

include_once __DIR__ . '/class/database.php';

class Auth {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticateUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }
}

//istanza del Database
$database = new Database();
$pdo = $database->getConnection();

//istanza di Auth
$auth = new Auth($pdo);

$error_message = ""; //variabile di errore

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se l'utente esiste già nel database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $existing_user = $stmt->fetch();

    if (!$existing_user) {
        // L'utente non esiste ancora, procedi con l'inserimento
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);
        
        // Reindirizzamento
        header("Location: login.php");
        exit;
    } else {
  
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-25 m-auto">
    <form method="POST">
        <h1 class="h3 mb-3 fw-normal">Registrati</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <div class="form-floating">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
            <label for="username">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
        </div>
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="remember-me">
            <label class="form-check-label" for="remember-me">
                Remember me
            </label>
        </div>
        
        <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    </form>
    <p class="mt-3 mb-0">Hai già un account? <a href="login.php">Accedi qui.</a></p>
</main>
</body>
</html>
