<?php

function html_favorite_articles(array $articles): string
{
    $count = count($articles);

    if ($count === 0) {
        return '<div class="container mt-4"><p>🧺 Vous n\'avez aucun article en favori pour le moment.</p></div>';
    }

    ob_start();
    ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="text-start fw-bold fs-4">
                🧺 Vos favoris <span class="badge bg-secondary"><?= $count ?></span>
            </h2>

            <form method="get" action="" class="text-end">
                <input type="hidden" name="page" value="favorites">
                <input type="hidden" name="action" value="clear">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    🧹 Vider la liste
                </button>
            </form>
        </div>

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



















/**function html_favorite_articles(array $articles): string
{
    $count = count($articles);

    if ($count ===0) {
        return '<div class="container mt-4"><p>🧺 Vous n\'avez aucun article en favori pour le moment.</p></div>';
    }

    ob_start();
    ?>
    <div class="container mt-4">
        <h2 class="text-center">🧺 Vos articles favoris</h2>
        <form method="get" action="" class="text-center">
            <input type="hidden" name="page" value="favorites">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="clear-favorites">🧹 Vider la liste</button>
        </form>


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
            -->
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
} */
