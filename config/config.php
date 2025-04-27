<?php
// Configurazione del database
$host = 'localhost';
$dbname = 'MONTANEMAURO';
$username = 'root';
$password = '';

try {
    // Connessione al database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}
?>