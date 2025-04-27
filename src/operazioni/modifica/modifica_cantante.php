<?php
// Includi il file di configurazione del database
require_once '../../../config/config.php';

// Inizializza variabili
$message = '';
$cantante = null;

// Verifica se è stato fornito un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $message = 'ID cantante non specificato';
} else {
    $id = intval($_GET['id']);

    // Se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recupera i dati dal form
            $nome_cognome = $_POST['nome_cognome'];
            $nome_arte = $_POST['nome_arte'];
            $nazionalita = $_POST['nazionalita'];
            $anno_nascita = intval($_POST['anno_nascita']);

            // Aggiorna il cantante nel database
            $stmt = $pdo->prepare("
                UPDATE cantanti 
                SET nome_cognome = :nome_cognome, 
                    nome_arte = :nome_arte, 
                    nazionalita = :nazionalita, 
                    anno_nascita = :anno_nascita
                WHERE id = :id
            ");

            $stmt->bindParam(':nome_cognome', $nome_cognome);
            $stmt->bindParam(':nome_arte', $nome_arte);
            $stmt->bindParam(':nazionalita', $nazionalita);
            $stmt->bindParam(':anno_nascita', $anno_nascita, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $message = 'Cantante aggiornato con successo!';
        } catch (PDOException $e) {
            $message = 'Errore durante l\'aggiornamento: ' . $e->getMessage();
        }
    }

    // Recupera i dati del cantante per la visualizzazione nel form
    try {
        $stmt = $pdo->prepare("SELECT * FROM cantanti WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cantante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cantante) {
            $message = 'Cantante non trovato!';
        }
    } catch (PDOException $e) {
        $message = 'Errore durante il recupero dei dati: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Cantante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mx-auto max-w-2xl p-4">
        <!-- Back button and header -->
        <div class="relative flex items-center justify-center mb-6">
            <a href="javascript:history.back()" class="absolute top-3 left-0">
                <img src="../../../assets/left-arrow.svg" alt="left arrow">
            </a>
            <h1 class="text-2xl font-bold">Modifica Cantante</h1>
        </div>

        <?php if (!empty($message)): ?>
            <div class="<?php echo strpos($message, 'Errore') !== false ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($cantante): ?>
            <!-- Aggiorna il form con lo stile del file index.php -->
            <div class="formContainer flex flex-col items-center justify-center mt-10 mb-20">
                <div class="formBox bg-neutral-200 py-10 px-5 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold mb-6">Modifica Cantante</h2>
                    <form method="POST" action="">
                        <input type="text" name="nome_cognome" value="<?php echo htmlspecialchars($cantante['nome_cognome']); ?>" placeholder="Nome e Cognome" required class="w-full mb-4 px-4 py-2 rounded-lg outline-none">
                        <input type="text" name="nome_arte" value="<?php echo htmlspecialchars($cantante['nome_arte']); ?>" placeholder="Nome d'Arte" required class="w-full mb-4 px-4 py-2 rounded-lg outline-none">
                        <input type="text" name="nazionalita" value="<?php echo htmlspecialchars($cantante['nazionalita']); ?>" placeholder="Nazionalità" required class="w-full mb-4 px-4 py-2 rounded-lg outline-none">
                        <input type="number" name="anno_nascita" value="<?php echo $cantante['anno_nascita']; ?>" placeholder="Anno di Nascita" required min="1900" max="<?php echo date('Y'); ?>" class="w-full mb-6 px-4 py-2 rounded-lg outline-none">
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-400 duration-300">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>