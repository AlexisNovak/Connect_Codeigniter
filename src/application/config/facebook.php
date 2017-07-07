<?php

//for facebook

  $config = array(
              // "providers" => array(
              //     "Facebook" => array(
              //         "enabled" => true,
              //         "keys" => array("id" => "294902044046158", "secret" => "e62e398d048e47c645a3dd418588cc04"),
              //     )
              // ),
  			"providers" => array(
                  "Facebook" => array(
                      "enabled" => true,
//                      "keys" => array("id" => "1719372654995843", "secret" => "b63048c5effe32d336b57f2b39cea2a9"),
                      "keys" => array("id" => "1563309927325084", "secret" => "8e274b66dcd0bb845de3a515dd9dcd8a"),
                  )
              ),
              // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
              "debug_mode" => (ENVIRONMENT == 'development'),
              "debug_file" => APPPATH . '/logs/hybridauth.log'
  );
?>