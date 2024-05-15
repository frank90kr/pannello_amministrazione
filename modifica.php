<?php
include_once __DIR__ . '/header.php';
include_once __DIR__ . '/class/database.php';

session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit; 
}

$user_id = $_SESSION['user_id'];


$db = new Database();
$conn = $db->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['film_id'])) {
    $film_id = $_POST['film_id'];

   
    $query = "SELECT id, titolo, genere, anno FROM film WHERE id = :film_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':film_id', $film_id);
    $stmt->execute();
    $film = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if (!$film) {
        echo "Film non trovato.";
        exit;
    }
} else {
    echo "film non esistente.";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_film'])) {
   
    $titolo = $_POST['titolo'];
    $genere = $_POST['genere'];
    $anno = $_POST['anno'];

    
    $query_update = "UPDATE film SET titolo = :titolo, genere = :genere, anno = :anno WHERE id = :film_id";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bindParam(':titolo', $titolo);
    $stmt_update->bindParam(':genere', $genere);
    $stmt_update->bindParam(':anno', $anno);
    $stmt_update->bindParam(':film_id', $film_id);

    if ($stmt_update->execute()) {
        
        header("Location: index.php");
        exit;
    } else {
        echo "Errore durante l'aggiornamento del film.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
    <div class="container">
        <h1 class="mt-3 text-white">Modifica film</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
            <div class="mb-3 mt-4">
                <label for="titolo" class="form-label text-white">Titolo</label>
                <input type="text" class="form-control" id="titolo" name="titolo" value="<?php echo $film['titolo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="genere" class="form-label text-white">Genere</label>
                <input type="text" class="form-control" id="genere" name="genere" value="<?php echo $film['genere']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="anno" class="form-label text-white">Anno</label>
                <input type="number" class="form-control" id="anno" name="anno" value="<?php echo $film['anno']; ?>" required>
            </div>
            <button type="submit" name="update_film" class="btn btn-success px-4 py-2 mt-3">Aggiorna</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
