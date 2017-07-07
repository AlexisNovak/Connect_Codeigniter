<?php

  /*
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */

  /**
   * Description of cust_parcel_type
   *
   * @author nice
   */
  class cust_parcel_type extends MY_Controller {

       //put your code here
       function __construct() {
            parent::__construct();
            $this->load->model('cust_parcel_type_model');
       }

       public function index() {
            $this->load->view('cust_parcel_type_list');
       }

       function get_list() {
            $list = $this->cust_parcel_type_model->get_customTypes();
            $org_list = $this->cust_parcel_type_model->get_orgList();
            echo json_encode(array('types' => $list, 'org_list' => $org_list));
       }

       function newtype() {
            $this->load->view('add_parcel_type');
       }

       function edit($id = 0) {
            $data = array('id' => $id);
            $this->load->view('add_parcel_type', $data);
       }

       function save() {
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->consignment_type_id) && !empty($post_data->consignment_type_id)) {
                 $id = (int) $post_data->consignment_type_id;
            }
            if (isset($post_data->display_name) && !empty($post_data->display_name)) {
                 $displayname = htmlentities($post_data->display_name);
            } else {
                 $error = true;
                 $errors['display_name'] = "Invalid name";
            }

            if (isset($post_data->description) && !empty($post_data->description)) {
                 $description = htmlentities($post_data->description);
            } else {
                 $error = true;
                 $errors['description'] = "Invalid name";
            }

            if (isset($post_data->org_id)) {
                 $content = $post_data->org_id;
                 $org_id = $content->id;
            } else {
                 $error = true;
                 $errors['org_ig'] = "Invalid name";
            }
            $data = array('display_name' => $displayname, 'description' => $description, 'org_id' => $org_id);
            if (isset($id)) {
                 $this->cust_parcel_type_model->updateWhere(array('consignment_type_id' => $id), $data, 'consignment_type');
            } else {
                 $this->cust_parcel_type_model->insertRow($data, 'consignment_type');
            }
            echo json_encode(array('status' => 1));
       }

       function getsingle($id = 0) {
            $data = array();
            if ($id != 0) {
                 $ctype = $this->cust_parcel_type_model->getOneWhere(array('consignment_type_id' => $id), 'consignment_type');
                 $org = $this->cust_parcel_type_model->getOneWhere(array('id' => $ctype->org_id), 'organizations');
                 $ctype->org_id = $org;
                 $data['ptype'] = $ctype;
            }
            echo json_encode($data);
       }

       function delete_type() {
            $post_data = json_decode(file_get_contents("php://input"));
            if (isset($post_data->id) && !empty($post_data->id)) {
                 $id = (int) $post_data->id;
            }
            if (isset($id)) {
                 $status = $this->cust_parcel_type_model->deleteWhere(array('consignment_type_id' => $id), 'consignment_type');
                 $data = array('status' => $status);
            } else {
                 $data = array('status' => false);
            }
            echo json_encode($data);
       }

  }
  