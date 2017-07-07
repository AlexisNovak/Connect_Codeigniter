<h3 class="order_title"><?= lang("multi_order_title") ?>
     <span class="pull-right">
          <span class="btn-cancel" ng-click="show_part_three()">
               <?= lang('back_to_service') ?>                                                  
          </span> 
          <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder(false)">    <?= lang('confirm_order') ?>                                                   
          </button>
     </span>
</h3>
<div class="col-sm-12 no-padding review_section">
     <div class="well col-sm-12">
          <div class="col-sm-12 no-padding">
               <div class="confirm_info">
                    <h4><?= lang('confirm_info') ?></h4>
                    <h5><?= lang('confirm_info_sub') ?></h5>
               </div>
          </div>
          <div class="col-sm-12 no-padding">
               <div class="col-sm-6 no-padding text-center">                    
                    <div class="cfm_box">
                         <div class="cfm_sub_h"><?= lang('collection_from_h') ?></div>
                         <div>
                              <p class="high_c">{{neworder.collect_from_l1}}</p>
                              <p>{{neworder.collect_from_l2}}</p>
                              <p>{{neworder.collection_zipcode}}</p>
                              <p ng-repeat="country in countrylist| filter:{'code' :neworder.collect_country} | limitTo:1">{{country.country}}</p>
                              <p class="high_c smal" ng-if="neworder.is_c_restricted_area">(<?= lang('collect_restrict') ?>)</p>
                              <p class="high_c smal" ng-if="!neworder.is_c_restricted_area && neworder.is_d_restricted_area">&nbsp;</p>
                         </div>
                         <hr>
                         <div>
                              <p class="cfm_sub_h2"><?= lang('contact_info') ?></p>
                              <p>{{neworder.collect_contactname}}</p>
                              <p>{{neworder.collect_phone}}</p>
                              <p>{{neworder.collect_email}}&nbsp;</p>
                         </div>
                         <div class="cfm_box_footer">
                              <table>
                                   <tr>
                                        <td>
                                             <span class="date">{{neworder.cdate_convert1_1}}</span><br>
                                             <span class="time">{{neworder.cdate_convert1_2}}</span>
                                        </td>
                                        <td>
                                             <span class="text-muted to"><?= lang('to') ?>&emsp;</span>
                                        </td>
                                        <td>
                                             <span class="date">{{neworder.cdate_convert2_1}}</span><br>
                                             <span class="time">{{neworder.cdate_convert2_2}}</span>
                                        </td>
                                   </tr>
                              </table>
                         </div>
                    </div>
               </div>
               <div class="clearfix"></div>
               <div class="col-sm-12 no-padding">

                    <div class="srv cfm_box_srv">
                         <p class="pull-right org_info">
                              <?= lang('billing_org') ?> : 
                              <span ng-repeat="org in orglist| filter:{'org_id' :neworder.org_id}:true | limitTo:1" ng-if="neworder.org_id">{{org.org_name}}
                              </span>
                         </p>
                         <div class="cfm_sub_h3">    
                              <span ng-show="neworder.delivery_is_assign == 1"><?= lang('requested_service') ?></span>
                              <span ng-show="neworder.delivery_is_assign == 2"><?= lang('service_assignment') ?></span>
                         </div>
                         <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 2"><?= lang('deadline_title') ?> : {{neworder.deadline}}</div>
                         <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 1" ng-if="neworder.assigned_service.service_name">
                              <div class="col-xs-12 col-sm-3 col-md-2">
                                   <span class="estmtd_price"><?= lang('estimated_price') ?></span><div class="clearfix"></div>
                                   <p class="price_tag">{{neworder.assigned_service.price}} SGD</p>
                                   <div ng-if="neworder.assigned_service.time_to_deliver">
                                        <p class="estmtd_price"> <?= lang('deliver_before') ?></p>
                                        <time> {{ neworder.assigned_service.time_to_deliver | amCalendar }} </time>
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-5 col-md-6">
                                   <p class="srv_title">{{neworder.assigned_service.service_name}}</p>
                                   <p class="srv_summary">{{neworder.assigned_service.description}}</p>
                                   <div class="col-xs-12 no-padding surcharge" ng-if="neworder.assigned_service.surcharge.length">
                                        <p>Surcharges</p>
                                        <span class="srcharge" ng-repeat="item in neworder.assigned_service.surcharge| limitTo:neworder.assigned_service.limit">{{item.name}} - <strong>${{item.price}}</strong></span> 
                                        <span class="srcharge more" ng-if="neworder.assigned_service.surcharge.length > 2 && neworder.assigned_service.limit == 2" ng-click="show_more_srcharge_items(neworder.assigned_service, $event)">more</span>
                                        <span class="srcharge more" ng-if="neworder.assigned_service.surcharge.length > 2 && neworder.assigned_service.limit !== 2" ng-click="show_less_srcharge_items(neworder.assigned_service, $event)">less</span>
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-4">
                                   <p><small>Service provided by</small></p>
                                   <div>
                                        <div class="srv_p_img" ng-if="neworder.assigned_service.logo != null">
                                             <img ng-src="{{neworder.assigned_service.logo}}" alt="">
                                        </div><span class="srv_courier courier_name" ng-click="view_courier_info(neworder.assigned_service.courier_id, $event)">{{neworder.assigned_service.courier_name}}</span>
                                   </div>
                              </div> 
                         </div>
                    </div>

               </div>
          </div>

     </div>
</div>
<div class="col-sm-12 no-padding margin_top_10">
     <h3 class="order_title">
          <span class="pull-right">
               <span class="btn-cancel" ng-click="show_part_three()">
                    <?= lang('back_to_service') ?>                                                  
               </span>
               <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder()"><?= lang('confirm_order') ?>                                                   
               </button>       
          </span>
     </h3>
</div>