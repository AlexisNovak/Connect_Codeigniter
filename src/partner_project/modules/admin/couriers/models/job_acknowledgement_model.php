<?php

  class Job_acknowledgement_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function acknowledge($data) {
            if ($this->db->insert('job_acknowledgement', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function getwonbid($job_id) {
            return $this->db->select('*')->from('job_acknowledgement')
                            ->where('job_id', $job_id)
                            ->where('is_approved', 0)
                            ->get()
                            ->row();
       }
       public function adjustprice($job_id, $data){
            return $this->db->where('job_id',$job_id)->update('job_acknowledgement',$data);
       }
  }
  