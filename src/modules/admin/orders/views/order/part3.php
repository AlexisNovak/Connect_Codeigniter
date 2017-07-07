<h3 class="order_title"><?= lang("new_order_title") ?>
     <span class="pull-right">
          <span class="btn-cancel" ng-click="show_part_two()">
               <?= lang('back_to_service') ?>                                                  
          </span> 
          <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder(true)">
               <?= lang('save_as_draft_btn') ?>                                                 
          </button>
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
                                   <div class="col-sm-12 no-padding" ng-if="neworder.upload">
                                        <div style="cursor: pointer;max-height: 200px;">
                                             <img src="" alt="" ng-src="<?= base_url() ?>filebox/orders/{{neworder.upload}}" ng-if="neworder.upload">
                                             <p ng-if="!neworder.upload">No image Provided</p>
                                        </div>
                                   </div>
                                   <div class="col-xs-12">
                                        <div class="col-xs-12 col-sm-6" ng-show="neworder.delivery_is_assign == 3">
                                             <span class="item_name"><?= lang('third_party') ?> </span>
                                             <p>{{neworder.third_party_email}}</p>
                                        </div>
                                        <div class="col-xs-12 col-sm-6" ng-show="neworder.delivery_is_assign == 2">
                                             <span class="item_name"><?= lang('deadline_title') ?> </span>
                                             <p>{{neworder.deadline}}</p>
                                        </div>
                                        <div class="col-xs-12 col-sm-6" ng-show="neworder.delivery_is_assign == 1" ng-if="neworder.assigned_service.service_name">
                                             <span class="item_name">Service Requested</span>
                                             <p class="srv_nam">{{neworder.assigned_service.service_name}}</p>
                                             <p><small>Provided by</small> <span class="srv_courier_rw courier_name" ng-click="view_courier_info(neworder.assigned_service.courier_id, $event)">{{neworder.assigned_service.courier_name}}</span>
                                             </p>
                                        </div> 
                                        <div class="col-xs-12 col-sm-6" ng-if="neworder.delivery_is_assign == 1">
                                             <span class="item_name"><?= lang('estimated_price_shot') ?></span>
                                             <p class="price_est">${{price| number:2}}</p>
                                        </div> 
                                        <div class="col-xs-12">
                                             <span class="item_name"><?= lang('ref_title') ?></span>
                                             <p>
                                                  <span class="otag" ng-repeat="ref in refs">{{ref}}</span>
                                                  <span ng-if="refs.length == 0" class="mute"><?= lang('no_ref') ?></span>
                                             </p>
                                        </div>
                                        <div class="col-xs-12">
                                             <span class="item_name"><?= lang('tags') ?></span>
                                             <p>    <span class="otag" ng-repeat="tag in tags">{{tag}}</span>
                                                  <span ng-if="tags.length == 0" class="mute"><?= lang('no_tag') ?></span>
                                             </p>
                                        </div>
                                        <div class="col-xs-12">
                                             <span class="item_name"><?= lang('remarks') ?></span>
                                             <p>
                                                  {{neworder.remarks}}
                                                  <span ng-if="!neworder.remarks" class="mute"><?= lang('no_remarks') ?></span>
                                             </p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
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
                                                  <p> {{neworder.delivery_company}}<br>
                                                       {{neworder.delivery_contactname}}, {{neworder.delivery_phone}}<br>
                                                  </p>
                                                  <p>{{neworder.delivery_address_l1}}, {{neworder.delivery_address_l2}}
                                                       <span ng-repeat="country in countrylist| filter:{'code' :neworder.delivery_country} | limitTo:1">{{country.country}}</span>
                                                       <span class="high_c smal" ng-if="neworder.is_d_restricted_area">(<?= lang('deliver_restrict') ?>)</span> 
                                                       <span class="high_c smal" ng-if="!neworder.is_c_restricted_area && neworder.is_d_restricted_area">&nbsp;</span>
                                                       {{neworder.delivery_zipcode}} </p>
                                                  <p> 

                                             </div>
                                        </div>
                                        <div class="col-xs-12">
                                             <div class="col-xs-12 col-sm-6 no-padding">
                                                  <div class="cfm_sub_h2"><?= lang('collection_window') ?></div>
                                                  <p>
                                                       <span class="date">{{neworder.cdate_convert1_1}}</span>
                                                       <span class="date">{{neworder.cdate_convert1_2}}</span> 
                                                       to <span class="date">{{neworder.cdate_convert2_1}}</span>
                                                       <span class="date">{{neworder.cdate_convert2_2}}</span></p>

                                             </div>
                                             <div class="col-xs-12 col-sm-6 no-padding">
                                                  <div class="cfm_sub_h2"><?= lang('delivery_window') ?></div>
                                                  <p>   <span class="date">{{neworder.ddate_convert1_1}}</span>
                                                       <span class="date">{{neworder.ddate_convert1_2}}</span> 
                                                       to <span class="date">{{neworder.ddate_convert2_1}}</span>
                                                       <span class="date">{{neworder.ddate_convert2_2}}</span>  <p> 

                                             </div>
                                        </div>
                                   </div>
                              </div>

                         </div>
                         <div class="item_details cfm_box">
                              <div class="cfm_sub_h"><?= lang('parcels') ?></div>
                              <div class="table-responsive">
                                   <table class="table table-striped">
                                        <tr>
                                             <th>Parcel #</th>
                                             <th>Parcel Type</th>
                                             <!--<th>List of Items in Parcel</th>-->
                                        </tr>
                                        <tr>
                                             <td>1</td>
                                             <td>{{neworder.type.display_name}}</td>
                                             <!--<td></td>-->
                                        </tr>
                                   </table>
                              </div>
                         </div>
                    </div>


               </div>
          </div>

          <div class="col-xs-12 col-sm-4 no-padding payment_section">
               <div class="col-xs-12">
                    <div class="col-xs-12 payment_list" ng-if="neworder.delivery_is_assign != 3">
                         <h3><?= lang('payment_methods') ?></h3>
                         <div class="col-xs-12 no-padding"><strong>Pay via</strong> <span class="pull-right add_ac" ng-click="create_account()">Apply for Credit Terms</span></div>
                         <div class="clearfix"></div>
                         <div id="payselectUiSelect">
                              <ui-select  ng-model="neworder.payment_mode" theme="bootstrap"  ng-disabled="disabled">
                                   <ui-select-match placeholder="Pick one...">
                                        <div style="line-height: 4px;padding-top: 8px;">{{$select.selected.name}}</div>
                                        <small ng-if="($select.selected.id != 0)" style="color: #f8c400;font-size: 10px;">{{ ($select.selected.id != 0) ? $select.selected.type : ''}}</small>
                                   </ui-select-match>
                                   <ui-select-choices repeat="payment in payment_modes | filter: {name: $select.search}">
                                        <div ng-bind-html="payment.name | highlight: $select.search" style="font-size: 16px;"></div>
                                        <small  ng-if="(payment.id != 0)" style="color: #f8c400;font-size: 10px;margin-top: -8px;">{{ (payment.id != 0) ? payment.type : ''}}</small>
                                   </ui-select-choices>
                              </ui-select>
                         </div>
                         <p ng-if="neworder.payment_mode.credit != null">Your current credit balance is <strong>{{neworder.payment_mode.credit|currency}}</strong></p>

                         <div class="m-t-md" ng-if="neworder.delivery_is_assign == 1">
                              <div><?= lang('threshold_price') ?></div>
                              <div>
                                   <div class="input-group input-group-sm input-c">
                                        <span class="input-group-addon" id="sizing-addon3">$</span>
                                        <input  min="{{price}}" type="number" class="form-control" ng-model="neworder.threshold" ng-disabled="neworder.assigned_service.is_public == 0" ng-class="{error:errors.threshold}">
                                   </div>
                                   <span class="th_info">(<?= lang('threshold_info') ?>)</span> 
                                   <div class="clearfix"></div>
                                   <p class="help-block m-b-none text-danger small" ng-show="errors.threshold">{{errors.threshold}}</p>
                              </div>
                         </div>
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
                         <span ng-show="neworder.delivery_is_assign == 1"><?= lang('requested_service') ?></span>
                         <span ng-show="neworder.delivery_is_assign == 2"><?= lang('service_assignment') ?></span>
                         <span ng-show="neworder.delivery_is_assign == 3"><?= lang('request_for_direct') ?></span>
                    </div>
                    <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 1" ng-if="neworder.assigned_service.service_name">
                         <div class="col-xs-12 col-sm-3 col-md-3">
                              <span class="estmtd_price"><?= lang('estimated_price') ?></span><div class="clearfix"></div>
                              <p class="price_tag" ng-bind="calcPrice(neworder.assigned_service)"></p>
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
                                   <span class="srcharge" ng-repeat="item in neworder.assigned_service.surcharge| limitTo:neworder.assigned_service.limit">{{item.name}} - <strong>${{item.price}}</strong></span> 
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

                              </div>

                              <div style="padding-top: 10px;" ng-show="neworder.assigned_service.insured_policy != null">
                                   <p style="font-size: 14px;">Courier Insurance Policy</p>
                                   <span style="color: #999"><small>{{neworder.assigned_service.insured_policy}}</small></span>
                              </div>
                         </div> 
                    </div>
                    <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 2">
                         <div class="col-sm-12">
                              <div class="bid_item"> 
                                   <p class="srv_item_info"><?= lang('bidding_type') ?></p>
                                   <p class="item_data">
                                        <span ng-if="neworder.org_id">
                                             <span ng-if="!neworder.open_bid">Closed Bidding</span>
                                             <span ng-if="neworder.open_bid">Open Bidding</span>
                                        </span>
                                        <span ng-if="!neworder.org_id">
                                             <span>Open Bidding</span>
                                        </span>
                                   </p>
                              </div>
                              <div class="bid_item">                               
                                   <p class="srv_item_info"><?= lang('bidding_expiry_date') ?></p>
                                   <p class="item_data" ng-if="neworder.deadline">{{neworder.deadline}}</p>
                                   <p class="item_data" ng-if="!neworder.deadline"><?= lang('no_deadline') ?></p>
                              </div>
                         </div>
                    </div>

                    <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 3">
                         <div class="col-sm-12">
                              <div class="bid_item">                               
                                   <p class="srv_item_info"><?= lang('third_party') ?></p>
                                   <p class="item_data">{{neworder.third_party_email}}</p>
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
               <span class="btn-cancel" ng-click="show_part_two()">
                    <?= lang('back_to_service') ?>                                                  
               </span> 
               <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder(true)">
                    <?= lang('save_as_draft_btn') ?>                                                 
               </button>
               <button type="button" class="btn btn-primary btn-sm" ng-click="saveOrder(false)">    <?= lang('confirm_order') ?>                                                   
               </button>       
          </span>
     </h3>
</div>