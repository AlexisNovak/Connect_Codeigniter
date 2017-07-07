<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  if (!function_exists('convert_time')) {

       function convert_time($time, $timezone) {
            $date = new DateTime($time);

            $date->setTimezone(new DateTimeZone($timezone));
            return $date->format('m/d/Y h:i A');
       }

  }