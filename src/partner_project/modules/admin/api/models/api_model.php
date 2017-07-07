<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends MY_Model {

    public function __construct() {
        parent::__construct('be_users', 'user_id');
    }
    
    public function getNotification($from_date = false, $to_date = false) {
       
        $where = array();
        if($from_date) {
            $where['post_date >='] = $from_date;
        }
        if($to_date) {
            $where['post_date <='] = $to_date;
        }

        return $this->db->select('*')->from('event_log')->where($where)->get()->result();
    }

}