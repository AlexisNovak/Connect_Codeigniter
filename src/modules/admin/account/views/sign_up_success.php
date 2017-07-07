

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
               .dark_green,.dark_green *{
                    color: #024f57;
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
                                   <center><h3 style="font-weight: bold" class="dark_green">Registration completed!</h3></center>
                                   <p style="margin-bottom: 0px;font-size: 14px;" class="dark_green">Thanks for registering with us :) We have sent you an email to validate your email. </p>
                                   <span class="text-danger" style="font-size: 12px;">(Pssh, sometimes the email may find its way to your spam box, so do take a look there if you do not see it in your inbox in the next 5min.)</span>
                                   <p style="padding-top: 5px;font-size: 15px;margin-bottom: 10px;"class="dark_green">While our team are validating your account, you might want to set up your organisation below first</p>
                                   <?php echo form_open(uri_string() . "/add_org", 'class=""'); ?>
                                   <?php echo form_fieldset(); ?>
                                   <div class="form-group">
                                        <?php if (form_error('short_name') || form_error('org_name') || form_error('website')) : ?>
                                               <div class="clr"></div>
                                               <span class="help-inline">
                                                    <span class="field_error org_error">
                                                         <?php
                                                         if (form_error('org_name')) {
                                                              echo form_error('org_name');
                                                         }
                                                         ?>

                                                         <?php
                                                         if (form_error('short_name')) {
                                                              echo form_error('short_name');
                                                         }
                                                         ?>

                                                         <?php
                                                         if (form_error('website')) {
                                                              echo form_error('website');
                                                         }
                                                         ?>

                                                    </span>
                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
                                        <input type="hidden" name="user_data" value="<?= ($user_id) ? $user_id : '' ?>"/>
                                        <label class="control-label  dark_green" for="org_name">Organisation Name</label>
                                        <input type="text" placeholder="Your company name" title="Your company name" value="<?= set_value('org_name') ?>" name="org_name" id="org_name" ng-model="signupData.org_name" class="form-control">


                                   </div>
                                   <div class="form-group">
                                        <?php if (false) : ?>
                                               <div class="clr"></div>
                                               <span class="help-inline">
                                                    <?php if (form_error('short_name')) : ?>
                                                         <span class="field_error">
                                                              <?php echo form_error('short_name'); ?></span>
                                                    <?php endif; ?>
                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
                                        <label class="control-label dark_green" for="short_name">Short Name </label> 
                                        <input type="text" title="Short Name" placeholder="a short name to represent your coy e.g. cocacola" value="<?php echo set_value('short_name'); ?>" name="short_name" id="short_name" class="form-control" ng-model="signupData.short_name">

                                   </div>
                                   <div class="form-group">
                                        <?php if (FALSE) : ?>
                                               <span class="help-inline field_error">
                                                    <?php echo form_error('website'); ?>
                                                    <div class="clr"></div>
                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
                                        <label class="control-label dark_green" for="website">Website URL</label>
                                        <input type="text" title="website" placeholder="http://your-company-name.com" value="<?= set_value('website') ?>" name="website" id="website" ng-model="signupData.website" class="form-control">
                                        <div class="clr"></div>

                                   </div>
                                   <button class="btn btn-logo-green btn-block" ng-click="signup()">Yes, please add my organisation</button>
                                   <p style="margin-top:10px;"> <?php echo anchor('account/sign_in', "Go back to Sign in page now", 'class="btn btn-block btn-logo-yellow margin_top_10"'); ?></p>
                                   </form>
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