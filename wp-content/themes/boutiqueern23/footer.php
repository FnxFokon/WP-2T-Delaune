<footer class="d-flex flex-row justify-content-around p-3 foot-er">
    <?php
    //appelle du menu avec sous-menu
    wp_nav_menu(array(
        "theme_location" => "menu-footer", //on indique le menu à afficher
        "menu_class" => "custom-menu-footer", //ajout de la class pour le css
        "container" => false,
        'walker' => new simple_menu() //récupèration de notre template du menu
    ))

    ?>
</footer>
</body>

</html>