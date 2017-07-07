<div id="rightView" ng-hide="$state.current.name === 'tender_requests.delivery'">
     <div ui-view></div>
</div>
<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="tenderorderCtrl" ng-show="$state.current.name === 'tender_requests.delivery'">
               <div class="col-lg-12">
                    <div class="hpanel">                        
                         <div class="panel-body">                            
                              <div ng-show="show_table">
                                   <div class="angular_popup pull-right warning_box popup_mid" ng-show="save_request_popup"> 
                                        <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_request_change()"></i></h3>
                                        <p style="text-align: center;padding: 10px;"><?= lang('delivery_request_change') ?></p>
                                        <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="edit_request_details()" ><?= lang('yes') ?></span>
                                             <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_request_change()"><?= lang('no') ?></span>
                                        </div>
                                   </div>
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
                                                  <input ng-change="findorders()" aria-controls="order_list"  class="form-control input-sm" type="search" ng-model="orderslistdata.filter" placeholder="<?= lang('search_label') ?>">
                                             </div> 
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.category" ng-change="findorders()">
                                                       <option value="all">All Requests</option>
                                                       <option value="open">Open</option>
                                                       <option value="closed">Closed</option>
                                                       <option value="expired">Expired</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.organisation"  
                                                          ng-options="org.org_id as org.org_name for org in orglist" ng-change="findteams()">
                                                       <option value=""><?= lang("order_filter_org") ?></option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;" ng-if="orderslistdata.organisation">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.team" ng-change="findorders()">
                                                       <option value="all">All Teams</option>
                                                       <option ng-repeat="team in filter_teamlist" value="{{team.id}}">{{team.name}}</option>
                                                  </select>
                                             </div>
                                             <div class="no-padding table_filter" style="display: inline-flex;">
                                                  <span ng-click="new_delivery_request()" class="btn btn-sm btn-info"><?= lang('new_delivery_tender') ?></span>
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
                                                       <th style="width:15%;"><?= lang('orders_table_collection') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.collection_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.collection_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.collection_address.reverse == false}" class="pull-right" ng-click="sort('collection_address')"></i>  
                                                       </th>
                                                       <th style="width:15%;"><?= lang('orders_table_delivery') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.delivery_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.delivery_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.delivery_address.reverse == false}" class="pull-right" ng-click="sort('delivery_address')"></i>  
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>   
                                                       </th>
                                                       <th style="width:10%;" ng-if="!org_id"><?= lang('orders_table_organisation') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>  
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_date') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.cdate.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.cdate.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.cdate.reverse == false}" class="pull-right" ng-click="sort('cdate')"></i>  
                                                       </th>
                                                       <th style="width:10%;">
                                                            <?= lang('action') ?>
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <a ui-sref="tender_requests.delivery.view_order({order_id:order.public_id})" class="link_color"> {{order.public_id}}</a>

                                                       </td>
                                                       <td>
                                                            {{order.collection_contact_name}}<br>
                                                            {{order.collection_address}}<br>
                                                            {{order.from_country}}<br>
                                                            {{order.collection_contact_number}}
                                                            <span ng-if="order.crestrict !=0" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td>
                                                            {{order.delivery_contact_name}}<br>
                                                            {{order.delivery_address}}<br>
                                                            {{order.to_country}}<br>
                                                            {{order.delivery_contact_phone}}
                                                            <span ng-if="order.drestrict == 1" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td>{{order.status}}</td>
                                                       <td ng-if="!org_id">{{order.org_name|| "-"}}
                                                            <span ng-if="order.group_name">({{order.group_name}})</span>
                                                       </td>
                                                       <td>{{order.cdate}}</td>
                                                       <td>
                                                            <span ng-click="change_request(order.consignment_id)" title="<?= lang('edit_btn') ?>" ng-if="order.consignment_status_id==<?=C_GETTING_BID?>"><i class="fa fa-edit"></i></span>

                                                       </td>
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="7"><?= lang('nothing_to_display') ?></td>
                                                  </tr>
                                             </tbody>
                                             <tbody id="orders_loading" class="loading">
                                                  <tr>                                                  
                                                       <td colspan="6" class="text-center">
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
                              <div ng-show="show_init">
                                   <div class="init_div">
                                        <h2><?= lang('init_delivery_tender') ?></h2>
                                        <p><?= lang('init_delivery_tender_info') ?></p>
                                        <span ng-click="new_delivery_request()" class="btn btn-lg btn-info"><?= lang('new_delivery_tender') ?></span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>