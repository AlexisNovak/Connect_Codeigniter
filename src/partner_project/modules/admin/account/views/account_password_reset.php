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



        <div class="login-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center m-b-md">
                        <h3><?php echo lang('password_page_name'); ?></h3>
                        <small>

                            KAI Square's 6Connect

                        </small>
                    </div>
                    <div class="hpanel">
                        <div class="panel-body lite-shadow">
                            <?php echo form_open(uri_string()); ?>
                            <?php echo form_fieldset(); ?>

                            <span class="help-inline">

                                <?php if ($this->session->flashdata('password_info')) : ?>
                                    <span class="field_success "><?php echo $this->session->flashdata('password_info'); ?></span>
                                    <div class="well" style="margin-bottom: 20px;">Success Click here to <a class="forgot_link bold_link" href="<?php echo site_url('account/sign_in'); ?>">login</a> </div>
                                <?php endif; ?>
                                    <div class="well well-sm instruction" style="margin-bottom: 20px;">
                                    <?php echo lang('password_safe_guard_your_account'); ?>
                                </div>
                            </span>

                            <div class="form-group ">
                                <label class="control-label" for="password_new_password"><?php echo lang('password_new_password'); ?></label>
                                <input type="password" placeholder="New Password" title="Your New Password"  value="<?php set_value('password_new_password'); ?>" name="password_new_password" id="password_new_password"  class="form-control 
                                <?php
                                if (form_error('password_new_password')) {
                                    echo "input_error"
                                    ?>  <?php } ?>">
                                <span class="help-block small">

                                    Your new password

                                </span>
                                <?php
                                if (form_error('password_new_password')) {
                                    ?>
                                    <span class="help-inline">
                                        <?php echo form_error('password_new_password'); ?>
                                    </span>
                                <?php } ?>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="password_retype_new_password"><?php echo lang('password_retype_new_password'); ?></label>
                                <input type="password" placeholder="Retype Password" title="Retype your Password"  value="<?php set_value('password_retype_new_password'); ?>" name="password_retype_new_password" id="password_retype_new_password"  class="form-control 
                                <?php
                                if (form_error('password_retype_new_password')) {
                                    echo "input_error"
                                    ?>  <?php } ?>">
                                <span class="help-block small">

                                    Retype your new password

                                </span>
                                <?php
                                if (form_error('password_retype_new_password')) {
                                    ?>
                                    <span class="help-inline">
                                        <?php echo form_error('password_retype_new_password'); ?>
                                    </span>
                                <?php } ?>
                            </div>

                            <button class="btn btn-success btn-block" ><?= lang('password_change_my_password') ?></button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12 text-center">
                    &copy; 2015 Copyright KAI Square's 6Connect
                </div>
            </div>
        </div>


    </div>

</div>
<?php echo $this->load->view('site/footer'); ?>




















