<?php

  class Remarks_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       function addRemark($data) {
            return $this->db->insert('reviewer_remarks', $data);
       }

       function getRemarks($ref) {
            return $this->db->select("R.remark,DATE_FORMAT(R.added,'%d-%m-%Y') as date,U.username", FALSE)
                            ->from("reviewer_remarks as R")
                            ->join("member as U", "R.reviewer=U.id", "left")
                            ->where("R.ref_no", $ref)
                            ->get()
                            ->result();
       }

  }
  