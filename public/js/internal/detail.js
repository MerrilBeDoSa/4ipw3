$(function() {
    // Vérifie si l'utilisateur est connecté (méthode plus fiable)
    function isUserLoggedIn() {
        // 1. Vérifie la présence du cookie de session
        const hasSessionCookie = document.cookie.split(';').some(
            item => item.trim().startsWith('PHPSESSID=')
        );

        // 2. Vérifie si un élément du DOM indique la connexion
        const hasLoggedInElement = $('.user-status.logged-in').length > 0;

        return hasSessionCookie || hasLoggedInElement;
    }

    // Cache les détails déjà chargés
    const loadedDetails = new Set();

    // Fonction pour charger les détails
    function loadArticleDetail($article) {
        const href = $article.find('a').attr('href');
        const artIdMatch = href && href.match(/art_id=(\d+)/);

        if (!artIdMatch) return;

        const artId = artIdMatch[1];

        // Évite de recharger les détails déjà affichés
        if (loadedDetails.has(artId)) {
            $article.find('.article-detail-overlay').show();
            return;
        }

        $.ajax({
            url: 'index.php',
            data: {
                page: 'article_detail',
                ajax: 1,
                art_id: artId
            },
            success: function(html) {
                if (html.trim()) {
                    const $overlay = $('<div class="article-detail-overlay">').html(html);
                    $article.append($overlay);
                    loadedDetails.add(artId);
                }
            },
            error: function() {
                console.error('Erreur de chargement des détails');
            }
        });
    }

    function unloadArticleDetail($article) {
        $article.find('.article-detail-overlay').hide();
    }

    // Initialisation des événements
    function initHoverEvents() {
        if (!isUserLoggedIn()) {
            return;
        }

        // Événements pour les connectés
        $(document)
            .on('mouseenter', '.wsj-article-item, .wsj-featured-item', function() {
                loadArticleDetail($(this));
            })
            .on('mouseleave', '.wsj-article-item, .wsj-featured-item', function() {
                unloadArticleDetail($(this));
            });
    }

    // Initialisation
    initHoverEvents();
});