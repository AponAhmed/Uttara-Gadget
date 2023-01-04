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

    public int $currentPage = 1;
    public int $itemPerPage = 1;
    private $offset = 0;
    private $conditionStr = "1";

    public $conditions = [];
    private $data;

    public function __construct($table)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $wpdb->prefix . $table;
        $this->columns = [];
        $this->filter = [];

        if (isset($_GET['paged']) && !empty($_GET['paged'])) {
            $this->currentPage = intval($_GET['paged']);
            $this->offset = ($this->currentPage - 1) * $this->itemPerPage;
        }
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
        if (count($this->conditions) > 0) {
            foreach ($this->conditions as $key => $value) {
                $this->conditionStr .= $value[0] . $value[1] . $value[2];
            }
        }
    }

    /**
     * Get Data From Data Table
     */
    public function getData()
    {
        $this->data = $this->db->get_results("SELECT $this->selectedFields FROM $this->table WHERE $this->conditionStr ORDER BY $this->primary_key DESC LIMIT $this->offset,$this->itemPerPage");
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
            $htm .= "</tr></thead>";
            return $htm;
        }
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
