<?php
require_once __DIR__ . '/../model/sponsor.php';
require_once __DIR__ . '/../view/sponsor.php';

/**
 * Génère le code HTML d'une bannière sponsor à partir des données JSON fournies.
 *
 * @param array $data Tableau associatif contenant les informations du sponsor
 *                    (texte, couleur, images, lien).
 * @return string Le HTML de la bannière sponsor.
 */
function html_banner($data)
{
    // Traitement du texte avec gestion des sauts de ligne
    $text = nl2br($data['text'] ?? '');

    // Couleur principale de la bannière
    $color = $data['color'] ?? '#ffffff';

    // Image principale affichée (logo du sponsor)
    $image = $data['image'] ?? '';

    // Image de fond de la bannière
    $background = $data['background_image'] ?? '';

    // Lien cliquable vers le site du sponsor
    $link = $data['link'] ?? '#';

    // Capture du code HTML dans un tampon pour pouvoir le retourner
    ob_start(); ?>
    <head>
        <link rel="stylesheet" href="./css/internal/main.css" /> <!-- Feuille de style personnalisée -->
    </head>
    <section class="sponsor-banner" style="--sponsor-color: <?= $color ?>; --sponsor-bg: url('<?= $background ?>');">
        <div class="sponsor-gauche">
            <h2>Sponsor</h2>
            <!-- Affichage du texte sponsorisé avec sauts de ligne conservés -->
            <p><?= $text ?></p>
        </div>
        <div class="sponsor-droite">
            <?php if ($image): ?>
                <!-- Affiche le logo du sponsor avec un lien vers son site -->
                <a href="<?= $link ?>" target="_blank">
                    <img src="<?= $image ?>" alt="Image sponsor 1">
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
