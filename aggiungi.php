<?php
include_once __DIR__ . '/header.php';
include_once __DIR__ . '/class/database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titolo = $_POST['titolo'];
    $genere = $_POST['genere'];
    $anno = $_POST['anno'];

   

    
    $db = new Database();
    $conn = $db->getConnection();

   
    $query = "INSERT INTO film (titolo, genere, anno) VALUES (:titolo, :genere, :anno)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':titolo', $titolo);
    $stmt->bindParam(':genere', $genere);
    $stmt->bindParam(':anno', $anno);

    if ($stmt->execute()) {
        //reindirizza alla pagina principale
        header("Location: index.php");
        exit;
    } else {
        // Errore durante l'inserimento nel database
        $errorInfo = $stmt->errorInfo();
        echo "Errore: " . $errorInfo[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi nuovo film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
    <div class="container">
        <h1 class="mt-3 text-white">Aggiungi nuovo film</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3 mt-4">
                <label for="titolo" class="form-label text-white">Titolo</label>
                <input type="text" class="form-control" id="titolo" name="titolo" required>
            </div>
            <div class="mb-3">
                <label for="genere" class="form-label text-white">Genere</label>
                <input type="text" class="form-control" id="genere" name="genere" required>
            </div>
            <div class="mb-3">
                <label for="anno" class="form-label text-white">Anno</label>
                <input type="number" class="form-control" id="anno" name="anno" required>
            </div>
            <button type="submit" class="btn btn-success px-4 py-2 mt-3">Aggiungi</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
