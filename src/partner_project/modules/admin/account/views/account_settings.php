<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">

                    <?php
                      if (isset($settings_info)) {
                           ?>
                           <div class="alert alert-success fade in">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $settings_info; ?>
                           </div>
                      <?php } ?>

                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="col-lg-8  no-padding" style="background:#fff;">
                              <div class="hpanel">
                                   <div class="panel-body" style="border:none !important;">
                                        <form class="form-horizontal account_setting_form" ng-submit="save()">
                                           

                                            

                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_phone"><?php echo lang('settings_phone'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.settings_phone', 'id' => 'settings_phone', 'value' => set_value('settings_phone') ? set_value('settings_phone') : (isset($account_details->phone_no) ? $account_details->phone_no : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.settings_phone_error">
                                                            {{errors.settings_phone_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_fax"><?php echo lang('settings_fax'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.settings_fax', 'id' => 'settings_fax', 'value' => set_value('settings_fax') ? set_value('settings_fax') : (isset($account_details->fax_no) ? $account_details->fax_no : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.settings_fax_error">
                                                            {{errors.settings_fax_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                            
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
                                                  <span class="text-center text-success" ng-show="success"><?= lang('settings_details_updated') ?></span>
                                                  <button type="submit" class="btn btn-primary"><?php echo lang('settings_save'); ?></button>
                                                  <button type="reset" class="btn btn-small">Cancel</button>
                                             </div>
                                        </form>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>