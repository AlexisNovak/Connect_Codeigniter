<!DOCTYPE html>
<html ng-app="partner-portal">
     <head>
          <?php echo $this->load->view('site/head', array('title' => 'Forgot password')); ?>
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
               
          </style>
     </head>
     <body class="blank" ng-app="appController">



          <div class="color-line"></div>



          <div class="login-container reset-sent">
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
                                   <?php echo lang('reset_password_unsuccessful'); ?>
                                   <p class="text-center go-back-link"><?php echo anchor('account/sign_in', lang('go_back_login')); ?></p>
                                   <div class="col-md-10 col-md-offset-1 text-center sign_in_footer">
                                        <?php echo lang('website_copy_right_phokki'); ?> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
          <script>
               $(document).ready(function () {
                    setTimeout(function () {
                         window.location.href = '<?= site_url('account/sign_in') ?>';
                    }, 60000);
               });
          </script>
          <?php echo $this->load->view('site/footer'); ?>















