<?php

  class Couriers_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function courier_by_token($token) {
            return $this->db->select("courier_id,company_name")
                            ->from("couriers")
                            ->where("access_key", $token)
                            ->get()
                            ->result();
       }

       public function get_by_email($email) {
            return $this->db->select("*")
                            ->from("couriers")
                            ->where("email", $email)
                            ->get()
                            ->row();
       }

       public function get_by_id($id) {
            return $this->db->select("*")
                            ->from("couriers")
                            ->where("courier_id", $id)
                            ->get()
                            ->row();
       }

       public function get_for_mail($id) {
            return $this->db->select("C.courier_id,C.email,C.company_name as name")
                            ->from("couriers as C")
                            ->where("C.courier_id", $id)
                            ->get()
                            ->row();
       }

       public function get_by_service($service_id) {
            return $this->db->select("C.*")
                            ->from("couriers C")
                            ->join("courier_service CS", "C.courier_id=CS.courier_id", "inner")
                            ->where("CS.id", $service_id)
                            ->get()
                            ->row();
       }

       public function getprofile($id) {
            return $this->db->select("courier_id, email,company_name,url, description, reg_address, billing_address, fullname, reg_no, phone, fax, support_email,is_approved")
                            ->from("couriers")
                            ->where("courier_id", $id)
                            ->get()
                            ->row();
       }

       public function create($name, $email, $password = NULL, $token = NULL, $url = "", $description = "", $logo = NULL, $invited = 0) {
            if ($password !== NULL) {
                 $this->load->helper('account/phpass');
                 $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                 $hashed_password = $hasher->HashPassword($password);
            }
            if ($this->db->insert("couriers", array(
                        "company_name" => $name,
                        "email" => $email,
                        "password" => isset($hashed_password) ? $hashed_password : NULL,
                        "url" => $url,
                        "description" => $description,
                        "access_key" => $token,
                        "logo" => $logo,
                        "is_invited" => $invited
                    )))
                 return $this->db->insert_id();
            return 0;
       }

       public function update_password($courier_id, $password = NULL, $old_pass = NULL) {
            if ($password !== NULL) {
                 $this->load->helper('account/phpass');
                 $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                 $hashed_password = $hasher->HashPassword($password);
            }
            $this->db->set('modified_on', mdate('%Y-%m-%d %H:%i:%s', now()));
            $this->db->set('oldpasswords', $old_pass);
            $this->db->set('password', isset($hashed_password) ? $hashed_password : NULL);
            $this->db->where("courier_id", $courier_id);
            return $this->db->update('couriers');
       }

       public function get_old_password($courier_id) {
            return $this->db->select('password,oldpasswords')
                            ->from('couriers')
                            ->where('courier_id', $courier_id)
                            ->get()
                            ->row();
       }

       public function update($courier_id, $data) {
            $data['modified_on'] = mdate('%Y-%m-%d %H:%i:%s', now());
            return $this->db->where("courier_id", $courier_id)->update("couriers", $data);
       }

       public function update_by_access_key($key, $data) {
            $data['modified_on'] = mdate('%Y-%m-%d %H:%i:%s', now());
            return $this->db->where("access_key", $key)->update("couriers", $data);
       }

       public function verifyEmail($email) {
            $this->db->where("email", $email);
            return $this->db->update("couriers", array("is_verified" => 1));
       }

       public function getLogo($courier_id) {
            $query = $this->db->select("logo")
                    ->from("couriers")
                    ->where("courier_id", $courier_id)
                    ->get()
                    ->row();
            if ($query) {
                 return $query->logo;
            }
            return NULL;
       }

       public function getCourier_by_token($token) {
            return $this->db->select("*")
                            ->from("couriers")
                            ->where("access_key", $token)
                            ->get()
                            ->row();
       }

       public function getCourier_by_token_with_approval($token) {
            return $this->db->select("*")
                            ->from("couriers")
                            ->where("access_key", $token)
                            ->where("is_approved", 1)
                            ->get()
                            ->row();
       }

       public function getcourierlist_count($search = NULL) {
            if ($search != NULL) {
                 $this->db->where('(company_name LIKE \'%' . $search . '%\' OR email like \'%' . $search . '%\')');
            }
            $query = $this->db->select('courier_id')->from('couriers')->get();
            return $query->num_rows();
       }

       public function getcourierlist($perpage, $search, $start) {
            if ($search != NULL) {
                 $this->db->where('(company_name LIKE \'%' . $search . '%\' OR email like \'%' . $search . '%\')');
            }
            $this->db->limit($perpage, $start);
            return $this->db->select('courier_id, email, access_key, company_name,  url,is_verified as verified, is_approved as approved')->from('couriers')->get()->result();
       }

       public function get_services_assigned($courier_id) {
            $sql = "SELECT courier_service.id as service_id,courier_service.display_name as service_name,"
                    . "courier_service.description as description,courier_service.org_id "
                    . "FROM courier_service "
                    . "WHERE courier_service.courier_id=$courier_id AND is_new != 0";
            return $this->db->query($sql)->result();
       }

       public function myorganisations_count($courier_id, $search) {
            if ($search != NULL) {
                 $this->db->where('organizations.name LIKE \'%' . $search . '%\'');
            }
            $this->db->select('DISTINCT M.org_id,organizations.name as org_name', FALSE);
            $this->db->from('courier_service as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.courier_id', $courier_id);
            $this->db->where('M.org_id <> 0');
            return $this->db->get()->num_rows();
       }

       function myorganisations($courier_id, $perpage, $search, $start) {
            if ($search != NULL) {
                 $this->db->where('organizations.name LIKE \'%' . $search . '%\'');
            }
            $this->db->select('DISTINCT M.org_id,organizations.name as org_name', FALSE);
            $this->db->from('courier_service as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.courier_id', $courier_id);
            $this->db->where('M.org_id <> 0');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function myorganisations_all($courier_id) {
            $this->db->select('DISTINCT M.org_id,organizations.name as org_name', FALSE);
            $this->db->from('courier_service as M');
            $this->db->join('organizations', 'M.org_id=organizations.id', 'left');
            $this->db->where('M.courier_id', $courier_id);
            $this->db->where('M.org_id <> 0');
            return $this->db->get()->result();
       }

       public function get_approved_services($courier_id, $org_id) {
            $this->db->select('CS.display_name as service_name')
                    ->from('courier_service AS CS')
                    ->where('CS.org_status', 2)
                    ->where('CS.courier_id', $courier_id)
                    ->where('CS.is_archived', 0)
                    ->where('CS.org_id', $org_id);
            return $this->db->get()->result();
       }

       public function get_statuslist() {
            return $this->db->select('status_id as id,display_name')
                            ->from('consignment_status')
                            ->where('for_courier', 1)
                            ->get()->result();
       }

       public function get_all_statuslist($exclude_status = array()) {
            if (!empty($exclude_status)) {
                 $this->db->where_not_in('status_id', $exclude_status);
            }
            return $this->db->select('status_id,courier_display_name as display_name')
                            ->from('consignment_status')
                            ->get()->result();
       }

       function update_reset_sent_datetime($courier_id) {
            $this->load->helper('date');

            $resetsenton = mdate('%Y-%m-%d %H:%i:%s', now());

            $this->db->update('couriers', array('resetsenton' => $resetsenton), array('courier_id' => $courier_id));

            return strtotime($resetsenton);
       }

       /**
        * Remove password reset datetime
        *
        * @access public
        * @param int $user_id
        * @return void
        */
       function remove_reset_sent_datetime($courier_id) {
            $this->db->update('couriers', array('resetsenton' => NULL), array('courier_id' => $courier_id));
       }

       public function get_compliance_ratings_list() {
            return $this->db->get('compliance_ratings')->result();
       }

       function get_bid_consignments($bid_id) {
            return $this->db->where('bid_id', $bid_id)->get('bid_consignment_relation')->row();
       }

       function getCourierService_by_id($service_id, $id = NULL) {
            if ($id != NULL) {
                 $this->db->where('id !=', $id);
            }
            $data = $this->db->select('*')
                    ->from('courier_service')
                    ->where('service_id', $service_id)
                    ->where('is_new', 1)
                    ->get();
            if ($data->num_rows() > 0) {
                 return FALSE;
            }
            return TRUE;
       }

       function get_member_by_id($id) {
            return $this->db->select('*')
                            ->from('member')
                            ->where('id', $id)
                            ->get()
                            ->row();
       }
       
       function get_courier_service_ById($service_id){
            return $this->db->select('*')
                            ->from('courier_service')
                            ->where('id', $service_id)
                            ->get()
                            ->row();
       }

  }
  