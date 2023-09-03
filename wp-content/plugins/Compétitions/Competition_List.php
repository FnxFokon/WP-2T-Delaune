<?php

if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "/wp-admin/includes/class-wp-list-table.php";
}

// On import notre classe de Service
require_once plugin_dir_path(__FILE__) . "service/GIT_Database_Service.php";

class Competition_List extends WP_List_Table
{
    private $dal;

    // On définit le constructeur
    public function __construct()
    {
        parent::__construct(
            array(
                "singular" => __("Compétitions"),
                "plural" => __("Compétition")
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
        $perPage = $this->get_items_per_page("competition_per_page", 4);
        $currentPage = $this->get_pagenum();

        // On attribut les data
        $data = $this->dal->findAllCompetition();
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
            'label' => 'Label',
            'date_debut' => 'Début',
            'date_fin' => 'Fin'
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
    public function get_competition_label($competition_id)
    {
        $competition = $this->dal->get_competition_by_id($competition_id);
        return $competition ? $competition->label : '';
    }

    // On choisi la colonne par default
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'label':
            case 'date_debut':
            case 'date_fin':
                return $item->$column_name;
            default:
                return print_r($item, true);
        }
    }

    // On récupere la colonne
    public function get_sortable_columns()
    {
        $sortable = [
            'id' => ['id', true],
            'label' => ['label', true],
            'date_debut' => ['date_debut', true],
            'date_fin' => ['date_fin', true]
        ];
        return $sortable;
    }

    // Génere les colonnes pour le tableau
    public function column_cb($item)
    {
        $item = (array) $item;

        return sprintf(
            '<input type="checkbox" name="delete-competition[]" value="%s" />',
            $item["id"]
        );
    }

    // On récupere les actions grouper pour une table
    public function get_bulk_actions()
    {
        $actions = [
            "delete-competition" => __("Delete")
        ];
        return $actions;
    }
}
