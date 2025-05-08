<?php

require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../view/template.php';
require_once __DIR__ . '/../view/article.php';

function main_press(): string
{
    $menu = get_menucsv();
    $date = $_GET["date"] ?? null;
    // si une date est passée en paramètre, on filtre les articles
    if (!empty($_GET['date'])) {
        $date = $_GET['date'];
        $articles = get_articles_by_date($date);
    } else {
        // sinon on affiche tout (ou rien, selon mon choix)
        $articles = [];
    }

    // get all articles


    // get HTML code
    return join("\n", [
        html_head($menu),
        html_all_articles_main($articles),
        html_foot(),
    ]);
}