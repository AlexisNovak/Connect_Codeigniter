<?php

  class System extends MY_Controller {

       function __construct() {
            parent::__construct();
       }

       public function isAnonymus() {
            if ($this->session->userdata("user_id")) {
                 return FALSE;
            }
            return TRUE;
       }

  }
  