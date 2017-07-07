<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');
  if (!function_exists('valid_email')) {

       function valid_email($address) {
            return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
       }

  }

  function save_mail($to, $to_name, $subject, $message, $type = 0, $cc = '', $bcc = '', $attachment = NULL) {
       $CI = & get_instance();
       $CI->load->model('mailqueue_model', 'mailq');
       $data = array(
           'to' => $to,
           "name" => $to_name,
           "subject" => $subject,
           'message' => json_encode($message),
           'status' => 1,
           'user_type' => $type
       );
       $data['cc'] = $cc;
       $data['bcc'] = $bcc;
       if ($attachment !== NULL) {
            $data['attachment'] = json_encode($attachment);
       }
       return $CI->mailq->add_mailqueue($data);
  }

  function send_mail($to, $to_name, $subject, $message, $cc = '', $bcc = '', $attachment = NULL) {
       $CI = & get_instance();
       $CI->load->library('email');
       $CI->load->config('email');
       $CI->email->from($CI->config->item('from_email'), $CI->config->item('from_email_name'));
       $CI->email->to($to, $to_name);
       $CI->email->subject($subject);
       $CI->email->message($message);
       $CI->email->set_newline("\r\n");

       if (!empty($attachment)) {
            if (is_array($attachment)) {
                 foreach ($attachment as $file)
                      $CI->email->attach($file);
            } else
                 $CI->email->attach($file);
       }

       if (!empty($cc))
            $CI->email->cc($cc);

       if (!empty($bcc))
            $CI->email->bcc($bcc);

       if ($CI->email->send()) {
            return TRUE;
       } else {
            return $CI->email->print_debugger();
       }
  }
  