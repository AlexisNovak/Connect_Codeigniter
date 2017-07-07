<form name="new_order_p2" class="form-horizontal" ng-submit="new_order_p2.$valid && show_part_four()">
     <h3 class="order_title"><?= lang("multi_order_title") ?>
          <span class="pull-right">
               <span class="btn-cancel" ng-click="show_part_two()">
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

                    <div class="col-xs-12 no-padding">
                         <div class="col-sm-6 no-padding">
                              <div class=" req_bid_wrap" ng-click="set_bid_request(1)" ng-class="{active:neworder.delivery_is_assign == 1}">
                                   <div class="col-sm-12 no-padding">     
                                        <div class="hammer_wrap">
                                             <img src="<?= base_url("resource/images/service_tab.png") ?>">
                                        </div>
                                        <p class="req_head"><?= lang('use_available_service') ?></p>
                                        <p><?= lang('available_service_intro') ?></p>   
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
                                                       <span class="label label-custom" ng-if="service.is_public == 0"><?= lang("approved_service") ?></span>
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
                         </div>

                    </div>
               </div>

          </div>
     </fieldset>
     <div class="col-sm-12 no-padding margin_top_10">
          <h3 class="order_title">
               <span class="pull-right">
                    <span class="btn-cancel" ng-click="show_part_two()">
                         <?= lang('back_btn') ?>                                                  
                    </span>  
                    <button type="submit" class="btn btn-primary btn-sm">
                         <?= lang('next') ?>                                                  
                    </button>         
               </span>
          </h3>
     </div>
</form>