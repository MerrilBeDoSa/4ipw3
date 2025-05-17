<?php
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../view/article.php';

function main_article_detail() {
    // Vérifier si c'est une requête AJAX
    if (empty($_GET['ajax'])) {
        header("Location: index.php");
        exit;
    }


    // Debug: Afficher le contenu de la session
    error_log("Session data: " . print_r($_SESSION, true));

    // VDémarrer la session si elle n'est pas active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['login']['is_logged'])) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }

    $art_id = (int)($_GET['art_id'] ?? 0);
    $article = get_article_a($art_id);

    if (!$article) {
        return '';
    }

    // Calcul des métriques
    $article['word_count'] = str_word_count(strip_tags($article['content'] ?? ''));
    $article['reading_time'] = ceil($article['word_count'] / 200);
    $article['category'] = $article['category'] ?? 'Général';


    // Récupération du rôle avec validation
    $role = ($_SESSION['login']['role'] ?? 'user') === 'admin' ? 'admin' : 'user';

    error_log("Role determined: " . $role); // Debug

    return html_article_detail($article, $role);
}