<?php

class Tip_of_the_day extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('tip_of_the_day_model');
    }

    public function index() {
        $this->load->view('tip_of_the_day_list');
    }

    public function get_tip_list() {
        $result = array();
        $tips = $this->tip_of_the_day_model->get_all_tips();
        foreach ($tips as $tip) {
            $tip->content = html_entity_decode($tip->content);
        }
        $result['tips'] = $tips;
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function save() {
        $result = array();
        $error = FALSE;
        $errors = array();
        $post_data = json_decode(file_get_contents("php://input"));
        if (isset($post_data->id) && !empty($post_data->id)) {
            $id = (int) $post_data->id;
        }
        if (isset($post_data->content) && !empty($post_data->content)) {
            $content = htmlentities($post_data->content);
        } else {
            $error = true;
            $errors['content'] = lang('tip_content_error');
        }
        if (!$error) {
            $user_id = $this->session->userdata('partner_user_id');
            if (isset($id)) {
                $rs = $this->tip_of_the_day_model->update_tip($id, array('content' => $content, 'updated_on' => date('Y-m-d H:i:s', now())));
            } else {
                $rs = $this->tip_of_the_day_model->add(array('content' => $content, 'user_id' => $user_id));
            }
            if ($rs) {
                if (isset($id)) {
                    $result['msg'] = lang('update_tip_added_suc');
                } else {
                    $result['msg'] = lang('new_tip_added_suc');
                }
                $result['class'] = "alert-success";
                $result['status'] = 1;
            } else {
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
                $result['status'] = 0;
            }
        } else {
            $result['msg'] = lang('try_again');
            $result['class'] = "alert-danger";
            $result['status'] = 0;
            $result['errors'] = $errors;
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function delete_tip() {
        $result = array();
        $post_data = json_decode(file_get_contents("php://input"));
        if (isset($post_data->id) && !empty($post_data->id)) {
            $id = (int) $post_data->id;
            if ($this->tip_of_the_day_model->delete_tip($id)) {
                $result['msg'] = lang('tip_delete_suc');
                $result['class'] = "alert-success";
                $result['status'] = 1;
            } else {
                $result['msg'] = lang('try_again');
                $result['class'] = "alert-danger";
                $result['status'] = 0;
            }
        } else {
            $result['msg'] = lang('try_again');
            $result['class'] = "alert-danger";
            $result['status'] = 0;
        }
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit();
    }

    public function get_tip_of_the_day() {
        $result = array();
        $tip = $this->tip_of_the_day_model->get_random_tip();
        if ($tip) {
            $result['tip'] = html_entity_decode($tip->content);
        }
        echo json_encode($result);
        exit();
    }

}
