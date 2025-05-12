<?php
function html_search($min_readtime = 1, $max_readtime = 30, $keyword = ''){
    return <<<HTML
    <form method="post" class="search-form">
        <div class="search-section">
            <label for="name_search">Recherche par mot-clé :</label>
            <input type="text" name="name_search" id="name_search" 
                   value="$keyword" placeholder="Entrez un mot-clé">
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
    
    <script>
        const minSlider = document.getElementById('min_readtime');
        const maxSlider = document.getElementById('max_readtime');
        const minValue = document.getElementById('min-readtime-value');
        const maxValue = document.getElementById('max-readtime-value');

        // Met à jour les affichages et ajuste les bornes
        minSlider.addEventListener('input', function () {
            minValue.textContent = this.value;
            maxSlider.min = this.value;
            if (parseInt(maxSlider.value) < parseInt(this.value)) {
                maxSlider.value = this.value;
                maxValue.textContent = this.value;
            }
        });

        maxSlider.addEventListener('input', function () {
            maxValue.textContent = this.value;
        });
    </script>
HTML;
}
