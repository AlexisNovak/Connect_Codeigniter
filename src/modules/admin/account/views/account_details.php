<?php
  if (isset($settings_info)) {
       ?>
       <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $settings_info; ?>
       </div>
  <?php } ?>
<div class="panel-body" style="border:none !important;">
     <div class="col-lg-8  no-padding" style="background:#fff;">
          <form class="form-horizontal account_setting_form" ng-submit="save()">
               

               <div class="form-group">
                    <label class="col-sm-3 control-label" for="settings_language"><?php echo lang('settings_language'); ?></label>

                    <div class="col-sm-9">
                         <?php $account_language = ($this->input->post('settings_language') ? $this->input->post('settings_language') : (isset($account->language) ? $account->language : 'english')); ?>
                         <select id="settings_language" ng-model="user.settings_language" class="select form-control">
                              <?php foreach ($languages as $language => $value) : ?>
                                     <option value="<?php echo $language; ?>"<?php if ($account_language == $language) echo ' selected="selected"'; ?>>
                                          <?php echo $value; ?>
                                     </option>
                                <?php endforeach; ?>
                         </select>
                    </div>
               </div>

               <div class="form-group">
                    <label class="col-sm-3 control-label" for="settings_country"><?php echo lang('settings_country'); ?></label>

                    <div class="col-sm-9">
                         <?php $account_country = ($this->input->post('settings_country') ? $this->input->post('settings_country') : (isset($account_details->country) ? $account_details->country : '')); ?>
                         <select id="settings_country" ng-model="user.settings_country" class="select form-control">
                              <option value=""><?php echo lang('settings_select'); ?></option>
                              <?php foreach ($countries as $country) : ?>
                                     <option value="<?php echo $country->code; ?>"<?php if ($account_country == $country->code) echo ' selected="selected"'; ?>>
                                          <?php echo $country->country; ?>
                                     </option>
                                <?php endforeach; ?>
                         </select>
                    </div>
               </div>
               <div class="form-group text-right">
                    <div class="col-sm-9 col-sm-offset-3">
                         <span class="text-center text-success" ng-show="success"><?= lang('settings_details_updated') ?></span>
                         <button type="submit" class="btn btn-primary"><?php echo lang('settings_save'); ?></button>
                         <button type="reset" class="btn btn-small">Cancel</button>
                    </div>
               </div>
          </form>
     </div>
</div>