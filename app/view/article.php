<?php

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
    $date = htmlspecialchars($article_a['date_published'] ?? '');
    $image_name = $article_a['image_name'] ?? '';
    $image_path = !empty($image_name) ? MEDIA_ARTICLE_PATH . htmlspecialchars($image_name) : '';

    $out = <<<HTML
    <section class="article">
        <article>
            <h1>$title</h1>
            <h2>$hook</h2>
    HTML;

    if (!empty($image_path)) {
        $out .= <<<HTML
            <div class="media_article"><img src="$image_path" class="rounded shadow-sm" loading="lazy" alt="$title" width="972"></div>
        HTML;
    }

    $out .= <<<HTML
            <div class="article-date">$date</div>
            <div class="article-content">$content</div>
        </article>
    </section>
    HTML;

    return $out;
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
            <div class="wsj-article-meta">
                <time class="wsj-article-date"><?= htmlspecialchars($date) ?></time>
                <?php if ($readtime !== null): ?>
                    <span class="wsj-readtime">
                        <i class="bi bi-clock-fill text-primary"></i>
                        <?= (int)$readtime ?> min
                    </span>
                <?php endif; ?>
            </div>
        </a>
    </article>
    <?php
    return ob_get_clean();
}


function html_all_articles_main($article_aa)
{
    if (empty($article_aa)) {
        return '<div class="wsj-articles-container"><p>Aucun article disponible</p></div>';
    }

    // Séparer le premier article (featured) des autres
    $featured_article = array_shift($article_aa);
    $other_articles = array_chunk($article_aa, 2); // Groupes de 2 articles

    ob_start();
    ?>
    <div class="wsj-articles-container">
        <!-- Article vedette en haut -->
        <div class="wsj-featured-article">
            <?= html_article_preview($featured_article, true) ?>
        </div>

        <!-- Grille d'articles -->
        <div class="wsj-articles-grid">
            <?php foreach ($other_articles as $article_pair): ?>
                <div class="wsj-article-row">
                    <?php foreach ($article_pair as $article): ?>
                        <div class="wsj-article-col">
                            <?= html_article_preview($article) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}