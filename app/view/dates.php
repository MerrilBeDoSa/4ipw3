<?php

function html_date_sidebar(array $dates): string
{
    ob_start();
    ?>
    <aside class="date-sidebar p-3">
        <h3>Articles récents :</h3>
        <ul class="list-group">
            <?php foreach ($dates as $date): ?>
                <li class="list-group-item">
                    <a href="?page=press&date=<?= urlencode($date) ?>">
                        <?= htmlspecialchars($date) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
    <?php
    return ob_get_clean();
}

