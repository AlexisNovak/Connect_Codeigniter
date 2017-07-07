<h3 class="order_title"><?= lang("multi_order_title") ?>
     <span class="pull-right">
          <span class="btn-cancel" ng-click="show_part_three()">
               <?= lang('back_to_service') ?>                                                  
          </span> 
          <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder(false)">    <?= lang('confirm_order') ?>                                                   
          </button>
     </span>
</h3>
<div class="col-xs-12 no-padding review_section">
     <div class="well col-sm-12">
          <div class="col-sm-12 no-padding">
               <div class="confirm_info">
                    <h4><?= lang('confirm_info') ?></h4>
                    <h5 ng-if="neworder.delivery_is_assign == 1"><?= lang('confirm_info_sub') ?></h5>
               </div>
          </div>
          <div class="col-xs-12 col-sm-8 no-padding">
               <div class=" no-padding col-sm-12" >
                    <div class="col-sm-12 no-padding">
                         <div class="item_details cfm_box">
                              <p class="org_info text-right">
                                   <?= lang('billing_org') ?> <br>
                                   <span ng-repeat="org in orglist| filter:{'org_id' :neworder.org_id}:true | limitTo:1" ng-if="neworder.org_id">{{org.org_name}}
                                   </span>
                                   <span ng-if="!neworder.org_id"><?= lang('nan') ?></span>
                              </p>
                              <div class="cfm_sub_h"><?= lang('item_detail') ?></div>
                              <div>

                                   <div class="col-xs-12">                                       
                                        <div class="col-xs-12">
                                             <div class="col-xs-12 col-sm-6 no-padding">
                                                  <div class="cfm_sub_h2"><?= lang('collection_from_h') ?></div>
                                                  <p>
                                                       {{neworder.collect_company}}<br>
                                                       {{neworder.collect_contactname}}, {{neworder.collect_phone}}<br>
                                                  </p>
                                                  <p>{{neworder.collect_from_l1}}, {{neworder.collect_from_l2}}
                                                       <span ng-repeat="country in countrylist| filter:{'code' :neworder.collect_country} | limitTo:1">{{country.country}}</span>
                                                       <span class="high_c smal" ng-if="neworder.is_c_restricted_area">(<?= lang('collect_restrict') ?>)</span> 
                                                       <span class="high_c smal" ng-if="!neworder.is_c_restricted_area && neworder.is_d_restricted_area">&nbsp;</span>
                                                       {{neworder.collection_zipcode}} </p>
                                                  <p>                                                 
                                             </div>
                                             <div class="col-xs-12 col-sm-6 no-padding">
                                                  <div class="cfm_sub_h2"><?= lang('delivery_to_h') ?></div>
                                                  <div class="multi_info">
                                                       <p><strong>Multiple Locations</strong><p> 
                                                       <p class="small">(Refer to the uploaded file below)</p>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12">
                                             <div class="col-xs-12 col-sm-6 no-padding">
                                                  <div class="cfm_sub_h2"><?= lang('collection_window') ?></div>
                                                  <p>
                                                       <span class="date">{{neworder.cdate_convert1_1}}</span>
                                                       <span class="date">{{neworder.cdate_convert1_2}}</span> 
                                                       :: <span class="date">{{neworder.cdate_convert2_1}}</span>
                                                       <span class="date">{{neworder.cdate_convert2_2}}</span>
                                                  </p>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="item_details cfm_box">
                              <div class="cfm_sub_h"><?= lang('parcels') ?></div>
                              <div class="col-xs-12 no-padding">
                                   <div class="col-xs-12 col-sm-4 text-center">
                                        <p style="color:#aaa;font-size:18px;font-weight: 600;">You have uploaded</p>
                                        <div>
                                             <img src="<?= base_url() . 'resource/images/xls.png' ?>" height="80px"> 
                                        </div>
                                        <a href="" id="uploaded_file_link" class="courier_name2" style="word-wrap: break-word;"></a>
                                   </div>
                                   <div class="col-xs-12 col-sm-8">
                                        <p style="color:#f00;font-size:18px;font-weight: 600;">Take Note</p>   
                                        <p style="font-size: 13px;line-height: 24px;"><?= lang('muliple_note') ?></p>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <div class="col-xs-12 col-sm-4 no-padding payment_section">
               <div class="col-xs-12">
                    <div class="col-xs-12 payment_list">
                         <h3><?= lang('payment_methods') ?></h3>
                         <div class="col-xs-12 no-padding"><strong>Pay via</strong> <span class="pull-right add_ac" ng-click="create_account()">Add post-paid Account</span></div>
                         <div>
                              <select class="form-control" ng-model="neworder.payment_mode" style="height: 34px;" ng-options="payment as payment.name for payment in payment_modes">
                              </select>
                         </div>
                         <p ng-if="neworder.payment_mode.credit != null">Your current credit balance is <strong>{{neworder.payment_mode.credit|currency}}</strong></p>
                    </div>
                    <div class="notes m-t-md">
                         <p class="title"><?= lang('things_to_note') ?></p>
                         <p><?= lang('things_to_note_content') ?></p>
                    </div>
               </div>
          </div>
          <div class="col-sm-12 no-padding">
               <div class="srv cfm_box_srv">
                    <div class="cfm_sub_h3">    
                         <span><?= lang('requested_service') ?></span>
                    </div>
                    <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 1" ng-if="neworder.assigned_service.service_name">
                         <div class="col-xs-12 col-sm-3 col-md-3">
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
                              <p class="srv_terms" ng-if="neworder.assigned_service.payment_terms"><?= lang('service_payment_title') ?>  <div ng-bind-html="neworder.assigned_service.payment_terms"></div></p>
                              <div class="col-xs-12 no-padding surcharge" ng-if="neworder.assigned_service.surcharge.length">
                                   <p>Surcharges</p>
                                   <span class="srcharge" ng-repeat="item in neworder.assig ned_service.surcharge| limitTo:neworder.assigned_service.limit">{{item.name}} - <strong>${{item.price}}</strong></span> 
                                   <span class="srcharge more" ng-if="neworder.assigned_service.surcharge.length > 2 && neworder.assigned_service.limit == 2" ng-click="show_more_srcharge_items(neworder.assigned_service, $event)">more</span>
                                   <span class="srcharge more" ng-if="neworder.assigned_service.surcharge.length > 2 && neworder.assigned_service.limit !== 2" ng-click="show_less_srcharge_items(neworder.assigned_service, $event)">less</span>
                              </div>
                         </div>
                         <div class="col-xs-12 col-sm-4 col-md-3">
                              <p><small>Service provided by</small></p>
                              <div class="srv_courier">
                                   <div class="srv_p_img" ng-if="neworder.assigned_service.logo != null">
                                        <img ng-src="{{neworder.assigned_service.logo}}" alt="">
                                   </div>
                                   <div class="clearfix"></div>
                                   <p class=" courier_name" ng-click="view_courier_info(neworder.assigned_service.courier_id, $event)">{{neworder.assigned_service.courier_name}}</p>
                                   <p>Tel : <strong>{{neworder.assigned_service.phone}}</strong></p>
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