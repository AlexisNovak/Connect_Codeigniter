<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12"> 


                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="col-lg-8  no-padding" style="background:#fff;">
                              <div class="hpanel">
                                   <div class="panel-body" style="border:none !important;"> 
                                        <form name="changePasswordForm" class="form-horizontal" ng-submit="changePasswordForm.$valid && save()">
                                             <?php echo form_fieldset(); ?>
                                             <p class="well well-sm text-success" ng-show="success"><?= lang('password_password_has_been_changed') ?></p>
                                             <br>
                                             <div class="form-group">
                                                  <label class="col-sm-4 control-label" for="password_current_password"><?php echo lang('password_current_password'); ?></label>
                                                  <div class="col-sm-8">
                                                       <?php echo form_password(array('ng-model' => 'password_current_password', 'id' => 'password_current_password', 'class' => 'form-control', 'autocomplete' => 'off')); ?>
                                                       <span class="help-inline text-danger" ng-show="errors.current_password_error">
                                                            {{errors.current_password_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-4 control-label" for="password_new_password">
                                                       <?php echo lang('password_new_password'); ?>
                                                  </label>
                                                  <div class="col-sm-8">
                                                       <input type="password" class="form-control" ng-model="password_new_password"  ng-class="{error:errors.new_password_error}" autocomplete="off" required>
                                                       <span class="help-inline text-danger" ng-show="errors.new_password_error">
                                                            {{errors.new_password_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <label class="col-sm-4 control-label" for="password_retype_new_password"><?php echo lang('password_retype_new_password'); ?></label>
                                                  <div class="col-sm-8">
                                                       <input type="password" class="form-control" ng-model="password_retype_new_password"  ng-class="{error:errors.password_retype_new_password}" autocomplete="off" required>
                                                       <span class="help-inline text-danger" ng-show="errors.new_retype_password_error">
                                                            {{errors.new_retype_password_error}}
                                                       </span>
                                                  </div>
                                             </div>
                                             <div class="form-group text-right">
                                                  <div class="col-sm-12">
                                                       <button type="submit" class="btn btn-primary"><?php echo lang('password_change_my_password'); ?></button>
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

<div class="span10">

     <?php if ($this->session->flashdata('password_info')) : ?>
            <div class="alert alert-success fade in">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                 <?php echo $this->session->flashdata('password_info'); ?>
            </div>
       <?php endif; ?>




</div>