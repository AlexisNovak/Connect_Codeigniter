<?php

  class Third_party_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_order($order_id, $slug) {
            return $this->db->select('C.*,consignment_type.display_name,F.country as from_country,T.country as to_country,'
                                    . 'ZF.offset as from_zone,ZT.offset as to_zone,CS.display_name as consignment_status,'
                                    . 'S.display_name as service,S.payment_terms,Z.courier_id,Z.company_name as courier_name,TP.email as third_party_email')
                            ->from('consignments as C')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('zoneinfo as ZF', 'ZF.zoneinfo=C.collection_timezone', 'left')
                            ->join('zoneinfo as ZT', 'ZT.zoneinfo=C.delivery_timezone', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->join('couriers_external as TP', 'TP.job_id=C.consignment_id', 'left')
                            ->where('TP.job_id', $order_id)
                            ->where('C.consignment_id', $order_id)
                            ->where('TP.permalink', $slug)
                            ->get()
                            ->row();
       }

       public function listjobmessages($job_id) {

            return $this->db->query('SELECT * FROM (SELECT C.comment_id as message_id,U.username as username,O.name as company_name,'
                            . '"2" as by_you,  C.comment as question,NULL as reply,C.time as created_date , C.time as updated_date '
                            . 'FROM consignment_comments as C JOIN consignments as CG ON CG.consignment_id=C.order_id '
                            . 'JOIN member as U ON U.id=CG.created_user_id LEFT JOIN organizations as O ON O.id=CG.org_id '
                            . 'WHERE order_id = ' . $job_id . ' UNION '
                            . 'SELECT message_id, U.username as username, O.name as company_name, '
                            . 'CASE WHEN M.courier_id=0 THEN 1 ELSE 0 END AS by_you,'
                            . ' question, reply, M.created_date,'
                            . ' M.updated_date FROM (consignment_messages as M) JOIN consignments as CG ON CG.consignment_id=M.job_id '
                            . 'JOIN member as U ON U.id=CG.created_user_id '
                            . 'JOIN couriers_external as C ON C.job_id= ' . $job_id
                            . ' LEFT JOIN organizations as O ON O.id=CG.org_id '
                            . 'WHERE M.job_id =' . $job_id
                            . ') as U ORDER BY U.created_date desc', false)->result();
       }

  }
  