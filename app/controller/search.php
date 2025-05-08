<?php

function main_search()
{
    // get all articles
    $all_article_a = get_article_a();

    // get HTML code
    return join("\n", [
        ctrl_head(),
        html_search_form(),
        html_all_articles_main($all_article_a),
        html_foot(),
    ]);
}


