<?php

  class Courier_service_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function push_service($data) {
            if ($this->db->insert("courier_service", $data)) {
                 return $this->db->insert_id();
            }
       }

       public function suspend_service($service_id, $courier_id) {
            return $this->db->where(array('service_id' => $service_id, "courier_id" => $courier_id))->update("courier_service", array(
                        "status" => 2
            ));
       }

       public function suspend_service_by_id($service_id) {
            return $this->db->where('id', $service_id)->update("courier_service", array(
                        "status" => 3
            ));
       }

       public function activate_service($service_id) {
            return $this->db->where('id', $service_id)->update("courier_service", array(
                        "status" => 1
            ));
       }

       public function removeservice($service_id, $courier_id) {
            $where = array(
                "service_id" => $service_id,
                "courier_id" => $courier_id
            );
            return $this->db->where($where)->update('courier_service', array(
                        "is_for_bidding" => 0,
                        "status" => 2
            ));
       }

       public function suspendservice($company, $courier_id) {
            $where = array(
                "org_id" => $company,
                "courier_id" => $courier_id,
                "status" => 1
            );
            return $this->db->where($where)->update('courier_service', array(
                        "status" => 3
            ));
       }

       public function activateservice($company, $courier_id) {
            $where = array(
                "org_id" => $company,
                "courier_id" => $courier_id,
                "status" => 3
            );
            return $this->db->where($where)->update('courier_service', array(
                        "status" => 1
            ));
       }

       public function assignservice($service_id, $company) {
            $where = array(
                "service_id" => $service_id,
                "is_for_bidding" => 1,
                "status" => 1
            );
            return $this->db->where($where)->update('courier_service', array(
                        "org_id" => $company,
                        "is_for_bidding" => 0
            ));
       }

       public function list_services($courier_id, $search) {
            if ($search != "") {
                 $this->db->where('(display_name LIKE \'%' . $search . '%\' OR description LIKE \'%' . $search . '%\')');
            }
            return $this->db->select("*")
                            ->from("courier_service")
                            ->where("courier_id", $courier_id)
                            ->get()
                            ->result();
       }

       function getserviceslist_count($courier_id, $search) {
            $this->db->select('courier_service.id');
            $this->db->from('courier_service');
            $this->db->where('courier_service.courier_id', $courier_id);
            $this->db->where('courier_service.status', 1);
            if (@$search != '') {
                 $this->db->where('courier_service.display_name LIKE \'%' . @$search . '%\'');
            }
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getserviceslist_by_courier($courier_id, $perpage, $search, $start) {
            $this->db->select('CS.id,CS.display_name,CS.description, CS.service_id,CS.status,CS.org_status,CS.is_public,CS.auto_approve,CS.is_new,organizations.name as org_name');
            $this->db->from('courier_service as CS');
            $this->db->where('CS.courier_id', $courier_id);
            $this->db->join('organizations', 'organizations.id=CS.org_id', 'left');
            $this->db->where('CS.status', 1);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function getallserviceslist_count($courier_id, $search) {
            if ($category != NULL) {
                 switch ($category) {
                      //active
                      case 1:$this->db->where('CS.status', 1);
                           $this->db->where('CS.is_archived', 0);
                           break;
                      //suspended
                      case 2:$this->db->where('CS.status', 3);
                           $this->db->where('CS.is_archived', 0);
                           break;
                      //archived
                      case 3:
                           $this->db->where('CS.is_archived', 1);
                           break;
                 }
            }
            $this->db->select('CS.id');
            $this->db->from('courier_service as CS');
            $this->db->where('CS.courier_id', $courier_id);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->where('CS.status <> 2');
            $query = $this->db->get();
            return $query->num_rows();
       }

       function getallserviceslist_by_courier($courier_id, $perpage, $search, $start, $category = NULL) {
            if ($category != NULL) {
                 switch ($category) {
                      //active
                      case 1:$this->db->where('CS.status', 1);
                           $this->db->where('CS.is_archived', 0);
                           break;
                      //suspended
                      case 2:$this->db->where('CS.status', 3);
                           $this->db->where('CS.is_archived', 0);
                           break;
                      //archived
                      case 3:
                           $this->db->where('CS.is_archived', 1);
                           break;
                 }
            }
            $this->db->select('CS.id,CS.courier_id,CS.display_name as service,CS.description,CS.is_new,'
                    . 'CS.is_public,CS.destination, '
                    . "TIME_FORMAT(CS.start_time, '%h:%i %p' ) as start_time,"
                    . " TIME_FORMAT( CS.end_time, '%h:%i %p' ) as end_time, "
                    . "CS.week_0, CS.week_1, CS.week_2, CS.week_3, CS.week_4, CS.week_5, CS.week_6,"
                    . 'CS.service_id,C.company_name as courier, CS.service_id,CS.is_archived,'
                    . 'CS.status,CS.org_status,CS.is_public,CS.auto_approve,organizations.name as org_name', FALSE);
            $this->db->from('courier_service as CS');
            $this->db->join('couriers as C', 'C.courier_id=CS.courier_id', 'left');
            $this->db->where('CS.courier_id', $courier_id);
            $this->db->join('organizations', 'organizations.id=CS.org_id', 'left');
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->where('CS.status <> 2');
            $this->db->order_by('CS.id', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function get_service_requests_count($courier_id, $perpage, $search, $start) {
            $this->db->select('CS.id,CS.display_name as service,CS.description,count(RCS.request_id) as count', FALSE);
            $this->db->from('courier_service as CS');
            $this->db->where('CS.courier_id', $courier_id);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->join('request_courier_service as RCS', 'CS.id=RCS.service_id');
            $this->db->where('CS.status <> 2');
            $this->db->group_by('CS.id');
            $this->db->order_by('CS.id', 'DESC');
            $this->db->limit($perpage, $start);
            return $this->db->get()->result();
       }

       function get_requested_services_count($courier_id, $search) {
            $this->db->select('CS.id,count(RCS.request_id) as count', FALSE);
            $this->db->from('courier_service as CS');
            $this->db->where('CS.courier_id', $courier_id);
            if (@$search != '') {
                 $this->db->where('CS.display_name LIKE \'%' . @$search . '%\'');
            }
            $this->db->join('request_courier_service as RCS', 'CS.id=RCS.service_id');
            $this->db->where('CS.status <> 2');
            $this->db->group_by('CS.id');
            $this->db->order_by('CS.id', 'DESC');
            return $this->db->get()->num_rows();
       }

       public function get_service_row_id($service_id) {
            $query = $this->db->select('id')
                    ->where(array(
                        'service_id' => $service_id,
                        "status" => 1
                    ))
                    ->get('courier_service')
                    ->row();
            return $query ? $query->id : 0;
       }

       public function get_all_services_for_bidding($courier_id) {
            return $this->db->select('id,service_id,display_name')
                            ->from('courier_service')
                            ->where('is_for_bidding', 1)
                            ->where('status', 1)
                            ->where('courier_id', $courier_id)
                            ->get()
                            ->result();
       }

       public function get_details_by_id($id) {
            return $this->db->select('CS.display_name,CS.description, '
                                    . "TIME_FORMAT(CS.start_time, '%h:%i %p' ) as start_time,"
                                    . " TIME_FORMAT( CS.end_time, '%h:%i %p' ) as end_time, "
                                    . "CS.week_0, CS.week_1, CS.week_2, CS.week_3, CS.week_4, CS.week_5, CS.week_6,"
                                    . 'CS.is_for_bidding,CS.status,CS.org_status,CS.origin,CS.destination,'
                                    . 'CS.is_public,CS.auto_approve,CS.payment_terms,O.name as org_name', FALSE)
                            ->where('CS.id', $id)
                            ->from('courier_service as CS')
                            ->join('organizations as O', 'O.id=CS.org_id', 'left')
                            ->get()
                            ->row();
       }

       public function get_by_id($id) {
            return $this->db->select(" CS.*,TIME_FORMAT(CS.start_time, '%h:%i %p' ) as start_time,  TIME_FORMAT( CS.end_time, '%h:%i %p' ) as end_time", FALSE)
                            ->where('CS.id', $id)
                            ->from('courier_service as CS')
                            ->get()
                            ->row();
       }

       public function get_service_row($id) {
            return $this->db->where('id', $id)->get('courier_service')->row();
       }

       public function get_service_origin($id) {
            return $this->db->select('C.country')
                            ->from('courier_service as CS')
                            ->join('country AS C', 'C.code=CS.origin', 'left')
                            ->where('CS.id', $id)
                            ->get()
                            ->row();
       }

       public function get_service_destination($d_codes) {
            return $this->db->select('C.country', FALSE)
                            ->from('country AS C')
                            ->where_in('C.code', $d_codes)
                            ->get()
                            ->result();
       }

       public function update_service($id, $data, $where = NULL) {
            if ($where != NULL) {
                 $this->db->where($where);
            }
            
            return $this->db->update('courier_service', $data, array(
                        'id' => $id
            ));
       }

       public function get_service_prices($service_id, $type = -1) {
            if ($type >= 0)
                 $this->db->where('type', $type);
            if (isset($data['id']))
                 unset($data['id']);
            return $this->db->select('SP.*, CT.display_name')
                            ->from('service_parcel_price SP')
                            ->join('consignment_type CT', 'CT.consignment_type_id=SP.type')
                            ->where('service_id', $service_id)
                            ->order_by('CT.display_name')
                            ->get()
                            ->result();
       }

       public function add_price_to_service($service_id, $data) {
            $result = $this->get_service_prices($service_id, $data['type']);
            if (count($result) == 0) {
                 if ($this->db->insert('service_parcel_price', $data))
                      return $this->db->insert_id();
            } else
                 return $this->update_service_price($service_id, $data);
       }

       public function update_service_price($service_id, $data) {
            return $this->db->update('service_parcel_price', $data, array('service_id' => $service_id, 'type' => $data['type']));
       }

       public function delete_parcel_price($id) {
            return $this->db->where('id', $id)->delete('service_parcel_price');
       }

       public function get_service_payment_terms($id) {
            return $this->db->select('payments')
                            ->from('courier_service')
                            ->where('id', $id)
                            ->get()
                            ->row();
       }

  }
  