<?php
require_once __DIR__ . '/../model/common.php';

/**
 * Génère le HTML d'un article complet
 * @param array|null $article_a Données de l'article
 * @return string HTML de l'article complet ou message d'erreur
 */
function html_article_main($article_a)
{
    if ($article_a === null) {
        return '<section class="article"><article><h1>Article non trouvé</h1></article></section>';
    }

    $title = htmlspecialchars($article_a['title'] ?? '');
    $hook = htmlspecialchars($article_a['hook'] ?? '');
    $content = $article_a['content'] ?? '';
    $date_raw = htmlspecialchars($article_a['date_published'] ?? '');
    $date_iso = date('Y-m-d', strtotime($date_raw));
    $date = date('d F Y', strtotime($date_raw));

    $image_base = pathinfo($article_a['image_name'] ?? '', PATHINFO_FILENAME);
    $image_path = resolve_image_path($image_base);

    ob_start();
    ?>
        <head>
            <link rel="stylesheet" href="./css/internal/main.css" /> <!-- lib interne / perso -->
        </head>

    <section class="article-main">
        <div id="article-detail-box" class="article-details-box"></div>
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

/**
 * Génère un aperçu d'article (version courte)
 * @param array $article Données de l'article
 * @param bool $is_featured Si l'article est en vedette
 * @return string HTML de l'aperçu
 */
function html_article_preview($article, $is_featured = false)
{
    // Verification et remplacement des valeurs null
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

/**
 * Génère la grille complète des articles
 * @param array $article_aa Liste des articles
 * @param bool $is_search Si c'est un résultat de recherche
 * @return string HTML de la grille d'articles
 */
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
/**
 * Génère les détails techniques d'un article selon le rôle
 * @param array $article Données de l'article
 * @param string $role 'admin' ou 'user'
 * @return string HTML des détails
 */
function html_article_detail(array $article, string $role): string {
    $date = $article['date_published'] ? date('d/m/Y', strtotime($article['date_published'])) : '';
    $category = htmlspecialchars($article['category'] ?? '');
    $word_count = $article['word_count'] ?? 0;
    $reading_time = $article['reading_time'] ?? 0;

    ob_start(); ?>
    <div class="detail-content">
        <?php if ($role === 'admin'): ?>
            <hr class="detail-separator">
            <p><strong>Date :</strong> <?= $date ?></p>
            <p><strong>Lecture :</strong> <?= $reading_time ?> min</p>
            <p><strong>Catégorie :</strong> <?= $category ?></p>
            <p><strong>ID :</strong> <?= $article['id'] ?? '' ?></p>
            <p><strong>Lecture :</strong> <?= $reading_time ?> min</p>
            <p><strong>nombre de mots :</strong> <?= $word_count ?></p>
            <p><strong>Mots :</strong> <?= $word_count ?></p>
        <?php endif; ?>
        <?php if ($role === 'user'): ?>
            <hr class="detail-separator">
            <p><strong>Date :</strong> <?= $date ?></p>
            <p><strong>Lecture :</strong> <?= $reading_time ?> min</p>
            <p><strong>Catégorie :</strong> <?= $category ?></p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}