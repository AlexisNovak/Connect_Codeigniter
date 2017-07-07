<?php

  class Messages_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_msg_count($user_id) {
            return $this->db->select('count(MQ.id) as count')
                            ->from('mailqueue as MQ')
                            ->join('member as M', "M.email= MQ.to")
                            ->join('member_details as MD', "MD.user_id= M.id AND MQ.created_on > MD.last_msg_time")
                            ->where('M.id', $user_id)
                            ->where('MQ.user_type', 1)
                            ->get()
                            ->row();
       }

       public function get_latest_msgs($user_id, $page = 1, $limit = 10) {
            return $this->db->select('MQ.message')
                            ->from('mailqueue AS MQ')
                            ->join('member as M', "M.email= MQ.to")
                            ->where('M.id', $user_id)
                            ->where('MQ.user_type', 1)
                            ->order_by('MQ.id', 'DESC')
                            ->limit($limit, ($page - 1) * $limit)
                            ->get()
                            ->result();
       }

       public function get_smsg_count($user_id) {
            return $this->db->select('count(M.message_id) as count')
                            ->from('service_requests as SR')
                            ->join('service_request_messages as M', 'M.request_id=SR.req_id', 'left')
                            ->join('member_details as MD', 'MD.user_id=SR.user_id AND M.created_date > MD.last_msg_time', 'left')
                            ->where('SR.user_id', $user_id)
                            ->where('M.type', 2)
                            ->where('MD.user_id', $user_id)
                            ->get()
                            ->row();
       }

       public function get_scount_courier($courier_id) {
            $this->db->where('status', 1);
            $this->db->where("expiry_date >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");

            return $this->db->select('count(req_id) as count', FALSE)
                            ->from('service_requests as SR')
                            ->where('status <> 2')
                            ->where('(SR.added_on > (select last_msg_time from couriers WHERE courier_id = ' . $courier_id . '))')
                            ->get()
                            ->row();
       }

       public function get_omsg_count($user_id) {
            return $this->db->select('count(M.message_id) as count')
                            ->from('consignments as C')
                            ->join('consignment_messages as M', 'M.job_id=C.consignment_id', 'left')
                            ->join('member_details as MD', 'MD.user_id=C.created_user_id AND M.created_date > MD.last_msg_time', 'left')
                            ->where('C.created_user_id', $user_id)
                            ->where('MD.user_id', $user_id)
                            ->get()
                            ->row();
       }

       public function get_ocount_courier($courier_id) {
            $this->db->select("count(consignment_id) as count");
            $this->db->from('consignments as C');
            $this->db->join("pre_approved_bidders as PB", 'PB.org_id=C.org_id AND PB.courier_id=' . $courier_id, 'left');
            $this->db->where('C.is_deleted', 0);
            $this->db->where("C.is_for_bidding", 1);
            $this->db->where("C.is_confirmed", 0);
            $this->db->where("C.bidding_deadline >='" . mdate('%Y-%m-%d %H:%i:%s', now()) . "'");
            $this->db->where('(C.is_open_bid=1 OR PB.id IS NOT NULL)');
            $this->db->where('(C.created_date > (select last_msg_time from couriers WHERE courier_id = ' . $courier_id . '))');
            return $this->db->get()->row();
       }

       public function update_last_msg($user_id) {
            return $this->db->update('member_details', array('last_msg_time' => date('Y-m-d H:i:s')), array('user_id' => $user_id));
       }

       public function get_last_msg_time($user_id) {
            $row = $this->db->select('last_msg_time')->from('member_details')->where('user_id', $user_id)->get()->row();
            if ($row) {
                 return $row->last_msg_time;
            }
            return "";
       }

       public function get_last_msg_time_courier($courier_id) {
            $row = $this->db->select('last_msg_time')->from('couriers')->where('courier_id', $courier_id)->get()->row();
            if ($row) {
                 return $row->last_msg_time;
            }
            return "";
       }

       public function get_msg_count_courier($courier_id) {
            return $this->db->select('count(MQ.id) as count')
                            ->from('mailqueue as MQ')
                            ->join('couriers as C', "C.email= MQ.to AND MQ.created_on > C.last_msg_time")
                            ->where('C.courier_id', $courier_id)
                            ->where('MQ.user_type', 2)
                            ->get()
                            ->row();
       }

       public function get_latest_msgs_courier($courier_id, $page = 1, $limit = 10) {
            return $this->db->select('MQ.message')
                            ->from('mailqueue AS MQ')
                            ->join('couriers as C', "C.email= MQ.to")
                            ->where('C.courier_id', $courier_id)
                            ->where('MQ.user_type', 2)
                            ->order_by('MQ.id', 'DESC')
                            ->limit($limit, ($page - 1) * $limit)
                            ->get()
                            ->result();
       }

       public function update_last_msg_courier($courier_id) {
            return $this->db->update('couriers', array('last_msg_time' => date('Y-m-d H:i:s')), array('courier_id' => $courier_id));
       }

//
//       public function get_smsg_count($user_id) {
//            return $this->db->select('count(M.message_id) as count')
//                            ->from('service_requests as SR')
//                            ->join('service_request_messages as M', 'M.request_id=SR.req_id', 'left')
//                            ->join('member_details as MD', 'MD.user_id=SR.user_id AND M.created_date > MD.last_msg_time', 'left')
//                            ->where('SR.user_id', $user_id)
//                            ->where('M.type', 2)
//                            ->where('MD.user_id', $user_id)
//                            ->get()
//                            ->row();
//       }
//
//       public function get_latest_msgs($user_id) {
//            return $this->db->query('SELECT * FROM (SELECT M.question as content, C.public_id as link, 1 as type, M.created_date FROM (consignments as C) JOIN consignment_messages as M ON M.job_id=C.consignment_id LEFT JOIN member_details as MD ON MD.user_id=C.created_user_id  WHERE C.created_user_id =  ' . $user_id . ' AND MD.user_id =' . $user_id . ' UNION ' .
//                            'SELECT M.question as content, M.request_id as link, 2 as type, M.created_date  FROM (service_requests as SR) JOIN service_request_messages as M ON M.request_id=SR.req_id LEFT JOIN member_details as MD ON MD.user_id=SR.user_id WHERE SR.user_id =  ' . $user_id . ' AND M.type =  2 AND MD.user_id =  ' . $user_id . ') as U order by U.created_date DESC LIMIT 10')->result();
//       }
  }
  