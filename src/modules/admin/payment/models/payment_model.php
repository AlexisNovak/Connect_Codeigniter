<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of payment_model
   *
   * @author nice
   */
  class payment_model extends MY_Model {

       //put your code here
       public function __construct($table_name = NULL, $primary_key = NULL) {
            parent::__construct($table_name, $primary_key);
       }

       function getpaymentlist_count($filter) {
            $search = $filter['search'];
            if (isset($search) && $search != '') {
                 $where = "(P.contact_name LIKE '%$search%' OR (O.name LIKE '%$search%' AND P.holder_type=2) OR (M.username LIKE '%$search%' AND P.holder_type=1))";
                 $this->db->where($where);
            }
            if (isset($filter['account_type']) && $filter['account_type'] != '') {
                 $this->db->where('P.account_type', intval($filter['account_type']));
            }
            if (isset($filter['holder_type']) && $filter['holder_type'] != '') {
                 $this->db->where('P.holder_type', intval($filter['holder_type']));
            }
            $count = $this->db->select('P.*')
                            ->from('payment_accounts AS P')
                            ->join('member AS M', 'M.id=P.account_holder', "LEFT")
                            ->join('organizations AS O', 'O.id=P.account_holder', "LEFT")
                            ->group_by('P.id')
                            ->where('P.status <> 0')
                            ->get()->num_rows();
            return $count;
       }

       function getpaymentlist($perpage, $filter, $start) {
            $search = $filter['search'];
            if (isset($search) && $search != '') {
                 $where = "(P.contact_name LIKE '%$search%' OR (O.name LIKE '%$search%' AND P.holder_type=2) OR (M.username LIKE '%$search%' AND P.holder_type=1))";
                 $this->db->where($where);
            }

            if (isset($filter['account_type']) && $filter['account_type'] != '') {
                 $this->db->where('P.account_type', intval($filter['account_type']));
            }
            if (isset($filter['holder_type']) && $filter['holder_type'] != '') {
                 $this->db->where('P.holder_type', intval($filter['holder_type']));
            }

            $data = $this->db->select('P.status,O.name AS Oname, M.username AS Mname,P.credit,P.account_holder,P.account_type,P.holder_type,P.id as account_id, P.contact_name as account_name, P.contact_number as phone_number, P.address_line1,'
                            . ' P.address_line2, P.city, P.country_code, P.postal_code, P.invoice_attention as attention, P.company_reg_no as reg_no,'
                            . ' P.deliveries_per_month as deli_per_mnth, P.comments')
                    ->from('payment_accounts as P')
                    ->join('member AS M', 'M.id=P.account_holder', "LEFT")
                    ->join('organizations AS O', 'O.id=P.account_holder', "LEFT")
                    ->group_by('P.id')
                    ->where('P.status <> 0')
                    ->limit($perpage, $start)
                    ->get()
                    ->result();
            $ret = array();
            foreach ($data as $value) {
                 $value->account_typename = "";
                 if ($value->account_type == 1) {
                      $value->account_typename = "Pre-Paid";
                 } else if ($value->account_type == 2) {
                      $value->account_typename = "Post-Paid";
                 }
                 if ($value->holder_type == 1) {
                      $value->holder_typename = "Individual";
                      $user = $this->db->select('*')
                                      ->from('member')
                                      ->where('id', $value->account_holder)
                                      ->get()->row();
                      $value->parent_name = $user->username;
                 } else if ($value->holder_type == 2) {
                      $value->holder_typename = "Organisation";
                      $org = $this->db->select('*')
                                      ->from('organizations')
                                      ->where('id', $value->account_holder)
                                      ->get()->row();
                      $value->parent_name = $org->name;
                 }
                 $ret[] = $value;
            }
            return $ret;
       }

       function get_by_id($account_id) {
            return $this->db->select('*')
                            ->from('payment_accounts')
                            ->where('id', $account_id)
                            ->get()->row();
       }

       function update_by_id($id, $orgData) {
            return $this->db->select('*')
                            ->where('id', $id)
                            ->update('payment_accounts', $orgData);
       }

       function getTransaction($id, $from, $to) {
            return $this->db->select('*')
                            ->from('consignments')
                            ->where('payment_acount_id', $id)
                            ->where('created_date >=', $from)
                            ->where('created_date <= ', $to)
                            ->get()->result();
       }

  }
  