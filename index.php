<?php
include_once __DIR__ . '/header.php';
include_once __DIR__ . '/class/database.php';

session_start();

// Controllo
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit; 
}

$user_id = $_SESSION['user_id'];

// Connessione al database
$db = new Database();
$conn = $db->getConnection();

// Query per recuperare i dati dei film
$query = "SELECT id, titolo, genere, anno FROM film";
$result = $conn->query($query);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista dei film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-2">Lista dei film</h1>
        <ul class="list-group">
            <?php foreach ($result as $row): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $row['titolo']; ?> - <?php echo $row['genere']; ?> (<?php echo $row['anno']; ?>)
                    <div class="btn-group" role="group" aria-label="Modifica o elimina">
                        <form action="modifica.php" method="POST">
                            <input type="hidden" name="film_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm me-2">Modifica</button>
                        </form>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="delete_film_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
