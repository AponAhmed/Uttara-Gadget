<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of DataList
 *
 * @author Mahabub
 */
class DataList
{

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

    /**
     * Column Value Filter
     * Example: ['db-field'=> calback]
     * @var array
     */
    public array $filter;

    public function __construct($table)
    {
        global $wpdb;
        $this->table = $wpdb->prefix . $table;
        $this->columns = [];
        $this->filter = [];
    }

    /**
     * Set Data table's Visible Column
     * @param array $column
     */
    public function setColumn(array $column)
    {
        $this->columns = $column;
    }

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

    public function get()
    {
        $tt = '<table class="wp-list-table widefat fixed striped table-view-list posts">';
        $tt .= $this->generateThead();
        $tt .= '</table>';
        return $tt;
    }
}
