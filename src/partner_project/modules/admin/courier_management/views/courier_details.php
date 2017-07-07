<div class="col-lg-8 p-lg">
     <form class="form-horizontal account_setting_form" ng-submit="save()">

          <div class="form-group">
               <label class="col-sm-3 control-label" for="profile_company_name"><?php echo lang('profile_company_name'); ?></label>

               <div class="col-sm-9">
                    <?php echo form_input(array('class' => "form-control", 'ng-model' => 'courier.profile_company_name', 'id' => 'profile_company_name', 'value' => set_value('profile_company_name') ? set_value('profile_company_name') : (isset($account->company_name) ? $account->company_name : ''), 'maxlength' => '24')); ?>
                    <span class="help-inline text-danger" ng-show="errors.profile_company_name_error">
                         {{errors.profile_company_name_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class=" col-sm-3 control-label" for="settings_email"><?php echo lang('settings_email'); ?></label>

               <div class="col-sm-9">
                    <input class="form-control" type="email" ng-model="courier.settings_email" readonly>

                    <span class="help-inline text-danger" ng-show="errors.settings_email_error">
                         {{errors.settings_email_error}}
                    </span>

               </div>
          </div>

          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_fullname"><?php echo lang('settings_fullname'); ?></label>

               <div class="col-sm-9">

                    <input type="text" class="form-control" ng-model="courier.settings_fullname" ng-class="{error:errors.settings_fullname_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_fullname_error">
                         {{errors.settings_fullname_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_url"><?php echo lang('settings_url'); ?></label>

               <div class="col-sm-9">
                    <input type="text" class="form-control" ng-model="courier.settings_url" ng-class="{error:errors.settings_url_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_url_error">
                         {{errors.settings_url_error}}
                    </span>
               </div>
          </div>

          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_address"><?php echo lang('settings_address'); ?></label>

               <div class="col-sm-9">
                    <textarea class="form-control" id="settings_address" name="settings_address" ng-model="courier.settings_address"  ng-class="{error:errors.settings_address_error}"></textarea>
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_address_error">
                         {{errors.settings_address_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_billing_address"><?php echo lang('settings_billing_address'); ?></label>

               <div class="col-sm-9">
                    <textarea class="form-control" id="settings_billing_address" name="settings_billing_address" ng-model="courier.settings_billing_address"  ng-class="{error:errors.settings_billing_address_error}"></textarea>
                    <small>
                         <input type="checkbox" icheck ng-model="courier.same_addr" ng-change="setbilling()">
                         click if same as Registered Address
                    </small>
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_billing_address_error">
                         {{errors.settings_billing_address_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_reg_no"><?php echo lang('settings_reg_no'); ?></label>

               <div class="col-sm-9">
                    <input type="text" class="form-control" ng-model="courier.settings_reg_no"  ng-class="{error:errors.settings_reg_no_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_reg_no_error">
                         {{errors.settings_reg_no_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_phone"><?php echo lang('settings_phone'); ?></label>

               <div class="col-sm-9">
                    <input type="text" class="form-control" ng-model="courier.settings_phone"  ng-class="{error:errors.settings_phone_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_phone_error">
                         {{errors.settings_phone_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_fax"><?php echo lang('settings_fax'); ?></label>

               <div class="col-sm-9">
                    <input type="text" class="form-control" ng-model="courier.settings_fax"  ng-class="{error:errors.settings_fax_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_fax_error">
                         {{errors.settings_fax_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_description"><?php echo lang('description'); ?></label>

               <div class="col-sm-9">
                    <textarea class="form-control" id="settings_description" name="settings_description" ng-model="courier.settings_description" ng-class="{error:errors.settings_description_error}"></textarea>
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_description_error">
                         {{errors.settings_description_error}}
                    </span>
               </div>
          </div>
          <div class="form-group">
               <label class="col-sm-3 control-label" for="settings_support_email"><?php echo lang('settings_support_email'); ?></label>

               <div class="col-sm-9">
                    <input type="email" class="form-control" ng-model="courier.settings_support_email"  ng-class="{error:errors.settings_support_email_error}">
                    <span class="help-inline text-danger text-danger" ng-show="errors.settings_support_email_error">
                         {{errors.settings_support_email_error}}
                    </span>
               </div>
          </div>

          <div class="form-group text-right">
               <span class="text-center text-success" ng-show="success"><?= lang('settings_details_updated') ?></span>
               <button type="submit" class="btn btn-primary"><?php echo lang('settings_save'); ?></button>
               <button type="button" class="btn btn-small" ng-click="goback()">Cancel</button>
          </div>
     </form>
</div>