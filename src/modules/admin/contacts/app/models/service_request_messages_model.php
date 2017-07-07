<?php

  class Service_request_messages_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_message($data) {
            if ($this->db->insert('service_request_messages', $data)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function get_last_comment($request_id) {
            $query = 'SELECT * FROM (SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply,  "message" as type,'
                    . ' C.company_name as courier,'
                    . ' DATE_FORMAT(created_date, "%d %b %Y %h:%i %p") as time,M.created_date as time2, '
                    . ' DATE_FORMAT(updated_date, "%d %b %Y %h:%i %p") as reply_time'
                    . ' FROM service_request_messages as M '
                    . ' JOIN couriers as C ON C.courier_id=M.courier_id '
                    . ' WHERE type=2 AND '
                    . ' request_id = ' . $request_id . ' UNION '
                    . 'SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply, "comment" as type,'
                    . ' NULL as courier,'
                    . ' DATE_FORMAT(created_date, "%d %b %Y %h:%i %p") as time,M.created_date as time2,'
                    . ' DATE_FORMAT(updated_date, "%d %b %Y %h:%i %p") as reply_time'
                    . ' FROM service_request_messages as M '
                    . ' WHERE type=1 AND '
                    . ' request_id = ' . $request_id . ') AS U ORDER BY U.time2 DESC';
            return $this->db->query($query)->row();
       }

       public function add_reply($msg_id, $data) {
            $this->db->where('message_id', $msg_id);
            $this->db->update('service_request_messages', $data);
            return;
       }

       public function getmessages($request_id) {
            $query = 'SELECT * FROM (SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply,  "message" as type,'
                    . ' C.company_name as courier,'
                    . ' DATE_FORMAT(created_date, "%d %b %Y %h:%i %p") as time,M.created_date as time2, '
                    . ' DATE_FORMAT(updated_date, "%d %b %Y %h:%i %p") as reply_time'
                    . ' FROM service_request_messages as M '
                    . ' JOIN couriers as C ON C.courier_id=M.courier_id '
                    . ' WHERE type=2 AND '
                    . ' request_id = ' . $request_id . ' UNION '
                    . 'SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply, "comment" as type,'
                    . ' NULL as courier,'
                    . ' DATE_FORMAT(created_date, "%d %b %Y %h:%i %p") as time,M.created_date as time2,'
                    . ' DATE_FORMAT(updated_date, "%d %b %Y %h:%i %p") as reply_time'
                    . ' FROM service_request_messages as M '
                    . ' WHERE type=1 AND '
                    . ' request_id = ' . $request_id . ') AS U ORDER BY U.time2 DESC';
            return $this->db->query($query)->result();
       }

       public function get_message_count($request_id) {
            $this->db->select('message_id')->from('service_request_messages')
                    ->where('request_id', $request_id);
            return $this->db->get()->num_rows();
       }

       public function get_reply_count($request_id) {
            $this->db->select('message_id')->from('service_request_messages')
                    ->where('request_id', $request_id)->where('(reply IS NOT NULL OR type=1)');
            return $this->db->get()->num_rows();
       }

       public function list_req_messages($request_id, $courier_id) {
            $query = 'SELECT * FROM (SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply,  "message" as type,'
                    . ' C.company_name as courier,'
                    . 'CASE WHEN M.courier_id='.$courier_id.' THEN 1 ELSE 0 END AS by_you,'
                    . ' created_date,updated_date '
                    . ' FROM service_request_messages as M '
                    . ' JOIN couriers as C ON C.courier_id=M.courier_id AND (M.courier_id='.$courier_id.' OR M.reply IS NOT NULL)'
                    . ' WHERE type=2 AND '
                    . ' request_id = ' . $request_id . ' UNION '
                    . 'SELECT message_id, M.courier_id, request_id, question as msg, '
                    . 'reply, "comment" as type,'
                    . ' NULL as courier,'
                    . ' 2 AS by_you,'
                    . ' created_date,updated_date '
                    . ' FROM service_request_messages as M '
                    . ' WHERE type=1 AND '
                    . ' request_id = ' . $request_id . ') AS U ORDER BY U.created_date DESC';
            return $this->db->query($query)->result();
       }

  }
  