<?php

/**
 * génère la page avec un (seul) article complet
 * @return string html
 */
function main_article(): string
{
    $menu = get_menucsv();
    $art_id = $_GET['art_id'] ?? null;

    // Validation de l'ID
    if (!$art_id || !ctype_digit($art_id)) {
        return html_head() . '<h1>ID d\'article invalide</h1>' . html_foot();
    }

    $article_a = get_article_a((int)$art_id);

    return join("\n", [
        html_head($menu),
        html_article_main($article_a),
        html_foot(),
    ]);

}