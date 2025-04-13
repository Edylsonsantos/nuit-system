<?php
require_once 'Database.php';

class Nuit {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function pesquisarNuit($nuit, $numeroDocumento) {
        $query = "SELECT * FROM registro_nuit WHERE nuit = :nuit AND documento_numero = :numero_documento";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nuit', $nuit);
        $stmt->bindParam(':numero_documento', $numeroDocumento);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
