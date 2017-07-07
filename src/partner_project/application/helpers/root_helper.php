<?php

  if (!function_exists('outer_base_url')) {

       
       // function to get outer base url exclude partner_project
       function outer_base_url($uri = '') {
            $CI = & get_instance();
            $return = $CI->config->base_url($uri);
            return str_replace(IFRAME_FOLDER.'/', '', $return);
       }

  }
