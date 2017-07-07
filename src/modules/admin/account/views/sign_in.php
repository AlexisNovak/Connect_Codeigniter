<!DOCTYPE html>
<html ng-app="partner-portal">
     <head>
          <?php echo $this->load->view('site/head'); ?>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "";
          </script>
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
     <body class="blank" ng-app="appController">
          <div class="color-line"></div>
          <div class="login-container">
               <div class="row">
                    <div class="col-md-12">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <?php if (isset($password_info)) : ?>
                                          <span class="field_success "><?php echo $password_info; ?></span>
                                     <?php endif; ?>  
                                   <?php echo form_open(site_url('account/sign_in'), 'class=""'); ?>
                                   <?php echo form_fieldset(); ?>
                                   <?php if (isset($sign_in_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_error; ?></span>
                                     <?php endif; ?>
                                   <?php if (isset($sign_in_username_email_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_username_email_error; ?></span>
                                     <?php endif; ?>
                                   <div class="form-group ">
                                        <label class="control-label" for="username">Email</label>
                                        <input type="email" placeholder="Email" title="Please enter you email" required value="<?php echo set_value('sign_in_username_email'); ?>" name="sign_in_username_email" id="sign_in_username_email" ng-model="signinData.username" class="form-control">
                                        <span class="help-block small"><?= lang('username_info') ?></span>
                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="password">Password</label>
                                        <input type="password" title="Please enter your password" placeholder="Password" required value="" name="sign_in_password" id="sign_in_password" class="form-control">
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

                                        <a class="forgot_link" href="<?php echo site_url('account/forgot_password'); ?>">Forgot Password ?</a>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="<?php
                                     if ($this->config->item("sign_up_enabled"))
                                          echo 'col-lg-6';
                                     else
                                          echo 'col-lg-12';
                                   ?> no-padding margin_left" style="margin-right:5px">
                                        <button class="btn btn-logo-green btn-block" ng-click="login()">Login</button>
                                   </div>
                                   <?php if ($this->config->item("sign_up_enabled")) { ?>
                                          <div class="col-lg-6 no-padding margin_right" style="margin-right:-5px">
                                               <a class="btn btn-logo-yellow btn-block" href="<?php echo site_url('account/sign_up'); ?>">Register</a>
                                          </div>
                                     <?php } ?>
                                   <div class="clearfix"></div>
                                   <div class="col-lg-6 no-padding margin_left" style="margin-right:5px">
                                        <a href="<?= site_url('social/facebook') ?>" class="fb-link"><div class="fb-icon-bg"><i class="fa fa-2x fa-facebook"></i></div>
                                             <div class="fb-bg"></div></a>
                                   </div>
                                   <div class="col-lg-6 no-padding margin_right" style="margin-right:-5px">
                                        <a href="<?= site_url('social/login/Google') ?>" class="g-link"><div class="g-icon-bg"><i class="fa fa-2x fa-google-plus"></i></div>
                                             <div class="g-bg"></div></a>
                                   </div>
                                   <div class="clearfix"></div>
                                   <p class="text-muted" style="padding: 6px 0px;">Are you a courier? Click <a href="<?php echo site_url('couriers/login'); ?>" class="link_color">here</a> to login.</p>    
                                   </fieldset>
                                   </form>

                                   <div class="col-md-10 col-md-offset-1 text-center sign_in_footer">
                                        <?php echo lang('website_copy_right_phokki'); ?> 
                                   </div>
                              </div>

                         </div>
                    </div>
               </div>

          </div>

          <script>

               var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";

               var ROOT_PATH = "<?= base_url() ?>";

               function redirectHash()
               {
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
                         radioClass: 'iradio_square-green',
                    });

                    redirectHash();
               });

          </script>
     </div>

</div>
<?php echo $this->load->view('site/footer'); ?>