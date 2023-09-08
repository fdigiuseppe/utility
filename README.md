# utility
Utility set

- site_scrapeV2.php:
  - utilizza composer per l'installazione di librerie di terze parti
  - require '../vendor/autoload.php'; -- perchè sul server la cartella vendor è in root
  - composer command:
    - composer require fabpot/goutte
    - composer require symfony/dom-crawler
