<?php
// Includi il file di configurazione del database
require_once '../../../config/config.php';

// Inizializza variabili
$message = '';
$canzone = null;
$cantanti = [];

// Verifica se è stato fornito un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $message = 'ID canzone non specificato';
} else {
    $id = intval($_GET['id']);

    // Se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Recupera i dati dal form
            $titolo = $_POST['titolo'];
            $nome_cantante = $_POST['nome_cantante'];
            $durata_input = $_POST['durata']; // Formato MM:SS dall'input
            $genere = $_POST['genere'];
            $anno_uscita = intval($_POST['anno_uscita']);
            $id_cantante = intval($_POST['id_cantante']);
            
            // Converti la durata da MM:SS a TIME (HH:MM:SS)
            list($minuti, $secondi) = explode(':', $durata_input);
            $durata = sprintf('00:%02d:%02d', $minuti, $secondi);

            // Aggiorna la canzone nel database
            $stmt = $pdo->prepare("
                UPDATE canzoni 
                SET titolo = :titolo, 
                    nome_cantante = :nome_cantante, 
                    durata = :durata, 
                    genere = :genere, 
                    anno_uscita = :anno_uscita, 
                    id_cantante = :id_cantante 
                WHERE id = :id
            ");

            $stmt->bindParam(':titolo', $titolo);
            $stmt->bindParam(':nome_cantante', $nome_cantante);
            $stmt->bindParam(':durata', $durata);
            $stmt->bindParam(':genere', $genere);
            $stmt->bindParam(':anno_uscita', $anno_uscita, PDO::PARAM_INT);
            $stmt->bindParam(':id_cantante', $id_cantante, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $message = 'Canzone aggiornata con successo!';
        } catch (PDOException $e) {
            $message = 'Errore durante l\'aggiornamento: ' . $e->getMessage();
        }
    }

    // Recupera i dati della canzone per la visualizzazione nel form
    try {
        $stmt = $pdo->prepare("SELECT * FROM canzoni WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $canzone = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$canzone) {
            $message = 'Canzone non trovata!';
        } else {
            // Converti la durata dal formato TIME a MM:SS per il form
            $time_parts = explode(':', $canzone['durata']);
            $canzone['durata_display'] = $time_parts[1] . ':' . $time_parts[2];
        }

        // Recupera la lista dei cantanti per il menu a tendina
        $stmt = $pdo->query("SELECT id, nome_arte, nome_cognome FROM cantanti ORDER BY nome_arte");
        $cantanti = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Modifica Canzone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mx-auto max-w-2xl p-4">
        <!-- Back button and header -->
        <div class="relative flex items-center justify-center mb-6">
            <a href="javascript:history.back()" class="absolute top-3 left-0">
                <img src="../../../assets/left-arrow.svg" alt="left arrow">
            </a>
            <h1 class="text-2xl font-bold">Modifica Canzone</h1>
        </div>

        <?php if (!empty($message)): ?>
            <div class="<?php echo strpos($message, 'Errore') !== false ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($canzone): ?>
            <!-- Aggiorna il form con lo stile del file index.php -->
            <div class="formContainer flex flex-col items-center justify-center mt-10 mb-20">
                <div class="formBox bg-neutral-200 py-10 px-5 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold mb-6">Modifica Canzone</h2>
                    <form method="POST" action="">
                        <input type="text" name="titolo" value="<?php echo htmlspecialchars($canzone['titolo']); ?>" placeholder="Titolo" required class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                        <input type="text" name="nome_cantante" value="<?php echo htmlspecialchars($canzone['nome_cantante']); ?>" placeholder="Nome Cantante" required class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                        <input type="text" name="durata" value="<?php echo htmlspecialchars($canzone['durata_display']); ?>" placeholder="Durata (MM:SS)" pattern="^([0-9]{1,2}):([0-5][0-9])$" title="Inserisci la durata nel formato MM:SS (es. 3:45)" class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                        <input type="text" name="genere" value="<?php echo htmlspecialchars($canzone['genere']); ?>" placeholder="Genere" required class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                        <input type="number" name="anno_uscita" value="<?php echo $canzone['anno_uscita']; ?>" placeholder="Anno di Uscita" required min="1900" max="<?php echo date('Y'); ?>" class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                        <select name="id_cantante" class="w-full mb-6 px-4 py-2 rounded-lg border border-gray-300 outline-none">
                            <option value="0">Seleziona un cantante</option>
                            <?php foreach ($cantanti as $cantante): ?>
                                <option value="<?php echo $cantante['id']; ?>" <?php echo ($canzone['id_cantante'] == $cantante['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cantante['nome_arte'] . ' (' . $cantante['nome_cognome'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-400 duration-300">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>