<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of payment_model
   *
   * @author nice
   */
  class feedback_model extends MY_Model {

       //put your code here
       public function __construct($table_name = NULL, $primary_key = NULL) {
            parent::__construct($table_name, $primary_key);
       }

       function get_feedback_count($filter) {
            $count = $this->db->select('R.*')
                            ->from('courier_rating AS R')
                            ->join('member AS M', 'M.id=R.user_id', "LEFT")
                            ->join('couriers AS C', 'C.courier_id=R.courier_id', "LEFT")
                            ->join('courier_service AS CS', 'CS.id=R.service_id', "LEFT")
                            ->group_by('R.id')
                            ->get()->num_rows();
            return $count;
       }

       function get_feedback($perpage, $filter, $start) {
            $data = $this->db->select('R.*, UNIX_TIMESTAMP(R.timestamp) AS timedata,C.fullname AS courier_name,M.username AS customer_name,CS.display_name AS service_name')
                    ->from('courier_rating AS R')
                    ->join('member AS M', 'M.id=R.user_id', "LEFT")
                    ->join('couriers AS C', 'C.courier_id=R.courier_id', "LEFT")
                    ->join('courier_service AS CS', 'CS.id=R.service_id', "LEFT")
                    ->limit($perpage, $start)
                    ->get()
                    ->result();
            return $data;
       }

       function get_single_feedback($id) {
            $data = $this->db->select('R.id,(R.score/2) AS rating,C.fullname AS courier_name,M.username AS customer_name,CS.display_name AS service_name,R.review')
                    ->from('courier_rating AS R')
                    ->join('member AS M', 'M.id=R.user_id', "LEFT")
                    ->join('couriers AS C', 'C.courier_id=R.courier_id', "LEFT")
                    ->join('courier_service AS CS', 'CS.id=R.service_id', "LEFT")
                    ->where('R.id', $id)
                    ->get()
                    ->row();
            return $data;
       }

  }
  