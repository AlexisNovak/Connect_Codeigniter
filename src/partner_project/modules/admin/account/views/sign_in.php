<!DOCTYPE html>
<html ng-app="partner-portal">
     <head>
          <?php echo $this->load->view('site/head'); ?>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "";
          </script>

     </head>
     <body class="blank" ng-app="appController">
          <div class="color-line"></div>
          <div class="login-container">
               <div class="row">
                    <div class="col-md-12">

                         <div class="text-center m-b-md">
                              <h3><?php echo lang('sign_in_heading'); ?></h3>
                              <small><?php echo lang('website_intro'); ?></small>
                         </div>
                         <div class="hpanel">
                              <div class="panel-body lite-shadow">
                                   <?php if (isset($password_info)) : ?>
                                          <span class="field_success "><?php echo $password_info; ?></span>
                                     <?php endif; ?>  
                                   <?php echo form_open(site_url('account/sign_in' . $this->input->get('continue') ? '/?continue=' . urlencode($this->input->get('continue')) : ''), 'class=""'); ?>
                                   <?php echo form_fieldset(); ?>
                                   <?php if (isset($sign_in_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_error; ?></span>
                                     <?php endif; ?>
                                   <?php if (isset($sign_in_username_email_error)) : ?>
                                          <span class="field_error"><?php echo $sign_in_username_email_error; ?></span>
                                     <?php endif; ?>
                                   <div class="form-group ">
                                        <label class="control-label" for="username">Username</label>
                                        <input type="text" placeholder="Username / Email" title="Please enter you username" required value="" name="sign_in_username_email" id="sign_in_username_email" ng-model="signinData.username" class="form-control">
                                        <span class="help-block small">

                                             Your unique username to application

                                        </span>
                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="password">Password</label>
                                        <input type="password" title="Please enter your password" placeholder="Password" required value="" name="sign_in_password" id="sign_in_password" class="form-control" ng-model="signinData.password">
                                        <span class="help-block small">

                                             Your strong password

                                        </span>
                                   </div>
                                   <div class="checkbox col-lg-6 no-padding" style="margin-top: 0px !important">
                                        <input type="checkbox" class="i-checks" name="sign_in_remember" value="checked" checked>
                                        Remember login
                                        <p class="help-block small">(if this is a private computer)</p>
                                   </div>
                                   <div class="checkbox col-lg-6 no-padding text-right" style="margin-top: 0px !important">

                                        <a class="forgot_link" href="<?php echo site_url('account/forgot_password'); ?>">Forgot Password ?</a>
                                   </div>
                                   <button class="btn btn-success btn-block" ng-click="login()">Login</button>
                                   <a class="btn btn-default btn-block" href="<?php echo site_url('account/sign_up'); ?>">Register</a>
                                   </form>

                              </div>
                         </div>
                    </div>
               </div>
               <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12 text-center">
                         <?php echo lang('website_copy_right'); ?> 
                    </div>
               </div>
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