# AGENTS.md

Guida per agenti AI che lavorano su `ap-wp-theme-tw`, un tema WordPress custom per il sito **galeosuites** basato su `_tw` (Underscores + Tailwind), ACF Blocks, Alpine.js e WooCommerce.

## Panoramica dello stack

- **WordPress theme** (classico, non FSE) con blocchi ACF custom.
- **Tailwind CSS 3** per lo styling (compilato in `theme/style.css`).
- **Alpine.js 3** per l'interattività frontend; **GSAP**, **Swiper**, **Plyr**, **PhotoSwipe**, **Select2** per feature specifiche.
- **WooCommerce** per l'e-commerce (con personalizzazioni in `functions-woocommerce.php` e `theme/woocommerce/`).
- **ACF Pro** per i campi dei blocchi (JSON sync in `theme/acf-json/`).
- Build con **webpack** (JS), **Tailwind CLI** (CSS), **esbuild** (admin WC), **svgstore** (sprite SVG).
- Node `20` (vedi `.nvmrc`); package manager: **yarn**.

## Comandi

I comandi JS/CSS girano dalla **root del tema** (questa cartella). I comandi PHP usano Composer.

```bash
# Setup iniziale
nvm install && nvm use && yarn

# Sviluppo: watch di tutto (tailwind frontend/editor, webpack, esbuild)
yarn watch

# Build singola di sviluppo
yarn dev          # = yarn development

# Build di produzione (minificata)
yarn prod         # = yarn production

# Bundle deployabile (produzione + zip in node_scripts/zip.js)
yarn bundle

# Live reload (proxy su http://galeosuites.local)
yarn watch:browser-sync

# Lint JS + CSS
yarn lint
yarn lint-fix

# Lint PHP (PHPCS, WordPress Coding Standards)
composer php:lint
composer php:lint:autofix       # phpcbf
composer php:lint:changed       # solo file modificati (git)

# Traduzioni
composer make-pot
```

### Pipeline di build (cosa genera cosa)

- `theme/style.css` ← `tailwind/tailwind.css` (frontend). **Generato — non editare a mano** (è in `.gitignore`).
- `theme/style-editor.css` ← Tailwind con `_TW_TARGET=editor` (editor Gutenberg).
- `theme/wc-editor.css` ← `tailwind/wc-editor.css`.
- `theme/js/script.min.js` ← webpack, entry `javascript/script.js` → `javascript/app.js`.
- `theme/js/wc-admin-scripts.min.js` ← esbuild da `javascript/wc-admin.js`.
- `theme/assets/svg/main.svg` ← sprite da `svg-sprites/*.svg`.

## Struttura del progetto

```
tailwind/                  Sorgenti CSS Tailwind (config + custom/)
  tailwind.config.js       Config Tailwind principale
  custom/                  base, fonts, components, utilities, woocommerce
javascript/                Sorgenti JS (NON i bundle compilati)
  app.js                   Entry: inizializza Alpine + tutti i moduli
  modules/                 Un modulo per feature (Alpine*, Slider, Wishlist, ...)
  polyfills/
svg-sprites/               SVG sorgente per lo sprite
node_scripts/zip.js        Script di packaging per `yarn bundle`

theme/                     Il tema WordPress vero e proprio
  functions.php            Setup tema, enqueue, autoloader, costanti
  functions-woocommerce.php Personalizzazioni WooCommerce
  inc/                     block-types.php, custom-post-types.php, template-*, widgets, acf-options-pages, plugins
  blocks/<slug>/           Un blocco ACF per cartella: block.json + block.php
  Classes/                 Classi PHP namespacing `Classes\...` (autoload PSR-like)
    Blocks/                Logica di render dei blocchi (BaseBlock + uno per blocco)
    Core/                  Nav, Wishlist, RecentlyViewed, FlexibleArchiveFilters, PredictiveSearch, ...
  Components/              Componenti PHP riusabili (namespace `Components\...`)
  template-parts/, templates/, woocommerce/  Override dei template WP/WC
  acf-json/                Sync dei field group ACF (commit insieme al codice)
  js/, style*.css          ARTEFATTI COMPILATI — non editare
```

## Pattern e convenzioni

### Autoloader PHP custom
`functions.php` registra uno `spl_autoload_register` che mappa il namespace direttamente sul filesystem: `Classes\Blocks\CTA` → `theme/Classes/Blocks/CTA.php`, `Components\Button` → `theme/Components/Button.php`. **Il nome del file deve combaciare esattamente con il nome della classe** e la cartella con il namespace.

### Blocchi ACF
Per aggiungere/modificare un blocco:
1. Cartella in `theme/blocks/<slug>/` con `block.json` (`"name": "acf/<slug>"`, `renderTemplate: block.php`) e `block.php`.
2. `block.php` istanzia la classe di render: `$bl = new \Classes\Blocks\NomeBlocco( true, $block ); $bl->render();`.
3. La logica vive in `theme/Classes/Blocks/NomeBlocco.php` (estende `BaseBlock`).
4. **Registra lo slug in `theme/inc/block-types.php`** nell'array `$APWPThemeTWCustomBlocks` (es. `'acf/<slug>'`), altrimenti il blocco non compare nell'editor.
5. I campi ACF vengono salvati in `theme/acf-json/` — committali.

`BaseBlock::ACF($field)` astrae `get_field`/`get_sub_field` a seconda del contesto (passato come `$getField` nel costruttore).

### Componenti
I `theme/Components/*` estendono `BaseComponent` e sono utility di rendering (Button, Link, Image, Title, Text, Nav/*). Riusali invece di duplicare markup.

### Costanti feature-flag (in `functions.php`)
- `PREDICTIVE_SEARCH_ENABLED`
- `WISHLIST_FUNCTIONALITY_ENABLED` (attualmente `false`)
- `FLEX_FILTERS_ENABLED` (true se WooCommerce è attivo)

### JavaScript
Ogni feature è una classe in `javascript/modules/`, istanziata in `javascript/app.js`. Alpine e i suoi plugin vengono caricati con import dinamici dentro `initializeScripts()`. Per aggiungere un modulo: crealo in `modules/`, importalo e istanzialo in `app.js`, poi `yarn watch`.

## WooCommerce

Il tema integra WooCommerce con stili e markup completamente custom (Tailwind), non quelli di default.

- **Personalizzazioni PHP**: [theme/functions-woocommerce.php](theme/functions-woocommerce.php). Qui vengono **dequeuati tutti gli stili WooCommerce** (`woocommerce_enqueue_styles` → array vuoto) e deregistrati gli stili dei WC Blocks (`ap_wp_disable_wp_blocks`): lo styling arriva tutto da Tailwind (`tailwind/custom/woocommerce/`).
- **Override dei template**: [theme/woocommerce/](theme/woocommerce/) con le sottocartelle `cart/`, `checkout/`, `notices/`, `global/`, `inc/`. Per modificare il markup di una pagina WC, sovrascrivi qui il template corrispondente (stessa struttura del plugin WooCommerce).
- **Admin WC**: `javascript/wc-admin.js` → compilato in `theme/js/wc-admin-scripts.min.js` via esbuild.
- **Frontend interattivo**: i moduli Alpine in `javascript/modules/` gestiscono carrello, checkout, notice e varianti — `AlpineWooCart.js`, `AlpineWooCheckout.js`, `AlpineWooNotices.js`, `AlpineWCVariationsSelect.js`, oltre a `ProductGalleries.js`.
- **Classi Core correlate**: `WCCartWidget`, `Wishlist`, `RecentlyViewed`, `FlexibleArchiveFilters`, `PredictiveSearch` in `theme/Classes/Core/`.
- **Feature flag**: `FLEX_FILTERS_ENABLED` si attiva automaticamente quando WooCommerce è attivo.

### Eventi JS WooCommerce
WooCommerce emette i propri eventi jQuery sul `document.body` (es. `updated_checkout`, `added_to_cart`, `found_variation`, `wc_fragments_loaded`). Quando devi reagire a stati del carrello/checkout/varianti dal lato Alpine, **aggancia questi eventi** invece di reimplementare la logica. L'elenco completo e gli snippet di binding sono in [WOO_EVENTS_LIST.md](WOO_EVENTS_LIST.md).

## Note importanti per gli agenti

- **Non editare i file generati**: `theme/style.css`, `theme/style-editor.css`, `theme/wc-editor.css`, `theme/js/*.min.js`, `theme/assets/svg/main.svg`. Modifica i sorgenti in `tailwind/` e `javascript/`, poi ricompila.
- **Le classi Tailwind** vanno aggiunte nei template PHP / output dei Component; la safelist è in `theme/safelist.txt`.
- **Stile codice**: PHP segue i WordPress Coding Standards (vedi `phpcs.xml.dist`); JS/CSS seguono ESLint + Prettier (`.eslintrc`, `prettier-plugin-tailwindcss`). Lancia `yarn lint` / `composer php:lint` prima di considerare un lavoro concluso.
- **Lingua**: i nomi dei blocchi e gran parte del dominio sono in italiano (es. `colonna-immagini`, `degustazione`, `mappa-territorio`). Mantieni la coerenza linguistica esistente.
- **Text domain**: `ap-wp-theme` per le traduzioni.
- Documentazione correlata: `README.md` (setup/deploy), `WOO_EVENTS_LIST.md` (eventi WooCommerce).
