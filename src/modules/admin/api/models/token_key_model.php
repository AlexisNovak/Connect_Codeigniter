<?php

  class Token_key_model extends MY_Model {

       var $joins = array();

       public function __construct() {
            parent::__construct();
            $this->prefix = '';
            $this->_TABLES = array('TOKEN_KEY' => $this->prefix . 'token_key');
            //$this->_TABLES=array('CONTACT_LIST'=>$this->prefix.'contact_list');
            $this->_JOINS = array('KEY' => array('join_type' => 'LEFT', 'join_field' => 'join1.id=join2.id',
                    'select' => 'field_names', 'alias' => 'alias_name'),
            );
       }

       public function insert($table_name, $data) {
            return $this->db->insert($table_name, $data);
       }

       public function getuser($token) {
            return $this->db->from('token_key')->where('token', $token)->get()->row();
       }

//       function update_username($token, $new_username) {
//            return $this->db->update('token_key', array('username' => $new_username), array('token' => $token));
//       }
//
//       function update_password($token, $userpass) {
//            return $this->db->update('token_key', array('password' => md5($userpass)), array('token' => $token));
//       }

       public function getTokenKeys($where = NULL, $order_by = NULL, $limit = array('limit' => NULL, 'offset' => '')) {
            $fields = 'token_key.*';

            foreach ($this->joins as $key):
                 $fields = $fields . ',' . $this->_JOINS[$key]['select'];
            endforeach;

            $this->db->select($fields);
            $this->db->from($this->_TABLES['TOKEN_KEY'] . ' token_key');

            foreach ($this->joins as $key):
                 $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'], $this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
            endforeach;

            if (!is_null($where)) {
                 $this->db->where($where);
            }

            if (!is_null($order_by)) {
                 $this->db->order_by($order_by);
            }
            if (!is_null($limit['limit'])) {
                 $this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : ''));
            }
            return $this->db->get();
       }

       public function getContactList($where = NULL, $order_by = NULL, $limit = array('limit' => NULL, 'offset' => '')) {
            $fields = 'contact_list.contact_name,contact_list.contact_number';

            foreach ($this->joins as $key):
                 $fields = $fields . ',' . $this->_JOINS[$key]['select'];
            endforeach;

            $this->db->select($fields);
            $this->db->from('contact_list');

            if (!is_null($where)) {
                 $this->db->where($where);
            }

            if (!is_null($order_by)) {
                 $this->db->order_by($order_by);
            }
            if (!is_null($limit['limit'])) {
                 $this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : ''));
            }
            return $this->db->get();
       }

       public function countTokenKeys($where = NULL) {
            $this->db->select('count(*) as rows');
            $this->db->from($this->_TABLES['TOKEN_KEY'] . ' token_keys');

            foreach ($this->joins as $key):
                 $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'], $this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
            endforeach;
            if (!is_null($where)) {
                 $this->db->where($where);
            }
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                 $row = $result->row_array();
                 return $row['rows'];
            }
            return 0;
       }
       public function get_token($user_id){
            return $this->db->from('token_key')->where('user_id', $user_id)->get()->row();
       }
  }
  