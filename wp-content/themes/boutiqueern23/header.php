<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name') ?></title>
    <!-- import de bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- on importe notre style.css -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"; ?>">

</head>

<body>
    <header class="text-white p-3 site-header head-er">
        <a class="text-light" href="<?php echo get_bloginfo('wpurl') ?>">
            <h2><?php echo get_bloginfo('name') ?></h2>
        </a>

        <!-- on affiche le menu -->
        <?php
        //appelle du menu avec sous-menu
        wp_nav_menu(array(
            "theme_location" => "menu-sup", //on indique le menu à afficher
            "menu_class" => "custom-menu", //ajout de la class pour le css
            "container" => false,
            'walker' => new Depth_menu() //récupèration de notre template du menu
        ))

        ?>
    </header>