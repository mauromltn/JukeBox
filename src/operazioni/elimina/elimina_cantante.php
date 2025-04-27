<?php
// Includi il file di configurazione del database
require_once '../../../config/config.php';

// Verifica se è stato fornito un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID cantante non specificato.");
}

$id = intval($_GET['id']);

try {
    // Prepara ed esegui la query per eliminare il cantante
    $stmt = $pdo->prepare("DELETE FROM cantanti WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Reindirizza alla pagina principale con un messaggio di successo
    header("Location: ../search.php?message=Cantante eliminato con successo");
    exit();
} catch (PDOException $e) {
    // Gestione degli errori
    die("Errore durante l'eliminazione del cantante: " . $e->getMessage());
}
?>