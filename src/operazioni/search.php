<?php
// Includi il file di configurazione del database
require_once '../../config/config.php';

// Recupera la query di ricerca dall'URL
$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

// Array per memorizzare i risultati
$songs = [];
$artists = [];

// Se c'è una query di ricerca, esegui le query al database
if ($query) {
    try {
        // Cerca nelle canzoni
        $stmt = $pdo->prepare("
            SELECT * FROM canzoni 
            WHERE titolo LIKE :query 
            OR nome_cantante LIKE :query 
            OR genere LIKE :query
        ");
        $searchTerm = "%$query%";
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatta la durata per ogni canzone
        foreach ($songs as &$song) {
            $tempo = $song['durata'];
            $parti = explode(':', $tempo);
            $song['durata_formattata'] = $parti[1] . ':' . $parti[2];
        }
        unset($song); // buona pratica
        
        // Cerca nei cantanti
        $stmt = $pdo->prepare("
            SELECT * FROM cantanti 
            WHERE nome_cognome LIKE :query 
            OR nome_arte LIKE :query 
            OR nazionalita LIKE :query
        ");
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gestione degli errori di database
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>
                Errore nella ricerca: " . $e->getMessage() . "
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati di Ricerca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto max-w-5xl p-4">
        <!-- Back button and header -->
        <div class="relative flex items-center justify-center mb-6">
            <a href="../index.php" class="absolute top-3 left-0">
                <img src="../../assets/left-arrow.svg" alt="left arrow">
            </a>
            <h1 class="text-4xl font-bold">Risultati di Ricerca</h1>
        </div>
        
        <!-- Search query display -->
        <p class="text-center text-gray-500 mb-8">"<?php echo $query; ?>"</p>
        
        <!-- Two-column layout -->
        <div class="grid grid-cols-2 gap-40">
            <!-- Songs column -->
            <div>
                <h2 class="text-3xl font-bold mb-8">Canzoni</h2>
                <?php if (empty($songs)): ?>
                    <p class="text-gray-500">Nessun Risultato.</p>
                <?php else: ?>
                    <?php foreach ($songs as $song): ?>
                        <div class="bg-neutral-300 rounded-xl p-4 shadow-md mb-5">
                            <div class="flex justify-between">
                                <div class="flex flex-col gap-0.5">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($song['titolo']); ?></h3>
                                    <p class="text-sm"><strong>Artista:</strong> <?php echo htmlspecialchars($song['nome_cantante']); ?></p>
                                    <p class="text-sm"><strong>Data di uscita:</strong> <?php echo htmlspecialchars($song['anno_uscita']); ?></p>
                                    <p class="text-sm"><strong>Genere:</strong> <?php echo htmlspecialchars($song['genere']); ?></p>
                                    <p class="text-sm"><strong>Durata:</strong> <?php echo htmlspecialchars($song['durata_formattata']); ?></p>
                                </div>
                                <div class="flex flex-col justify-end text-center gap-2">
                                    <a
                                        href="modifica/modifica_canzone.php?id=<?php echo $song['id']; ?>"
                                        class="text-sm font-medium bg-white py-1 px-2 rounded-xl hover:bg-blue-500 hover:text-white duration-300"
                                    >
                                        Modifica
                                    </a>
                                    <a
                                        href="elimina/elimina_canzone.php?id=<?php echo $song['id']; ?>"
                                        class="text-sm font-medium bg-white py-1 px-2 rounded-xl hover:bg-red-500 hover:text-white duration-300"
                                        onclick="return confirm('Sei sicuro di voler eliminare questa canzone?');"
                                    >
                                        Elimina
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Artists column -->
            <div >
                <h2 class="text-3xl font-bold mb-8">Cantanti</h2>
                <?php if (empty($artists)): ?>
                    <p class="text-gray-500">Nessun Risultato.</p>
                <?php else: ?>
                    <?php foreach ($artists as $artist): ?>
                        <div class="bg-neutral-300 rounded-lg p-4 shadow-md mb-5">
                            <div class="flex justify-between">
                                <div class="flex flex-col gap-0.5">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($artist['nome_arte']); ?></h3>
                                    <p class="text-sm"><strong>Nome:</strong> <?php echo htmlspecialchars($artist['nome_cognome']); ?></p>
                                    <p class="text-sm"><strong>Nazionalità:</strong> <?php echo htmlspecialchars($artist['nazionalita']); ?></p>
                                    <p class="text-sm"><strong>Anno di nascita:</strong> <?php echo htmlspecialchars($artist['anno_nascita']); ?></p>
                                </div>
                                <div class="flex flex-col justify-end text-center gap-2">
                                    <a
                                        href="modifica/modifica_cantante.php?id=<?php echo $artist['id']; ?>"
                                        class="text-sm font-medium bg-white py-1 px-2 rounded-xl hover:bg-blue-500 hover:text-white duration-300"
                                    >
                                        Modifica
                                    </a>
                                    <a
                                        href="elimina/elimina_cantante.php?id=<?php echo $artist['id']; ?>"
                                        class="text-sm font-medium bg-white py-1 px-2 rounded-xl hover:bg-red-500 hover:text-white duration-300"
                                        onclick="return confirm('Sei sicuro di voler eliminare questo cantante?');"
                                    >
                                        Elimina
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>