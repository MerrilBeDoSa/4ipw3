<?php

require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../view/dates.php';
require_once __DIR__ . '/../model/common.php';
require_once __DIR__ . '/../view/template.php';

function main_dates(): string
{
    $menu = get_menucsv();
    $dates = get_all_dates_article(); // // récupère les dates distinctes
    $counts = get_article_counts_by_date(); // retourne ['2023-12-31' => 3, ...]

    return join("\n", [
        html_head($menu),
        html_date_sidebar($dates, $counts),
        html_foot(),
    ]);
}
