<?php

/**  function html_date_sidebar(array $dates): string
{
    ob_start();
    ?>
    <section class="container mt-4">
        <h3 class="mb-3"><span style="font-size: 1.3em;">📅</span> Articles par date</h3>
        <div class="dates-wrapper">
            <?php foreach ($dates as $date): ?>
                <a href="?page=press&date=<?= urlencode($date) ?>" class="date-badge">
                    <?= date_format(DateTime::createFromFormat('Y-m-d', $date), 'j F Y') ?>
                </a>
            <?php endforeach; ?>
        </div>


    </section>
    <?php
    return ob_get_clean();
}
*/


function html_date_sidebar(array $dates, array $count_by_date = []): string
{
    ob_start(); ?>
    <section class="date-section container my-5">
        <h2>Articles par date :</h2>
        <div class="dates-wrapper">
            <?php foreach ($dates as $date): ?>
                <?php
                $formatted = date_format(DateTime::createFromFormat('Y-m-d', $date), 'j F Y');
                $article_count = $count_by_date[$date] ?? 0;
                ?>
                <a href="?page=press&date=<?= urlencode($date) ?>" class="date-badge">
                    📅 <?= $formatted ?> (<?= $article_count ?>)
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
