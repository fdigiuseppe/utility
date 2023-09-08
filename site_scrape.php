<!DOCTYPE html>
<html>
<head>
    <title>Scannerizza Dominio</title>
</head>
<body>
    <h1>Scannerizza Dominio</h1>

    <form method="post" action="">
        <label for="domain">Inserisci un dominio https://:</label>
        <input type="text" name="domain" id="domain" required>
        <input type="submit" name="submit" value="Scannerizza">
    </form>

    <?php
      $counter = 0;

      function getLinks($url, $siteDomain) {
        $html = file_get_contents($url);
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        $linkList = [];

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            // Rimuove spazi vuoti e controlla che l'URL non sia vuoto o contenga solo #
            $href = trim($href);
            if (!empty($href) && $href !== '#') {
              if (strpos($href, "javascript:void(0)") === false) {
                  if (filter_var($href, FILTER_VALIDATE_URL)) {
                      // Verifica se l'URL appartiene al dominio del sito
                      $parsedUrl = parse_url($href);
                      if ($parsedUrl['host'] === $siteDomain) {
                          $linkList[] = $href;
                      }
                  } else {
                      $absoluteUrl = rtrim($url, '/') . '/' . ltrim($href, '/');
                      $linkList[] = $absoluteUrl;
                  }
              }
            }
        }

        // Rimuove le URL duplicate
        $linkList = array_unique($linkList);

        return $linkList;
      }

      // Ottieni l'URL da esaminare dall'URL della pagina
      if (isset($_GET['url'])) {
        $siteUrl = 'https://' . $_GET['url'];

        // Estrarre il dominio dal sito URL
        $parsedSiteUrl = parse_url($siteUrl);
        $siteDomain = $parsedSiteUrl['host'];

        // Ottenere l'elenco dei collegamenti dal sito web
        $links = getLinks($siteUrl, $siteDomain);

        // Stampa l'elenco dei collegamenti
        echo "<br/>";
        foreach ($links as $link) {
            echo $link . "<br>";
            $counter++;
        }
      } else {
        echo "Oppure specificare l'URL da esaminare come parametro 'url' nell'URL (es. ?url=www.miosito.it).";
      }


      // Url da submit form
      if (isset($_POST['submit'])) {
          $domain = 'https://' . $_POST['domain'];

          // Estrarre il dominio dal sito URL
          $parsedSiteUrl = parse_url($domain);
          $siteDomain = $parsedSiteUrl['host'];

          // Ottenere l'elenco dei collegamenti dal sito web
          $links = getLinks($domain, $siteDomain);

          // Mostrare l'elenco delle pagine
          echo "<h2>Elenco delle pagine presenti sul dominio $domain:</h2>";
          echo "<ul>";

          foreach ($links as $link) {
              echo $link . "<br>";
              $counter++;
          }
          echo "</ul>";
      }

      //conteggio risultatis
      echo "<br/>";
      echo "<h2>Counter :" . $counter . "</h2>";

    ?>


</body>
</html>
