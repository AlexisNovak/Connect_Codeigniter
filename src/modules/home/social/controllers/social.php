<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Social extends CI_Controller {

       public function __construct() {
            parent::__construct();
            $this->load->model(array('account/account_model', 'account/account_details_model', 'api/token_key_model'));
       }

       public function index() {
            $this->load->view('social/home');
       }

       public function login($provider) {
            log_message('debug', "controllers.HAuth.login($provider) called");

            try {
                 log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
                
 $this->load->library('HybridAuthLib');
                 if ($provider == 'Facebook') {
                      $this->facebook();
                 }

                 if ($this->hybridauthlib->providerEnabled($provider)) {
                      log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
                      $service = $this->hybridauthlib->authenticate($provider);

                      if ($service->isUserConnected()) {
                           log_message('debug', 'controller.HAuth.login: user authenticated.');

                           $user_profile = $service->getUserProfile();

                           log_message('info', 'controllers.HAuth.login: user profile:' . PHP_EOL . print_r($user_profile, TRUE));

                           $data['user_profile'] = $user_profile;
                           if (!$user = $this->account_model->get_by_email($user_profile->email)) {

                                $user_id = $this->account_model->create($user_profile->firstName, $user_profile->email, NULL);

                                // Add user details (auto detected country, language, timezone)
                                $this->account_details_model->update($user_id, array('fullname' => $user_profile->firstName . ' ' . $user_profile->lastName));
                                $token = $this->token();
                                $data['user_id'] = $user_id;
                                $data['token'] = $token;
                                $this->token_key_model->insert('token_key', $data);
                                $this->session->set_userdata('user_id', $user_id);
                           } else {
                                $this->session->set_userdata('user_id', $user->id);
                           }
                           redirect('account/sign_in');

                           $this->load->view('social/done', $data);
                      } else { // Cannot authenticate user
                           show_error('Cannot authenticate user');
                      }
                 } else { // This service is not enabled.
                      log_message('error', 'controllers.HAuth.login: This provider is not enabled (' . $provider . ')');
                      show_404($_SERVER['REQUEST_URI']);
                 }
            } catch (Exception $e) {
                 $error = 'Unexpected error';
                 switch ($e->getCode()) {
                      case 0 :
                           $error = 'Unspecified error.';
                           break;
                      case 1 :
                           $error = 'Hybriauth configuration error.';
                           break;
                      case 2 :
                           $error = 'Provider not properly configured.';
                           break;
                      case 3 :
                           $error = 'Unknown or disabled provider.';
                           break;
                      case 4 :
                           $error = 'Missing provider application credentials.';
                           break;
                      case 5 :
                           log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
                           //redirect();
                           if (isset($service)) {
                                log_message('debug', 'controllers.HAuth.login: logging out from service.');
                                $service->logout();
                           }
                           show_error('User has cancelled the authentication or the provider refused the connection.');
                           break;
                      case 6 :
                           $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
                           break;
                      case 7 :
                           $error = 'User not connected to the provider.';
                           break;
                 }

                 if (isset($service)) {
                      $service->logout();
                 }

                 log_message('error', 'controllers.HAuth.login: ' . $error);
                 show_error('Error authenticating user.');
            }
       }

       public function endpoint() {

            log_message('debug', 'controllers.HAuth.endpoint called.');
            log_message('info', 'controllers.HAuth.endpoint: $_REQUEST: ' . print_r($_REQUEST, TRUE));

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                 log_message('debug', 'controllers.HAuth.endpoint: the request method is GET, copying REQUEST array into GET array.');
                 $_GET = $_REQUEST;
            }

            log_message('debug', 'controllers.HAuth.endpoint: loading the original HybridAuth endpoint script.');
            require_once APPPATH . '/third_party/hybridauth/index.php';
    }

       public function facebook() {
            //facebook
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            parse_str($_SERVER['QUERY_STRING'], $_REQUEST);
            $this->load->config('facebook');
            $this->load->helper('url');
            $providers = $this->config->item('providers');
            $credentials = $providers['Facebook']['keys'];
            $fb_config = array(
                'appId' => $credentials['id'],
                'secret' => $credentials['secret']
            );
            $this->load->library('Facebook', $fb_config);

            $fb_user_id = $this->facebook->getUser();

            if ($fb_user_id == 0) {
                 redirect($this->facebook->getLoginUrl(array('redirect_uri' => site_url('social/facebook'), 'scope' => 'email, public_profile')));
            } else {

                 $fb_user = $this->facebook->api('/me');
                 $user = array(
                     'first_name' => isset($fb_user['first_name']) ? $fb_user['first_name'] : '',
                     'last_name' => isset($fb_user['last_name']) ? $fb_user['last_name'] : '',
                     'login_provider' => 'facebook',
                     'login_provider_id' => $fb_user_id,
                     'active' => 1,
                     'email' => isset($fb_user['email']) ? $fb_user['email'] : ''
                 );
                 if (!$user = $this->account_model->get_by_email($user->email)) {

                      $user_id = $this->account_model->create($user->first_name, $user->email, NULL);

                      // Add user details (auto detected country, language, timezone)
                      $this->account_details_model->update($user_id, array('fullname' => $user->first_name . ' ' . $user->last_name));
                      $token = $this->token();
                      $data['user_id'] = $user_id;
                      $data['token'] = $token;
                      $this->token_key_model->insert('token_key', $data);
                      $this->session->set_userdata('user_id', $user_id);
                 } else {
                      $this->session->set_userdata('user_id', $user->id);
                 }
                 $this->facebook->destroySession();
                 redirect('account/sign_in');
            }
       }

  }

  /* End of file social.php */
/* Location: ./application/controllers/hauth.php */
