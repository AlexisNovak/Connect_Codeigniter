<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  function debug($var, $label = 'Debug Data') {
       echo '<h1>' . $label . '</h1><pre>';
       print_r($var);
       echo '</pre>';
       exit();
  }

  function debug_continue($var, $label = 'Debug Data') {
       echo '<h1>' . $label . '</h1><pre>';
       print_r($var);
       echo '</pre>';
  }

  if (!function_exists('_can')) {

       function _can($feature) {
            global $features;
            return in_array($feature, $features);
       }

  }

  function pluck($objects, $name) {
       $array = array();
       foreach ($objects as $object) {
            $array[] = $object->$name;
       }
       return $array;
  }
  