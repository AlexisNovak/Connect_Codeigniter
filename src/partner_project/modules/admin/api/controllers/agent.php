<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  require(APPPATH . '/libraries/REST_Controller.php');

  class Agent extends REST_Controller {

       public function __construct() {
            parent::__construct();
            $this->load->model('agent/agent_model');
            $this->load->model('agent/agentcompany_model');
            $this->load->helper('utility');
       }

       public function request_post() {
            //log_message("error","called API request");

            $domain = $this->input->post('domain');
            $api_key = $this->input->post('api_key');
            $profile = $this->agentcompany_model->getMasterCompanyProfileByDomain($domain);
            $host_parts = explode(".", $_SERVER['HTTP_HOST']);
            // $host_parts = host_part($host_parts);
            $own_profile = $this->agentcompany_model->getMasterCompanyProfileByDomain($host_parts[0]);

            //log_message("error","profile ".print_r($profile,true));
            //log_message("error","own profile ".print_r($own_profile,true));
            //create a record in agent table
            $agent = array(
                'company_id' => $profile['company_id'],
                'company_name' => $profile['company_name'],
                'email' => $profile['email'],
                'api_url' => $domain,
                'api_key' => $api_key,
                'status' => 0
            );

            //log_message("error","agent saved ".print_r($agent,true));

            $agent_id = $this->agent_model->saveAgent($agent);
            if (!$agent_id) {
                 $this->response(array(
                     'success' => FALSE,
                     'msg' => 'Error : API error1'
                 ));
                 exit;
            }

            //save approval request
            $token = generateRandomString();
            $request = array(
                'agent_id' => $agent_id,
                'token' => $token,
                'is_valid' => 1
            );

            //log_message("error","approval request ".print_r($request,true));

            if (!$this->agent_model->saveAgentRequest($request)) {
                 $this->response(array(
                     'success' => FALSE,
                     'msg' => 'Error : API error2'
                 ));
                 exit;
            }

            //send email for approval
            $url = getProtocol() . "://" . $_SERVER['HTTP_HOST'] . "/index.php/agent/approve?r=" . $token;

            //log_message("error","approval email ".print_r($url,true));

            $msg = $this->load->view('api/mail/approval_mail', array(
                'to_company_email' => $own_profile['email'],
                'from_company_name' => $profile['company_name'],
                'approve_url' => $url
                    ), TRUE);

            //log_message("error", $msg);

            $this->save_mail($own_profile['email'], '', "Agent Request", $msg, $profile);

            /*        $this->load->library('email');
              $this->email->from($profile['email'],$profile['company_name']);
              $this->email->to($own_profile['email']);
              $this->email->subject("Agent Request");
              $this->email->message($msg);
              $this->email->send();
             */
            $this->response(array(
                'success' => TRUE,
                'msg' => 'Request pending for approval'
            ));
       }

       public function approve_post() {
            $domain = $this->input->post('domain');
            $api_key = $this->input->post('api_key');
            $agent_api_key = $this->input->post('my_api_key');

            //verify api_key?
            //log_message("error","API approve call : API_KEY = ".$api_key);
            $condition = array(
                'api_url' => $domain
            );
            $data = array(
                'api_key' => $agent_api_key,
                'status' => 1
            );
            $this->agent_model->updateAgent($condition, $data);

            $this->response(array(
                'success' => TRUE,
                'msg' => 'Request approved'
            ));
       }

       public function approve2_post() {
            $domain = $this->input->post('domain');
            $email = $this->input->post('email');
            $company_id = $this->input->post('company_id');
            $company_name = $this->input->post('company_name');
            $api_key = $this->input->post('api_key');
            $agent_api_key = $this->input->post('my_api_key');

            //verify api_key?

            $condition = array(
                'verify_key' => $api_key
            );
            $data = array(
                'company_id' => $company_id,
                'company_name' => $company_name,
                'email' => $email,
                'api_key' => $agent_api_key,
                'api_url' => $domain,
                'status' => 1
            );
            $this->agent_model->updateAgent($condition, $data);

            $this->response(array(
                'success' => TRUE,
                'msg' => 'Request approved'
            ));
       }

       private function _verifyApiKey($subdomain, $token) {
            $agent = $this->agent_model->getAgent(array('api_url' => $subdomain));
            if (empty($agent)) {
                 return false;
            }
            $verify = getApiToken($subdomain, $agent->verify_key);
            return $token == $verify;
       }

       public function consignmentassignment_post() {

            $agent = $this->agent_model->getOneWhere(array('api_key' => $this->input->post('verify_key'), 'verify_key' => $this->input->post('api_key')), 'agents');

            $consignment = $this->input->post('consignment');
            $delivery = $this->input->post('delivery');

            $delchk = $this->agent_model->getOneWhere(array('source_delivery_id' => $delivery['delivery_id'], 'source_id' => $agent->agent_id), 'delivery');
            if ($delchk) {
                 $this->response(array(
                     'success' => FALSE,
                     'msg' => 'Consignment Already Assigned to this agent.'
                 ));
            }

            unset($consignment['consignment_id']);
            $consignment['vip_user_id'] = 0;
            $consignment['customer_id'] = 0;
            $consignment['read'] = 0;
            $consignment['created_date'] = date('Y-h-d H:i:s');
            // log_message("error", 'consignment '.print_r($consignment,true));
            $consignment_id = $this->agent_model->insertRow($consignment, 'consignment');

            $delivery['consignment_id'] = $consignment_id;
            $delivery['source_id'] = $agent->agent_id;
            $delivery['driver_id'] = 0;
            $delivery['agent_id'] = 0;
            $delivery['created_date'] = date('Y-h-d H:i:s');
            $delivery['source_delivery_id'] = $delivery['delivery_id'];
            unset($delivery['delivery_id']);
            // log_message("error", 'delivery '.print_r($delivery,true));
            $this->agent_model->insertRow($delivery, 'delivery');

            $this->response(array(
                'success' => TRUE,
                'msg' => 'Consignment Assigned to agent.'
            ));
       }

       public function change_status_post() {

            $agent = $this->agent_model->getOneWhere(array('api_key' => $this->input->post('verify_key'), 'verify_key' => $this->input->post('api_key')), 'agents');

            $delivery_id = $this->input->post('delivery_id');
            $status_id = $this->input->post('status_id');

            $this->agent_model->updateWhere(array('delivery_id' => $delivery_id), array('status_id' => $status_id), 'delivery');

            $insert_data['user_type'] = '';
            $insert_data['agent_id'] = $agent->agent_id;
            $insert_data['driver_id'] = 0;
            $insert_data['gps'] = '';
            $insert_data['changed_user_id'] = '';
            $insert_data['delivery_id'] = $delivery_id;
            $insert_data['old_status_id'] = '';
            $insert_data['new_status_id'] = $status_id;
            $insert_data['created_date'] = date('Y-m-d H:i:s');
            $this->agent_model->insertRow($insert_data, 'log_change_status');

            $this->response(array(
                'success' => TRUE,
                'msg' => 'Consignment Status changed.'
            ));
       }

       protected function save_mail($to, $to_name, $subject, $message, $profile) {

            $mail_settings = $this->options_model->getOption('mail_settings');
            //debug($mail_settings);
            if (!isset($mail_settings['protocol']))
                 return false;

            $this->load->library('email');

            if ($mail_settings['protocol'] == 'sendmail') {
                 $config['protocol'] = 'sendmail';
                 $config['mailpath'] = $mail_settings['sendmail_path'];
            } else if ($mail_settings['protocol'] == 'smtp') {
                 $config['protocol'] = 'smtp';
                 $config['smtp_host'] = $mail_settings['smtp_host'];
                 $config['smtp_port'] = $mail_settings['smtp_port'];
                 $config['smtp_user'] = $mail_settings['smtp_username'];
                 $config['smtp_pass'] = $mail_settings['smtp_password'];
            } else {
                 $config['protocol'] = 'mail';
            }

            $config['charset'] = 'iso-8859-1';
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $this->email->from($profile['email'], $profile['company_name']);
            $this->email->to($to, $to_name);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->set_newline("\r\n");

            return $this->email->send();




            /* $this->load->library('email');

              $config['protocol'] = 'smtp';

              //$config['smtp_host'] = 'smtp.ntc.net.np';
              //$config['smtp_host'] = 'smtp.subisu.net.np';

              //$config['smtp_host'] = 'smtp.sendgrid.net';
              //$config['smtp_user'] = 'dns@webcreators.com';
              //$config['smtp_pass'] = 'a11RepXX';
              //$config['smtp_port'] = 25;

              $config['smtp_host'] = 'mail.alucio.com.np';
              $config['smtp_user'] = 'web@alucio.com.np';
              $config['smtp_pass'] = 'asdfasdf';
              $config['smtp_port'] = 25;

              $config['smtp_host'] = 'ssl://smtp.googlemail.com';
              $config['smtp_user'] = 'sushilalucio@gmail.com';
              $config['smtp_pass'] = 'alucio123';
              $config['smtp_port'] = 465;

              // $config['mailtype'] = 'html';
              // $config['charset']  = 'utf-8';
              // $config['newline']  = "\r\n";
              // $config['wordwrap'] = TRUE;

              $config['charset'] = 'iso-8859-1';
              $config['wordwrap'] = TRUE;
              $config['mailtype'] = 'html';
              $this->email->initialize($config);

              $this->email->from( $profile['email'],$profile['company_name']);
              $this->email->to($to, $to_name);
              $this->email->subject($subject);
              $this->email->message($message);
              $this->email->set_newline("\r\n");

              return $this->email->send(); */
       }

  }
  