

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

        <div class="login-container padding_top_3">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center m-b-md">
                        <h3><?php echo lang('sign_up_heading'); ?></h3>

                    </div>
                    <div class="hpanel">
                        <div class="panel-body lite-shadow">
                            <?php echo form_open(uri_string(), 'class=""'); ?>
                            <?php echo form_fieldset(); ?>
                            <div class="form-group">
                                <label class="control-label" for="sign_up_username">Username</label>
                                <?php if (isset($invited_token)) {
                                    ?>
                                    <input type="hidden" name="invited_token" value="<?= $invited_token ?>">
                                    <?php
                                }
                                ?>
                                <input type="text" placeholder="Username" title="Please enter you username" required value="" name="sign_up_username" id="sign_up_username" ng-model="signupData.username" class="form-control">
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
                                <label class="control-label" for="sign_up_password">Email</label>
                                <input type="text" title="Email" placeholder="Email" required value="<?php if (isset($invited_email)) echo $invited_email; ?>" name="sign_up_email" id="sign_up_email" class="form-control" ng-model="signupData.email">
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
                            <button class="btn btn-success btn-block" ng-click="signup()">Submit</button>
                            <p style="margin-top:10px;"><?php echo lang('sign_up_already_have_account'); ?> <?php echo anchor('account/sign_in', lang('sign_up_sign_in_now'), 'class="btn btn-block btn-default margin_top_10"'); ?></p>
                            </form>
                            <?php if (isset($sign_in_error)) : ?>
                                <div id="output"><?php echo $sign_in_error; ?></div>
                            <?php endif; ?>
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