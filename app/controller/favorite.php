<?php

require_once __DIR__ . '/../model/article.php';
require_once __DIR__ . '/../model/common.php';
require_once __DIR__ . '/../view/template.php';
require_once __DIR__ . '/../view/article.php';
require_once __DIR__ . '/../view/favorites.php';


function main_favorites(): string
{
    $action = $_GET['action'] ?? null;
    $article_id = $_GET['id'] ?? null;



    if ($action === 'add' || $action === 'remove') {
        if ($article_id !== null && ctype_digit($article_id)) {
            $article_id = (int)$article_id;}

        // Charger les favoris existants
        $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];

        if (!is_array($favorites)) {
            $favorites = [];
        }

        if ($action === 'add') {
            if (!in_array($article_id, $favorites)) {
                $favorites[] = $article_id;
            }
        } elseif ($action === 'remove') {
            $favorites = array_filter($favorites, fn($id) => (int)$id !== $article_id);
        }

        // Sauvegarde dans le cookie (expire dans 30 jours)
        setcookie('favorites', json_encode($favorites), time() + 30 * 24 * 60 * 60, '/');

        // Redirection pour éviter le double traitement
        if ($action === 'clear') {
            setcookie('favorites', json_encode([]), time() + 30 * 24 * 60 * 60, '/');
            header("Location: ?page=favorites");
            exit;
        }
    }

    // si l'action est "clear", on vide tous les favoris
    if ($action === 'clear') {
        setcookie('favorites', json_encode([]), time() + 30 * 24 * 60 * 60, '/');
        header("Location: ?page=favorites");
        exit;
    }


    $menu = get_menucsv();
    $articles = [];

    // Lire le cookie des favoris (format JSON)
    if (!empty($_COOKIE['favorites'])) {
        $fav_ids = json_decode($_COOKIE['favorites'], true);

        // Sécurité : filtre les ID numériques
        $fav_ids = array_filter($fav_ids, fn($id) => ctype_digit((string)$id));

        // Récupère chaque article favori
        foreach ($fav_ids as $id) {
            $article = get_article_a((int)$id);
            if ($article) {
                $articles[] = $article;
            }
        }
    }


    return join("\n", [
        html_head($menu),
        html_favorite_articles($articles),
        html_foot()
    ]);
}