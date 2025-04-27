<?php
require_once '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolo = $_POST['titolo'];
    $nome_cantante = $_POST['nome_cantante'];
    $durata = $_POST['durata'];
    $genere = $_POST['genere'];
    $anno_uscita = $_POST['anno_uscita'];

    // Converte durata da min:sec a formato 00:minuti:secondi
    list($minuti, $secondi) = explode(':', $durata);
    $durata_finale = sprintf('00:%02d:%02d', $minuti, $secondi);

    $query = "INSERT INTO canzoni (titolo, nome_cantante, durata, genere, anno_uscita) VALUES (:titolo, :nome_cantante, :durata, :genere, :anno_uscita)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':titolo' => $titolo,
        ':nome_cantante' => $nome_cantante,
        ':durata' => $durata_finale,
        ':genere' => $genere,
        ':anno_uscita' => $anno_uscita
    ]);

    header('Location: ../../index.php');
    exit;
}
?>