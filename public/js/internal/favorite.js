/**
 * Page load
 */
$( function() {

    // URL du serveur
    const server_url = '/public/index.php';

    // évènement associé aux boutons "add product to cart"
    $('button.add_favorite').click(function() {
        const param = {
            page        : "favorite_ajax",
            action		: "add_favorite",
            art_id      : $(this).attr('for')
        };
        $.post( server_url, param, display_cart, "json" );
    });

    // évènement associé aux boutons "delete product from cart"
    $('button.del_favorite').click(function() {
        const param = {
            page        : "favorite_ajax",
            action		: "del_favorite",
            art_id      : $(this).attr('for')
        };
        $.post( server_url, param, display_cart, "json" );
    });

    // évènement associé au bouton "effacer panier"
    $('button.del_cart').click(function() {
        if(confirm( "Are you sure ?"))
        {
            const param = {
                action : "del_all_favorites",
            };
            $.post( server_url, param, display_cart, "json" );
        }
    });

    // afficher le panier au chargement de la page
    const param = {
        page        : "favorite_ajax",
    };
    $.post( server_url, param, display_cart, "json" );

});

/*
 * Mise à jour du panier à l'écran
 */
function display_cart(cart_data)
{
    console.log(cart_data);

    let s = '';
    $.each( cart_data, function( i, v )
    {
        s += "<li>" + v + "</li>"
    });
    $("ul#panier_contenu").html(s);

    // activer / désactiver les boutons add/del correspondant

    // on met tous les boutons "retirer favoris" à display:none
    $('button.del_favorite').css('display','none');
    // on met tous les boutons "ajouter favoris" à display:block
    $('button.add_favorite').css('display','block');

    // on inverse pour les articles du panier
    $.each( cart_data, function( k, id )
    {
        // produit in panier => cacher "add', montrer "del"
        $('button.add_favorite[for="'+id+'"]').css('display','none');
        $('button.del_favorite[for="'+id+'"]').css('display','block');
    });

}

