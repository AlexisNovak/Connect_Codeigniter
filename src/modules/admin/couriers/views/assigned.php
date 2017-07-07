<div id="rightView" ng-hide="$state.current.name === 'assigned_orders'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'assigned_orders'">
     <div animate-panel>
          <div class="row">
               <div class="col-lg-12 ">
                    <div class="col-lg-12  no-padding"
                         style="background: #fff; border: 1px solid #E4E5E7;">
                         <div class="hpanel">
                              <div class="panel-body" style="border: none !important;">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                                        <div class="clearfix"></div>
                                        <div class="pull-left">
                                             <div class="dataTables_length">
                                                  <label> Show <select class="form-control" name="perpage"
                                                                       ng-model="orderslistdata.perpage"
                                                                       ng-options="ordersperpages as ordersperpages.label for ordersperpages in ordersperpage"
                                                                       ng-change="perpagechange()">
                                                            <option style="display: none" value class>15</option>
                                                       </select> entries
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">
                                             <div class="table_filter" style="display: inline-flex;">
                                                  <label class=" pull-left no-padding "
                                                         style="padding-right: 2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;"> 
                                                       <input
                                                            ng-change="findorders()" aria-controls="order_list"
                                                            class="form-control input-sm" type="search"
                                                            ng-model="orderslistdata.filter">
                                                  </span>

                                             </div>
                                             <div class="table_filter" style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage"
                                                          ng-model="orderslistdata.service" ng-change="findorders()">
                                                       <option value="" disabled>services</option>
                                                       <option value="all">All</option>
                                                       <option ng-repeat="serv in filter_servicelist"
                                                               value="{{serv.service_id}}">{{serv.service_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter" style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage"
                                                          ng-model="orderslistdata.status" ng-change="findorders()">
                                                       <option value="" disabled>status</option>
                                                       <option value="all">All</option>
                                                       <option ng-selected="orderslistdata.status == stat.status_id"
                                                               ng-repeat="stat in filter_statuslist"
                                                               value="{{stat.status_id}}">{{stat.display_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter" style="display: inline-flex;"
                                                  ng-show="org_dropdown">
                                                  <select class="form-control input-sm" name="perpage"
                                                          ng-model="orderslistdata.organisation"
                                                          ng-options="org.org_id as org.org_name for org in orglist"
                                                          ng-change="findorders()">
                                                       <option value=""><?= lang("order_filter_org") ?></option>
                                                  </select>
                                             </div>

                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="order_list"
                                               class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 10%;"><?= lang('order_tracking_id') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.public_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.public_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.public_id.reverse == false}"
                                                                 class="pull-right" ng-click="sort('public_id')"></i></th>
                                                       <th style="width: 10%;"><?= lang('order_assigned_id') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.private_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.private_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.private_id.reverse == false}"
                                                                 class="pull-right" ng-click="sort('private_id')"></i></th>
                                                       <th style="width: 10%;"><?= lang('orders_table_username') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.username.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.username.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.username.reverse == false}"
                                                                 class="pull-right" ng-click="sort('username')"></i></th>
                                                       <th style="width: 10%;"><?= lang('orders_table_services') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.service.reverse == false}"
                                                                 class="pull-right" ng-click="sort('service')"></i></th>
                                                       <th style="width: 15%;"><?= lang('orders_table_collection') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.collection_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.collection_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.collection_address.reverse == false}"
                                                                 class="pull-right" ng-click="sort('collection_address')"></i>
                                                       </th>
                                                       <th style="width: 15%;"><?= lang('orders_table_delivery') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.delivery_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.delivery_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.delivery_address.reverse == false}"
                                                                 class="pull-right" ng-click="sort('delivery_address')"></i>
                                                       </th>
                                                       <th style="width: 10%;" ng-if="!org_id"><?= lang('orders_table_organisation') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.org_name.reverse == false}"
                                                                 class="pull-right" ng-click="sort('org_name')"></i></th>
                                                       <th style="width: 10%;"><?= lang('orders_table_status') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.status.reverse == false}"
                                                                 class="pull-right" ng-click="sort('status')"></i></th>
                                                       <th style="width: 10%;"><?= lang('orders_table_date') ?>
                                                            <i
                                                                 ng-class="{'glyphicon glyphicon-sort':orderheaders.cdate.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.cdate.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.cdate.reverse == false}"
                                                                 class="pull-right" ng-click="sort('cdate')"></i></th>
                                                       <th style="width: 10%;"><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr
                                                       ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                       <td><a
                                                                 ui-sref="assigned_orders.view_order({aorder_id:order.public_id})"
                                                                 class="link_color"> {{order.public_id}}</a></td>
                                                       <td>{{order.private_id}}</td>
                                                       <td>{{order.username}}</td>
                                                       <td>{{order.service}} 
                                                            <!--<span ng-if="order.price != 0"> {{ order.price | currency:"SGD$":0 }} </span>-->
                                                       </td>
                                                       <td>{{order.collection_contact_name}}<br>
                                                            {{order.collection_address}}<br> {{order.from_country}}<br>
                                                            {{order.collection_contact_number}} <span
                                                                 ng-if="order.crestrict != 0" class="row_icon"
                                                                 title="<?= lang('restricted_area_tooltip') ?>"><i
                                                                      class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td>{{order.delivery_contact_name}}<br>
                                                            {{order.delivery_address}}<br> {{order.to_country}}<br>
                                                            {{order.delivery_contact_phone}} <span
                                                                 ng-if="order.drestrict != 0" class="row_icon"
                                                                 title="<?= lang('restricted_area_tooltip') ?>"><i
                                                                      class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td ng-if="!org_id">{{order.org_name|| "-"}}</td>
                                                       <td><strong>{{order.status}}</strong><br>
                                                            <span ng-if="order.price != 0">SGD{{order.price}}</span></td>
                                                       <td>{{order.cdate}}</td>
                                                       <td><span class="btn btn-sm btn-default m-b-xs"
                                                                 ng-if="order.private_id && order.consignment_status_id != '10'"
                                                                 ng-click="show_update_status(order.consignment_id)">Update
                                                                 Status</span>
                                                            <span class="btn btn-sm btn-default m-b-xs"
                                                                  ng-if="!order.private_id && order.is_for_bidding == 0"
                                                                  ng-click="show_accept(order.consignment_id, order.is_for_bidding, order.price)">Accept</span>
                                                            <span class="btn btn-sm btn-default m-b-xs"
                                                                  ng-if="!order.private_id"
                                                                  ng-click="show_reject_order(order.consignment_id, order.is_for_bidding)">Reject</span>
                                                            <span class="btn btn-sm btn-default m-b-xs"
                                                                  ng-if="!order.private_id && order.is_for_bidding == 1"
                                                                  ng-click="show_accept(order.consignment_id, order.is_for_bidding, order.price)">Assign a private ID</span>
                                                            <span class="btn btn-sm btn-default m-b-xs"
                                                                  ng-if="order.private_id"
                                                                  ng-click="show_change_price(order.consignment_id)">Change
                                                                 Price</span>
                                                            <span class="btn btn-sm btn-default m-b-xs"
                                                                  ng-if="order.cancel_request == 1"
                                                                  ng-click="show_allow_cancel(order.consignment_id)">Allow cancel
                                                                 order</span>
                                                       </td>
                                                  </tr>
                                             <div class="angular_popup popup_lg pull-right warning_box" ng-show="show_accept_popup">
                                                  <h3> 
                                                       <span ng-show="show_price_field">Accept Job?</span>
                                                       <span ng-show="!show_price_field">Assign a Private ID?</span>

                                                       <i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>
                                                  <div class="col-lg-12">
                                                       <div class="form-group" ng-show="show_price_field">
                                                            <h5>Job Fee </h5>
                                                            <p ng-show="show_price_field" class="small">By default, we set it as the computed fee of you have given us</p>
                                                            <div class="clearfix"></div>
                                                            <input type="text"
                                                                                        ng-model="accept.price" class="form-control"> <span
                                                                                        class="help-block m-b-none text-danger"
                                                                                        ng-show="accept.errors.price">{{accept.errors.price}}</span>

                                                       </div>
                                                       <div class="form-group">
                                                            <h5>Courier Tracking ID</h5> 
                                                            <p ng-show="show_price_field" class="small">You can set your own tracking code to this job, or just leave this blank</p>
                                                            <div class="clearfix"></div>
                                                            <input type="text"
                                                                                              ng-model="accept.private_id" class="form-control"> <span
                                                                                              class="help-block m-b-none text-danger"
                                                                                              ng-show="accept.errors.private_id">{{accept.errors.private_id}}</span>
                                                       </div>
                                                       <div class="form-group">
                                                            <h5>Remarks</h5>
                                                            <p ng-show="show_price_field" class="small">We recommend you leave an explanation if you change the fee</p>
                                                            <div class="clearfix"></div>
                                                            <textarea type="text" ng-model="accept.remarks"
                                                                      class="form-control"
                                                                      placeholder="any delivery details to let user know"></textarea>
                                                            <span class="help-block m-b-none text-danger"
                                                                  ng-show="accept.errors.remarks">{{accept.errors.remarks}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10"
                                                             ng-click="accept_order()" ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10"
                                                             ng-click="cancel_accept()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_sm pull-right warning_box"
                                                  ng-show="show_update_status_popup">
                                                  <h3>
                                                       Update Status<i class="fa fa-close pull-right"
                                                                       ng-click="cancel_update_status()"></i>
                                                  </h3>
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label>Status</label> <select ng-model="update.status"
                                                                                          class="form-control">
                                                                 <option value="">Select status</option>
                                                                 <option ng-repeat="ustat in update_statuslist"
                                                                         value="{{ustat}}">{{ustat.display_name}}</option>
                                                            </select> <span class="help-block m-b-none text-danger"
                                                                            ng-show="update.errors.status">{{update.errors.status}}</span>

                                                       </div>
                                                       <div class="form-group">
                                                            <label>Remarks</label>
                                                            <textarea type="text" ng-model="update.remarks"
                                                                      class="form-control"
                                                                      placeholder="any delivery details to let user know"></textarea>
                                                            <span class="help-block m-b-none text-danger"
                                                                  ng-show="update.errors.remarks">{{update.errors.remarks}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10"
                                                             ng-click="update_order()" ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10"
                                                             ng-click="cancel_update_status()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_sm pull-right warning_box"
                                                  ng-show="show_change_price_popup">
                                                  <h3>
                                                       Change Price<i class="fa fa-close pull-right"
                                                                      ng-click="cancel_change_price()"></i>
                                                  </h3>
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label>Price</label> <input type="text"
                                                                                        ng-model="change_price.price" placeholder="price"
                                                                                        class="form-control"> <span
                                                                                        class="help-block m-b-none text-danger"
                                                                                        ng-show="change_price.errors.price">{{change_price.errors.price}}</span>

                                                       </div>
                                                       <div class="form-group">
                                                            <label>Remarks</label>
                                                            <textarea type="text" ng-model="change_price.remarks" class="form-control" placeholder="reason"></textarea>
                                                            <span class="help-block m-b-none text-danger" ng-show="change_price.errors.remarks">{{change_price.errors.remarks}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10"
                                                             ng-click="add_change_price()"
                                                             ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10"
                                                             ng-click="cancel_change_price()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>

                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_allow_cancel_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_allow_cancel()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('cancel_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="allow_cancel()" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_allow_cancel()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>  
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_reject_order_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_reject_order()"></i></h3>
                                                 
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                             <p style="text-align: left;"><?= lang('reject_order_confirm') ?></p>
                                                            <textarea type="text" ng-model="rejectorder.reason" class="form-control" placeholder="reason"></textarea>
                                                            <span class="text-danger text-center">{{rejectorder.reasonerror}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="reject_order()" style=""><?= lang('confirm') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_reject_order()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>  
                                             <tr class="no-data">
                                                  <td colspan="10"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                             <tbody id="orders_loading" class="loading">
                                                  <tr>
                                                       <td colspan="10" class="text-center"><img
                                                                 src="<?php echo base_url(); ?>resource/images/loading-bars.svg"
                                                                 width="36" height="36" alt="<?= lang('loading') ?>"></td>
                                                  </tr>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div class="col-md-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing
                                                  {{start}} to {{end}} of {{total}} entries</div>
                                        </div>
                                        <div class="col-md-8 text-right no-padding">

                                             <paging class="small" page="orderslistdata.currentPage"
                                                     page-size="orderslistdata.perpage_value"
                                                     total="orderslistdata.total" adjacent="{{adjacent}}"
                                                     dots="{{dots}}" scroll-top="{{scrollTop}}"
                                                     hide-if-empty="false" ul-class="{{ulClass}}"
                                                     active-class="{{activeClass}}"
                                                     disabled-class="{{disabledClass}}" show-prev-next="true"
                                                     paging-action="getOrders(page)"> </paging>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>



