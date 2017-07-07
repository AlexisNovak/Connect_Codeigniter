<?php

class drivers_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	function getDrivers($perpage, $search, $status, $start){
		$this->db->select('*')
		->from('drivers');
		if (@$search != '') {
			$this->db->where('(drivers.name LIKE \'%' . @$search . '%\' OR drivers.mobile_number LIKE \'%' . @$search . '%\')');
		}
		 return $this->db->where('is_deleted', 0)
		 ->where('status', $status)
		 ->group_by('drivers.id')	
		->limit($perpage, $start)
		->get()
		->result();
	}

	function getDriversCount($search){
		return $this->db->select('*')
		->from('drivers')
		->where('is_deleted', 0)
		->get()->num_rows();
	}

	function activate_driver($driver_id){
		$where = array(
			'id' =>$driver_id
			);
		$data = array(
			'status' =>1
			);
		$tablename = 'drivers';
		return $this->updateWhere($where, $data, $tablename);
	}

	function suspend_driver($driver_id){
		$where = array(
			'id' =>$driver_id
			);
		$data = array(
			'status' =>0
			);
		$tablename = 'drivers';
		return $this->updateWhere($where, $data, $tablename);
	}

	function delete_driver($driver_id){
		$where = array(
			'id' =>$driver_id
			);
		$data = array(
			'is_deleted' =>1
			);
		$tablename = 'drivers';
		return $this->updateWhere($where, $data, $tablename);
	}

	function getDriverDetailsByDriverId($driver_id){
		return $this->db->select('*')
		->from('drivers')
		->where('id', $driver_id)
		->get()
		->row();
	}

	function test_email_unique($id, $where, $table){
		return $this->db->select('*')
		->from('drivers')
		->where('email', $where)
		->where('id !=', $id)
		->get()
		->result();
	}

	function test_mob_unique($id, $where, $table){
		return $this->db->select('*')
		->from('drivers')
		->where('mobile_number', $where)
		->where('id !=', $id)
		->get()
		->result();
	}

}
