<?php

/**
 * génère la page avec un (seul) article complet
 * @return string html
 */
function main_article(): string
{
    // Gestion de l'ajout/suppression de favoris
    if (isset($_GET['fav_toggle']) && ctype_digit($_GET['fav_toggle'])) {
        $fav_id = (int)$_GET['fav_toggle'];
        // Lire les favoris existants (ou tableau vide)
        $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];
        if (!is_array($favorites)) $favorites = [];

        // Ajouter ou supprimer
        if (in_array($fav_id, $favorites)) {
            $favorites = array_diff($favorites, [$fav_id]);
        } else {
            $favorites[] = $fav_id;
        }

        // Réécriture du cookie pour 30 jours
        setcookie('favorites', json_encode(array_values($favorites)), time() + 3600 * 24 * 30, "/");

        // Redirection pour éviter les doubles actions au refresh
        header("Location: ?page=article&art_id=" . $fav_id);
        exit;
    }

    $menu = get_menucsv();

    // Récupérer l'ID d'article depuis GET ou depuis les paramètres de thème/font
    $art_id = $_GET['art_id'] ?? $_GET['art_id_preserve'] ?? null;

    // Validation de l'ID
    if (!$art_id || !ctype_digit($art_id)) {
        return html_head($menu) . '<h1>ID d\'article invalide</h1>' . html_foot();
    }

    $article_a = get_article_a((int)$art_id);

    return join("\n", [
        html_head($menu, (int)$art_id), // On passe l'ID d'article à html_head
        html_article_main($article_a),
        html_foot(),
    ]);
}