
<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 " > 
                    <?php
                      if (isset($profile_info)) {
                           ?>
                           <div class="alert alert-success fade in">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $profile_info; ?>
                           </div>
                      <?php } ?>

                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="col-lg-8  no-padding" style="background:#fff;">
                              <div class="hpanel">
                                   <div class="panel-body" style="border:none !important;"> 

                                        <form  class="form-horizontal" ng-submit="save()">
                                             <?php echo form_fieldset(); ?>
                                             <div class="form-group">
                                                  <label class=" col-sm-3 control-label" for="settings_email"><?php echo lang('settings_email'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.email', 'id' => 'settings_email', 'value' => set_value('settings_email') ? set_value('settings_email') : (isset($account->email) ? $account->email : ''), 'maxlength' => 160)); ?>

                                                       <span class="help-inline text-danger" ng-show="errors.email_error">
                                                            {{errors.email_error}}
                                                       </span>

                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_fullname"><?php echo lang('settings_fullname'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.fullname', 'id' => 'settings_fullname', 'value' => set_value('settings_fullname') ? set_value('settings_fullname') : (isset($account_details->fullname) ? $account_details->fullname : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.fullname_error">
                                                            {{errors.fullname_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_descripiton"><?php echo lang('settings_description'); ?></label>

                                                  <div class="col-sm-9">
                                                       <textarea class="form-control" id="settings_description" name="settings_description" ng-model="user.description"><?php echo set_value('settings_description') ? set_value('settings_description') : (isset($account_details->description) ? $account_details->description : '') ?></textarea>

                                                       <span class="help-inline text-danger" ng-show="errors.description_error">
                                                            {{errors.description_error}}
                                                       </span>
                                                  </div>
                                             </div>                                           
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_phone"><?php echo lang('settings_phone'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.phone_no', 'id' => 'settings_phone', 'value' => set_value('settings_phone') ? set_value('settings_phone') : (isset($account_details->phone_no) ? $account_details->phone_no : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.phone_no_error">
                                                            {{errors.phone_no_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_fax"><?php echo lang('settings_fax'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.fax_no', 'id' => 'settings_fax', 'value' => set_value('settings_fax') ? set_value('settings_fax') : (isset($account_details->fax_no) ? $account_details->fax_no : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.fax_no_error">
                                                            {{errors.fax_no_error}}
                                                       </span>
                                                  </div>
                                             </div>

                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_language"><?php echo lang('settings_language'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php $account_language = ($this->input->post('settings_language') ? $this->input->post('settings_language') : (isset($account->language) ? $account->language : 'english')); ?>
                                                       <select id="settings_language" ng-model="user.language" class="select form-control">
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
                                                       <select id="settings_country" ng-model="user.country" class="select form-control">
                                                            <option value=""><?php echo lang('settings_select'); ?></option>
                                                            <?php foreach ($countries as $country) : ?>
                                                                   <option value="<?php echo $country->code; ?>"<?php if ($account_country == $country->code) echo ' selected="selected"'; ?>>
                                                                        <?php echo $country->country; ?>
                                                                   </option>
                                                              <?php endforeach; ?>
                                                       </select>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12 text-right">
                                                       <button type="submit" class="btn btn-primary"><?php echo lang('profile_save'); ?></button>
                                                       <button type="button" class="btn btn-default" ng-click="cancelEditing()"><?= lang('cancel_btn') ?></button>
                                                  </div>
                                             </div>
                                             <?php echo form_fieldset_close(); ?>
                                        </form>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
