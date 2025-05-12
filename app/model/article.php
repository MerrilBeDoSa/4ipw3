<?php
/**
 * retourne l'article à afficher sur la home page
 * en première position
 * temporairement c'est l'article d'id 1
 * @param $art_id id de l'article à getter dans la db
 * @return array array avec les données de l'article
 */
/**
 * Retourne l'article à afficher sur la home page
 * @param int $art_id ID de l'article à récupérer (par défaut 1)
 * @return array|null Tableau avec les données de l'article ou null si non trouvé
 */
function get_article_a($art_id = 1): ?array
{
    switch (DATABASE_TYPE) {
        case "MySql":
            $article = get_article_a_sql($art_id);
            return $article !== false ? $article : null;
        case "JSON":
            return null;
        default:
            throw new RuntimeException("Type de base de données non supporté");
    }
}

/*
 * Retourne l'objet PDO
 * Crée l'onjet PDO s'il n'existe pas
 *
 */
function get_pdo(){
    static $pdo = null;

    if(empty($pdo)){
        $pdo = new PDO( DATABASE_DSN, DATABASE_USERNAME, DATABASE_PASSWORD);
    }
    return $pdo;
}

/**
 * @param $art_id
 * @return mixed
 */
function get_article_a_sql($art_id)
{
    $q = <<<SQL
SELECT 
    id_art AS id,
    title_art AS title,
    '' AS hook,
    content_art AS content,
    date_art AS date_published, 
    image_art AS image_name
FROM t_article 
WHERE id_art = :art_id
SQL;

    $pdo = get_pdo();
    $stmt = $pdo->prepare($q);
    $stmt->execute(['art_id' => $art_id]);

    $result = $stmt->fetch();

    // error_log("Article ID $art_id - Image: " . ($result['image_name'] ?? 'NULL'));

    return $result;
}
function get_latest_articles_by_category($category_id, $limit = 10)
{
    $q = <<<SQL
SELECT 
    id_art AS id,
    IFNULL(title_art, '') AS title,
    IFNULL(hook_art, '') AS hook,
    IFNULL(content_art, '') AS content,
    IFNULL(date_art, '') AS date_published, 
    IFNULL(image_art, '') AS image_name
FROM t_article 
WHERE fk_category_art = :category_id
ORDER BY date_art DESC 
LIMIT :limit
SQL;

    $pdo = get_pdo();
    $stmt = $pdo->prepare($q);
    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
// Pour les 10 derniers articles de la catégorie id 146 : "On n'est pas des pigeons"
$latest_articles = get_latest_articles_by_category(146);

function get_all_dates_article(): array
{
    $q = "SELECT DISTINCT DATE(date_art) AS date FROM t_article ORDER BY date DESC";
    $pdo = get_pdo();
    $stmt = $pdo->prepare($q);
    $stmt->execute();
    return array_map(fn($row) => $row['date'], $stmt->fetchAll());
}

function get_articles_by_date(string $date): array
{
    $q = <<<SQL
SELECT 
    id_art AS id,
    title_art AS title,
    hook_art AS hook,
    content_art AS content,
    date_art AS date_published, 
    image_art AS image_name
FROM t_article 
WHERE DATE(date_art) = :selected_date
ORDER BY date_art DESC
SQL;

    $pdo = get_pdo();
    $stmt = $pdo->prepare($q);
    $stmt->execute(['selected_date' => $date]);
    return $stmt->fetchAll();
}

