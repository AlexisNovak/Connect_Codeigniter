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
                                                  <input type="checkbox" ng-model="notf.bidreceived" icheck>
                                                  <p class="check_lbl"><?= lang('n_bid_received') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.servicebid" icheck> 
                                                  <p class="check_lbl"><?= lang('n_service_bid') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.statusupdate" icheck> 
                                                  <p class="check_lbl"><?= lang('n_status_update') ?></p>
                                             </div>
                                             <div class="form-group">
                                                  <input type="checkbox" ng-model="notf.comment_from_courier" icheck> 
                                                  <p class="check_lbl"><?= lang('n_comment_from_courier') ?></p>
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