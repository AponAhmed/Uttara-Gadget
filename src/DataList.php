<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of DataList
 *
 * @author Mahabub
 */
class DataList
{

    public $db;
    /**
     * Custom Table Name
     * @var string
     */
    public string $table;

    /**
     * Columns
     * Example: ['db-field'=>'Table Header']
     * @var array
     */
    public array $columns;

    public $primary_key = 'id';
    public $selectedFields = "*";
    /**
     * Column Value Filter
     * Example: ['db-field'=> calback]
     * @var array
     */
    public array $filter;
    private $page = '';

    public int $currentPage = 1;
    public int $itemPerPage = 15;
    public int $totalItems = 0;
    public int $totalPages = 0;
    private $offset = 0;
    private $conditionStr = "1";
    public $actions = [];

    public $conditions = [];
    private $data;


    public function __construct($table = '', $pageUrl = '', $itemPerPage = 15)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $wpdb->prefix . $table;
        $this->page = $pageUrl;
        $this->columns = [];
        $this->filter = [];


        $this->itemPerPage = $itemPerPage;
        if (isset($_GET['paged']) && !empty($_GET['paged'])) {
            $this->currentPage = intval($_GET['paged']);
            $this->offset = ($this->currentPage - 1) * $this->itemPerPage;
        }
        $this->actions = [
            'edit' => [
                'label' => 'Edit',
                'atts' => [
                    'class' => 'edit popup',
                    'href' => "new-$pageUrl",
                    'data-id' => "%id",
                ]
            ],
            'delete' => [
                'label' => 'Delete',
                'atts' => [
                    'class' => 'delete-data',
                    'href' => "javascript:void(0)",
                    'data-id' => "%id",
                    'data-type' => $pageUrl,
                    'onclick' => 'deleteData(this)'
                ]

            ],
        ];
    }

    function addAction($key, $action)
    {
        $this->actions[$key] = $action;
    }

    /**
     * Set Data table's Visible Column
     * @param array $column
     */
    public function setColumn(array $column)
    {
        $this->columns = $column;
    }

    function conditionBuilder()
    {
        $conditionStrArr = [];
        if (count($this->conditions) > 0) {
            foreach ($this->conditions as $key => $value) {
                //var_dump($value);
                $conditionStrArr[] .= $value[0] . $value[1] . $value[2];
            }
        }
        $this->conditionStr = implode(" AND ", $conditionStrArr);
    }

    /**
     * Get Data From Data Table
     */
    public function getData()
    {
        $this->conditionBuilder();
        $sql = "SELECT $this->selectedFields FROM $this->table WHERE $this->conditionStr ORDER BY $this->primary_key DESC LIMIT $this->offset,$this->itemPerPage";
        //var_dump($sql);
        $this->data = $this->db->get_results($sql);
    }

    /**
     * Paginations for Data Table
     */
    public function paginate()
    {
        $this->conditionBuilder();
        $this->totalItems = $this->db->get_var("SELECT COUNT(*) as total FROM $this->table WHERE $this->conditionStr");

        if ($this->totalItems > 0 && $this->totalItems > $this->itemPerPage) {
            $this->totalPages = ceil($this->totalItems / $this->itemPerPage);
        } else {
            $this->totalPages = 1;
        }

        $htm = "<div class=\"data-pagination\">";
        if ($this->totalPages > 1) {
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $link = "admin.php?page=$this->page&paged=$i";
                $clas = "";
                if ($this->currentPage == $i) {
                    $clas = 'class="active"';
                }
                $htm .= "<a $clas href=\"$link\">$i</a>";
            }
        }
        $htm .= "</div>";
        return $htm;
    }
    /**
     * Generate Table Header
     */
    public function generateThead()
    {
        if (count($this->columns) > 0) {
            $htm = "<thead><tr>";
            foreach ($this->columns as $key => $val) {
                $htm .= "<th>$val</th>";
            }
            if (count($this->actions) > 0) {
                $htm .= "<th></th>";
            }
            $htm .= "</tr></thead>";
            return $htm;
        }
    }

    function actions($data)
    {
        $htm = "<div class=\"data-actions\">";
        foreach ($this->actions as $action) {

            $atts = isset($action['atts']) ? $action['atts'] : [];
            foreach ($data as $key => $val) {
                foreach ($atts  as $param => $pval) {
                    $atts[$param] = str_replace("%$key", $val, $pval);
                }
            }

            $attsArr = [];
            foreach ($atts as $name => $val) {
                $attsArr[] = "$name=\"$val\"";
            }
            $attsStr = implode(" ", $attsArr);
            $htm .= "<a $attsStr >$action[label]</a>";
        }
        $htm .= "</div>";
        return $htm;
    }

    public function generateTbody()
    {
        $htm = "<tbody>";
        if (count($this->data) > 0) {
            foreach ($this->data as  $row) {
                $htm .= "<tr>";
                foreach ($this->columns as $key => $val) {
                    $value = $row->$key;
                    $htm .= "<td title=\"$val\">$value</td>";
                }
                if (count($this->actions) > 0) {
                    $htm .= "<td title=\"Actions\">";
                    $htm .= $this->actions($row);
                    $htm .= "</td>";
                }
                $htm .= "</tr>";
            }
        }
        $htm .= "</tbody>";
        return $htm;
    }

    public function get()
    {
        $tt = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
        $tt .= $this->generateThead();
        $tt .= $this->generateTbody();
        $tt .= '</table>';
        return $tt;
    }
}
