<?php
// Inclure le fichier contenant la fonction display_user_status()
require_once __DIR__ . '/../controller/login.php';



// Démarrer la session si elle n'est pas encore active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Afficher le statut de l'utilisateur
display_user_status();




/**
 * Génère la partie `<head>` et l'entête avec le menu.
 *
 * @param array $menu_array Tableau contenant les éléments du menu.
 * @param int|null $current_art_id ID de l'article courant (pour le préserver)
 * @return string
 */
function html_head($menu_array = [], $current_art_id = null)
{
    $debug = false;
    // Sous-menu
    $sub_menu_array = [
        ["Latest", "latest"],
        ["World", "world"],
        ["Business", "business"],
        ["U.S.", "us"],
        ["Politics", "politics"],
        ["Economy", "economy"],
        ["Tech", "tech"],
        ["Markets & Finance", "markets"],
        ["Opinion", "opinions"],
        ["Arts", "arts"],
        ["Lifestyle", "lifestyle"],
        ["Real Estate", "real estate"],
        ["Personal Finance", "personal finance"],
        ["Health", "health"],
        ["Style", "style"],
        ["Sports", "sports"]
    ];

    // Valide le tableau des menus
    $menu_array = validate_menu_array($menu_array);

    ob_start();
    ?>
    <html lang="fr">
    <head>
        <title>Accueil</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="./css/bootstrap/bootstrap.min.css" />  <!-- lib externe -->
        <link rel="stylesheet" href="./css/internal/main.css" /> <!-- lib interne / perso -->
        <script
                src="https://code.jquery.com/jquery-3.4.1.js"
                integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
                crossorigin="anonymous"></script>
        <script src="./js/quirks/QuirksMode.js"></script>
        <script src="./js/internal/favorite.js"></script>
        <script src="./js/internal/detail.js"></script>
    </head>

    <!-- l'émoji coeur change visuellement apres cliquage sans redirection de la page -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.wsj-heart-icon').forEach(icon => {
                icon.addEventListener('click', function (e) {
                    e.preventDefault(); // Empêche la redirection !!
                    const url = this.href;

                    fetch(url)
                        .then(() => {
                            // Change le cœur après ajout/retrait
                            const isFav = this.classList.contains('active');
                            this.textContent = isFav ? '🤍' : '❤️';
                            this.classList.toggle('active');
                        })
                        .catch(err => {
                            console.error("Erreur lors de la mise à jour des favoris :", err);
                        });
                });
            });
        });
    </script>

    <!-- option de présention-->
<?php
// Appliquer les préférences depuis les cookies
$theme_class = '';
$font_class = '';

// Si un nouveau thème est sélectionné, mettre à jour le cookie ET forcer $_COOKIE à jour
if (!empty($_GET['theme'])) {
    $theme = $_GET['theme'];
    setcookie('theme', $theme, time() + 3600, '/');
    $_COOKIE['theme'] = $theme; // ← forcer la mise à jour directe
}

// Si une nouvelle police est sélectionnée, même chose
if (!empty($_GET['font'])) {
    $font = $_GET['font'];
    setcookie('font', $font, time() + 3600, '/');
    $_COOKIE['font'] = $font; // ← forcer aussi
}

// Appliquer les classes CSS
if (!empty($_COOKIE['theme'])) {
    $theme_class = 'theme-' . htmlspecialchars($_COOKIE['theme']);
}

if (!empty($_COOKIE['font'])) {
    $font_class = 'font-' . htmlspecialchars($_COOKIE['font']);
}
?>

    <body class="<?= $theme_class ?> <?= $font_class ?>">

    <header>
        <div class="menu-container">
            <!-- Titre principal -->
            <h1 id="HomeTitle">THE WALL STREET JOURNAL.</h1>

            <!-- 🎨 Options de présentation theme -->
            <form method="get" style="display:flex; gap:1rem; align-items:center; margin-top: 1rem;">
                <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page'] ?? 'home') ?>">

                <!-- Ajout du champ caché pour préserver l'ID d'article -->
                <?php if ($current_art_id): ?>
                    <input type="hidden" name="art_id_preserve" value="<?= (int)$current_art_id ?>">
                <?php endif; ?>

                <label>🎨 Thème :
                    <select name="theme" onchange="this.form.submit()">
                        <option value="light" <?= ($_COOKIE['theme'] ?? '') === 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="dark" <?= ($_COOKIE['theme'] ?? '') === 'dark' ? 'selected' : '' ?>>Dark</option>
                        <option value="grey" <?= ($_COOKIE['theme'] ?? '') === 'grey' ? 'selected' : '' ?>>Grey</option>
                    </select>
                </label>

                <!-- 🔤 Options de présentation font -->
                <label>🔤 Police :
                    <select name="font" onchange="this.form.submit()">
                        <option value="black" <?= ($_COOKIE['font'] ?? '') === 'black' ? 'selected' : '' ?>>Black</option>
                        <option value="blue" <?= ($_COOKIE['font'] ?? '') === 'blue' ? 'selected' : '' ?>>Blue</option>
                        <option value="red" <?= ($_COOKIE['font'] ?? '') === 'red' ? 'selected' : '' ?>>Red</option>
                    </select>
                </label>
            </form>

            <!-- Menu principal -->
            <div class="menu-links">
                <?php
                foreach ($menu_array as $menu) {
                    // Vérifie que chaque élément est bien formé
                    $title = htmlspecialchars($menu[0], ENT_QUOTES, 'UTF-8');
                    $url = htmlspecialchars($menu[1], ENT_QUOTES, 'UTF-8');
                    echo <<<HTML
                <a href="?page={$url}">{$title}</a> |
HTML;
                }
                ?>
            </div>
        </div>
    </header>

    <!-- Sous-menu (nav) -->
    <nav class="secondary-menu">
        <?php
        foreach ($sub_menu_array as $sub_menu) {
            $title = $sub_menu[0];
            $url = $sub_menu[1];
            echo <<< HTML
        <a href="?category={$url}">{$title}</a>
HTML;
        }
        ?>
        <div class="user-status-bar">
            <?php echo display_user_status(); ?> <!-- Affiche le statut utilisateur -->
        </div>
    </nav>
    <?php

    if ($debug) {
        var_dump($_COOKIE);
        var_dump($_SESSION);
        var_dump($_GET);
        var_dump($_POST);
    }

    return ob_get_clean();
}


/**
 * Génère le pied de page HTML.
 *
 * @return string
 */
function html_foot()
{
    ob_start();
    ?>
    <!-- Logo WSJ au-dessus du footer -->
    <div class="wsj-footer-logo-bar">
        <div class="container d-flex flex-column align-items-start py-4 px-3">
            <img src="./media/wsj-logo.png" alt="Wall Street Journal Logo" class="wsj-logo">
            <span class="wsj-subtitle">a Dow Jones company</span>
        </div>
    </div>





    <footer>
        <?= html_footer_full() ?> <!-- Ceci appelle le footer complet -->
        Made with the amazing AWebWiz framework
        <img src="./media/awebwiz.png" alt="AWebWiz logo">
    </footer>
    </body>
    </html>
    <?php
    return ob_get_clean();
}


/**
 * Valide et filtre les entrées du tableau `$menu_array`.
 *
 * @param array $menu_array Tableau contenant les éléments du menu.
 * @return array Tableau filtré et valide.
 */
function validate_menu_array($menu_array)
{
    return array_filter($menu_array, function ($menu) {
        return isset($menu[0], $menu[1]) && is_string($menu[0]) && is_string($menu[1]);
    });
}
?>