<?php
function html_search(
    int $min_readtime = 1,
    int $max_readtime = 30,
    string $keyword = '',
    string $selected_cat = '',
    array  $category_aa = [],
    array  $search_results = []
) {
    // Construire les options de catégorie
    $category_options = '<option value="">-- Toutes les catégories --</option>';
    foreach ($category_aa as $cat) {
        $id   = htmlspecialchars($cat['id_cat']);
        $name = htmlspecialchars($cat['name_cat']);
        $sel  = ($selected_cat === $id) ? ' selected' : '';
        $category_options .= "<option value=\"$id\"$sel>$name</option>";
    }

    // On ouvre un seul <form> qui englobe les deux sidebars
    $html = <<<HTML
<div class="search-container">
  <form method="post" id="searchForm" class="search-form">
    <!-- ASIDE 1 : mots-clés + readtime -->
    <aside class="search-sidebar">
      <h3>Rechercher</h3>
      <label>
        Mot-clé
        <input type="text" name="name_search"
               value="{$keyword}"
               placeholder="Entrez un mot-clé">
      </label>

      <label>
        Temps de lecture
        <div class="readtime-slider">
          <input type="range" name="min_readtime" min="1" max="30"
                 value="{$min_readtime}">
          <span>{$min_readtime}–{$max_readtime} min</span>
          <input type="range" name="max_readtime" min="{$min_readtime}" max="30"
                 value="{$max_readtime}">
        </div>
      </label>

      <button type="submit" name="button_search" class="btn-primary">Rechercher</button>
    </aside>

    <!-- ASIDE 2 : filtre catégorie -->
    <aside class="search-sidebar">
      <h3>Catégorie</h3>
      <select name="category_filter"
              onchange="document.getElementById('searchForm').submit()">
        {$category_options}
      </select>
    </aside>
  </form>

  <!-- MAIN : résultats -->
  <main class="search-results">
HTML;

    if (isset($_POST['button_search']) || isset($_POST['category_filter'])) {
        if (empty($search_results)) {
            $html .= '<p>Aucun article ne correspond à votre recherche.</p>';
        } else {
            $count = count($search_results);
            $plural = $count > 1 ? 's' : '';
            $html .= "<p><strong>{$count}</strong> article{$plural} trouvé{$plural}.</p>";

            // Article vedette
            $feat = array_shift($search_results);
            $html .= '<div class="featured-article">'
                . html_article_preview($feat, true)
                . '</div>';

            // Grille des autres articles
            $rows = array_chunk($search_results, 2);
            $html .= '<div class="articles-grid">';
            foreach ($rows as $row) {
                $html .= '<div class="article-row">';
                foreach ($row as $a) {
                    $html .= '<div class="article-col">'
                        . html_article_preview($a)
                        . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }
    }

    // fermeture du main et du conteneur
    $html .= "</main></div>";

    return $html;
}
?>
