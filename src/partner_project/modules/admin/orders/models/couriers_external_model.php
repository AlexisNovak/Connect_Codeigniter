<?php

  class Couriers_external_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_couriers($user_id, $serach = NULL) {
            if ($serach != NULL) {
                 $this->db->where('(email LIKE \'%' . $serach . '%\')');
            }
            return $this->db->select('DISTINCT email', FALSE)
                            ->from('couriers_external')
                            ->where('user_id', $user_id)
                            ->limit(10)
                            ->get()
                            ->result();
       }

       public function add_job($data, $job_id) {
            if ($this->get_by_job_id($job_id)) {
                 $this->db->where('job_id', $job_id);
                 $this->db->update('couriers_external', $data);
            } else {
                 $data['permalink'] = random_string('alnum', 32);
                 $data['job_id'] = $job_id;
                 $this->db->insert('couriers_external', $data);
            }
            return;
       }

       public function update_job($data, $job_id) {
            return $this->db->update('couriers_external', $data, array('job_id' => $job_id));
       }

       public function get_by_job_id($job_id) {
            return $this->db->from('couriers_external')->where('job_id', $job_id)->get()->row();
       }

  }
  