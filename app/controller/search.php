<?php
require_once __DIR__ . '/../view/search.php';
require_once __DIR__ . '/../model/article.php';

function main_search()
{
    $min_readtime = $_POST['min_readtime'] ?? 1;
    $max_readtime = $_POST['max_readtime'] ?? 30;
    $keyword = htmlspecialchars($_POST['name_search'] ?? '');
    $selected_cat = $_POST['category_filter'] ?? '';
    $menu = get_menucsv();
    $category_aa = get_all_categories();

    // Si on est en train de rechercher (si le bouton a été cliqué)
    $search_results = [];
    if (isset($_POST['button_search'])) {
        $search_results = search_articles($keyword, $min_readtime, $max_readtime, $selected_cat);
    }

    return join("\n", [
        html_head($menu),
        html_search($min_readtime, $max_readtime, $keyword, $selected_cat, $category_aa, $search_results),
        html_foot(),
    ]);
}


function search_articles($keyword, $min_readtime, $max_readtime, $category_id = '', $limit = 10)
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
        $sql .= " AND (title_art LIKE :kw_title OR hook_art LIKE :kw_hook OR content_art LIKE :kw_content)";
        $params[':kw_title'] = "$keyword%";
        $params[':kw_hook'] = "%$keyword%";
        $params[':kw_content'] = "%$keyword%";
    }
    if (!empty($category_id)) {
        $sql .= " AND fk_category_art = :category_id";
        $params[':category_id'] = $category_id;
    }

    // Ajout tri et limite
    $sql .= " ORDER BY date_art DESC LIMIT $limit"; // injection directe de $limit (valeur entière)

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}
function get_all_categories()
{
    $pdo = get_pdo();
    $stmt = $pdo->query("SELECT id_cat, name_cat FROM t_category ORDER BY name_cat");
    return $stmt->fetchAll();
}
