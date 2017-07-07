<?php

  class Surcharge_items_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_items($servie_id) {
            return $this->db->select('id, item_name as name, unit_price as price, remarks')
                            ->from('surcharge_items')
                            ->where('service_id', $servie_id)
                            ->get()
                            ->result();
       }

       public function add_item($data) {
            if ($this->db->insert('surcharge_items', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function add_batch_item($data) {
            return $this->db->insert_batch('surcharge_items', $data);
       }

       public function update_item($id, $data) {
            $data['updated_on'] = date('y-m-d H:i:s');
            return $this->db->update('surcharge_items', $data, array('id' => $id));
       }

       public function delete_item($id) {
            return $this->db->where('id', $id)->delete('surcharge_items');
       }

  }
  