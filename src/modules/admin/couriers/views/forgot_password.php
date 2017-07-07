<!DOCTYPE html>
<html>
     <head>
          <?php echo $this->load->view('header', array('title' => 'Forgot password')); ?>
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
                              <h3><?php echo lang('forgot_password_page_name'); ?></h3>
                              <small>
                                   6Connect
                              </small>
                         </div>
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <?php echo form_open(uri_string()); ?>

                                   <div class="well m-b-md"><?php echo lang('forgot_password_instructions'); ?></div>
                                   <?php
                                     if (form_error('forgot_password_username_email') || isset($forgot_password_username_email_error)) {
                                          ?>
                                          <span class="field_error">
                                               <?php
                                               echo form_error('forgot_password_username_email');
                                               echo isset($forgot_password_username_email_error) ? $forgot_password_username_email_error : '';
                                               ?>
                                          </span>
                                     <?php } ?>
                                   <div class="form-group">
                                        <label class="control-label" for="forgot_password_username_email"><?php echo lang('forgot_password_username_email'); ?></label>

                                        <div class="controls" style="margin-bottom:15px;">
                                             <?php
                                               $value = set_value('forgot_password_username_email') ? set_value('forgot_password_username_email') : (isset($account) ? $account->username : '');
                                               $value = str_replace(array('\'', '"'), ' ', $value);
                                               echo form_input(array(
                                                   'name' => 'forgot_password_username_email',
                                                   'id' => 'forgot_password_username_email',
                                                   'value' => $value,
                                                   'maxlength' => '80',
                                                   'class' => 'form-control'
                                               ));
                                             ?>

                                             <span class="help-block small">Your Email to reset password</span>
                                        </div>

                                   </div>



                                   <div class="clearfix">
                                        <?php
                                          echo form_button(array(
                                              'type' => 'submit',
                                              'class' => 'btn btn-logo-green btn-block',
                                              'content' => lang('forgot_password_send_instructions')
                                          ));
                                        ?>
                                   </div>
                                   <div class="clear"></div>
                                   <?php echo form_close(); ?>
                                   <p class="well-sm text-center text-muted">or</p>
                                   <a href="<?= site_url("couriers/login") ?>" class="btn btn-logo-yellow btn-block"><?= lang('or_sign_in') ?></a>
                                   <div class="col-md-10 col-md-offset-1 text-center sign_in_footer">
                                        <?php echo lang('website_copy_right_phokki'); ?> 
                                   </div>

                              </div>
                         </div>
                    </div>
               </div>
          </div>



     </body>
</html>
