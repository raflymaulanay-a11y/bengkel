<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatables extends CI_Model
{
    private $table = "products";
    private $select = "";
    private $where = array();
    private $join = array();
    private $column = array();
    private $result = array();
    private $searchField = NULL;
    private $ordering = array();
    private $group = array();

    public function __construct()
    {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | SET TABLE
    |--------------------------------------------------------------------------
    */

    public function setTable($name)
    {
        $this->table = $name;
    }

    /*
    |--------------------------------------------------------------------------
    | SET SELECT
    |--------------------------------------------------------------------------
    */

    public function setSelect($select)
    {
        $this->select = $select;
    }

    /*
    |--------------------------------------------------------------------------
    | SET JOIN
    |--------------------------------------------------------------------------
    */

    public function setJoin($table, $on, $type = "left")
    {
        $this->join[] = array(
            "table" => $table,
            "on"    => $on,
            "type"  => $type
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SET GROUP
    |--------------------------------------------------------------------------
    */

    public function setGroup($group)
    {
        $this->group[] = $group;
    }

    /*
    |--------------------------------------------------------------------------
    | SET WHERE
    |--------------------------------------------------------------------------
    */

    public function setWhere($key, $value)
    {
        $this->where[$key] = $value;
    }

    /*
    |--------------------------------------------------------------------------
    | SET COLUMN
    |--------------------------------------------------------------------------
    */

    public function setColumn($column = array())
    {
        $this->column = $column;
    }

    /*
    |--------------------------------------------------------------------------
    | SET SEARCH FIELD
    |--------------------------------------------------------------------------
    */

    public function setSearchField($field)
    {
        $this->searchField = $field;
    }

    /*
    |--------------------------------------------------------------------------
    | SET ORDERING
    |--------------------------------------------------------------------------
    */

    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS QUERY
    |--------------------------------------------------------------------------
    */

    private function process()
    {
        /*
        | SELECT
        */

        if ($this->select != "") {
            $this->db->select($this->select, FALSE);
        }

        /*
        | JOIN
        */

        if (!empty($this->join)) {

            foreach ($this->join as $join) {

                $this->db->join(
                    $join["table"],
                    $join["on"],
                    $join["type"]
                );
            }
        }

        /*
        | SEARCH
        */

        if (
            isset($_GET['search']['value']) &&
            $_GET['search']['value'] != "" &&
            $this->searchField
        ) {

            $this->db->like(
                $this->searchField,
                $_GET['search']['value']
            );
        }

        /*
        | ORDERING
        */

        if (
            isset($_GET['order'][0]['column']) &&
            isset($_GET['order'][0]['dir'])
        ) {

            $order_column = (int) $_GET['order'][0]['column'];

            if (
                isset($this->ordering[$order_column]) &&
                $this->ordering[$order_column] != NULL &&
                $this->ordering[$order_column] != ""
            ) {

                $this->db->order_by(
                    $this->ordering[$order_column],
                    $_GET['order'][0]['dir']
                );
            }
        }

        /*
        | GROUP BY
        */

        if (!empty($this->group)) {

            foreach ($this->group as $group) {

                $this->db->group_by($group);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REPLACE COLUMN
    |--------------------------------------------------------------------------
    */

    private function columnReplace($index, $string)
    {
        /*
        | Nomor
        */

        $start = isset($_GET['start'])
            ? (int) $_GET['start']
            : 0;

        $nomor = $index + $start + 1;

        $string = str_replace(
            "<index>",
            $nomor,
            $string
        );

        /*
        | Ambil field
        |
        | Contoh:
        | <get-id>
        | <get-name>
        | <get-stock>
        | <get-nama_kategori>
        */

        preg_match_all(
            "/<get-([A-Za-z0-9_]+)>/",
            $string,
            $matches
        );

        if (!empty($matches[1])) {

            foreach ($matches[1] as $i => $field) {

                $value = "";

                if (
                    isset($this->result[$index]) &&
                    isset($this->result[$index][$field])
                ) {

                    $value =
                        $this->result[$index][$field];
                }

                $string = str_replace(
                    $matches[0][$i],
                    $value,
                    $string
                );
            }
        }

        return $string;
    }

    /*
    |--------------------------------------------------------------------------
    | GET TOTAL ROWS
    |--------------------------------------------------------------------------
    */

    public function get_num_rows()
    {
        /*
        | Jalankan query
        */

        $this->process();

        /*
        | WHERE
        */

        if (!empty($this->where)) {

            $query = $this->db->get_where(
                $this->table,
                $this->where
            );

        } else {

            $query = $this->db->get(
                $this->table
            );
        }

        return $query->num_rows();
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE
    |--------------------------------------------------------------------------
    */

    public function generate()
    {
        /*
        | Jalankan query
        */

        $this->process();

        /*
        | LIMIT
        */

        if (
            isset($_GET['length']) &&
            (int) $_GET['length'] > 0
        ) {

            $length = (int) $_GET['length'];

            $start = isset($_GET['start'])
                ? (int) $_GET['start']
                : 0;

            $this->db->limit(
                $length,
                $start
            );
        }

        /*
        | WHERE
        */

        if (!empty($this->where)) {

            $query = $this->db->get_where(
                $this->table,
                $this->where
            );

        } else {

            $query = $this->db->get(
                $this->table
            );
        }

        /*
        | Cek query
        */

        if (!$query) {

            echo json_encode(array(
                "draw"            => 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array()
            ));

            return;
        }

        /*
        | Ambil hasil
        */

        $this->result =
            $query->result_array();

        /*
        | Response
        */

        $response = array();

        $response["draw"] =
            isset($_GET["draw"])
                ? (int) $_GET["draw"]
                : 0;

        /*
        | Total data
        */

        $response["recordsTotal"] =
            $this->get_num_rows();

        $response["recordsFiltered"] =
            $this->get_num_rows();

        $response["data"] = array();

        /*
        | Isi DataTable
        */

        foreach (
            $this->result as $index => $row
        ) {

            $tmp = array();

            foreach (
                $this->column as $column
            ) {

                $tmp[] =
                    $this->columnReplace(
                        $index,
                        $column
                    );
            }

            $response["data"][] =
                $tmp;
        }

        /*
        | Output JSON
        */

        $this->output
            ->set_content_type("application/json")
            ->set_output(
                json_encode($response)
            );
    }
}