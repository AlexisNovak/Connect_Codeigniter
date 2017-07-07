<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of cust_parcel_type_model
   *
   * @author nice
   */
  class cust_parcel_type_model extends MY_Model{
       //put your code here
    function __construct() {
        parent::__construct();
    }
    function get_customTypes(){
         return $this->db->select("C.*,O.name",FALSE)
                 ->from('consignment_type AS C')
                 ->join('organizations AS O','C.org_id=O.id')
                 ->where('C.org_id is NOT NULL', NULL, FALSE)
                 ->get()->result();
//         return $this->getWhere(array('org_id!='=>"NULL"), 'consignment_type');
    }
    
    function get_orgList(){
         return $this->db->select("O.*",FALSE)
                 ->from('organizations AS O')
                 ->get()->result();
    }
  }
  