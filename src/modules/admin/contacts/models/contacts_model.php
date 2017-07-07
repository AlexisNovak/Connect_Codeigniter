<?php

  class Contacts_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function add_contact($contact) {
            if ($this->db->insert('contacts', $contact)) {
                 return $this->db->insert_id();
            }
            return 0;
       }

       public function update_contact($update_data, $where) {
            $update_data['updated_on'] = mdate('%Y-%m-%d %H:%i:%s', now());
            return $this->db->where($where)->update('contacts', $update_data);
       }

       public function list_mycontacts($me) {
            return $this->db->select('*')
                            ->from('contacts')
                            ->where('user_id', $me)
                            ->order_by('contact_name')
                            ->get()
                            ->result();
       }

       public function list_my_recent($me, $from, $search) {
            if ($search != NULL) {
                 $this->db->where('(CT.contact_name LIKE \'%' . $search . '%\' OR phone_number LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('CT.*')->from('recent_contacts as RC')
                            ->join('contacts as CT', 'CT.id=RC.to_contact_id')
                            ->where('RC.user_id', $me)
                            ->where('RC.from_contact_id', $from)
                            ->order_by('timestamp', 'DESC')
                            ->limit(10)
                            ->get()
                            ->result();
       }

       public function get_contactlist_count($user_id, $search) {
            if ($search != NULL) {
                 $this->db->where('(CT.contact_name LIKE \'%' . $search . '%\' OR phone_number LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('CT.id')
                            ->from('contacts AS CT')
                            ->where('(CT.user_id = ' . $user_id . ' OR (CT.id IN (SELECT contact_id from shared_contacts WHERE org_id IN (SELECT org_id from organization_members WHERE user_id=' . $user_id . ') AND shared_person <> ' . $user_id . ')))')
                            ->order_by('CT.contact_name')
                            ->get()
                            ->num_rows();
       }

       public function get_contactlist($user_id, $perpage, $search, $start) {
            if ($search != NULL) {
                 $this->db->where('(CT.contact_name LIKE \'%' . $search . '%\' OR phone_number LIKE \'%' . $search . '%\')');
            }
            return $this->db->select('CT.id, CT.contact_name, CT.email, CT.phone_number, CT.company_name, CT.department_name as dept_name,CT.user_id,'
                                    . ' CT.address_line1, CT.address_line2, CT.postal_code, CT.country_code,country.country as country_name')
                            ->from('contacts AS CT')
                            ->join('country', 'country.code=CT.country_code')
                            ->where('(CT.user_id = ' . $user_id . ' OR (CT.id IN (SELECT contact_id from shared_contacts WHERE org_id IN (SELECT org_id from organization_members WHERE user_id=' . $user_id . ') AND shared_person <> ' . $user_id . ')))')
                            ->order_by('CT.contact_name')
                            ->limit($perpage, $start)
                            ->get()
                            ->result();
       }

       public function delete_contact($contact_id, $user_id) {
            return $this->db->where('id', $contact_id)->where('user_id', $user_id)->delete('contacts');
       }

       public function add_shared_contacts($rows = array()) {
            return $this->db->insert_batch('shared_contacts', $rows);
       }

       public function delete_shared_contacts($contact_id) {
            return $this->db->where('contact_id', $contact_id)->delete('shared_contacts');
       }

       public function get_shared_orgs($contact_id) {
            return $this->db->select('O.id as org_id,O.name as org_name ')
                            ->from('shared_contacts as SC')
                            ->join('organizations as O', 'O.id=SC.org_id')
                            ->where('SC.contact_id', $contact_id)
                            ->get()
                            ->result();
       }

       public function add_recent($data) {
            $this->db->where($data);
            $q = $this->db->get('recent_contacts');

            if ($q->num_rows() > 0) {
                 $this->db->where('user_id', $data['user_id']);
                 $this->db->update('recent_contacts',array('timestamp'=>  mdate('%Y-%m-%d %h:%i:%s',now())));
            } else {
                 $this->db->set($data);
                 $this->db->insert('recent_contacts', $data);
            }
            return;
       }

  }
  