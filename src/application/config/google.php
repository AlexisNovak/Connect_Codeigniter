<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');
  /* !
   * HybridAuth
   * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
   * (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
   */

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

  $config = array(
              // set on "base_url" the relative url that point to HybridAuth Endpoint
              // 'base_url' => '/social/endpoint',
              // "providers" => array(
              //     "Google" => array(
              //         "enabled" => true,
              //         "keys" => array("id" => "1067007954619-dle3rid1jafsdsohtr8sdi9pm6ndd8et.apps.googleusercontent.com", "secret" => "1Jruq2xKNIcOgYAJc6sOM3CD"),
              //     )
              // ),
              'base_url' => '/social/endpoint',
              "providers" => array(
                  "Google" => array(
                      "enabled" => true,
                      "keys" => array("id" => "1071497411353-6ojpo8jkvirj46teth88qrfrhio8hs5h.apps.googleusercontent.com", "secret" => "W0oqmL_qAMfBKK_r13T1Bn94"),
                  )
              ),
              // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
              "debug_mode" => false,//(ENVIRONMENT == 'development'),
              "debug_file" => APPPATH . 'logs/hybridauth.log',
  );



  /* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */