<?php
//si woocommerce n'est pas activé, ne pas afficher le contenu de la page
if (!defined('ABSPATH')) {
    exit;
}

//on récupère le header
get_header();
?>

<!-- on affiche le contenu de la page -->
<div class="woocommerce-products-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                    <h1 class="woocommerce-products-page-title">
                        <?php echo get_the_title(wc_get_page_id('shop')) ?>
                    </h1>
                <?php endif; ?>
                <div class="d-flex flex-wrap">
                    <?php if (have_posts()) {
                        woocommerce_product_loop_start();
                        while (have_posts()) {
                            the_post();
                            //on affiche le rendu de la carte 
                    ?>
                            <div class="product-container">
                                <?php wc_get_template_part('content', 'product'); ?>
                            </div>
                    <?php
                        }
                        woocommerce_product_loop_end();
                    } else {
                        //si je n'ai pas de produits à afficher
                        echo "<p>Aucun produit à afficher</p>";
                    }




                    /** 
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //on récupère le footer -->
<?php get_footer(); ?>