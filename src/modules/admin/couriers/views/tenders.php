<div id="rightView" ng-hide="$state.current.name === 'tenders'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'tenders'">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 " > 
                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="hpanel">
                              <div class="panel-body" style="border:none !important;"> 
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                                        <div class="clearfix"></div>
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="orderslistdata.perpage"  
                                                               ng-options="ordersperpages as ordersperpages.label for ordersperpages in ordersperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findorders()" aria-controls="order_list"  class="form-control input-sm" type="search" ng-model="orderslistdata.filter">
                                                  </span>

                                             </div>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="order_list" class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                                                       <th style="width:10%;"><?= lang('order_tracking_id') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.public_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.public_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.public_id.reverse == false}" class="pull-right" ng-click="sort('public_id')"></i>        
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_username') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.username.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.username.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.username.reverse == false}" class="pull-right" ng-click="sort('username')"></i>                     
                                                       </th>
                                                       <th style="width:15%;"><?= lang('orders_table_collection') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.collection_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.collection_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.collection_address.reverse == false}" class="pull-right" ng-click="sort('collection_address')"></i>          
                                                       </th>
                                                       <th style="width:15%;"><?= lang('orders_table_delivery') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.delivery_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.delivery_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.delivery_address.reverse == false}" class="pull-right" ng-click="sort('delivery_address')"></i>  
                                                       </th>
                                                       <th style="width:15%;"><?= lang('orders_table_organisation') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>        
                                                       </th>
                                                       <th style="width:15%;"><?= lang('order_date') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.created_date.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.created_date.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.created_date.reverse == false}" class="pull-right" ng-click="sort('created_date')"></i>        
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.status_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.status_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.status_name.reverse == false}" class="pull-right" ng-click="sort('status_name')"></i>   
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_bid') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.bid.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.bid.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.bid.reverse == false}" class="pull-right" ng-click="sort('bid')"></i>   
                                                       </th>
                                                       <th style="width:10%;"><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <a ui-sref="tenders.view_tender({tender_id:order.public_id})" class="link_color"> {{order.public_id}}</a>

                                                       </td>
                                                       <td> {{order.username}}</td>
                                                       <td>
                                                            {{order.collection_address}}<br>
                                                            {{order.collection_country}}<br>
                                                            {{order.collection_post_code}}   
                                                            <span ng-if="order.crestrict !=0" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                            <br>
                                                            {{order.collection_date_from}} {{order.collection_time_from}} - {{order.collection_date_to}} {{order.collection_time_to}}
                                                       </td>
                                                       <td>
                                                            {{order.delivery_address}}<br>
                                                            {{order.delivery_country}}<br>
                                                            {{order.delivery_post_code}}   
                                                            <span ng-if="order.drestrict !=0" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                            <br>
                                                            {{order.delivery_date_from}} {{order.delivery_time_from}} - {{order.delivery_date_to}} {{order.delivery_time_to}}
                                                       </td>
                                                       <td>{{order.org_name || "-"}}</td>
                                                       <td>{{order.created_date}}</td>
                                                       <td>{{order.status_name}}</td>
                                                       <td>{{order.bid}}</td>
                                                       <td>
                                                            <!--                                                          
                                                                        
                                                                         * 1 new
                                                                         * 2 pending approval
                                                                         * 3 won
                                                                         * 4 lost
                                                                         * 5 withdrawn
                                                                         
                                                            -->
                                                            <span ng-if="order.status == 1" class="btn btn-default btn-sm" ng-click="show_bid(order.id)">Bid</span>
                                                            <span ng-if="order.status == 2 " class="btn btn-default btn-sm" ng-click="show_withdraw(order.bid_id)">Withdraw</span>
                                                            <span ng-if="order.status == 5" class="btn btn-default btn-sm" ng-click="show_bid(order.id)"> Re-Bid</span>
                                                       </td>
                                                  </tr>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_bid_popup"> 
                                                  <h3><?= lang('bid_popup_title') ?><i class="fa fa-close pull-right" ng-click="cancel_bid()"></i></h3>
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label>Service</label>
                                                            <select ng-model="bid.service" class="form-control">
                                                                 <option value="">Select Service</option>
                                                                 <option ng-repeat="service in servicelist" value="{{service}}">{{service.display_name}}</option>
                                                            </select> 
                                                            <span class="help-block m-b-none text-danger" ng-show="bid.errors.service">{{bid.errors.service}}</span>

                                                       </div>
                                                       <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="text" ng-model="bid.price" class="form-control">
                                                            <span class="help-block m-b-none text-danger" ng-show="bid.errors.price">{{bid.errors.price}}</span>

                                                       </div>
                                                       <div class="form-group">
                                                            <label>Remarks</label>
                                                            <textarea ng-model="bid.remarks" class="form-control" placeholder="<?= lang('bid_remark_ph') ?>"></textarea>
                                                            <span class="help-block m-b-none text-danger" ng-show="bid.errors.remarks">{{bid.errors.remarks}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="bid_order()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_bid()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_withdraw_popup"> 
                                                  <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_withdraw()"></i></h3>
                                                  <div class="col-lg-12">
                                                       <p><?= lang('withdraw_bid') ?></p>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="withdraw_order()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_withdraw()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <tr class="no-data">
                                                  <td colspan="9"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                             <tbody id="orders_loading" class="loading">
                                                  <tr>                                                  
                                                       <td colspan="9" class="text-center">
                                                            <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
                                                       </td>
                                                  </tr>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div class="col-md-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                        </div> 
                                        <div class="col-md-8 text-right no-padding">

                                             <paging
                                                  class="small"
                                                  page="orderslistdata.currentPage" 
                                                  page-size="orderslistdata.perpage_value" 
                                                  total="orderslistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getOrders(page)">
                                             </paging> 
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>