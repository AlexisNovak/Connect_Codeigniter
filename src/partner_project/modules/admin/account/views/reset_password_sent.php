<!DOCTYPE html>
<html ng-app="partner-portal">
    <head>
        <?php echo $this->load->view('site/head'); ?>
        <script>
            var BASE_URL = "<?php echo site_url(); ?>" + "/";
        </script>

    </head>
    <body class="blank" ng-app="appController">



        <div class="color-line"></div>



        <div class="container" style="padding-top:6%;">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="text-center m-b-md">
                        <h3><?php echo lang('forgot_password_page_name'); ?></h3>
                        <small>

                             KAI Square's Partner Portal

                        </small>
                    </div>
                    <div class="hpanel">
                        <div class="panel-body lite-shadow">
               
<?php echo sprintf(lang('reset_password_sent_instructions'), anchor('account/forgot_password', lang('reset_password_resend_the_instructions'))); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12 text-center">
                    &copy; 2015 Copyright KAI Square's Partner Portal
                </div>
            </div>
        </div>

       
    </div>

</div>
<?php echo $this->load->view('site/footer'); ?>















