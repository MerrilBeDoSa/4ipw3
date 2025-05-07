<?php

require_once __DIR__ . "/../model/login.php";
require_once __DIR__ . "/../view/template.php";
require_once __DIR__ . "/../model/article.php";
require_once __DIR__ . "/../view/article.php";
require_once __DIR__ . "/../model/common.php";

function main_home(): string
{
    $menu = get_menucsv();
    // Récupère les 10 derniers articles
    $latest_articles = get_latest_articles_by_category(146);

    return join("\n", [
        html_head($menu),
        html_all_articles_main($latest_articles), // Affiche la liste des aperçus
        html_foot(),
    ]);
}