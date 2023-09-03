<?php get_header() ?>

<!-- Vérifier que l'on est bien dans un produit de la boutique -->
<?php

//     //afficher ses catégories
//     echo "<div class='product-block'>";
//     echo "<h2>Catégories:</h2>";
//     $categories = get_the_terms(get_the_ID(), 'product_cat');
//     // Affichage des catégories si elles existent
//     if ($categories) {
//         echo "<ul>";
//         foreach ($categories as $category) {
//             echo "<li>" . $category->name . "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "Aucune catégorie assignée.";
//     }
//     echo "</div>";

//     //afficher ses tags s'il en a
//     $tags = get_the_terms(get_the_ID(), 'product_tag');
//     echo "<div class='product-block'>";
//     echo "<h2>Tags:</h2>";
//     // Affichage des tags si ils existent
//     if ($tags) {
//         echo "<ul>";
//         foreach ($tags as $tag) {
//             echo "<li>" . $tag->name . "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "Aucun tag assigné.";
//     }


//     //ajouter formulaire pour laisser un avis
//     //on utilise la fonction comments_template()
//     echo "<div class='product-block'>";
//     echo "<h2>Laisser un avis:</h2>";
//     comments_template();
//     echo "</div>";

//     //affichage des avis
//     echo "<div class='product-block'>";
//     echo "<h2>Avis:</h2>";
//     //on récupère les avis du produit
//     $comments = get_comments(array(
//         'post_id' => get_the_ID(),
//         'status' => 'approve'
//     ));
//     //on affiche les avis
//     if ($comments) {
//         echo "<ul>";
//         foreach ($comments as $comment) {
//             echo "<li>";
//             echo "<strong>" . $comment->comment_author . "</strong>";
//             echo "<br>";
//             echo $comment->comment_content;
//             echo "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "Aucun avis pour ce produit.";
//     }
//     echo "</div>";

//     //affichage des produits similaires
//     echo "<div class='product-block'>";
//     echo "<h2>Produits similaires:</h2>";
//     //on récupère les produits similaires
//     $related = wc_get_related_products(get_the_ID(), 4);
//     //on affiche les produits similaires
//     if ($related) {
//         echo "<ul>";
//         foreach ($related as $product) {
//             echo "<li>";
//             echo "<a href='" . get_permalink($product) . "'>";
//             echo get_the_post_thumbnail($product, 'thumbnail');
//             echo "</a>";
//             echo "<br>";
//             echo "<a href='" . get_permalink($product) . "'>";
//             echo get_the_title($product);
//             echo "</a>";
//             echo "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "Aucun produit similaire.";
//     }
//     echo "</div>";


//     //on récupère la note du produit
//     $rating = get_post_meta(get_the_ID(), '_wc_average_rating', true);
//     //on affiche la note
//     if ($rating) {
//         echo "<p>Note moyenne: $rating</p>";
//     } else {
//         echo "<p>Aucune note pour ce produit.</p>";
//     }
// }

if (is_product()) {
    $stock = get_post_meta(get_the_ID(), '_stock_status', true);
    switch ($stock) {
        case 'instock':
            $label = 'En stock';
            $class = "success";
            break;
        case 'outofstock':
            $label = 'Rupture de stock';
            $class = "danger";
            break;
        case 'onbackorder':
            $label = 'Sur commande';
            $class = "warning";
            break;
        default:
            $label = 'Pas d\'info stock';
            $class = "info";
    }

    //on récupère les avis du produit
    $comments = get_comments(array(
        'post_id' => get_the_ID(),
        'status' => 'approve'
    ));

    //on récupère les produits similaires
    $related = wc_get_related_products(get_the_ID(), 4);

    //on va structurer le squelette de la page produit
?>
    <div class="container_main d-flex flex-column">
        <div class="d-flex ">
            <!-- Détail du produit -->
            <div class="col-sm-4 offset-sm-1">
                <!-- image -->
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium')  ?> " class='img-fluid image_desc' alt="<?php echo get_the_title() ?>">
            </div>
            <div class="d-flex flex-column col-sm-4 offset-sm-1">
                <!-- infos du produit -->
                <div>
                    <!-- titre -->
                    <h2 class="title_prod">
                        <?php the_title() ?>
                    </h2>
                </div>
                <div>
                    <!-- description -->
                    <p class="desc_prod">
                        <?php the_content() ?>
                    </p>
                </div>
                <div class="d-flex align-items-center">
                    <!-- prix + stock -->
                    <div>
                        <!-- prix -->
                        <p class="price_prod">
                            <?php echo wc_price(get_post_meta(get_the_ID(), '_price', true)) ?>
                        </p>
                    </div>
                    <div>
                        <!-- stock -->
                        <span class='badge rounded-pill bg-<?php echo $class ?>'>
                            <?php echo $label ?>
                        </span>
                    </div>
                </div>
                <div>
                    <!-- bouton ajout panier -->
                    <?php
                    if ($stock == 'outofstock') {
                    ?>
                        <button class='btn btn-danger' disabled>Produit épuisé</button>
                    <?php
                    } else {

                    ?>
                        <a href='<?php echo get_permalink(get_option(' woocommerce_cart_page_id')) .
                                        "?add-to-cart=" . get_the_ID() ?>' class='btn btn-color'>
                            Ajouter au panier
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-center mt-5">
            <h2>Laissez nous un avis</h2>
            <!-- Partie commentaire -->
            <div>
                <!-- formulaire avis -->
                <?php comments_template(); ?>
            </div>
            <h2>Avis clients</h2>
            <div class="col-sm-4 container_com">
                <?php
                if ($comments) {
                ?>
                    <ul class="list-group list-group-flush>">
                        <?php
                        foreach ($comments as $comment) { ?>
                            <li>
                                <p class="author_com">
                                    <?php echo $comment->comment_author; ?>
                                </p>
                                <p class="text_com">
                                    <?php echo $comment->comment_content; ?>
                                </p>
                            </li>
                    <?php
                        }
                        echo "</ul>";
                    } else {
                        echo "Aucun avis pour ce produit.";
                    }
                    ?>

            </div>
        </div>
        <div>
            <!-- Partie produit similaire -->
            <h2>Produits similaires:</h2>
            <?php
            if ($related) {
                echo "<ul>";
                foreach ($related as $product) {
                    echo "<li>";
                    echo "<a href='" . get_permalink($product) . "'>";
                    echo "<img src='" . get_the_post_thumbnail_url($product, 'thumbnail') . "' alt='image produit' class='img-fluid'>";
                    echo "</a>";
                    echo "<br>";
                    echo "<a href='" . get_permalink($product) . "'>";
                    echo get_the_title($product);
                    echo "</a>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "Aucun produit similaire.";
            }
            ?>

        </div>
    </div>

<?php
}
?>
<?php get_footer() ?>