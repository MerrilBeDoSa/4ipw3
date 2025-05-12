<?php

function html_favorite_articles(array $articles): string
{
    if (empty($articles)) {
        return '<div class="container mt-4"><p>🧺 Vous n\'avez aucun article en favori pour le moment.</p></div>';
    }

    ob_start();
    ?>
    <div class="container mt-4">
        <h2 class="text-center">🧺 Vos articles favoris</h2>
        <table class="table table-bordered table-hover table-striped mt-3">
            <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Résumé</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td>
                        <?php if (!empty($article['image_name'])): ?>
                            <img src="<?= MEDIA_ARTICLE_PATH . htmlspecialchars($article['image_name']) ?>"
                                 alt="<?= htmlspecialchars($article['title']) ?>" width="100">
                        <?php else: ?>
                            <em>Aucune</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($article['title']) ?></td>
                    <td><?= htmlspecialchars($article['hook']) ?></td>
                    <td><?= htmlspecialchars($article['date_published']) ?></td>
                    <td>
                        <!-- Lien pour retirer un article (à implémenter ensuite) -->
                        <a href="?page=favorites&action=remove&id=<?= (int)$article['id'] ?>"
                           class="btn btn-sm btn-danger">
                            Retirer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
