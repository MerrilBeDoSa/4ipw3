<?php

require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../view/dates.php';
require_once __DIR__ . '/../model/common.php';
require_once __DIR__ . '/../view/template.php';

function main_dates(): string
{
    $menu = get_menucsv();
    $dates = get_all_dates_article(); // // récupère les dates distinctes

    return join("\n", [
        html_head($menu),
        html_date_sidebar($dates),
        html_foot(),
    ]);
}
