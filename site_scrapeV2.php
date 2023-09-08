<?php
require '../../vendor/autoload.php'; // Assicurati che Goutte e Symfony DomCrawler siano installati via Composer

use Goutte\Client;

// Inizializza il client Goutte
$client = new Client();

// Variabile per tenere traccia del conteggio delle pagine
$pageCount = 0;

// Funzione ricorsiva per esplorare l'alberatura delle pagine con limite di profondità
function explorePageTreeWithDepthLimit($crawler, $depth = 0)
{
    global $depthLimit, $pageCount;

    if ($depth > $depthLimit) {
        return;
    }

    foreach ($crawler->filter('a') as $link) {
        $indent = str_repeat('  ', $depth);
        echo $indent . '-> ' . $link->textContent . "<br>";
        $nextUrl = $link->getAttribute('href');

        // Ignora i link vuoti o link a se stessi (evita loop infiniti)
        if (!empty($nextUrl) && $nextUrl != '#') {
            global $client;
            $nextCrawler = $client->request('GET', $nextUrl);
            explorePageTreeWithDepthLimit($nextCrawler, $depth + 1);
            $pageCount++;
        }
    }
}

// Inizializza l'URL con un valore predefinito (o un valore vuoto)
$url = isset($_POST['url']) ? $_POST['url'] : '';

// Gestione del modulo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depthLimit = isset($_POST['depth']) ? intval($_POST['depth']) : 3;

    // Validazione URL
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Visita la pagina iniziale
        $crawler = $client->request('GET', $url);

        // Avvia l'esplorazione dell'alberatura delle pagine con il limite di profondità
        echo "Alberatura delle pagine con limite di profondità {$depthLimit}:" . "<br>";
        explorePageTreeWithDepthLimit($crawler);

        // Mostra il conteggio delle pagine
        echo "<br>Totale pagine esplorate: {$pageCount}";
    } else {
        echo "L'URL inserito non è valido.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Web Scraper</title>
</head>
<body>
    <h1>Web Scraper</h1>
    <form method="post">
        <label for="url">URL del sito: </label>
        <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($url); ?>https://" required>
        <br>
        <label for="depth">Limite di profondità:</label>
        <input type="number" name="depth" id="depth" value="0" min="0" required>
        <br>
        <input type="submit" value="Esegui lo scraping">
    </form>
</body>
</html>
