<?php

require_once '../app/view/sponsor.php';

function main_sponsor()
{
    //Contenu du sponsor
    $banner = get_sponsor_content();
    $menu = get_menucsv();

    return join("\n", [
        html_head($menu),
        html_banner($banner),
        html_foot()
    ]);
}
