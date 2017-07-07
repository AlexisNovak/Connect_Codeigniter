<!DOCTYPE html>
<html>
     <head>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "";
          </script>

          <?php echo $this->load->view('site/head', array('title' => 'Forgot password')); ?>
          <style>
               html { 
                    background: url(<?= base_url() ?>resource/styles/img/login.png) no-repeat center center fixed; 
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
               }
          </style>
     </head>
     <body class="blank">
          <div class="color-line"></div>
          <div class="login-container">
               <div class="row">
                    <div class="col-md-12">
                         <div class="text-center m-b-md">
                              <h3><?php echo lang('website_welcome'); ?></h3>
                         </div>
                         <div class="hpanel">
                              <div class="panel-body lite-shadow">

                                   <?php echo lang('member_register_success'); ?>
                                   <p><?php echo sprintf(lang('member_register_success_msg'), $email); ?></p>
                                   <?php
                                     echo anchor(site_url('account/sign_in'), lang('sign_up_sign_in_now'), 'class="btn btn-success btn-block"');
                                   ?>

                              </div>
                         </div>
                    </div>
               </div>

               <?php echo $this->load->view('footer'); ?>