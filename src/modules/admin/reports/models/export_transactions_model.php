<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of export_transactions_model
   *
   * @author nice
   */
  class export_transactions_model extends MY_Model {

       //put your code here
       public function __construct() {
            parent::__construct();
       }

       public function get_transatctions($start_date, $end_date, $user_id, $status, $org_id) {
            if ($start_date != "") {
                 $cdate = date('Y-m-d H:m:s', strtotime($start_date));
                 $this->db->where("C.created_date >= '$cdate'");
            }
            if ($end_date != "") {
                 $todate = date('Y-m-d H:m:s', strtotime($end_date));
                 $this->db->where("C.created_date <= '$todate'");
            }
            $data = $this->db->select('C.*,consignment_type.display_name,F.country as from_country,T.country as to_country,'
                                    . 'ZF.offset as from_zone,ZT.offset as to_zone,CS.display_name as consignment_status,'
                                    . 'S.display_name as service,Z.courier_id,Z.company_name as courier_name,U.username,G.name as team')
                            ->from('consignments as C')
                            ->join('member as U', 'U.id=C.created_user_id', 'left')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->join('group_members as GM', 'GM.user_id=C.created_user_id AND GM.org_id=C.org_id', 'left')
                            ->join('groups as G', 'G.id=GM.group_id', 'left')
                            ->where('C.org_id', $org_id)
                            ->where_in('C.consignment_status_id', $status)
                            ->get()->result();
            return $data;
       }

       public function getOrganisation($org_id) {
            if ($org_id) {
                 return $this->db->select('*')
                                 ->from('organizations')
                                 ->where('id', $org_id)
                                 ->get()->row();
            }
            return array();
       }

  }
  