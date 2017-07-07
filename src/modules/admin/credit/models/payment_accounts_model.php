<?php

  class Payment_accounts_model extends MY_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_accounts_count($id, $type = NULL) {
            if ($type != NULL) {
                 $this->db->where('P.holder_type', $type);
            }
            return $this->db->select('P.id')
                            ->from('payment_accounts as P')
                            ->where('P.account_holder', $id)
                            ->where('P.status', 2)
                            ->get()
                            ->num_rows();
       }

       public function get_approved_accounts($id, $type = NULL) {
            if ($type != NULL) {
                 $this->db->where('P.holder_type', $type);
            }
            return $this->db->select('P.id,P.contact_name as account_name,P.credit ')
                            ->from('payment_accounts as P')
                            ->where('P.account_holder', $id)
                            ->where('P.status', 2)
                            ->get()
                            ->result();
       }

       public function get_accounts($id, $type) {
            return $this->db->select('P.status,P.credit,P.id as account_id, P.contact_name as account_name, P.contact_number as phone_number, P.address_line1,'
                                    . ' P.address_line2, P.city, P.country_code, P.postal_code, P.invoice_attention as attention, P.company_reg_no as reg_no,'
                                    . ' P.deliveries_per_month as deli_per_mnth, P.comments')
                            ->from('payment_accounts as P')
                            ->where('P.account_holder', $id)
                            ->where('P.holder_type', $type)
                            ->where('P.status <> 0')
                            ->get()
                            ->result();
       }

       public function add_account($data, $id) {
            if ($id != NULL) {
                 $this->db->where('id', $id);
                 $this->db->where('status', 1);
                 $this->db->update('payment_accounts', $data);
                 return $id;
            } else {
                 if ($this->db->insert('payment_accounts', $data)) {
                      return $this->db->insert_id();
                 }
            }
            return 0;
       }

       public function delete_account($id) {
            if ($id != NULL) {
                 $this->db->where('id', $id);
                 $this->db->update('payment_accounts', array('status' => 0));
                 return TRUE;
            }
            return FALSE;
       }

  }
  