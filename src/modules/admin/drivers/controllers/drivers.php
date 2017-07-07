<?php

class Drivers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array(
            'drivers_model'
        ));
    }

    public function index() {
        $data['org_count'] = $count;
        $this->load->view('driver_details');
    }

    public function get_all_list($data = array()) {
        $search = '';
        $status = '';
        $ordersData = json_decode(file_get_contents('php://input'));
        if (isset($ordersData->perpage_value)) {
            $perpage = $ordersData->perpage_value;
        } else {
            $perpage = 5;
        }
        if (isset($ordersData->currentPage)) {
            $page = $ordersData->currentPage;
        } else {
            $page = 1;
        }
        if (isset($ordersData->filter)) {
            if ($ordersData->filter != NULL) {
                $search = $ordersData->filter;
            } else {
                $search = NULL;
            }
        }
        $status = $ordersData->status;

        $data['drivers'] = $this->drivers_model->getDrivers($perpage, $search, $status, (($page - 1) * $perpage));
        $data['total'] = intval($this->drivers_model->getDriversCount($search, $status));
        $data['currentPage'] = $page;
        $data['perpage_value'] = $perpage;
        echo json_encode($data);
    }

    public function get_driver_details_by_id($driver_id) {
        $data['driverdata'] = $this->drivers_model->getDriverDetailsByDriverId($driver_id);
        $number = explode(' ', $data['driverdata']->mobile_number);
        $dob = explode('-', $data['driverdata']->d_o_b);
        if($data['driverdata']->d_o_b != "0000-00-00"){
          $data['driverdata']->birthday=date('d M Y',  strtotime($data['driverdata']->d_o_b));
        }else{
            $data['driverdata']->birthday=null;
        }
        $data['driverdata']->d_o_b = $dob[1] . '/' . $dob[2] . '/' . $dob[0];
        $data['driverdata']->country_code = $number[0];
        $data['driverdata']->mobile_number = $number[1];
        echo json_encode($data);
    }

    public function activate_driver($driver_id = null) {
        $error = false;
        $result = false;
        if ($driver_id == null) {
            $error = TRUE;
        }
        if (!$error) {
            $result = $this->drivers_model->activate_driver($driver_id);
        }
        echo json_encode($result);
        exit();
    }

    public function suspend_driver($driver_id = null) {
        $error = false;
        $result = false;
        if ($driver_id == null) {
            $error = TRUE;
        }
        if (!$error) {
            $result = $this->drivers_model->suspend_driver($driver_id);
        }
        echo json_encode($result);
        exit();
    }

    public function delete_driver($driver_id = null) {
        $error = false;
        $result = false;
        if ($driver_id == null) {
            $error = TRUE;
        }
        if (!$error) {
            $result = $this->drivers_model->delete_driver($driver_id);
        }
        echo json_encode($result);
        exit();
    }

    public function adddriver() {
        $this->load->view('add_driver');
    }

    public function updateDriver($id) {
        $this->load->view('update_driver');
    }

    public function savedriver() {
        $driverdata = json_decode(file_get_contents('php://input'));
        $date = explode('/', $driverdata->dob);
        $error = array();
        $email_unique = $this->drivers_model->getOneWhere(array('email' => $driverdata->driver_email), 'drivers');
        if ($driverdata->driver_email != "") {
            if (!filter_var($driverdata->driver_email, FILTER_VALIDATE_EMAIL)) {
                $error['driver_email'] = "Email is Not Valid";
            }
            if (!empty($email_unique)) {
                $error['driver_email'] = "Email should be unique";
            }
        }
        $mobile_unique = $this->drivers_model->getOneWhere(array('mobile_number' => $driverdata->country_code . ' ' . $driverdata->mobile_number), 'drivers');
        if (!empty($mobile_unique)) {
            $error['mobile_number'] = "Mobile No. should be unique";
        }

        if (empty($error)) {
            $data = array(
                'name' => $driverdata->display_name,
                'mobile_number' => $driverdata->country_code . ' ' . $driverdata->mobile_number,
                'email' => $driverdata->driver_email,
                'identification_id' => $driverdata->driver_identification_id,
                'passcode' => $driverdata->passcode,
                'd_o_b' => $date[2] . '-' . $date[0] . '-' . $date[1],
                'status' => 1
            );
            $insert_id = $this->drivers_model->insertRow($data, 'drivers');
            $data1 = array(
                'courier_id' => $this->session->userdata('courier_id'),
                'driver_id' => $insert_id
            );
            $this->drivers_model->insertRow($data1, 'courier_drivers');
            $result = array(
                'status' => true,
                'insert_id' => $insert_id
            );
            echo json_encode($result);
        } else {
            $result = array(
                'status' => false,
                'error' => $error
            );
            echo json_encode($result);
        }
        exit();
    }

    public function update_driver_json($driver_id) {
        $driverdata = json_decode(file_get_contents('php://input'));
        $date = explode('/', $driverdata->d_o_b);
        $error = array();
        $email_unique = $this->drivers_model->test_email_unique($driver_id, $driverdata->email, 'drivers');
        if ($driverdata->email != "") {
            if (!filter_var($driverdata->email, FILTER_VALIDATE_EMAIL)) {
                $error['driver_email'] = "Email is Not Valid";
            }
            if (!empty($email_unique)) {
                $error['driver_email'] = "Email should be unique";
            }
        }
        $mobile_unique = $this->drivers_model->test_mob_unique($driver_id, $driverdata->country_code . ' ' . $driverdata->mobile_number, 'drivers');
        if (!empty($mobile_unique)) {
            $error['mobile_number'] = "Mobile No. should be unique";
        }

        if (empty($error)) {
            $data = array(
                'name' => $driverdata->name,
                'mobile_number' => $driverdata->country_code . ' ' . $driverdata->mobile_number,
                'email' => $driverdata->email,
                'identification_id' => $driverdata->identification_id,
                'passcode' => $driverdata->passcode,
                'd_o_b' => $date[2] . '-' . $date[0] . '-' . $date[1],
                'status' => 1
            );
            $where = array(
                'id' => $driverdata->id
            );
            $update = $this->drivers_model->updateWhere($where, $data, 'drivers');
            $result = array(
                'status' => true
            );
            echo json_encode($result);
        } else {
            $result = array(
                'status' => false,
                'error' => $error
            );
            echo json_encode($result);
        }
        exit();
    }

    public function view_driver($id) {
        $this->load->view('view_driver');
    }

}
