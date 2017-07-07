<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="form-holder">
                                        <form class="form-horizontal" name="new_service_form" ng-submit="new_service_form.$valid && save()" id="new_c_service_form">
                                             <h3 class="order_title"><?= lang('edit_service') ?>
                                                  <span class="pull-right">   
                                                       <span class="btn-cancel" ng-click="cancel_service()"><?= lang('cancel_btn') ?></span> 
                                                       <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>

                                                  </span>
                                             </h3>
                                             <fieldset>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('service_name') ?></label>
                                                            <input type="text" class="form-control" ng-model="new_service.display_name" placeholder="<?= lang('service_name_ph') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.display_name">{{errors.display_name}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('description') ?></label>
                                                            <textarea class="form-control" ng-model="new_service.description" rows="3" placeholder="<?= lang('service_description_ph') ?>"></textarea>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('service_id') ?></label>
                                                            <input type="text" class="form-control" ng-model="new_service.service_id" placeholder="<?= lang('service_id_ph') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.service_id">{{errors.service_id}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('origin') ?></label>
                                                            <select ng-model="new_service.origin" ng-options="country.code as country.country for country in scountrylist" class="form-control"></select>
                                                            <span class="help-block m-b-none" ng-show="errors.origin">{{errors.origin}}></span>
                                                       </div>
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('destination') ?></label>
                                                            <ui-select multiple ng-model="new_service.destination" theme="bootstrap" ng-disabled="disabled" ng-change="check_multiple()">
                                                                 <ui-select-match placeholder="Select destinations">{{$item.country}}</ui-select-match>
                                                                 <ui-select-choices repeat="country.code as country in dcountrylist| propsFilter: {country: $select.search, code: $select.search}">
                                                                      <div ng-bind-html="country.country | highlight: $select.search"></div>

                                                                 </ui-select-choices>
                                                            </ui-select>   
                                                            <span class="help-block m-b-none" ng-show="errors.destination">{{errors.destination}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label">Expected Service Price</label>
                                                            <input type="text" class="form-control" ng-model="new_service.price" placeholder="<?= lang('service_price_ph') ?>"> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.price">{{errors.price}}</span>
                                                       </div>
                                                  </div>  
                                                                                                               
                                                  <div class="form-group">
                                                       <div class="col-sm-6">
                                                            <label class="control-label">Delivery Time</label>
                                                            <select ng-model="new_service.deliverytime" class="form-control"  ng-options="sc.name for sc in csclist">
                                                            </select>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('service_start_time') ?></label>
                                                            <div class="col-sm-12 input-group bootstrap-timepicker timepicker">
                                                                 <input id="timepicker3" type="text" class="form-control" ng-model="new_service.start_time" placeholder="<?= lang('service_start_time_ph') ?>" required>
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.start_time">{{errors.start_time}}</span>
                                                            </div>
                                                       </div>
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('service_end_time') ?></label>
                                                            <div class=" col-sm-12 input-group bootstrap-timepicker timepicker">
                                                                 <input id="timepicker4" type="text" class="form-control" ng-model="new_service.end_time" placeholder="<?= lang('service_end_time_ph') ?>" required>
                                                                 <span class="help-block m-b-none text-danger" ng-show="errors.end_time">{{errors.end_time}}</span>
                                                            </div>
                                                       </div>
                                                       <script type="text/javascript">
                                                            $(function () {
                                                                 $('#timepicker3').timepicker({
                                                                      minuteStep: 5,
                                                                      showInputs: false,
                                                                      disableFocus: true,
                                                                      defaultTime: false,
                                                                      showMeridian: false, maxHours: 24

                                                                 });
                                                                 $('#timepicker4').timepicker({
                                                                      minuteStep: 5,
                                                                      showInputs: false,
                                                                      disableFocus: true,
                                                                      defaultTime: false,
                                                                      showMeridian: false, maxHours: 24
                                                                 });
                                                            });
                                                       </script>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('service_payment') ?></label>
                                                            <ui-select multiple ng-model="new_service.payment_term" theme="bootstrap" ng-disabled="disabled">
                                                                 <ui-select-match placeholder="Select payment terms">{{$item.name}}</ui-select-match>
                                                                 <ui-select-choices repeat="term.value as term in termslist| propsFilter: {name: $select.search}">
                                                                      <div ng-bind-html="term.name | highlight: $select.search"></div>
                                                                 </ui-select-choices>
                                                            </ui-select>                                                              
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.payment_term">{{errors.payment_term}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('available_days') ?></label>
                                                            <p class="inline-chk">                                                                          
                                                                 <span> <span class="chk"><input type="checkbox" icheck ng-model="new_service.week_0"></span>Sunday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_1"></span>Monday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_2"></span>Tuesday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_3"></span>Wednesday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_4"></span>Thursday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_5"></span>Friday</span> 
                                                                 <span> <span class="chk"> <input type="checkbox" icheck ng-model="new_service.week_6"></span>Saturday</span> 
                                                       </div>
                                                  </div>
                                                  <div class="form-group" ng-hide="new_service.org">
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('is_public') ?></label>
                                                            <p>
                                                                 <span style="float: left;margin-right:5px;"><input type="checkbox" icheck ng-model="new_service.is_public"></span> <?= lang('yes') ?>
                                                            </p>
                                                       </div>
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('is_auto_approve') ?></label>
                                                            <p>
                                                                 <span style="float: left;margin-right:5px;"><input type="checkbox" icheck ng-model="new_service.auto_approve"></span>  <?= lang('yes') ?>
                                                            </p> 
                                                       </div>
                                                  </div>

                                             </fieldset>
                                             <h3 class="order_title">
                                                  <span class="pull-right">   
                                                       <span class="btn-cancel" ng-click="cancel_service()"><?= lang('cancel_btn') ?></span> 
                                                       <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>

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
