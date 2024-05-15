<?php
session_start(); // Avvia la sessione

include_once __DIR__ . '/class/database.php';

class AuthLogin {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticateUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Memorizza l'ID dell'utente nella sessione
            $_SESSION['user_id'] = $user['id'];
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
$auth = new AuthLogin($pdo);

$error_message = ""; //variabile di errore

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Autenticazione
    if ($auth->authenticateUser($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Nome utente o password errati.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex align-items-center py-4 bg-secondary">
<main class="form-signin w-25 m-auto">
    <form method="POST">
        <h1 class="h3 mb-3 fw-normal text-white">Login</h1>
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
            <label class="form-check-label text-white" for="remember-me">
                Remember me
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    </form>
</main>
</body>
</html>
