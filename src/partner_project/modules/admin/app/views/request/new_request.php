<div class="content no-padding">
     <div class="wrap" animate-panel>
          <div class="row" ng-controller="newsrequestCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">                        
                         <div class="panel-body"> 
                              <div class="col-lg-12 no-padding margin_bottom_10">
                                   <div class="form-holder new_req">
                                        <form name="newSrequest" class="form-horizontal" ng-submit="newSrequest.$valid && save()" style="border:1px solid #fff">
                                             <h3 class="order_title"><?= lang("create_new_srequest") ?>
                                                  <span class="pull-right">   
                                                       <span class="btn-cancel" ng-click="cancel_create_req()"><?= lang('cancel_btn') ?></span> 
                                                       <button type="submit" class="btn btn-primary"><?= lang('submit_btn') ?></button>
                                                  </span>
                                             </h3>
                                             <fieldset style="padding:5px 15px;">
                                                  <div class="form-group">
                                                       <div class="col-xs-12 col-sm-8 col-md-6">
                                                            <label class="title"><?= lang('srequest_title') ?></label>
                                                            <input type="text" class="form-control" ng-model="req.title" placeholder="<?= lang('srequest_title_ph') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.title_error">{{errors.title_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12 col-sm-8 col-md-6">
                                                            <label class="title"><?= lang('srequest_org') ?></label>
                                                            <select class="form-control" ng-model="req.org_id" ng-options="org.org_id as org.org_name for org in adminorglist" required>
                                                                 <option value="">Select Organisation</option>
                                                            </select>
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.title_error">{{errors.title_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12 col-sm-12 col-md-10">
                                                            <label class="title"><?= lang('srequest_description_title') ?></label>
                                                            <textarea class="form-control" ng-model="req.description" rows="5" placeholder="<?= lang('srequest_description_ph') ?>"></textarea>
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.description_error">{{errors.description_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12">   
                                                            <label class="title"><?= lang('srequest_duration') ?></label>
                                                            <p class="help-block m-b-none field_info"><?= lang('srequest_duration_info') ?></p>
                                                            <div class=" col-xs-6 no-padding">
                                                                 <input type="text" class="form-control" ng-model="req.duration" placeholder="<?= lang('srequest_duration_ph') ?>" required>
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.duration_error">{{errors.duration_error}}</span>
                                                            </div>
                                                            <span class="month"><?= lang('months') ?></span>

                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12">   
                                                            <label class="title"><?= lang('srequest_delpermonth') ?></label>
                                                            <p class="help-block m-b-none field_info"><?= lang('srequest_delpermonth_info') ?></p>
                                                            <div class=" col-xs-6 no-padding">
                                                                 <input type="text" class="form-control" ng-model="req.delpermonth" placeholder="<?= lang('srequest_delpermonth_ph') ?>" required> 
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.delpermonth_error">{{errors.delpermonth_error}}</span>
                                                            </div><span class="month"><?= lang('srequest_delpermonth_sub') ?></span>

                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12">   
                                                            <div class=" col-xs-12 col-sm-10 no-padding">
                                                                 <label class="title"><?= lang('srequest_payment') ?></label>
                                                                 <p class="help-block m-b-none field_info"><?= lang('srequest_payment_info') ?></p>                                                          
                                                                 <input type="text" class="form-control" ng-model="req.payment" placeholder="<?= lang('srequest_payment_ph') ?>" required> 
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.payment_error">{{errors.payment_error}}</span>
                                                            </div>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-xs-12">   
                                                            <div class=" col-xs-12 col-sm-10 no-padding">
                                                                 <label class="title"><?= lang('srequest_compensation') ?></label>
                                                                 <input type="text" class="form-control" ng-model="req.compensation" placeholder="<?= lang('srequest_compensation_ph') ?>"> 
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.compensation_error">{{errors.compensation_error}}</span>
                                                            </div>
                                                       </div>
                                                  </div>
                                                   <div class="form-group">
                                                       <div class="col-xs-12">   
                                                            <div class="col-xs-12 col-sm-6 no-padding bdtpicker">
                                                                 <label class="title"><?= lang('srequest_expiry') ?></label>
                                                                 <input type="text" class="form-control" ng-model="req.expiry" id="tender_expiry"> 
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.expiry_error">{{errors.expiry_error}}</span>
                                                            </div>
                                                       </div>
                                                  </div>
                                                   <script>
                                                     $(function () {
                                                     $('#tender_expiry').datetimepicker({
                                                     format: 'MM/DD/YYYY h:mm A',
                                                             minDate: moment(),
                                                             defaultDate: moment().add('day', 1)
                                                     });
                                                             setTimeout(function () {
                                                             var result = moment().add('day', 1).format('MM/DD/YYYY h:mm A');
                                                                     $("#tender_expiry").val(result);
                                                                     $("#tender_expiry").trigger('input');
                                                             }, 1000);
                                                             $('#tender_expiry').on('dp.change', function () {
                                                     $("#tender_expiry").trigger('input');
                                                     }); //                                                  
                                                     });</script>
                                             </fieldset>
                                             <h3 class="order_title" style="margin-bottom: 0 !important;">
                                                  <span class="pull-right">   
                                                       <span class="btn-cancel" ng-click="cancel_create_req()"><?= lang('cancel_btn') ?></span> 
                                                       <button type="submit" class="btn btn-primary"><?= lang('submit_btn') ?></button>
                                                  </span>
                                             </h3>
                                        </form>
                                   </div>
                              </div> 
                         </div>  
                    </div>
               </div>
          </div>
     </div>
</div>