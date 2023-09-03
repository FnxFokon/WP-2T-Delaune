<?php

if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "/wp-admin/includes/class-wp-list-table.php";
}

// On import notre classe de Service
require_once plugin_dir_path(__FILE__) . "service/GIT_Database_Service.php";

class Joueur_List extends WP_List_Table
{
    private $dal;

    // On fait notre constructeur
    public function __construct()
    {
        parent::__construct(
            array(
                "singular" => __("Joueur"),
                "plural" => __("Joueurs")
            )
        );

        // On instancie le service
        $this->dal = new GIT_Database_Service();
    }

    // On prepare les items
    public function prepare_items()
    {
        // On attribut les variables
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        // On attribut la page
        $perPage = $this->get_items_per_page("joueurs_per_page", 8);
        $currentPage = $this->get_pagenum();

        // On attribut les data
        $data = $this->dal->findAllJoueur();
        $totalPage = count($data);

        // On trie
        usort($data, array(&$this, "usort_reorder")); //on va trier les données
        $paginationData = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        // On indique la valeurs de la pagination
        $this->set_pagination_args([
            "total_items" => $totalPage,
            "per_page" => $perPage
        ]);

        // On définit le titre des colomn
        $this->_column_headers = array($columns, $hidden, $sortable);

        // On injecte les données
        $this->items = $paginationData;
    }

    // On récupere les columns
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'pseudo' => 'Pseudo',
            'email' => 'Email',
            'competition' => 'Compétition'
        ];
        return $columns;
    }

    // On récupere les columns caché
    public function get_hidden_columns()
    {
        return ['id' => 'id'];
    }

    // On fait une fonction pour trier en fonction de l'id
    public function usort_reorder($a, $b)
    {
        $orderBy = (!empty($_GET["orderby"])) ? $_GET["orderby"] : "id";
        $order = (!empty($_GET["order"])) ? $_GET["order"] : "desc";
        $result = strcmp($a->$orderBy, $b->$orderBy);
        return ($order === "asc") ? $result : -$result;
    }

    // On récupére le label de la compétition
    private function get_competition_label($competition_id)
    {
        $competition = $this->dal->get_competition_by_id($competition_id);
        return $competition ? $competition->label : '';
    }

    // On choisi la colonne par default
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'nom':
            case 'prenom':
            case 'pseudo':
            case 'email':
                return $item->$column_name;
            case 'competition':
                return $this->get_competition_label($item->competition_id);
                break;

            default:
                return print_r($item, true);
        }
    }

    // On récupere la colonne
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'nom' => ['nom', true],
            'prenom' => ['prenom', true],
            'pseudo' => ['pseudo', true],
            'email' => ['email', true],
            'competition' => ['competition', true]
        ];
        return $sortable;
    }

    // Génere les colonnes pour le tableau
    public function column_cb($item)
    {
        $item = (array) $item;

        return sprintf(
            '<input type="checkbox" name="delete-joueur[]" value="%s" />',
            $item["id"]
        );
    }

    // On récupere les actions grouper pour une table
    public function get_bulk_actions()
    {
        $actions = [
            "delete-joueur" => __("Delete")
        ];
        return $actions;
    }
}
