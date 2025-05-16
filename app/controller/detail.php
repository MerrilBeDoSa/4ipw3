<?php
require_once '../app/model/article.php';
require_once '../app/view/article.php';

function main_article_detail() {
    // Vérification stricte de la connexion
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['login']['is_logged'])) {
        return ''; // Retourne vide si non connecté
    }
    // Vérifier si c'est une requête AJAX
    if (empty($_GET['ajax'])) {
        header("Location: index.php");
        exit;
    }

    $art_id = (int)($_GET['art_id'] ?? 0);
    $article =  get_article_a($art_id); // Utilisez votre fonction qui récupère tous les détails

    if (!$article) {
        return '';
    }

    // Calcul du nombre de mots & temps de lecture
    $article['word_count'] = str_word_count(strip_tags($article['content'] ?? ''));
    $article['reading_time'] = ceil($article['word_count'] / 200);

    // Récupérer le rôle de l'utilisateur  // Vérifier l'état de la session sans la redémarrer
       if (session_status() !== PHP_SESSION_ACTIVE) {
           session_start();
       }
    $role = $_SESSION['login']['role'] ?? 'user';

    // Génération du fragment HTML pour l'overlay
    return html_article_detail($article, $role);
}