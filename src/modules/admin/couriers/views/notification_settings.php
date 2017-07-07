<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12"> 
                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="hpanel">
                              <div class="panel-body" style="border:none !important;"> 
                                   <form name="noff_form" ng-submit="update()">
                                        <fieldset>
                                             <legend><?= lang('notification_settings_title') ?></legend>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.assignorder" icheck>
                                                  <p class="check_lbl"><?= lang('n_assign_order') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.addcomment" icheck> 
                                                  <p class="check_lbl"><?= lang('n_add_response') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.bidwon" icheck> 
                                                  <p class="check_lbl"><?= lang('n_bid_won') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.cancelorder" icheck> 
                                                  <p class="check_lbl"><?= lang('n_cancel_order') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <button type="submit" class="btn btn-primary"><?= lang('update_btn') ?></button>
                                             </div>
                                        </fieldset>
                                   </form>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>