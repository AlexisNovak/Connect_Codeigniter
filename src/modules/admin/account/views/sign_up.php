

<!DOCTYPE html>
<html ng-app="partner-portal">
     <head>
          <?php echo $this->load->view('site/head'); ?>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "/";
          </script>
          <style>
               html { 
                    background: url(<?= base_url() ?>resource/styles/img/login.png) no-repeat center center fixed; 
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
               }
               .login_username{
                    font-weight: normal;
               }
          </style>

     </head>
     <body class="blank" ng-app="appController">
          <div class="color-line"></div>

          <div class="login-container">
               <div class="row">
                    <div class="col-md-12">
                         <!--                         <div class="text-center m-b-md">
                                                       <h3><?php echo lang('sign_up_heading'); ?></h3>
                         
                                                  </div>-->
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <?php echo form_open(uri_string(), 'class=""'); ?>
                                   <?php echo form_fieldset(); ?>
                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_username">Full Name</label>
                                        <?php if (isset($invited_token)) {
                                               ?>
                                               <input type="hidden" name="invited_token" value="<?= $invited_token ?>">
                                               <?php
                                          }
                                        ?>
                                        <input type="text" placeholder="Full Name" title="Please enter you fullname" required value="<?= set_value('sign_up_username') ?>" name="sign_up_username" id="sign_up_username" ng-model="signupData.username" class="form-control">
                                        <?php if (form_error('sign_up_username') || isset($sign_up_username_error)) : ?>
                                               <div class="clr"></div>
                                               <span class="help-inline">
                                                    <?php echo form_error('sign_up_username'); ?>
                                                    <?php if (isset($sign_up_username_error)) : ?>
                                                         <span class="field_error"><?php echo $sign_up_username_error; ?></span>
                                                    <?php endif; ?>
                                               </span>
                                          <?php endif; ?>

                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_password">Email <small class="login_username">(This will be used as your login username.)</small></label> 
                                        <input type="text" title="Email" placeholder="Email" required value="<?php
                                          if (isset($invited_email)) {
                                               echo $invited_email;
                                          } else {
                                               echo set_value('sign_up_email');
                                          }
                                        ?>" name="sign_up_email" id="sign_up_email" class="form-control" ng-model="signupData.email">
                                               <?php if (form_error('sign_up_email') || isset($sign_up_email_error)) : ?>
                                               <div class="clr"></div>
                                               <span class="help-inline">
                                                    <?php echo form_error('sign_up_email'); ?>
                                                    <?php if (isset($sign_up_email_error)) : ?>
                                                         <span class="field_error"><?php echo $sign_up_email_error; ?></span>
                                                    <?php endif; ?>
                                               </span>
                                          <?php endif; ?>
                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_password">Password</label>
                                        <input type="password" title="Password" placeholder="Password" required value="" name="sign_up_password" id="sign_up_password" ng-model="signupData.password" class="form-control">
                                        <div class="clr"></div>
                                        <?php if (form_error('sign_up_password')) : ?>
                                               <span class="help-inline">
                                                    <?php echo form_error('sign_up_password'); ?>
                                                    <div class="clr"></div>
                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
                                   </div>

                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_password">Confirm Password</label>
                                        <input type="password" title="Confirm Password" placeholder="Password" required value="" name="sign_up_confirm_password" id="sign_up_confirm_password" ng-model="signupData.confirm_password" class="form-control">
                                        <div class="clr"></div>
                                        <?php if (isset($sign_up_password_error)) : ?>
                                               <span class="field_error"><?php echo $sign_up_password_error; ?></span>
                                          <?php endif; ?>
                                   </div>
                                   <div class="form-group">
                                        <input type="checkbox" class="i-checks" name="sign_up_policy" value="1" <?php echo set_checkbox('sign_up_policy', '1'); ?>>
                                       
                                        <span class="small"><?= sprintf(lang('privacy_policy'), anchor(site_url('privacy'), lang('privacy_link'), array('target' => "_blank", 'class' => "link_color")), anchor(site_url('terms'), lang('terms_link'), array('target' => "_blank", 'class' => "link_color"))) ?></span>
                                        <div class="clr"></div>
                                        <?php if (form_error('sign_up_policy')) : ?>
                                               <span class="field_error">
                                                    <?php echo 'In order to use our services, you must agree to 6Connect\'s Terms of Service.' ?>
                                                    <div class="clr"></div>
                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
                                   </div>
                                   <button class="btn btn-logo-green btn-block" ng-click="signup()">Submit</button>
                                   <p style="margin-top:10px;"><?php echo lang('sign_up_already_have_account'); ?> <?php echo anchor('account/sign_in', lang('sign_up_sign_in_now'), 'class="btn btn-block btn-logo-yellow margin_top_10"'); ?></p>
                                   </form>
                                   <?php if (isset($sign_in_error)) : ?>
                                          <div id="output"><?php echo $sign_in_error; ?></div>
                                     <?php endif; ?>
                                   <div class="col-md-10 col-md-offset-1 text-center sign_in_footer">
                                        <?php echo lang('website_copy_right_phokki'); ?> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <!--               <div class="row" style="margin-top: 30px;">
                                   <div class="col-md-12 text-center">
               <?php echo lang('website_copy_right'); ?> 
                                   </div>
                              </div>-->
          </div>
          <script>
               $(document).ready(function () {
                    $('.i-checks').iCheck({
                         checkboxClass: 'icheckbox_square-green',
                         radioClass: 'iradio_square-green',
                    });
               });
          </script>
     </div>

</div>
<?php echo $this->load->view('site/footer'); ?>