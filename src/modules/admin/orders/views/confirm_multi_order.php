<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">                              
                              <div class="col-lg-12 no-padding margin_bottom_10" >  
                                   <?php
                                     if (isset($info) && $info) {
                                          ?>

                                          <div class="clearfix margin_bottom_10" ng-show="processing">
                                               <p class="text-success text-center"> <?= lang('order_processing') ?> 
                                                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">

                                          </div>
                                          <p class="text-danger text-center" ng-show="delivery_info_error">{{delivery_info_error}}</p>
                                          <div class="form-holder order_form">                                                                              
                                               <form name="new_order" class="form-horizontal" ng-submit="new_order.$valid && saveOrder()">
                                                    <h3 class="order_title"><?= lang("multi_order_title") ?>
                                                         <span class="pull-right">
                                                              <button type="submit" class="btn btn-primary" ng-class="{
                                                                                                                                                                                                                                                disabled:new_order.$invalid
                                                                                                                                                                                                                                           }"><?= lang('confirm_btn') ?></button>
                                                              <span ng-if="org_dropdown">
                                                                   <a ui-sref="delivery_orders" class="btn btn-default" style="margin-right: 10px;display: inline "><?= lang('cancel_btn') ?></a>
                                                              </span>
                                                              <span ng-if="!org_dropdown">
                                                                   <a ui-sref="organisation.orders({id:org_id})" class="btn btn-default" style="margin-right: 10px;display: inline "><?= lang('cancel_btn') ?></a>

                                                              </span>
                                                         </span>
                                                    </h3>
                                                    <legend><?= lang('order_caption') ?></legend>
                                                    <div class="col-lg-12 no-padding">
                                                         <div class="col-lg-12 well" style="margin-bottom: 15px;" ng-if="org_dropdown">
                                                              <h3>Select organisation</h3>
                                                              <div class="col-lg-6 no-padding" style="margin-bottom: 15px;">
                                                                   <select ng-model="neworder.org_id" class="form-control required col-lg-6" ng-options="org.org_id as org.org_name for org in orglist" required=""  ng-class="{
                                                                                                                                                                                                                                                     error:cerrors.org_id
                                                                                                                                                                                                                                                }" ng-change="resetServices()">
                                                                        <option value="">Select Organisation</option>                                                                            
                                                                   </select>
                                                                   <span class="help-block m-b-none text-danger" ng-show="cerrors.org_id">{{cerrors.org_id}}</span>
                                                              </div>
                                                         </div>
                                                    </div>
                                                    <!--                                                         <div class="col-lg-12 no-padding chk" style="margin-top: 20px;">
                                                    
                                                                                                                  <span style="float: left;margin:auto 10px;"> 
                                                                                                                       <input type="checkbox" ng-model="neworder.delivery_is_assign" icheck>
                                                                                                                  </span> 
                                                                                                                  <h3 style="float:left;margin-right: 15px;margin-top: -2px;"><?= lang('service_assignment') ?></h3>
                                                                                                                  <div class="clear"></div>
                                                                                                                  <p><?= lang('assignment_description') ?></p>
                                                                                                             </div>
                                                                                                             <div class="col-lg-12 no-padding" ng-show="neworder.delivery_is_assign">
                                                                                                                  <div class="col-lg-12 assign_service" style="margin-top: 20px;">
                                                    
                                                                                                                       <div class="col-sm-3" style="margin-top: 15px;">
                                                                                                                            <div class="form-group ser_ass_text">
                                                                                                                                 <input type="text" ng-model="neworder.deadline" class="form-control " ng-class="{error:errors.deadline}" placeholder="<?= lang('deadline') ?>" style="height:50px" id="bidding_deadline">
                                                                                                                            </div>
                                                                                                                            <span class="help-block m-b-none text-danger" ng-show="errors.deadline">{{errors.deadline}}</span>
                                                                                                                       </div>
                                                    
                                                    
                                                                                                                       <div class="col-sm-9" style="margin-top: 15px;" ng-if="scop.open_bid">
                                                                                                                            <input type="checkbox" ng-model="neworder.open_bid" ng-class="{error:errors.open_bid}" icheck> <?= lang('order_open_bid') ?>
                                                                                                                       </div>
                                                                                                                  </div> 
                                                                                                             </div>-->

                                                    <div class="col-lg-12 no-padding" ng-show="!neworder.delivery_is_assign">                                                              

                                                         <div class="col-lg-12 assign_service" data-style="margin-top: 20px;">

                                                              <div class="col-lg-6" style="margin-top: 15px;">
                                                                   <!--                                                                                                                                                <div class="form-group ser_ass_text">
                                                                                                                                                                                                          <input type="text" class="form-control" ng-model="neworder.assigned_service.service_name"  placeholder="<?= lang('assign_service') ?>"  ng-class="{error:errors.assigned_service}" >                                                                             <i class="fa fa-external-link" ng-click="show_service_providers()"></i>
                                                                                                                                                                                                          </div>-->
                                                                   <div class="form-group ser_ass_text">
                                                                        <select  class="form-control" ng-model="scop.service_selected" ng-change="set_service()"  ng-options="service.service_name for service in servicelist track by service.service_id"  ng-class="{error:cerrors.assigned_service}">
                                                                             <option value="">{{assign_text}}</option>
                                                                        </select> 
                                                                        <span ng-if="neworder.assigned_service">By <span ng-click="view_courier_info(neworder.assigned_service.courier_id)" class="courier_name">{{neworder.assigned_service.courier_name}}</span></span>
                                                                   </div>
                                                                   <span class="help-block m-b-none text-danger" ng-show="cerrors.assigned_service">{{cerrors.assigned_service}}</span>
                                                              </div>
                                                              <div class="col-lg-6" style="margin-top: 15px;">
                                                                   <p>{{neworder.assigned_service.description}}</p>
                                                              </div>

                                                         </div>                                                              
                                                    </div>                                                        

                                                    <div class="col-lg-12 no-padding clearfix" style="position: relative;" >
                                                         <div class="col-lg-12 no-padding a-box" style="">
                                                              <h2><?= lang('collect_from') ?></h2>
                                                              <div class="col-lg-12 address_box">
                                                                   <div class="col-lg-12">
                                                                        <p><?= lang('collect_from_sub') ?></p>

                                                                        <div class="col-lg-10 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="text" ng-model="neworder.collect_from_l1" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_from_l1
                                                                                                                                                                                                                                                               }" placeholder="<?= lang('collect_add_l1') ?>" required>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_from_l1">{{cerrors.collect_from_l1}}</span>


                                                                             </div>
                                                                        </div>

                                                                        <div class="col-lg-10 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="text" ng-model="neworder.collect_from_l2" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_from_l2
                                                                                                                                                                                                                                                               }" placeholder="<?= lang('collect_add_l2') ?>" required>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_from_l2">{{cerrors.collect_from_l2}}</span>
                                                                             </div>
                                                                        </div>

                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="text"  ng-model="neworder.collection_zipcode" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collection_zipcode
                                                                                                                                                                                                                                                               }" placeholder="<?= lang('collect_zipcode') ?>" required>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collection_zipcode">{{cerrors.collection_zipcode}}</span>
                                                                             </div>
                                                                        </div> 

                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="form-group">
                                                                                  <select  ng-model="neworder.collect_country" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_country
                                                                                                                                                                                                                                                               }" placeholder="<?= lang('collect_country') ?>" ng-options="country.code as country.country for country in countrylist"  ng-change="getCTimezones(true)" required>
                                                                                       <option value="">--select country--</option>
                                                                                  </select>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_country">{{cerrors.collect_country}}</span>
                                                                             </div>
                                                                        </div> 
                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="angular_popup collection_timezone_pop pull-right" draggable="true" ng-show="view_collect_timezone_list">  
                                                                                  <h3><?= lang('timezone') ?>
                                                                                       <i class="fa fa-close pull-right" ng-click="cancel_collection_timezone()"></i>
                                                                                  </h3>
                                                                                  <div class="form-holder">                                                                                
                                                                                       <div class="form-group">
                                                                                            <div class="col-sm-12"> 
                                                                                                 <select  class="form-control required" ng-model="neworder.collect_timezone" ng-change="cancel_collection_timezone()" ng-options="zone as zone.zoneinfo +' (' + zone.offset + ')' for zone in timezones track by zone.zoneinfo">
                                                                                                 </select>
                                                                                            </div>
                                                                                       </div>
                                                                                  </div>
                                                                             </div>
                                                                             <div class="form-group">
                                                                                  <input type="text" id="collectdate_input" ng-model="neworder.collect_date" name="daterange" class="form-control hidden" placeholder="<?= lang('collect_date') ?>"  required>
                                                                                  <div id="collectdate" class="form-control required datepicker"  ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_date
                                                                                                                                                                                                                                                               }">
                                                                                       <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                                                       <span>{{neworder.collect_date}}</span> 
                                                                                  </div> 
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_date">{{cerrors.collect_date}}</span>
                                                                                  <p class="date_info"><?= lang('based_on') ?>
                                                                                       <span class="timezone"> {{neworder.collect_timezone.zoneinfo + '(' + neworder.collect_timezone.offset + ')'}}</span>
                                                                                       (<a class="change_timezone" ng-click="show_collection_timezone()" ><?= lang('change_timezone') ?></a>)
                                                                                       <span class="m-b-none text-danger" ng-show="cerrors.collect_timezone">{{cerrors.collect_timezone}}</span>
                                                                                  </p>
                                                                                  <p class="date_info">The selected collection period is from <span class="timezone">XXX to YYY</span> for the selected country</p>
                                                                             </div>
                                                                        </div> 

                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="text" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_contactname
                                                                                                                                                                                                                                                               }" ng-model="neworder.collect_contactname" placeholder="<?= lang('contact_name') ?>" required>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_contactname">{{cerrors.collect_contactname}}</span>
                                                                             </div>
                                                                        </div>

                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="email" class="form-control" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_email
                                                                                                                                                                                                                                                               }" ng-model="neworder.collect_email"  placeholder="<?= lang('order_email') ?>">
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_email">{{cerrors.collect_email}}</span>
                                                                             </div>
                                                                        </div> 

                                                                        <div class="col-lg-7 no-padding">
                                                                             <div class="form-group">
                                                                                  <input type="text" class="form-control required" ng-class="{
                                                                                                                                                                                                                                                                    error:cerrors.collect_phone
                                                                                                                                                                                                                                                               }" ng-model="neworder.collect_phone"  placeholder="<?= lang('phone_number') ?>" required>
                                                                                  <span class="help-block m-b-none text-danger" ng-show="cerrors.collect_phone">{{cerrors.collect_phone}}</span>
                                                                             </div>
                                                                        </div> 

                                                                   </div>
                                                              </div>
                                                         </div>

                                                    </div>

                                               </form> <div class="col-lg-12 no-padding clearfix">
                                                    <h3>Deliveries</h3>
                                                    <div class="table-responsive">
                                                         <table class="table table-bordered table-condensed">
                                                              <thead>
                                                                   <tr>
                                                                        <th>No</th>
                                                                        <th>Quantity</th>
                                                                        <th>Delivery Address</th>
                                                                        <th>Delivery Postal Code</th>
                                                                        <th>Delivery  Country</th>
                                                                        <th>Delivery Date From</th>
                                                                        <th>Delivery Time From</th>
                                                                        <th>Delivery Date To</th>
                                                                        <th>Delivery Time To</th>
                                                                        <th>Timezone</th>
                                                                        <th>Contact Name</th>
                                                                        <th>Contact Email</th>
                                                                        <th>Contact Phone</th>
                                                                        <th>Send Notification</th>
                                                                        <th>Remarks</th>
                                                                        <th>Is Bulk</th>
                                                                        <th>Length</th>
                                                                        <th>Breadth</th>
                                                                        <th>Height</th>
                                                                        <th>Weight</th>
                                                                        <th>Issue</th>
                                                                        <th>Action</th>
                                                                   </tr>
                                                              </thead>
                                                              <tbody>
                                                                   <tr id="deliver_{{$index}}" ng-repeat="delivery in deliveries track by $index" ng-class="{
                                                                                                                                                                                                                                                          warning:delivery.error
                                                                                                                                                                                                                                                     }">
                                                                        <td>{{$index + 1}}</td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-number="delivery.quantity">{{delivery.quantity|| 0}}</span>
                                                                        </td>
                                                                        <td>                                                                                  
                                                                             <span onaftersave="submit_edit(delivery)" editable-textarea="delivery.delivery_address">{{delivery.delivery_address|| 'empty'}}</span>
                                                                        </td>
                                                                        <td>                                                                                  
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.delivery_post_code">{{delivery.delivery_post_code|| 'empty'}}</span>
                                                                        </td>
                                                                        <td>                                                                                 
                                                                             <span onaftersave="submit_edit(delivery)" editable-select="delivery.delivery_country" e-ng-options="country.country as country.country for country in countrylist">
                                                                                  {{delivery.delivery_country|| 'not set'}}
                                                                             </span>
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-bsdate="delivery.delivery_date_from" e-datepicker-popup="dd-MM-yyyy" e-is-open="opened">{{(delivery.delivery_date_from| date:"yyyy-MM-dd") || 'empty'}}</span>

                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.delivery_time_from">{{ (delivery.delivery_time_from | date:"HH:mm") || 'empty'}}</span>
                                                                        </td>
                                                                        <td>

                                                                             <span onaftersave="submit_edit(delivery)" editable-bsdate="delivery.delivery_date_to" e-datepicker-popup="dd-MM-yyyy">{{(delivery.delivery_date_to| date:"yyyy-MM-dd") || 'empty'}}</span>

                                                                        </td> 
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.delivery_time_to">{{ (delivery.delivery_time_to | date:"HH:mm") || 'empty'}}</span>
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-select="delivery.delivery_timezone" e-ng-options="tzone.zoneinfo as tzone.zoneinfo for tzone in dtimezones">
                                                                                  {{delivery.delivery_timezone|| 'not set'}}
                                                                             </span>                                                                                 
                                                                        </td>
                                                                        <td>                                                                                 
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.delivery_contact_name">{{delivery.delivery_contact_name|| 'empty'}}</span>
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-email="delivery.delivery_contact_email">{{delivery.delivery_contact_email|| 'empty'}}</span>

                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.delivery_contact_phone">{{delivery.delivery_contact_phone|| 'empty'}}</span>

                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-checkbox="delivery.send_notification_to_consignee" e-title="Send notification?">
                                                                                  {{ delivery.send_notification_to_consignee && "Yes" || "No" }}
                                                                             </span>

                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-textarea="delivery.remarks">{{delivery.remarks|| 'nothing'}}</span>

                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-checkbox="delivery.is_bulk" e-title="Is bulk Item?">
                                                                                  {{ delivery.is_bulk && "Yes" || "No" }}
                                                                             </span>                                                                                  
                                                                        </td> 
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.length" e-ng-pattern="/^[0-9]+(\.[0-9]{1,3})?$/">{{delivery.length|| '0'}}</span>                                                                                  
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.breadth">{{delivery.breadth|| '0'}}</span>   
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.height">{{delivery.height|| '0'}}</span>  
                                                                        </td>
                                                                        <td>
                                                                             <span onaftersave="submit_edit(delivery)" editable-text="delivery.weight">{{delivery.weight|| '0'}}</span>   
                                                                        </td>
                                                                        <td style="width:200px;font-size: 12px;" ng-bind-html="delivery.error">                                
                                                                        </td>
                                                                        <td>
                                                                             <span ng-click="delete($index)"><i class="fa fa-trash"></i></span>
                                                                        </td>
                                                                   </tr>

                                                                                                                   <!--                                                                             <tr id="deliver_{{$index}}" ng-repeat="delivery in deliveries track by $index" ng-class="{
                                                                                                                                                                             warning:delivery.error
                                                                                                                                                                        }">
                                                                                                                        <td>{{$index + 1}}</td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.quantity" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.quantity}}
                                                                                                                             </span><br>
                                                                                                                             
                                                                                                                      
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <textarea ng-model="delivery.delivery_address" ng-show="delivery.edit"></textarea>
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_address}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_post_code" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_post_code}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_country" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_country}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_date_from" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_date_from}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_time_from" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_time_from}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_date_to" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_date_to}}
                                                                                                                             </span>
                                                                                                                        </td> 
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_time_to" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_time_to}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_timezone" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_timezone}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_contact_name" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_contact_name}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="email" ng-model="delivery.delivery_contact_email" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_contact_email}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.delivery_contact_phone" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.delivery_contact_phone}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.send_notification_to_consignee" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.send_notification_to_consignee}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <textarea ng-model="delivery.remarks" ng-show="delivery.edit"></textarea>
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.remarks}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.is_bulk" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.is_bulk}}
                                                                                                                             </span>
                                                                                                                        </td> 
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.length" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.length}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.breadth" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.breadth}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.height" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.height}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <input type="text" ng-model="delivery.weight" ng-show="delivery.edit">
                                                                                                                             <span ng-hide="delivery.edit">                                     
                                                                                                                                  {{delivery.weight}}
                                                                                                                             </span>
                                                                                                                        </td>
                                                                                                                        <td style="width:200px;font-size: 12px;" ng-bind-html="delivery.error">                                
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                             <span ng-click="edit_row(delivery)" ng-hide="delivery.edit"><i class="fa fa-edit"></i></span>
                                                                                                                             <span ng-click="submit_edit(delivery)" ng-show="delivery.edit"><i class="fa fa-save"></i></span>
                                                                                                                             <span ng-click="delete($index)"><i class="fa fa-close"></i></span>
                                                                                                                        </td>
                                                                                                                   </tr>-->

                                                                   <!--                                                                        
                                                                                                                                             <tr id="deliver_{{$index}}" ng-repeat="delivery in deliveries track by $index" ng-class="{danger:delivery.error}">
                                                                                                                                                <td>{{$index + 1}}</td>
                                                                                                                                                <td>{{delivery.quantity}}</td>
                                                                                                                                                <td>{{delivery.delivery_address}}</td>
                                                                                                                                                <td>{{delivery.delivery_post_code}}</td>
                                                                                                                                                <td>{{delivery.delivery_country}}</td>
                                                                                                                                                <td>{{delivery.delivery_date}}</td>
                                                                                                                                                <td>{{delivery.delivery_timezone}}</td>
                                                                                                                                                <td>{{delivery.delivery_contact_name}}</td>
                                                                                                                                                <td>{{delivery.delivery_contact_email}}</td>
                                                                                                                                                <td>{{delivery.delivery_contact_phone}}</td>
                                                                                                                                                <td>{{delivery.send_notification_to_consignee}}</td>
                                                                                                                                                <td>{{delivery.remarks}}</td>
                                                                                                                                                <td> {{delivery.is_bulk}}</td>
                                                                                                                                                <td>{{delivery.length}}</td>
                                                                                                                                                <td>{{delivery.breadth}}</td>
                                                                                                                                                <td>{{delivery.height}}</td>
                                                                                                                                                <td>{{delivery.breadth}}</td>
                                                                                                                                                <td ng-bind-html="delivery.error">                                
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                     <span ng-click="edit_row(delivery)" ng-hide="delivery.edit"><i class="fa fa-edit"></i></span>
                                                                                                                                                     <span ng-click="submit_edit(delivery)" ng-show="delivery.edit"><i class="fa fa-save"></i></span>
                                                                                                                                                     <span ng-click="delete($index)"><i class="fa fa-close"></i></span>
                                                                                                                                                </td>
                                                                                                                                           </tr>
                                                                   -->
                                                              </tbody>
                                                         </table>
                                                    </div>

                                               </div>

                                          </div>

                                          <?php
                                     } else {
                                          ?>
                                          <p class="well text-danger"><?= lang('nothing_to_display') ?></p>
                                          <?php
                                     }
                                   ?>
                              </div>
                              <div class="clear"></div>
                         </div>


                    </div>
               </div>

          </div>
     </div>
</div>
<script>
     $(function () {
//          $('input[name="daterange"]').daterangepicker({
//               timePicker: true,
//               format: 'MM/DD/YYYY h:mm A',
//               minDate: new Date(),
//               timePickerIncrement: 30,
//               timePicker12Hour: true,
//               timePickerSeconds: false
//          });

     $('#collectdate').daterangepicker({
     timePicker: true,
             format: 'MM/DD/YYYY h:mm A',
             minDate: new Date(),
             timePickerIncrement: 30,
             timePicker12Hour: true,
             timePickerSeconds: false

     }, function (start, end, label) {
     var result = start.format('MM/DD/YYYY h:mm A') + ' - ' + end.format('MM/DD/YYYY h:mm A');
             $('#collectdate span').html(result);
             $("#collectdate_input").val(result);
             $("#collectdate_input").trigger('input');
     });
             $('#bidding_deadline').daterangepicker({
     singleDatePicker: true,
             timePicker: true,
             format: 'MM/DD/YYYY h:mm A',
             minDate: new Date(),
             timePickerIncrement: 30,
             timePicker12Hour: true,
             timePickerSeconds: false

     });
     });
</script>