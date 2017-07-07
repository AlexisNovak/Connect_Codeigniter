<?php

  class Invoice_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_customers($courier_id) {
            return $this->db->select('DISTINCT M.id,M.username,M.email', FALSE)
                            ->from('consignments as C')
                            ->join('member as M', 'M.id=C.created_user_id')
                            ->join('courier_service as CS', 'CS.id=C.service_id')
                            ->where('CS.courier_id', $courier_id)
                            ->get()
                            ->result();
       }

       public function get_org_customers($org_ids, $courier_id) {
            return $this->db->select('DISTINCT M.id,M.username,M.email', FALSE)
                            ->from('consignments as C')
                            ->join('member as M', 'M.id=C.created_user_id')
                            ->join('organization_members as OM', 'OM.user_id=M.id')
                            ->join('courier_service as CS', 'CS.id=C.service_id')
                            ->where('CS.courier_id', $courier_id)
                            ->where_in('OM.org_id', $org_ids)
                            ->get()
                            ->result();
       }

       public function get_organisations($courier_id) {
            return $this->db->select('DISTINCT O.id,O.name', FALSE)
                            ->from('consignments as C')
                            ->join('courier_service as CS', 'CS.id=C.service_id')
                            ->join('organizations as O', 'O.id=C.org_id')
                            ->where('CS.courier_id', $courier_id)
                            ->get()
                            ->result();
       }

       public function get_assigned_organisations($courier_id) {
            return $this->db->select('DISTINCT O.id,O.name', FALSE)
                            ->from('courier_service as CS')
                            ->join('organizations as O', 'O.id=CS.org_id')
                            ->where('CS.courier_id', $courier_id)
                            ->get()
                            ->result();
       }

       public function get_statuslist($exclude = array()) {
            if (!empty($exclude)) {
                 $this->db->where_not_in('status_id', $exclude);
            }
            return $this->db->select('status_id,display_name')
                            ->from('consignment_status')
                            ->get()->result();
       }

       public function getCustomerConsignmentsHavingIds($customers, $start_date, $end_date) {
            if ($start_date != "") {
                 $cdate = date('Y-m-d H:m:s', strtotime($start_date));
                 $this->db->where("C.created_date >= '$cdate'");
            }
            if ($end_date != "") {
                 $todate = date('Y-m-d H:m:s', strtotime($end_date));
                 $this->db->where("C.created_date <= '$todate'");
            }
            if ($customers != NULL) {
                 $this->db->where_in("U.id", $customers);
            }

            return $this->db->select('U.id as customer_id,U.username as fullname,U.email')
                            ->from('consignments as C')
                            ->join('member as U', 'U.id=C.created_user_id')
                            ->get()->result();
       }

       public function get_orders($start_date, $end_date, $courier_id, $status, $org_ids, $customers) {
            if ($start_date != "") {
                 $cdate = date('Y-m-d H:m:s', strtotime($start_date));
                 $this->db->where("C.created_date >= '$cdate'");
            }
            if ($end_date != "") {
                 $todate = date('Y-m-d H:m:s', strtotime($end_date));
                 $this->db->where("C.created_date <= '$todate'");
            }
            if ($customers != NULL) {
                 $this->db->where_in("U.id", $customers);
            }
            if ($org_ids != NULL) {
                 $this->db->where_in("O.id", $org_ids);
            }
            return $this->db->select('C.*,consignment_type.display_name,F.country as from_country,T.country as to_country,'
                                    . 'CS.display_name as consignment_status,'
                                    . 'S.display_name as service,Z.courier_id,Z.company_name as courier_name,U.username as fullname,U.email,O.id as org_id,O.name as org_name')
                            ->from('consignments as C')
                            ->join('organizations as O', 'O.id=C.org_id', 'left')
                            ->join('member as U', 'U.id=C.created_user_id', 'left')
                            ->join('partner_members as PM', 'PM.member_id=U.id', 'left')
                            ->join('country as F', 'F.code=C.collection_country', 'left')
                            ->join('country as T', 'T.code=C.delivery_country', 'left')
                            ->join('consignment_type', 'consignment_type.consignment_type_id=C.consignment_type_id')
                            ->join('consignment_status as CS', 'CS.status_id=C.consignment_status_id')
                            ->join('courier_service as S', 'S.id=C.service_id', 'left')
                            ->join('couriers as Z', 'Z.courier_id=S.courier_id', 'left')
                            ->where('Z.courier_id', $courier_id)
                            ->where_in('C.consignment_status_id', $status)
                            ->order_by('C.created_date', 'DESC')
                            ->get()->result();
       }

       public function add_invoice($invoice) {
            return $this->db->insert('invoices', $invoice);
       }

       public function getinvoicelist_count_for_courier($courier_id, $search) {
            if ($search != NULL) {
                 $this->db->where('M.email LIKE \'%' . $search . '%\' OR M.username LIKE \'%' . $search . '%\'');
            }
            return $this->db->select('I.invoice_id as id')
                            ->from('invoices as I')
                            ->join('member as M', 'M.id=I.customer_id AND I.type=1', 'left')
                            ->join('organizations as O', 'O.id=I.customer_id AND I.type=2', 'left')
                            ->where('I.courier_id', $courier_id)
                            ->get()
                            ->num_rows();
       }

       public function getinvoicelist_for_courier($courier_id, $search, $perpage, $start) {
            if ($search != NULL) {
                 $this->db->where('M.email LIKE \'%' . $search . '%\' OR M.username LIKE \'%' . $search . '%\'');
            }
            return $this->db->select('I.invoice_id as id, invoice_no, courier_id,'
                                    . ' customer_id, created_date, is_deleted, deleted_date, deleted_user_id,'
                                    . ' type, from_date, to_date, file_name, pdf, excel,M.email,M.username, O.name as org_name')
                            ->from('invoices as I')
                            ->join('member as M', 'M.id=I.customer_id AND I.type=1', 'left')
                            ->join('organizations as O', 'O.id=I.customer_id AND I.type=2', 'left')
                            ->where('I.courier_id', $courier_id)
                            ->limit($perpage, $start)
                            ->get()
                            ->result();
       }

       public function get_invoive($id) {
            return $this->db->where('invoice_id', $id)->get('invoices')->row();
       }

       public function update_invoice($update_data, $id) {
            return $this->db->update('invoices', $update_data, array('invoice_id' => $id));
       }

       public function get_user($customer) {
            return $this->db->select('username as fullname,email')->from('member')->where('id', $customer)->get()->row();
       }

       function get_org($org_id) {
            return $this->db->select('name')
                            ->from('organizations')
                            ->where('id', $org_id)
                            ->get()->row();
       }

  }
  