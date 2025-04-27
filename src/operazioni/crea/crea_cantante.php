<?php
require_once '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cognome = $_POST['nome'];
    $nome_arte = $_POST['nome_arte'];
    $nazionalita = $_POST['nazionalita'];
    $anno_nascita = $_POST['anno_nascita'];

    $query = "INSERT INTO cantanti (nome_cognome, nome_arte, nazionalita, anno_nascita) VALUES (:nome_cognome, :nome_arte, :nazionalita, :anno_nascita)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nome_cognome' => $nome_cognome,
        ':nome_arte' => $nome_arte,
        ':nazionalita' => $nazionalita,
        ':anno_nascita' => $anno_nascita
    ]);

    header('Location: ../../index.php');
    exit;
}
?>