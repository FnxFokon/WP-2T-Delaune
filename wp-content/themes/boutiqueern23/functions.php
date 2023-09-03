<?php
//1. On enregistre le menu
function register_menu()
{
    register_nav_menus(
        array(
            'menu-sup' => __('Main menu'), // __() permet de traduire le mot dans les différents langages
            'menu-footer' => __('Footer menu')
        )
    );
    add_theme_support('post-thumbnails');
}
// === On initialise le menu ===
add_action('init', 'register_menu');

// On design un menu qui gère les sous-menus
class depth_menu extends Walker_Nav_Menu
{

    // Fonction pour démarrer le niveau de menu
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        // On récupère les titres
        $title = $data_object->title;

        // On récupère les liens
        $permalink = $data_object->url;

        // On gère l'indentation des liens
        // Signification de "\t" (hex 09) = tabulation
        // $indentation = str_repeat("\t", $depth);
        // + Classes CSS à ajouter
        $classes = empty($data_object->classes) ? array() : (array) $data_object->classes;
        $class_name = join(' ', apply_filters('nav_menu_css_array', array_filter($classes), $data_object));

        if ($depth > 0) {
            $output .= '<li class="' . esc_attr($class_name) . '">';
        } else {
            $output .= '<li class="' . esc_attr($class_name) . '">';
        }

        $output .= '<a href="' . $permalink . '">' . $title . '</a>';

        // On ajoute les categories des produits au hover du menu "Shop"
        if ($title === 'Shop') {
            $output .= '<ul class="sub-menu">';

            $terms = get_terms('product_cat');
            foreach ($terms as $term) {
                $term_link = get_term_link($term);
                if (is_wp_error($term_link)) {
                    continue;
                }

                // Rendu HTML
                $output .= '<li><a href="' . esc_url($term_link) . '">' . $term->name . '</a></li>';
            }

            // On ferme le ul
            $output .= '</ul>';
        }
    }
}

//Menu simple pour le footer
class Simple_menu extends Walker_Nav_Menu
{
    //on va appeler et surcharger la méthode start_el()
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        //1) on récupère les data du menu dans des variables
        $title = $data_object->title; //récupère les titres du menu
        $permalink = $data_object->url; //récupère les liens du menu

        //2) on construit le template
        $output .= "<div class='nav-item d-flex flex-row p-10 '>"; //on ouvre une div
        $output .= "<a class='nav-link text-light m-1 custom_a' href='$permalink'>"; //on ouvre un a et on lui donne $permalien en href
        $output .= $title; //on affiche le titre
        $output .= "</a>"; //on ferme le a
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</div>"; //on ferme la div
    }
}

//ajout de la fonctionnalité 'logo' pour changer l'image du header
function custom_header_logo()
{
    //on définit un tableau de paramètres
    $args = [
        "default-image" => get_template_directory_uri() . "/img/banniere.png",
        "default-text-color" => "000",
        "width" => 1000,
        "height" => 250,
        "flex-width" => true,
        "flex-height" => true
    ];
    //add_theme_support: 1er argument: le nom de la fonctionnalité, 
    //2ème argument: le tableau de paramètres
    add_theme_support("custom-header", $args);
}
//add_action: 1er argument: le hook, 2ème argument: le nom de la fonction
add_action("after_setup_theme", "custom_header_logo");
