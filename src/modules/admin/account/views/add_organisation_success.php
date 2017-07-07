

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
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="text-center logo_wrap">
                                        <img src="<?php echo base_url(); ?>resource/images/favicons.png">
                                   </div>
                                   <center><h3 style="font-weight: bold" class="dark_green">You almost There :)</h3></center>
                                   <p style="margin-bottom: 0px;font-size: 14px;" class="dark_green">
                                        Cool, you are almost ready now except validating your account via email.
                                        <!--We have your information all ready. Once you have validate your email, your account will be ready to use.-->
                                   </p>
                                   <br>
                                   <p class="text-danger" style="font-size: 12px;">
                                        Please remember to check your inbox (and sometimes spam box too.)
                                        <!--Sorry for the nag,, sometimes the email may find its way to your spam box, so do take a look there if you do not see it in your inbox in the next 5min.-->
                                   </p>
                                   <!--<p></p>-->
                                   <p style="margin-top:40px;"> <?php echo anchor('account/sign_in', "Go back to Sign in page now", 'class="btn btn-block btn-logo-yellow margin_top_10"'); ?></p>
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