<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $table_name = NULL;
    protected $primary_key = NULL;

    public function __construct($table_name = NULL, $primary_key = NULL) {
        parent::__construct();
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
    }

    public function select($select, $quote = true) {
        $this->db->select($select, $quote);
        return $this;
    }

    public function order_by($field, $type = 'ASC') {
        $this->db->order_by($field, $type);
        return $this;
    }

    public function where_in($field, $values) {
        $this->db->where_in($field, $values);
        return $this;
    }

    public function getAll($table_name = '', $perpage = NULL, $offset = NULL) {
        if ($table_name == '')
            $table_name = $this->table_name;

        return (is_null($perpage) or is_null($offset)) ? $this->db->get($table_name)->result() : $this->db->get($table_name, $perpage, $offset)->result();
    }

    public function getWhere($where, $table_name = '', $perpage = NULL, $offset = NULL) {
        if ($table_name == '')
            $table_name = $this->table_name;

        return (is_null($perpage) or is_null($offset)) ? $this->db->get_where($table_name, $where)->result() : $this->db->get_where($table_name, $where, $perpage, $offset)->result();
    }

    public function getOneWhere($where, $table_name = '', $object = TRUE) {
        if ($table_name == '')
            $table_name = $this->table_name;

        return $object ? $this->db->get_where($table_name, $where)->row()
                       : $this->db->get_where($table_name, $where)->row_array();
    }

    public function getFromId($id, $primary_key = '', $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        if ($primary_key == '')
            $primary_key = $this->primary_key;
        return $this->db->get_where($table_name, array($primary_key => $id))->row();
    }

    public function countAll($table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;

        return $this->db->count_all($table_name);
    }

    public function countWhere($where, $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;

        return $this->db->where($where)->count_all_results($table_name);
    }

    public function insertRow($data, $table_name = '', $insert_id = true) {
        if ($table_name == '')
            $table_name = $this->table_name;
        if (!$this->db->insert($table_name, $data))
            return false;
        return ($insert_id) ? $this->db->insert_id() : true;
    }

    public function insertRows($data, $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        return @$this->db->insert_batch($table_name, $data);
    }

    public function updateRow($id, $data, $primary_key = '', $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        if ($primary_key == '')
            $primary_key = $this->primary_key;
        $this->db->where($primary_key, $id);
        return $this->db->update($table_name, $data);
    }

    public function updateRows($data, $primary_key = '', $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        if ($primary_key == '')
            $primary_key = $this->primary_key;
        @$this->db->update_batch($table_name, $data, $primary_key);
    }

    public function updateWhere($where, $data, $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        return $this->db->where($where)->update($table_name, $data);
    }

    public function deleteRow($id, $primary_key = '', $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        if ($primary_key == '')
            $primary_key = $this->primary_key;
        return $this->db->delete($table_name, array($primary_key => $id));
    }

    public function deleteRows($ids, $primary_key = '', $table_name = '') {
        if (!is_array($ids))
            return false;

        if ($table_name == '')
            $table_name = $this->table_name;
        if ($primary_key == '')
            $primary_key = $this->primary_key;
        foreach ($ids as $id) {
            $this->db->or_where($primary_key, $id);
        }
        return $this->db->delete($table_name);
    }

    public function deleteWhere($where, $table_name = '') {
        if ($table_name == '')
            $table_name = $this->table_name;
        return $this->db->where($where)->delete($table_name);
    }
  
}