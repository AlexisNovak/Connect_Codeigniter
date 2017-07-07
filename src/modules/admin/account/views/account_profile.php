
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

                                             <!--                                        <div class="form-group">
                                                                                          <label class="col-sm-3 control-label" for="profile_username"><?php echo lang('profile_username'); ?></label>
                                             
                                                                                          <div class="col-sm-9">
                                             <?php echo form_input(array('class' => "form-control", 'ng-model' => 'user.profile_username', 'id' => 'profile_username', 'value' => set_value('profile_username') ? set_value('profile_username') : (isset($account->username) ? $account->username : ''), 'maxlength' => '24')); ?>
                                                                                               <span class="help-inline text-danger" ng-show="errors.profile_username_error">
                                                                                                    {{errors.profile_username_error}}
                                                                                               </span>
                                                                                          </div>
                                                                                     </div>-->
                                             <div class="form-group">
                                                  <label class=" col-sm-3 control-label" for="settings_email"><?php echo lang('settings_email'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.settings_email', 'id' => 'settings_email', 'value' => set_value('settings_email') ? set_value('settings_email') : (isset($account->email) ? $account->email : ''), 'maxlength' => 160)); ?>

                                                       <span class="help-inline text-danger" ng-show="errors.settings_email_error">
                                                            {{errors.settings_email_error}}
                                                       </span>

                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_fullname"><?php echo lang('settings_fullname'); ?></label>

                                                  <div class="col-sm-9">
                                                       <?php echo form_input(array('class' => 'form-control', 'ng-model' => 'user.settings_fullname', 'id' => 'settings_fullname', 'value' => set_value('settings_fullname') ? set_value('settings_fullname') : (isset($account_details->fullname) ? $account_details->fullname : ''), 'maxlength' => 160)); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.settings_fullname_error">
                                                            {{errors.settings_fullname_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="settings_descripiton"><?php echo lang('settings_description'); ?></label>

                                                  <div class="col-sm-9">
                                                       <textarea class="form-control" id="settings_description" name="settings_description" ng-model="user.settings_description"><?php echo set_value('settings_description') ? set_value('settings_description') : (isset($account_details->description) ? $account_details->description : '') ?></textarea>

                                                       <span class="help-inline text-danger" ng-show="errors.settings_description_error">
                                                            {{errors.settings_description_error}}
                                                       </span>
                                                  </div>
                                             </div>
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
                                             <div class="form-group <?php echo (form_error('profile_username')) ? 'error' : ''; ?>">
                                                  <label class="col-sm-3 control-label" for="profile_picture"><?php echo lang('profile_picture'); ?></label>

                                                  <div class="col-sm-9">
                                                       <p>
                                                            <?php if (isset($account_details->picture) && strlen(trim($account_details->picture)) > 0) : ?>
                                                                   <?php echo showPhoto($account_details->picture, array('nocache' => TRUE)); ?> &nbsp;
                                                                   <button type="button" ng-click="profile_pic_delete()" class="btn"><i class="icon-trash"></i><?= lang('profile_delete_picture') ?></button>
                                                              <?php else : ?>

                                                              <div class="accountPicSelect clearfix">
                                                                   <div class="pull-left radio col-sm-4">
                                                                        <input type="radio" ng-model="user.pic_selection" value="custom" checked="true" />
                                                                        <?php echo showPhoto(); ?>
                                                                   </div>
                                                                   <div class="pull-left col-sm-8">
                                                                        <p><?php echo lang('profile_custom_upload_picture'); ?><br>
                                                                             <input type="file" name="account_picture_upload" id="account_picture_upload">
                                                                             <small>(<?php echo lang('profile_picture_guidelines'); ?>)</small>
                                                                        </p>
                                                                   </div>
                                                              </div>

                                                              <div class="accountPicSelect clearfix">
                                                                   <div class="pull-left radio col-sm-4">
                                                                        <input type="radio" ng-model="user.pic_selection" value="gravatar" />
                                                                        <?php echo showPhoto($gravatar); ?>
                                                                   </div>
                                                                   <div class="pull-left col-sm-6">
                                                                        <p>
                                                                             <small><a href="http://gravatar.com/" target="_blank">Gravatar</a></small>
                                                                        </p>
                                                                   </div>
                                                              </div>

                                                       <?php endif; ?>
                                                       </p>
                                                       <?php if (!isset($account_details->picture)) : ?>
                                                         <?php endif; ?>

                                                       <?php
                                                         if (isset($profile_picture_error)) {
                                                              ?>
                                                              <span class="help-inline">
                                                                   <?php echo $profile_picture_error; ?>
                                                              </span>
                                                         <?php } ?>
                                                       <span class="help-inline" ng-show="errors.profile_picture_error">
                                                            {{errors.profile_picture_error}}
                                                       </span>
                                                  </div>
                                             </div>

                                             <div class="form-group">
                                                  <div class="col-sm-12 text-right">
                                                       <button type="submit" class="btn btn-primary"><?php echo lang('profile_save'); ?></button>
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
