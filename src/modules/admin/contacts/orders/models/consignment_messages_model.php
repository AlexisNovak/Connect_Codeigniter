<?php

  class Consignment_messages_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function addmessage($data) {
            if ($this->db->insert('consignment_messages', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function get_last_comment($order_id) {
            $query = 'SELECT C.comment_id as msg_id,C.comment as msg,NULL as reply, DATE_FORMAT(C.time,"%d %b %Y %h:%i %p") as time ,C.time as time2,NULL as replytime,"comment" as type,NULL as courier '
                    . 'FROM consignment_comments as C '
                    . 'WHERE order_id = ' . $order_id . ' ORDER BY msg_id DESC';
            return $this->db->query($query)->row();
       }

       public function add_reply($msg_id, $data) {
            $this->db->where('message_id', $msg_id);
            if ($this->db->update('consignment_messages', $data)) {
                 return TRUE;
            }
            return;
       }

       public function add_comment($data) {
            if ($this->db->insert('consignment_comments', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function get_order($msg_id) {
            return $this->db->select('job_id')->from('consignment_messages')->where('message_id', $msg_id)->get()->row();
       }

       public function listjobmessages($job_id, $courier_id) {
            return $this->db->query('SELECT * FROM (SELECT C.comment_id as message_id,U.username as username,O.name as company_name,'
                            . '"2" as by_you,  C.comment as question,NULL as reply,C.time as created_date , C.time as updated_date '
                            . 'FROM consignment_comments as C JOIN consignments as CG ON CG.consignment_id=C.order_id '
                            . 'JOIN member as U ON U.id=CG.created_user_id LEFT JOIN organizations as O ON O.id=CG.org_id '
                            . 'WHERE order_id = ' . $job_id . ' UNION '
                            . 'SELECT message_id, U.username as username, O.name as company_name, '
                            . 'CASE WHEN M.courier_id=' . $courier_id . ' THEN 1 ELSE 0 END AS by_you,'
                            . ' question, reply, M.created_date,'
                            . ' M.updated_date FROM (consignment_messages as M) JOIN consignments as CG ON CG.consignment_id=M.job_id '
                            . 'JOIN member as U ON U.id=CG.created_user_id '
                            . 'JOIN couriers as C ON C.courier_id=M.courier_id '
                            . 'LEFT JOIN organizations as O ON O.id=CG.org_id '
                            . 'WHERE M.job_id =' . $job_id . ' AND (M.courier_id=' . $courier_id . ' OR M.reply IS NOT NULL ) '
                            . ') as U ORDER BY U.created_date desc', false)->result();
       }

       public function getmessages($order_id) {
            $query = 'SELECT * FROM (SELECT C.comment_id as msg_id,C.comment as msg,NULL as reply, DATE_FORMAT(C.time,"%d %b %Y %h:%i %p") as time , C.time as time2,NULL as replytime,"comment" as type,NULL as courier '
                    . 'FROM consignment_comments as C '
                    . 'WHERE order_id = ' . $order_id . ' UNION '
                    . 'SELECT M.message_id as msg_id, M.question as msg, M.reply, DATE_FORMAT(M.created_date,"%d %b %Y %h:%i %p") as time,M.created_date as time2, DATE_FORMAT(M.updated_date,"%d %b %Y %h:%i %p") as replytime, "message" as type,C.company_name as courier '
                    . 'FROM consignment_messages as M '
                    . ' JOIN couriers as C ON C.courier_id=M.courier_id '
                    . 'WHERE job_id = ' . $order_id . ' AND M.courier_id <> 0 UNION '
                    . 'SELECT M.message_id as msg_id, M.question as msg, M.reply, DATE_FORMAT(M.created_date,"%d %b %Y %h:%i %p") as time,M.created_date as time2, DATE_FORMAT(M.updated_date,"%d %b %Y %h:%i %p") as replytime, "message" as type,C.email as courier '
                    . 'FROM consignment_messages as M '
                    . ' JOIN couriers_external as C ON C.job_id=M.job_id AND M.courier_id = 0 '
                    . 'WHERE M.job_id = ' . $order_id . ') as U ORDER BY U.time2 DESC';
            return $this->db->query($query)->result();
       }

       public function get_message_count($order_id) {
            $this->db->select('message_id')->from('consignment_messages')->where('job_id', $order_id);
            return $this->db->get()->num_rows();
       }

       public function get_reply_count($order_id) {
            $this->db->select('message_id')->from('consignment_messages')->where('job_id', $order_id)->where('reply IS NOT NULL');
            return $this->db->get()->num_rows();
       }

  }
  