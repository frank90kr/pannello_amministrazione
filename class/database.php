<?php
class Database {
    private $host = 'localhost';
    private $db   = 'pannello_amministrazione';
    private $user = 'root';
    private $pass = '';

    public function getConnection() {
        $dsn = "mysql:host={$this->host};dbname={$this->db}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, $this->user, $this->pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
?>
