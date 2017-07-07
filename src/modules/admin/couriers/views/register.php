<!DOCTYPE html>
<html>
     <head>
          <?php echo $this->load->view('header'); ?>
          <script>
               var BASE_URL = "<?php echo site_url(); ?>" + "/";
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
               <div class="row">
                    <div class="col-md-12">
                         <div class="text-center m-b-md login_head">
                              <h3><?php echo lang('courier_sign_up_heading'); ?></h3>
                         </div>
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <?php echo form_open(site_url('couriers/register'), 'class=""'); ?>
                                   <?php echo form_fieldset();
                                   ?>
                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_companyname">Company Name</label>
                                        <input type="text" placeholder="Company Name" title="Please enter you company name" value="<?php echo set_value('sign_up_companyname'); ?>" name="sign_up_companyname" class="form-control" required >
                                        <?php if (form_error('sign_up_companyname') || isset($sign_up_companyname_error)) : ?>
                                               <div class="clr"></div>
                                               <span class="help-inline">
                                                    <?php echo form_error('sign_up_companyname'); ?>
                                                    <?php if (isset($sign_up_companyname_error)) : ?>
                                                         <span class="field_error"><?php echo $sign_up_companyname_error; ?></span>
                                                    <?php endif; ?>
                                               </span>
                                          <?php endif; ?>

                                   </div>
                                   <div class="form-group">
                                        <label class="control-label" for="sign_up_email"><?= lang('sign_up_email') ?></label>
                                        <input type="email" title="Email" placeholder="Email" name="sign_up_email" id="sign_up_email" value="<?php echo set_value('sign_up_email'); ?>" class="form-control" required>
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
                                        <input type="password" title="Password" placeholder="Password" value="<?php echo set_value('sign_up_password'); ?>" name="sign_up_password" class="form-control" required>
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
                                        <input type="password" title="Confirm Password" placeholder="Password" value="" name="sign_up_confirm_password" class="form-control" required>
                                        <div class="clr"></div>
                                        <?php if (form_error('sign_up_confirm_password')) : ?>
                                               <span class="help-inline">
                                                    <?php echo form_error('sign_up_confirm_password'); ?>
                                               </span>
                                          <?php endif; ?>
                                        <?php if (isset($sign_up_password_error)) : ?>
                                               <span class="help-inline">
                                                    <span class="field_error"><?php echo $sign_up_password_error; ?></span>

                                               </span>
                                          <?php endif; ?>
                                        <div class="clr"></div>
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
                                   <button class="btn btn-logo-green btn-block">Submit</button>
                                   <p style="margin-top:10px;"><?php echo lang('sign_up_already_have_account'); ?>
                                        <?php echo anchor(site_url('couriers/login'), lang('sign_up_sign_in_now'), 'class="btn btn-block btn-logo-yellow margin_top_10"'); ?></p>
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
               <script>
                    $(document).ready(function () {
                         $('.i-checks').iCheck({
                              checkboxClass: 'icheckbox_square-green',
                              radioClass: 'iradio_square-green'
                         });
                    });
               </script>
               <?php echo $this->load->view('footer'); ?>