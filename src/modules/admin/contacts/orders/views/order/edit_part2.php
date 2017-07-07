<form name="new_order_p2" class="form-horizontal" ng-submit="new_order_p2.$valid && show_part_three()">
     <h3 class="order_title"><?= lang("edit_order_title") ?>
          <span class="pull-right">
               <span class="btn-cancel" ng-click="show_part_one()">
                    <?= lang('back_btn') ?>                                                  
               </span>  
               <button type="submit" class="btn btn-primary btn-sm">
                    <?= lang('next') ?>                                                  
               </button>         
          </span>
     </h3>
     <legend>C. <?= lang('order_caption_service') ?></legend>
     <fieldset>
          <div class="col-sm-12"><p><?= lang('assignment_description') ?></p></div>
          <div class="col-sm-12 no-padding">
               <div class="col-sm-12" style="margin-bottom: 10px;padding-top: 15px;" ng-if="org_dropdown && !single_org">
                    <p class="org-label"><?= lang('your_organisation') ?></p>
                    <div class="col-sm-6" style="margin-bottom: 10px;">
                         <select ng-model="neworder.org_id" class="form-control required col-sm-6" ng-options="org.org_id as org.org_name for org in orglist" ng-class="{error:errors.org_id}" ng-change="resetServices()">
                              <option value=""><?= lang('select_org_ph') ?></option>                                                                            
                         </select>
                         <span class="help-block m-b-none text-danger" ng-show="errors.org_id">{{errors.org_id}}</span>
                    </div>
                    <div class="col-sm-6" style="margin-bottom: 10px;">
                         <p class="new_billing"> 
                              <a ui-sref="organisations" target="_blank"><?= lang('create') ?></a> <?= lang('new_billing_org') ?>
                         </p>
                    </div>
                    <div class="col-xs-12 no-padding">
                         <div class="col-sm-4 no-padding">
                              <div class=" req_bid_wrap service_wrap" ng-click="set_bid_request(1)" ng-class="{active:neworder.delivery_is_assign == 1}">
                                   <div class="col-sm-12 no-padding">     
                                        <div class="hammer_wrap service_wraper">
                                             <img src="<?= base_url("resource/images/service_tab.png") ?>">
                                        </div>
                                        <p class="req_head"><?= lang('use_available_service') ?></p>
                                        <p><?= lang('available_service_intro') ?></p>   
                                   </div>                                   
                              </div>
                         </div>
                         <div class="col-sm-4 no-padding">
                              <div class="req_bid_wrap quote_wrap" ng-click="set_bid_request(2)" ng-class="{active:neworder.delivery_is_assign == 2}">
                                   <div class="col-sm-12 no-padding">     
                                        <div class="hammer_wrap quote_wraper">
                                             <img src="<?= base_url("resource/images/bidding_tab.png") ?>">
                                        </div>
                                        <p class="req_head"><?= lang('get_a_quote') ?></p>
                                        <p><?= lang('bid_market_info') ?></p>   
                                   </div>                                  
                              </div>
                         </div>
                         <div class="col-sm-4 no-padding">
                              <div class="req_bid_wrap quote_wrap" ng-click="set_bid_request(3)" ng-class="{active:neworder.delivery_is_assign == 3}">
                                   <div class="col-sm-12 no-padding">     
                                        <div class="hammer_wrap quote_wraper">
                                             <img src="<?= base_url("resource/images/direct_tab.png") ?>">
                                        </div>
                                        <p class="req_head"><?= lang('direct_assign') ?></p>
                                        <p><?= lang('direct_assign_info') ?></p>   
                                   </div>                                  
                              </div>
                         </div>
                         <div class="col-sm-12 custom_well">

                              <div class="col-sm-12" ng-if="neworder.delivery_is_assign == 1">
                                   <p class="a_srv_h">Available services 
                                        <span class="pull-right">  
                                             <span class="no-padding table_filter" style="display: inline-flex;">
                                                  <select ng-model="servicelistdata.payment" ng-change="getServiceList(false)">
                                                       <option value="{{payment.value}}" ng-repeat="payment in payments">{{payment.name}}</option>
                                                  </select>  
                                             </span>
                                             <span class="no-padding table_filter" style="display: inline-flex;" ng-show="neworder.org_id">
                                                  <select ng-model="servicelistdata.type" ng-change="getServiceList(false)">
                                                       <option value="{{type.value}}" ng-repeat="type in filtertypelist">{{type.name}}</option>
                                                  </select>  
                                             </span>
                                             <span>  Sort by : 
                                                  <span ng-class="{'active':service_filter_field == 'priority'}" ng-click="set_filter('priority')" class="srv_filter">recommended</span> | 
                                                  <span ng-class="{'active':service_filter_field == 'price'}" ng-click="set_filter('price')" class="srv_filter">price</span>                                   
                                             </span>
                                        </span>
                                   </p>
                                   <div class="clearfix"></div>
                                   <p class="help-block m-b-none text-danger" ng-if="errors.assigned_service">{{errors.assigned_service}}</p>
                                   <div class="clearfix"></div>
                                   <p ng-if="not_available" class="not_avail_info"><?= lang('not_service_available') ?></p>
                                   <ul class="srv_list">
                                        <li class="srv col-sm-12" ng-repeat="service in servicelist| orderBy:service_filter_field track by $index" id="service{{service.service_id}}" ng-click="set_service(service, true)" ng-attr-title="{{(service.is_public == 0) ? 'This service is approved for use by your billing organisation.':''}}">
                                             <div class="col-xs-12 col-sm-3 col-md-3">
                                                  <p class="estimate"><?= lang('estimated_price') ?></p>
                                                  <p class="price_tag" ng-bind="calcPrice(service)"></p>
                                                  <div ng-if="service.time_to_deliver">
                                                       <p class="estimate"> <?= lang('deliver_before') ?></p>
                                                       <time> {{ service.time_to_deliver | amCalendar }} </time>
                                                  </div>
                                             </div>
                                             <div class="col-xs-12 col-sm-5 col-md-6">
                                                  <p class="srv_title">{{service.service_name}}
                                                       <span class="label label-custom" ng-if="service.org_status == 2"><?= lang("approved_service") ?></span>
                                                  </p>
                                                  <p class="srv_summary">{{service.description}}</p>
                                                  <p class="srv_terms" ng-if="service.payment_terms"><?= lang('service_payment_title') ?>  <div ng-bind-html="service.payment_terms"></div></p>
                                                  <div class="col-xs-12 surcharge no-padding" ng-if="service.surcharge.length">
                                                       <p>Surcharges</p>
                                                       <span class="srcharge" ng-repeat="item in service.surcharge| limitTo:service.limit">{{item.name}} - <strong>${{item.price}}</strong></span> 
                                                       <span class="srcharge more" ng-if="service.surcharge.length > 2 && service.limit == 2" ng-click="show_more_srcharge_items(service, $event)">more</span>
                                                       <span class="srcharge more" ng-if="service.surcharge.length > 2 && service.limit !== 2" ng-click="show_less_srcharge_items(service, $event)">less</span>
                                                  </div>
                                             </div>
                                             <div class="col-xs-12 col-sm-4 col-md-3">
                                                  <span class="pull-right tick"><img src="<?= base_url('resource/images/tick.png') ?>"></span>
                                                  <p><small>Service provided by</small></p>
                                                  <div>
                                                       <div class="srv_p_img" ng-if="service.logo != null">
                                                            <img ng-src="{{service.logo}}" alt="">
                                                       </div>
                                                       <span class="srv_courier courier_name" ng-click="view_courier_info(service.courier_id, $event)">{{service.courier_name}}</span>
                                                       <div ng-if="service.compliance_id">
                                                            <span class="compliance" ng-class="service.label_class">{{service.rating}}</span>
                                                       </div>
                                                  </div>

                                             </div>

                                        </li>
                                   </ul>
                                   <p ng-show="servicelist.length == 0"><?= lang('no_available_service_info') ?></p>
                              </div>
                              <div class="col-sm-12" ng-if="neworder.delivery_is_assign == 2">
                                   <p class="a_srv_h">Tender</p>
                                   <div class="col-sm-12 no-padding"> 
                                        <div class="col-sm-6 col-md-4">
                                             <div class="form-group ser_ass_text bdtpicker">
                                                  <p style="margin: 0;font-size: 16px;"><?= lang('deadline_title') ?></p>
                                                  <input type="text" ng-model="neworder.deadline" class="form-control " ng-class="{error:errors.deadline}" placeholder="<?= lang('deadline') ?>" style="height:50px" id="bidding_deadline">
                                             </div>
                                             <span class="help-block m-b-none text-danger" ng-show="errors.deadline">{{errors.deadline}}</span>
                                        </div>
                                        <div class="col-sm-6 col-md-8" ng-show="neworder.org_id">
                                             <div class="open_bid_wrap open"  ng-if="scop.open_bid"  style="display: inline-flex">
                                                  <span style="margin:auto 15px auto 0;"> 
                                                       <input type="checkbox" ng-model="neworder.open_bid" ng-class="{error:errors.open_bid}" icheck> 
                                                  </span>
                                                  <div>
                                                       <h4><?= lang('open_bid') ?></h4>
                                                       <p> <?= lang('open_tender_info') ?></p>
                                                  </div>
                                             </div>
                                             <div class="open_bid_wrap closed"  ng-if="!scop.open_bid">
                                                  <div ng-show="scop.c_count > 0">
                                                       <h4 class="tender-title"><?= lang('closed_tender') ?></h4>
                                                       <p> <?= lang('closed_tender_info') ?></p>
                                                  </div>
                                                  <div ng-show="scop.c_count == 0">
                                                       <p style="margin-top: 15px;"> <?= lang('no-approved-couriers') ?></p>
                                                  </div>
                                             </div>
                                        </div>
                                        <script>
                                                     $(function () {
                                                     $('#bidding_deadline').datetimepicker({
                                                     format: 'MM/DD/YYYY h:mm A',
                                                             minDate: moment(),
                                                             defaultDate: moment().add('day', 1)
                                                     });
                                                             setTimeout(function () {
                                                             var result = moment().add('hour', 1).format('MM/DD/YYYY h:mm A');
                                                                     $("#bidding_deadline").val(result);
                                                                     $("#bidding_deadline").trigger('input');
                                                             }, 1000);
                                                             $('#bidding_deadline').on('dp.change', function () {
                                                     $("#bidding_deadline").trigger('input');
                                                     }); //                                                  
                                                     });</script>
                                   </div>                                  
                              </div>
                              <div class="col-sm-12" ng-if="neworder.delivery_is_assign == 3">
                                   <p class="a_srv_h"><?= lang('third_party') ?></p>
                                   <div class="col-xs-12 col-sm-12 col-md-6">
                                        <div class="form-group" ng-class="neworder.third_party_emailerror">
                                             <p style="margin: 0;font-size: 16px;"><?= lang('third_party_email_title') ?></p>
                                             <input class="form-control" ng-model="neworder.third_party_email" ng-change="suggest_third_party()" placeholder="<?= lang('third_party_email_ph') ?>" style="height:50px" ng-class="{error:errors.third_party_email}">
                                             <div class="third-party-list" ng-show="show_third_party_list">
                                                  <ul class="list-group" style="margin-bottom: 0px !important;">
                                                       <li ng-repeat="party in third_parties track by $index" ng-click="setParty(party)" class="list-group-item">
                                                            <a>{{party.email}}</a>
                                                       </li>

                                                  </ul>
                                             </div>
                                             <span class="help-block m-b-none text-danger" ng-show="errors.third_party_email">{{errors.third_party_email}}</span>
                                        </div>                                        
                                   </div>
                              </div>
                         </div>

                    </div>
               </div>

          </div>
     </fieldset>
     <div class="col-sm-12 no-padding margin_top_10">
          <h3 class="order_title">
               <span class="pull-right">
                    <span class="btn-cancel" ng-click="show_part_one()">
                         <?= lang('back_btn') ?>                                                  
                    </span>  
                    <button type="submit" class="btn btn-primary btn-sm">
                         <?= lang('next') ?>                                                  
                    </button>         
               </span>
          </h3>
     </div>
</form>