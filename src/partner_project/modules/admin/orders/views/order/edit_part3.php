<h3 class="order_title"><?= lang("edit_order_title") ?>
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
<div class="col-sm-12 no-padding review_section">
     <div class="well col-sm-12">
          <div class="col-sm-12 no-padding">
               <div class="confirm_info">
                    <h4><?= lang('confirm_info') ?></h4>
                    <h5 ng-if="neworder.delivery_is_assign == 1"><?= lang('confirm_info_sub') ?></h5>
               </div>
          </div>
          <div class="col-sm-12 no-padding">
               <div class="col-sm-3 no-padding" ng-if="neworder.upload">
                    <div class="img_holder" style="cursor: pointer;">
                         <img src="" alt="" ng-src="<?= outer_base_url() ?>filebox/orders/{{neworder.upload}}" ng-if="neworder.upload">
                         <p ng-if="!neworder.upload">No image Provided</p>
                    </div>
               </div>
               <div class=" no-padding" ng-class="{'col-sm-9':neworder.upload,'col-sm-12':!neworder.upload}">
                    <div class="col-sm-12 no-padding">
                         <div class="item_details cfm_box">
                              <p class="org_info">
                                   <?= lang('billing_org') ?> : 
                                   <span ng-repeat="org in orglist| filter:{'org_id' :neworder.org_id}:true | limitTo:1" ng-if="neworder.org_id">{{org.org_name}}
                                   </span>
                              </p>
                              <div class="cfm_sub_h"><?= lang('item_detail') ?></div>
                              <div>
                                   <table class="review_table">
                                        <tr>
                                             <td><?= lang('item') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">{{neworder.type.display_name}}
                                                  <br>
                                                  <span class="dimension" ng-if="neworder.type.consignment_type_id ==<?= CUSTOM_ITEM ?>">{{neworder.weight}}<?= lang('cm') ?>(<strong>W</strong>) x {{neworder.height}}<?= lang('cm') ?>(<strong>H</strong>) x {{neworder.breadth}}<?= lang('cm') ?>(<strong>B</strong>), {{neworder.weight}}<?= lang('kg') ?></span>
                                             </td>
                                        </tr>
                                        <tr>
                                             <td><?= lang('order_quantity') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">{{neworder.quantity}}</td>
                                        </tr>
                                        <tr ng-if="neworder.delivery_is_assign == 1 && neworder.quantity > 1">
                                             <td><?= lang('estimated_price_shot') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">
                                                  ${{neworder.quantity * neworder.assigned_service.price| number:2}}
                                             </td>
                                        </tr>
                                        <tr ng-if="neworder.delivery_is_assign == 1">
                                             <td><?= lang('threshold_price') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">
                                                  <div class="input-group input-group-sm input-c">
                                                       <span class="input-group-addon" id="sizing-addon3">$</span>
                                                       <input type="text" class="form-control" ng-model="neworder.threshold" ng-disabled="neworder.assigned_service.is_public == 0" ng-class="{error:errors.threshold}">
                                                  </div>
                                                  <span class="price_info">(<?= lang('threshold_info') ?>)</span> 
                                                  <div class="clearfix"></div>
                                                  <p class="help-block m-b-none text-danger small" ng-show="errors.threshold">{{errors.threshold}}</p>
                                             </td>
                                        </tr>
                                        <tr>
                                             <td><?= lang('ref_title') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">
                                                  <span class="otag" ng-repeat="ref in refs">{{ref}}</span>
                                                  <span ng-if="refs.length == 0" class="mute"><?= lang('no_ref') ?></span>
                                             </td>
                                        </tr>
                                        <tr>
                                             <td><?= lang('tags') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td class="details">
                                                  <span class="otag" ng-repeat="tag in tags">{{tag}}</span>
                                                  <span ng-if="tags.length == 0" class="mute"><?= lang('no_tag') ?></span>
                                             </td>
                                        </tr>
                                        <tr>
                                             <td><?= lang('remarks') ?></td>
                                             <td class="colon">:&emsp;</td>
                                             <td>
                                                  {{neworder.remarks}}
                                                  <span ng-if="!neworder.remarks" class="mute"><?= lang('no_remarks') ?></span>

                                             </td>
                                        </tr>
                                   </table>
                              </div>
                         </div>
                    </div>
                    <div class="col-sm-12 no-padding text-center">
                         <div class="col-sm-6 no-padding">
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
                                                       <span class="to"><?= lang('to') ?></span>
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
                         <div class="col-sm-6 no-padding">
                              <div class="cfm_box">
                                   <div class="cfm_sub_h"><?= lang('delivery_to_h') ?></div>
                                   <div>
                                        <p class="high_c">{{neworder.delivery_address_l1}}</p>
                                        <p>{{neworder.delivery_address_l2}}</p>
                                        <p>{{neworder.delivery_zipcode}}</p>
                                        <p ng-repeat="country in countrylist| filter:{'code' :neworder.delivery_country} | limitTo:1">{{country.country}}</p>
                                        <p class="high_c smal" ng-if="neworder.is_d_restricted_area">(<?= lang('deliver_restrict') ?>)</p>
                                        <p class="high_c smal" ng-if="!neworder.is_d_restricted_area && neworder.is_c_restricted_area">&nbsp;</p>
                                   </div><hr>
                                   <div>
                                        <p class="cfm_sub_h2"><?= lang('contact_info') ?></p>
                                        <p>{{neworder.delivery_contactname}}</p>
                                        <p>{{neworder.delivery_phone}}</p>
                                        <p>{{neworder.delivery_email}}&nbsp;</p>
                                   </div>
                                   <div class="cfm_box_footer">
                                        <table>
                                             <tr>
                                                  <td>
                                                       <span class="date">{{neworder.ddate_convert1_1}}</span><br>
                                                       <span class="time">{{neworder.ddate_convert1_2}}</span>
                                                  </td>
                                                  <td>
                                                       <span class="to"><?= lang('to') ?></span>
                                                  </td>
                                                  <td>
                                                       <span class="date">{{neworder.ddate_convert2_1}}</span><br> 
                                                       <span class="time">{{neworder.ddate_convert2_2}}</span> 
                                                  </td>
                                             </tr>

                                        </table> 
                                   </div>
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
                              <div class="col-sm-12 no-padding" ng-show="neworder.delivery_is_assign == 3">

                                   <div class="col-sm-12">
                                        <p class="srv_title"><?= lang('third_party') ?></p>
                                        <p class="srv_summary">{{neworder.third_party_email}}</p>
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