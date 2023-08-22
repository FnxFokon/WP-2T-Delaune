<?php
// Certaines versions de WP n'arrive pas à extends la class WP
// Pour ce cas on charge manuellement la class WP_List_Table
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    // ABSPATH recupère le chemin jusqu'à la racine du projet
}

require_once plugin_dir_path(__FILE__) . "/service/Club_Database_service.php";
// on require notre fichier pour pouvoir communiquer avec notre BDD

class Club_Liste extends WP_List_Table //Permet de créer une page de liste
{
    private $dal;

    public function __construct($args = array()) //on déclare le construct
    {
        parent::__construct([
            'singular' => __("Club"), //on redefinie le nom au singulier
            'plural' => __("Clubs") //on redefinie le nom au pluriel
        ]);

        $this->dal = new Club_Database_service(); //on implémente la Class
    }

    public function prepare_items() // fonction native de WP pour préparer notre liste
    {
        //ici on prépare toutes les variables dont on aura besoin
        $columns = $this->get_columns(); // On récupère les colonnes
        $hidden = $this->get_hidden_columns(); //on ajoute cette variable si on veut masquer des colonnes
        $sortable = $this->get_sortable_columns(); //permet de trier les colonnes
        //pagination
        $perPage = $this->get_items_per_page('club_per_page', 10); //pour afficher un nombre de résultats par page
        $currentPage = $this->get_pagenum(); // pour savoir dans quelle page on est
        //les donnés
        $data = $this->dal->findAll(); //pour récupérer toutes les infos de la DB
        $totalPage = count($data); //pour savoir le nombre de ligne de $data
        // tri
        usort($data, array(&$this, 'usort_reorder')); //&$this => pour faire référence à notre class modifié par le tri
        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->set_pagination_args([ //on redefinit les valeurs de la pagination
            'total_items' => $totalPage,
            'per_page' => $perPage
        ]);

        $this->_column_headers = [$columns, $hidden, $sortable]; //permet de construire les titre de colonnes
        $this->items = $paginationData; // permet d'alimenter les champs
    }

    public function get_columns() //pour surcharger get_columns
    {
        $columns = [
            'id' => 'id',
            'nom' => 'Nom',
            'email' => 'Email',
            'telephone' => 'Téléphone',
            'rue' => 'Rue',
            'ville' => 'Ville',
            'code' => 'Code postale',
            'categorie' => 'Domaine de prédilection',
            'competition' => 'Participe aux compétitions'
        ];
        return $columns;
    }

    //function supplémentaire pour masquer des colonnes
    public function get_hidden_columns()
    {
        return []; // on retourne un tableau vide car on veut afficher toutes les colonnes
        // si on voulais masquer des colonnes issu de la BDD on les déclare ici
    }

    //permet de remplir les champs suivant le nom de colonne
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'nom':
            case 'email':
            case 'telephone':
            case 'rue':
            case 'ville':
            case 'code':
            case 'categorie':
            case 'competition':
                return $item->$column_name;
                break;
            default:
                return print_r($item, true);
        }
    }

    //permet d'affilier les champs que l'on souhaite trier
    public function get_sortable_columns()
    {
        $sortable = array(
            'id' => array('id', true),
            'nom' => array('nom', true),
            'email' => array('email', true),
            'telephone' => array('telephone', true),
            'rue' => array('rue', true),
            'ville' => array('ville', true),
            'code' => array('code', true),
            'categorie' => array('categorie', true),
            'competition' => array('competition', true)
        );
        return $sortable;
    }

    //fonction pour le tri 
    public function usort_reorder($a, $b)
    {
        //Si je passe un paramètre de tri dans l'URL 
        // sinon je tri par id par defaut
        $orderBy = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'id';
        //idem pour l'ordre de tri
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
        $result = strcmp($a->$orderBy, $b->$orderBy); // on compare la string de a avec la string de b
        return ($order === 'asc') ? $result : -$result; // si order === asc on retourne le resultat sinon l'inverse du resultat
    }
}
