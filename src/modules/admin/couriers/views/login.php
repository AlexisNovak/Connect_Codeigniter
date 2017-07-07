<!DOCTYPE html>
<html>
     <head>
          <?php echo $this->load->view('header'); ?>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "";
          </script>
          <style>
               html { 
                    background: url(<?= base_url() ?>resource/images/courier_login_bg.png) no-repeat center center fixed; 
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

               <?php if ($this->session->flashdata('message')) : ?>
                      <p class="alert alert-danger">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                           </button>
                           <?php echo $this->session->flashdata('message'); ?>
                      </p>
                 <?php endif; ?>  
               <div class="row">
                    <div class="col-md-12">
                         <div class="text-center m-b-md login_head">
                              <h3><?php echo lang('courier_sign_in_heading'); ?></h3>
                         </div>
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <?php if (isset($password_info)) : ?>
                                          <span class="field_success "><?php echo $password_info; ?></span>
                                     <?php endif; ?>  
                                   <?php echo form_open(site_url('couriers/login'), 'class=""'); ?>

                                   <?php if (isset($sign_in_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_error; ?></span>
                                     <?php endif; ?>
                                   <?php if (isset($sign_in_username_email_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_username_email_error; ?></span>
                                     <?php endif; ?>


                                   <div class="form-group ">
                                        <label class="control-label">Email</label>
                                        <input type="email" placeholder="Email" title="Please enter you email" required name="sign_in_username_email" id="sign_in_username_email" class="form-control" value="<?php echo set_value('sign_in_username_email'); ?>">
                                        <span class="help-block small"><?= lang('username_info') ?></span>
                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="password">Password</label>
                                        <input type="password" title="Please enter your password" placeholder="Password" required  value="<?php echo set_value('sign_in_password'); ?>" name="sign_in_password" id="sign_in_password" class="form-control">
                                        <span class="help-block small">

                                             Your strong password

                                        </span>
                                   </div>
                                   <input type="hidden" name="hashdata" value=""/>
                                   <div class="checkbox col-lg-6 no-padding" style="margin-top: 0px !important">
                                        <input type="checkbox" class="i-checks" name="sign_in_remember" value="checked" checked>
                                        Remember login
                                        <p class="help-block small">(if this is a private computer)</p>
                                   </div>
                                   <div class="checkbox col-lg-6 no-padding text-right" style="margin-top: 0px !important">

                                        <a class="forgot_link" href="<?php echo site_url('couriers/forgot_password'); ?>">Forgot Password ?</a>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="col-lg-6 no-padding margin_left" style="margin-right:5px">
                                        <button class="btn btn-logo-green btn-block">Login</button>
                                   </div>
                                   <div class="col-lg-6 no-padding margin_right" style="margin-right:-5px">
                                        <a class="btn btn-logo-yellow btn-block" href="<?php echo site_url('couriers/register'); ?>">Register</a>
                                   </div>
                                   <div class="clearfix"></div>
                                   <p class="text-muted" style="padding: 6px 0px;">Are you a customer? Click <a href="<?php echo site_url('account/sign_in'); ?>" class="link_color">here</a> to login.</p>    
                                   </form>
                                   <div class="col-md-10 col-md-offset-1 text-center sign_in_footer">
                                        <?php echo lang('website_copy_right_phokki'); ?> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <script>

                    var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";

                    var ROOT_PATH = "<?= base_url() ?>";

                    function redirectHash() {
                         var uri = location.href;
                         var hash = location.hash;
                         if (hash)
                         {
                              $('input[name="hashdata"]').val(hash);
                         }
                    }
                    $(document).ready(function () {
                         $('.i-checks').iCheck({
                              checkboxClass: 'icheckbox_square-green',
                              radioClass: 'iradio_square-green'
                         });
                         redirectHash();
                    });
               </script>
               <?php echo $this->load->view('footer'); ?>