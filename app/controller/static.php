<?php

function main_static(): string
{
    $menu = get_menucsv();
    $html = file_get_contents(__DIR__ . '/../../asset/static_content/about.html');

    return join("\n", [
        html_head($menu),
        $html,
        html_foot(),
    ]);
}
