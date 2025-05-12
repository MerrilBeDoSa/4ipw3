<?php
require_once __DIR__ . '/../view/search.php';
require_once __DIR__ . '/../model/article.php';

function main_search()
{
    $min_readtime = $_POST['min_readtime'] ?? 1;
    $max_readtime = $_POST['max_readtime'] ?? 30;
    $keyword = $_POST['name_search'] ?? '';
    $menu = get_menucsv();


    // Récupère les articles filtrés
    $filtered_articles = search_articles($keyword, $min_readtime, $max_readtime);

    return join("\n", [
        html_head($menu),
        html_search($min_readtime, $max_readtime, $keyword),
        html_all_articles_main($filtered_articles),
        html_foot(),
    ]);
}

function search_articles($keyword, $min_readtime, $max_readtime, $limit = 10)
{
    $pdo = get_pdo();

    // Construction de la requête
    $sql = "SELECT 
                id_art AS id,
                title_art AS title,
                hook_art AS hook,
                content_art AS content,
                date_art AS date_published,
                image_art AS image_name,
                readtime_art AS readtime
            FROM t_article
            WHERE readtime_art BETWEEN :min_readtime AND :max_readtime";

    $params = [
        ':min_readtime' => $min_readtime,
        ':max_readtime' => $max_readtime
    ];

    // Recherche par mot-clé
    if (!empty($keyword)) {
        $sql .= " AND (title_art LIKE :keyword OR content_art LIKE :keyword)";
        $params[':keyword'] = "%$keyword%";
    }

    // Ajout tri et limite
    $sql .= " ORDER BY date_art DESC LIMIT $limit"; // injection directe de $limit (valeur entière)

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

