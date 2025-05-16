<?php
require_once __DIR__ . '/../model/common.php';

function html_article_main($article_a)
{
    // Vérifie si l'article existe
    if ($article_a === null) {
        return '<section class="article"><article><h1>Article non trouvé</h1></article></section>';
    }

    // Échappement des variables pour la sécurité
    $title = htmlspecialchars($article_a['title'] ?? '');
    $hook = htmlspecialchars($article_a['hook'] ?? '');
    $content = $article_a['content'] ?? '';
    $date_raw = htmlspecialchars($article_a['date_published'] ?? '');
    $date_iso = date('Y-m-d', strtotime($date_raw));
    $date = date('d F Y', strtotime($date_raw));

    // Résolution du chemin de l'image (png, jpg, ...)
    $image_base = pathinfo($article_a['image_name'] ?? '', PATHINFO_FILENAME);
    $image_path = resolve_image_path($image_base);

    ob_start();
    ?>
    <section class="article-main">
        <header class="article-header">
            <h1 class="article-title"><?= $title ?></h1>
            <?php if ($hook): ?>
                <h2 class="article-hook"><?= $hook ?></h2>
            <?php endif; ?>
            <div class="article-meta">
                <time datetime="<?= $date_iso ?>" class="article-date"><?= $date ?></time>
            </div>
        </header>

        <?php if (!empty($image_path)): ?>
            <div class="media_article">
                <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= $title ?>">
            </div>
            <hr class="article-separator">
        <?php endif; ?>

        <div class="article-content">
            <?= $content ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function html_article_preview($article, $is_featured = false)
{
    // Vérification et remplacement des valeurs null
    $title = $article['title'] ?? 'Titre non disponible';
    $hook = $article['hook'] ?? '';
    $date = $article['date_published'] ?? 'Date inconnue';
    $readtime = $article['readtime'] ?? null;
    $art_id = $article['id'] ?? 0;
    $image_name = $article['image_name'] ?? '';
    $image_path = !empty($image_name) ? MEDIA_ARTICLE_PATH . $image_name : '';

    // Vérification de l'extension de l'image
    $image_base = pathinfo($article['image_name'] ?? '', PATHINFO_FILENAME); // Nom sans extension
    $image_path = resolve_image_path($image_base);


    $title_class = $is_featured ? 'wsj-featured-title' : 'wsj-article-title';
    $article_class = $is_featured ? 'wsj-featured-item' : 'wsj-article-item';

    ob_start();
    ?>
<article class="<?= $article_class ?>">
    <a href="?page=article&art_id=<?= (int)$art_id ?>">
        <?php if (!empty($image_path)): ?>
            <div class="wsj-image-container">
                <img src="<?= htmlspecialchars($image_path) ?>"
                     alt="<?= htmlspecialchars($title) ?>"
                     class="<?= $is_featured ? 'wsj-featured-image' : 'wsj-article-image' ?>">
            </div>
        <?php endif; ?>
        <h2 class="<?= $title_class ?>"><?= htmlspecialchars($title) ?></h2>
        <p class="wsj-article-hook"><?= htmlspecialchars($hook) ?></p>
        <div class="wsj-article-meta d-flex align-items-center gap-2">
            <time class="wsj-article-date"><?= htmlspecialchars($date) ?></time>
            <?php if ($readtime !== null): ?>
                <span class="wsj-readtime">
            <i class="bi bi-clock-fill text-primary"></i>
            <?= (int)$readtime ?> min
        </span>
            <?php endif; ?>

            <!-- ///////////// -->
            <?php
            $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];
            if (!is_array($favorites)) $favorites = [];
            $is_fav = in_array($art_id, $favorites);
            $heart_icon = $is_fav ? '❤️' : '🤍';
            ?>
            <a href="?page=favorites&action=<?= $is_fav ? 'remove' : 'add' ?>&id=<?= (int)$art_id ?>"
               class="wsj-heart-icon" title="<?= $is_fav ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                <?= $heart_icon ?>
            </a>


        </div>
    </a>
    <!-- BOUTON FAVORI -->
    <?php
    // Favoris depuis cookie
    $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];
    if (!is_array($favorites)) $favorites = [];
    $is_fav = in_array($art_id, $favorites);

    $heart_class = $is_fav ? 'fav-icon active' : 'fav-icon';
    ?>
</article>
<?php
    return ob_get_clean();
}



function html_all_articles_main($article_aa, $is_search = false)
{
    ob_start();

    if (empty($article_aa)) {
        echo '<div class="wsj-articles-container">';
        if ($is_search) {
            echo '<p>Aucun article ne correspond à votre recherche.</p>';
        }
        echo '</div>';
        return ob_get_clean(); // ← AJOUT ICI pour sortir proprement
    }

    // Article vedette
    $featured_article = array_shift($article_aa);
    $other_articles = array_chunk($article_aa, 2);

    echo "<div class=\"wsj-featured-article\">";
    echo html_article_preview($featured_article, true);
    echo "</div>";

    // Grille d'articles
    echo "<div class=\"wsj-articles-grid\">";
    foreach ($other_articles as $article_pair) {
        echo "<div class=\"wsj-article-row\">";
        foreach ($article_pair as $article) {
            echo "<div class=\"wsj-article-col\">";
            echo html_article_preview($article);
            echo "</div>";
        }
        echo "</div>";
    }
    echo "</div></div>"; // fin des containers

    return ob_get_clean();
}



