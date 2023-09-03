<?php get_header();
//affichage de la page d'accueil
//si je suis dans la page d'accueil, je l'affiche
if (is_page()) {
    //on affiche le contenu de la page d'accueil
    //si j'ai des posts ou pages à afficher
    if (have_posts()) {
        //je boucle dessus
        while (have_posts()) {
            //je récupère les données du post (ou de la page)
            the_post();
            //j'affiche le contenu de la page
            the_content();
        }
    }
} elseif (is_shop()) {
    //sinon si on est dans la boutique
    //je récupère le contenu general de la page
    wc_get_template_part("archive", "product");
} else {
}

?>




<?php get_footer(); ?>