<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jukebox</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header class="w-full pt-8 px-2">
        <nav class="relative flex justify-center">
            <span class="absolute top-1.5 left-8 text-3xl font-bold">JukeBox</span>

            <form action="operazioni/search.php" method="GET">
               <div class="flex w-80 h-12 items-center justify-between px-4 rounded-full bg-neutral-900 text-white border-2 hover:shadow-[0_0_0_3px_black] duration-300">
                  <input
                     type="text"
                     name="query"
                     value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                     placeholder="Ricerca Canzoni o Cantanti"
                     class="w-60 outline-none"
                  >

                  <button type="submit" class="cursor-pointer hover:scale-110 active:scale-98 duration-300">
                     <img src="../assets/search.svg" alt="search icon">
                  </button>
               </div>
            </form>
        </nav>
    </header>

    <main class="w-full mt-40">
        <section class="w-full flex flex-col items-center text-center">
            <h1 class="w-230 text-5xl font-bold mb-15">
                Gestisci facilmente le tue canzoni e i tuoi cantanti preferiti
            </h1>
            <p class="w-200 text-xl text-neutral-700">
                Aggiungi, aggiorna ed elimina informazioni su canzoni e cantanti in pochi click. Compila il form
                scegliendo tra “Canzone” o “Cantante”.
                Usa la barra di ricerca per trovare subito quello che ti serve. I risultati verranno mostrati in una
                nuova pagina!
            </p>
        </section>

        <section class="flex flex-col bg-neutral-300 py-10 px-5 mt-40">
            <h1 class="text-4xl font-semibold">Inserisci</h1>

            <div class="formContainer flex items-start justify-around mt-10 mb-20">
                <div class="formBox">
                    <h2>Canzone</h2>
                    <form action="operazioni/crea/crea_canzone.php" method="POST">
                        <input type="text" name="titolo" placeholder="Titolo" required>
                        <input type="text" name="nome_cantante" placeholder="Cantante" required>
                        <input type="text" name="durata" placeholder="Durata (min:sec)" required>
                        <input type="text" name="genere" placeholder="Genere" required>
                        <input type="number" name="anno_uscita" placeholder="Anno di uscita" required min="1900">
                        <button type="submit" class="font-medium">Salva</button>
                    </form>
                </div>

                <div class="formBox">
                    <h2>Cantante</h2>
                    <form action="operazioni/crea/crea_cantante.php" method="POST">
                        <input type="text" name="nome" placeholder="Nome e cognome" required>
                        <input type="text" name="nome_arte" placeholder="Nome d'arte" required>
                        <input type="text" name="nazionalita" placeholder="Nazionalità" required>
                        <input type="number" name="anno_nascita" placeholder="Anno di nascita" required min="1900">
                        <button type="submit" class="font-medium">Salva</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="w-full flex flex-col items-center text-center my-40">
            <h1 class="w-260 text-5xl font-bold mb-20">
                Usa la barra di ricerca per trovare e modificare canzoni o cantanti
            </h1>

            <form action="operazioni/search.php" method="GET">
               <div class="flex w-150 h-15 items-center justify-between px-6 rounded-full bg-neutral-900 text-white border-3 hover:shadow-[0_0_0_4px_black] duration-300">
                  <input
                     type="text"
                     name="search"
                     value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                     placeholder="Ricerca Canzoni o Cantanti"
                     class="w-60 text-xl outline-none"
                  >

                  <button type="submit" class="cursor-pointer hover:scale-110 active:scale-98 duration-300">
                     <img src="../assets/search.svg" alt="search icon" class="w-8 h-8">
                  </button>
               </div>
            </form>
        </section>
    </main>

</body>

</html>