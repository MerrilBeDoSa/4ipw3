<?php

function html_search($min_readtime = 1, $max_readtime = 30, $keyword = '', $selected_cat = '', $category_aa = [], $search_results = []) {
    // Construit la liste déroulante des catégories
    $category_options = '<option value="">-- Toutes les catégories --</option>';
    foreach ($category_aa as $cat) {
        $id = htmlspecialchars($cat['id_cat']);
        $name = htmlspecialchars($cat['name_cat']);
        $selected = ($selected_cat == $id) ? 'selected' : '';
        $category_options .= "<option value=\"$id\" $selected>$name</option>";
    }

    // Formulaire de recherche
    $search_form = <<<HTML
    <form method="post" class="search-form">
        <div class="search-section">
            <label for="name_search">Recherche par mot-clé :</label>
            <input type="text" name="name_search" id="name_search" value="{$keyword}" placeholder="Entrez un mot-clé">
        </div>
        
        <div class="search-section">
            <label for="category_filter">Catégorie :</label>
            <select name="category_filter" id="category_filter">
                $category_options
            </select>
        </div>
        
        <div class="search-section">
            <label>Temps de lecture (minutes) :</label>
            <div class="readtime-slider">
                <span id="min-readtime-value">$min_readtime</span> min
                <input type="range" name="min_readtime" id="min_readtime" 
                       min="1" max="30" value="$min_readtime" class="slider">
                à
                <span id="max-readtime-value">$max_readtime</span> max
                <input type="range" name="max_readtime" id="max_readtime" 
                       min="$min_readtime" max="30" value="$max_readtime" class="slider">
            </div>
        </div>
        
        <button type="submit" name="button_search" class="search-button">
            Rechercher
        </button>
    </form>
HTML;

    // Résultats de la recherche (affichage)
    $search_results_output = '';
    if (!empty($search_results)) {
        $count = count($search_results);
        $search_results_output .= "<p>$count article" . ($count > 1 ? 's' : '') . " trouvé" . ($count > 1 ? 's' : '') . ".</p>";

        // Afficher les articles ici
        $featured_article = array_shift($search_results); // Premier article en vedette
        $other_articles = array_chunk($search_results, 2); // Reste des articles par paire

        // Article en vedette
        $search_results_output .= "<div class=\"wsj-featured-article\">";
        $search_results_output .= html_article_preview($featured_article, true);  // Utilise ta fonction de prévisualisation
        $search_results_output .= "</div>";

        // Grille d'articles
        $search_results_output .= "<div class=\"wsj-articles-grid\">";
        foreach ($other_articles as $article_pair) {
            $search_results_output .= "<div class=\"wsj-article-row\">";
            foreach ($article_pair as $article) {
                $search_results_output .= "<div class=\"wsj-article-col\">";
                $search_results_output .= html_article_preview($article);  // Prévisualisation des autres articles
                $search_results_output .= "</div>";
            }
            $search_results_output .= "</div>";
        }
        $search_results_output .= "</div>";
    } else {
        // Si aucun résultat
        $search_results_output .= '<p>Aucun article ne correspond à votre recherche.</p>';
    }

    // Retourner le formulaire et les résultats
    return $search_form . $search_results_output;
}
?>
