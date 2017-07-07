<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="form-holder">

                                        <h3 class="order_title"><?= lang('edit_service') ?>
                                             <span class="pull-right">   
                                                  <span class="btn-cancel" ng-click="cancel_service()"><?= lang('cancel_btn') ?></span> 
                                                  <button type="button" class="btn btn-primary" ng-click="update_payment()"><?= lang('save_btn') ?></button>

                                             </span>
                                        </h3>
                                        <fieldset>
                                             <form class="form-horizontal" name="new_service_form" ng-submit="new_service_form.$valid && save()" id="new_c_service_form">
                                                  <?php if (isset($account) && $account->root == 1) { ?>
                                                         <div class="form-group custom-select2" ng-class="{
                                                                               'has-error', errors.type
                                                                          }">
                                                              <div class="col-sm-12">
                                                                   <label>Courier</label>
                                                                   <ui-select  ng-model="new_service.courier_id" theme="select2"  ng-disabled="disabled"  ng-required="true" style="width:100%">
                                                                        <ui-select-match placeholder="Courier">{{$select.selected.company_name}}</ui-select-match>
                                                                        <ui-select-choices repeat="courier.courier_id as courier in courierlist| filter: {company_name: $select.search}">
                                                                             <div ng-bind-html="courier.company_name | highlight: $select.search" class="type_title"></div>

                                                                        </ui-select-choices>
                                                                   </ui-select> 
                                                                   <span class="help-block m-b-none text-danger" ng-show="errors.company_name">{{errors.company_name}}</span>
                                                              </div>
                                                         </div>
                                                    <?php } ?>

                                                  <div class="form-group">
                                                       <div class="col-sm-5">
                                                            <label class="control-label" style="margin-top: 14px;"><?= lang('service_name') ?></label>
                                                            <input type="text" class="form-control" ng-model="new_service.display_name" placeholder="<?= lang('service_name_ph') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.display_name">{{errors.display_name}}</span>
                                                       </div>
                                                       <div class="col-sm-6">
                                                            <label class="control-label" style="margin-bottom: 0px;"><?= lang('service_status') ?></label><br>
                                                            <span style="font-size: 11px;">Please Indicate what type of service is this</span>
                                                            <select ng-model="new_service.status" class="form-control">
                                                                 <option value="1">Active</option>
                                                                 <option value="3">Suspended</option>
                                                                 <option value="2">Removed</option>
                                                            </select>                                                              
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.payment_term">{{errors.payment_term}}</span>

                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <label class="control-label"><?= lang('description') ?></label>
                                                            <textarea class="form-control" ng-model="new_service.description" rows="3" placeholder="<?= lang('service_description_ph') ?>"></textarea>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-6">
                                                            <label class="control-label"><?= lang('service_id') ?></label>
                                                            <input type="text" class="form-control" ng-model="new_service.service_id" placeholder="<?= lang('service_id_ph') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.service_id">{{errors.service_id}}</span>
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
                                                       <div class="col-sm-6">
                                                            <label class="control-label" style="margin-bottom: 0">Delivery Speed</label><br>
                                                            <span style="font-size: 11px;">Please Indicate what type of service is this</span>
                                                            <select ng-model="new_service.deliverytime" class="form-control"  ng-options="sc.name for sc in csclist">
                                                            </select>
                                                       </div>
                                                  </div>

                                                  <div class="form-group">
                                                       <div class="col-md-7">
                                                            <label class="control-label" style="margin-bottom: 0">Booking Cut-off Timing</label><br>
                                                            <span style="font-size: 11px;">Please Indicate the booking cut-off time. If the booking time has passed, the service will not be available.</span>
                                                            <br>
                                                            <div class="clearfix"></div>
                                                            <div class="col-sm-6" style="padding-left: 0px">
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
                                                            <label class="control-label" style="margin-bottom: 0;"><?= lang('available_days') ?></label><br>
                                                            <span style="font-size: 11px;">Indicate the days in which this service will be operating.</span>
                                                            <br>
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
                                             </form>
                                        </fieldset>

                                        <fieldset>
                                             <div class="col-lg-12 m-t-lg">
                                                  <legend><?= lang('service_payment_terms') ?></legend>                                       
                                                  <div class="col-xs-3">
                                                       <span style="float: left;margin:auto 10px;"> 
                                                            <input type="checkbox" icheck ng-model="payment.credit"> 
                                                       </span>
                                                       <p><?= lang('credit_terms') ?></p>                                              

                                                  </div>
                                                  <div class="col-xs-3">
                                                       <span style="float: left;margin:auto 10px;"> 
                                                            <input type="checkbox" icheck ng-model="payment.credit_direct"> 
                                                       </span>
                                                       <p><?= lang('credit_terms_direct') ?></p> 
                                                  </div>
                                                  <div class="col-xs-3">
                                                       <span style="float: left;margin:auto 10px;"> 
                                                            <input type="checkbox" icheck ng-model="payment.sender"> 
                                                       </span>
                                                       <p><?= lang('cash_sender') ?></p> 
                                                  </div>
                                                  <div class="col-xs-3">
                                                       <span style="float: left;margin:auto 10px;"> 
                                                            <input type="checkbox" icheck ng-model="payment.recipient"> 
                                                       </span>
                                                       <p><?= lang('cash_recipient') ?></p> 
                                                  </div>
                                                  <!--                                                       <div class="col-xs-12">
                                                                                                              <button class="btn btn-info btn-sm" type="button" ng-click="update_payment()"><?= lang('save_btn') ?></button>
                                                                                                         </div>-->
                                             </div>
                                             <div class="col-lg-12 m-t-lg">
                                                  <legend><?= lang('parcel_type_title') ?></legend>
                                                  <?php if ($service['is_public'] == '1' || $service['is_public'] == '0') { ?>
                                                         <div class="col-sm-6">
                                                              <form class="form-horizontal">
                                                                   <div class="form-group">
                                                                        <label class="control-label col-sm-3"><?php echo lang('parcel_type'); ?></label>
                                                                        <div class="col-sm-9">
                                                                             <div class="form-group custom-select2">
                                                                                  <ui-select ng-model="selectedParcelType.selected" theme="select2" ng-disabled="disabled" ng-change="selectParcel()" required style="width:100%">
                                                                                       <ui-select-match placeholder="<?php echo lang('parcel_type_selection'); ?>">{{$select.selected.display_name}}</ui-select-match>
                                                                                       <ui-select-choices repeat="ordertype in typelist | filter: {display_name: $select.search}">
                                                                                            <div ng-bind-html="ordertype.display_name | highlight: $select.search" class="type_title"></div>
                                                                                            <span ng-bind-html="ordertype.description | highlight: $select.search"></span>
                                                                                       </ui-select-choices>
                                                                                  </ui-select>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.type">{{ errors.type}}</span>
                                                                             </div>
                                                                        </div>
                                                                   </div>
                                                                   <div class="form-group">
                                                                        <label class="control-label col-sm-3"><?php echo lang('parcel_type_price'); ?></label>
                                                                        <div class="col-sm-4" style="padding:0">
                                                                             <div ng-show="insertedPrice.type == '0'">
                                                                                  <?php echo lang('parcel_type_max_cubic_volume'); ?>:
                                                                                  <input type="text" ng-model="insertedPrice.maxVolume" class="form-control" />
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.maxVolume">{{ errors.maxvolume}}</span>
                                                                                  <?php echo lang('parcel_type_cubic_volume_cost'); ?>:
                                                                                  <input type="text" ng-model="insertedPrice.cubicCost" class="form-control" />
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.cubicCost">{{ errors.cubicCost}}</span>
                                                                                  <?php echo lang('parcel_type_max_weight'); ?>:
                                                                                  <input type="text" ng-model="insertedPrice.maxWeight" class="form-control" />
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.maxWeight">{{ errors.maxWeight}}</span>
                                                                                  <?php echo lang('parcel_type_weight_cost'); ?>:
                                                                                  <input type="text" ng-model="insertedPrice.weightCost" class="form-control" />
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.weightCost">{{ errors.weightCost}}</span>
                                                                             </div>
                                                                             <div ng-show="insertedPrice.type != '0'">
                                                                                  <input type="text" ng-model="insertedPrice.price" class="form-control" />
                                                                                  <span class="help-block m-b-none text-danger" ng-show="errors.price">{{ errors.price}}</span>
                                                                             </div>
                                                                        </div>
                                                                   </div>
                                                                   <div class="form-group">
                                                                        <label class="control-label col-sm-3"></label>
                                                                        <div class="col-sm-9" style="padding:0">
                                                                             <button class="btn btn-info btn-sm" ng-click="setPrice()" type="button"><?= lang('parcel_type_add_price') ?></button>
                                                                        </div>
                                                                   </div>
                                                              </form>
                                                         </div>
                                                    <?php } ?>
                                                  <table class="table table-bordered table-striped">
                                                       <thead>
                                                            <tr>
                                                                 <th style="width: 20%"><?= lang('parcel_type') ?></th>
                                                                 <th style="width: 10%"><?= lang('parcel_type_price') ?></th>
                                                                 <th style="width: 20%"><?= lang('action') ?></th>
                                                            </tr>


                                                       <tbody>
                                                            <tr ng-repeat="parceltype in parcelTypePrices">
                                                                 <td>
                                                                      {{ parceltype.display_name}}
                                                                 </td>
                                                                 <td>
                                                                      <div ng-show="parceltype.type != '0'">${{ parceltype.price || 'Unset' }}</div>
                                                                      <div ng-show="parceltype.type == '0'">
                                                                           <?php echo lang('parcel_type_max_cubic_volume'); ?>: <b>{{ parceltype.max_volume}}</b><br />
                                                                           <?php echo lang('parcel_type_cubic_volume_cost'); ?>: <b>${{ parceltype.volume_cost}}</b><br />
                                                                           <?php echo lang('parcel_type_max_weight'); ?>: <b>{{ parceltype.max_weight}}</b><br />
                                                                           <?php echo lang('parcel_type_weight_cost'); ?>: <b>${{ parceltype.weight_cost}}</b><br />
                                                                      </div>
                                                                 </td>
                                                                 <td style="white-space: nowrap">
                                                                      <?php if ($service['is_public'] == '1' || $service['is_public'] == '0') { ?>
                                                                             <div class="buttons" ng-show="!rowform.$visible">
                                                                                  <span class="text-danger" ng-click="removePrice(parceltype, $index)">
                                                                                       <i class="fa fa-trash"></i>
                                                                                  </span>
                                                                             </div>
                                                                        <?php } ?>
                                                                 </td>
                                                            </tr>
                                                       </tbody>
                                                       </thead>
                                                  </table>
                                             </div>
                                             <div class="col-lg-12 m-t-lg">
                                                  <legend><?= lang('surcharge_items') ?></legend>

                                                  <table class="table table-bordered table-striped">
                                                       <thead>
                                                            <tr>
                                                                 <th style="width: 20%"><?= lang('s_item_name') ?></th>
                                                                 <th style="width: 10%"><?= lang('s_item_price') ?></th>
                                                                 <th><?= lang('s_item_remarks') ?></th>
                                                                 <th><?= lang('s_item_location') ?></th>
                                                                 <th style="width: 20%"><?= lang('action') ?></th>
                                                            </tr>


                                                       <tbody>
                                                            <tr ng-repeat="item in surchargeitems">
                                                                 <td><span editable-text="item.name" e-name="name"
                                                                           e-form="rowform" e-required> {{ item.name || 'empty' }} </span>
                                                                 </td>
                                                                 <td><span editable-text="item.price" e-name="price"
                                                                           e-form="rowform" e-required> {{ item.price}} </span>
                                                                 </td>
                                                                 <td><span editable-textarea="item.remarks" e-name="remarks"
                                                                           e-form="rowform" e-rows="4" e-cols="50" e-required> {{
                                                                                     item.remarks || 'empty' }} </span></td>
                                                                 <td><span editable-select="item.location" e-name="location"
                                                                           e-form="rowform" e-ng-options="s.value as s.text for s in locations"> {{
                                                                                     item.location_name || 'NA' }} </span></td>
                                                                 <td style="white-space: nowrap">
                                                                      <!-- form -->
                                                                      <form editable-form name="rowform"
                                                                            onbeforesave="saveItem($data,item.id)"
                                                                            ng-show="rowform.$visible" class="form-buttons form-inline"
                                                                            shown="inserted == item">
                                                                           <button type="submit" ng-disabled="rowform.$waiting"
                                                                                   class="btn btn-primary btn-sm">
                                                                                        <?= lang('save_btn') ?>
                                                                           </button>
                                                                           <button type="button" ng-disabled="rowform.$waiting"
                                                                                   ng-click="cancel_item(rowform, $index)"
                                                                                   class="btn btn-sm btn-default">
                                                                                        <?= lang('cancel_btn') ?>
                                                                           </button>
                                                                      </form>
                                                                      <div class="buttons" ng-show="!rowform.$visible">
                                                                           <span class="text-primary" ng-click="rowform.$show()"><i
                                                                                     class="fa fa-edit"></i></span> <span class="text-danger"
                                                                                                                ng-click="removeItem(item, $index)"><i class="fa fa-trash"></i></span>
                                                                      </div>
                                                                 </td>
                                                            </tr>
                                                       </tbody>
                                                       </thead>
                                                  </table>
                                                  <div class="no-padding table_filter"
                                                       style="display: inline-flex;">
                                                       <button class="btn btn-info btn-sm" ng-click="addItem()" type="button"><?= lang('add_item') ?></button>
                                                  </div>
                                                  <br>
                                                  <br>
                                             </div>

                                             <div class="col-lg-12 m-t-lg">
                                                  <legend >Load Control</legend>
                                                  <p>Max. no. of delivery you can accept in a day.</p>
                                                  <div class="clearfix">

                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-4 " style="padding-left: 0">
                                                            <input class="form-control" type="text" ng-model="new_service.load_limit">
                                                       </div>
                                                  </div>
                                                  <br>
                                                  <br>
                                             </div>
                                        </fieldset>

                                        <h3 class="order_title">
                                             <span class="pull-right">   
                                                  <span class="btn-cancel" ng-click="cancel_service()"><?= lang('cancel_btn') ?></span> 
                                                  <button type="button" ng-click="update_payment()" class="btn btn-primary"><?= lang('save_btn') ?></button>

                                             </span>
                                        </h3>
                                   </div>




                              </div>
                         </div>
                    </div>

               </div>
          </div>
     </div>
</div>
