<?php
require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../view/article.php';

function main_article_detail() {
    // Vérifier si c'est une requête AJAX
    if (empty($_GET['ajax'])) {
        header("Location: index.php");
        exit;
    }

    // Démarrer la session si elle n'est pas active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Vérifier strictement la connexion
    if (!isset($_SESSION['login']['is_logged']) || !$_SESSION['login']['is_logged']) {
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

    // Valeur par défaut pour la catégorie
    $article['category'] = $article['category'] ?? 'Général';

    // Récupération du rôle
    $role = $_SESSION['login']['role'] ?? 'user';

    return html_article_detail($article, $role);
}