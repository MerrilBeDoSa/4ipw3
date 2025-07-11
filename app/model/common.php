<?php

// uniquement pour centraliser les utilitaires

function get_menucsv(): array {
    $file = __DIR__ . "/../../asset/database/menu.csv";
    $menu = [];

    // si le fichier n'existe pas
    if (!file_exists($file)) {
        return [["Erreur menu", "home"]];

    }

    // il ouvre le fichier
    if (($handle = fopen($file, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, "|")) !== false) {
            if (count($data) === 2) {
                $menu[] = [$data[0], $data[1]];
            }
        }
        fclose($handle);
    }

    return $menu;
}



function get_last_dates(int $days = 7): array { // les 7 dernières dates
    $dates = [];
    for ($i = 1; $i <= $days; $i++) {
        $dates[] = date("Y-m-d", strtotime("-$i days"));
    }
    return $dates;
}
function resolve_image_path(string $base_name): string
{
    $valid_extensions = ['jpg', 'jpeg', 'png'];
    foreach ($valid_extensions as $ext) {
        $full_path = MEDIA_ARTICLE_PATH . $base_name . '.' . $ext;
        if (file_exists($full_path)) {
            return $full_path;
        }
    }
    return ''; // Aucun fichier trouvé
}



?>