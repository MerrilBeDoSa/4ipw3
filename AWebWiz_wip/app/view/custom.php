<?php

function html_border_page()
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Changer la Bordure</title>
        <link rel="stylesheet" href="./css/border.css">
        <script src="./js/border.js" defer></script>
    </head>
    <body>
    <div class="border">
        Exemple de texte avec bordure
    </div>
    <div class="custom-select" style="width:200px; margin-top: 20px;">
        <select id="border-select">
            <option value="none">Pas de bordure</option>
            <option value="thin">Bordure fine</option>
            <option value="thick">Bordure épaisse</option>
        </select>
        <button onclick="changeBorder()">Mettre à jour</button>
    </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}
?>
