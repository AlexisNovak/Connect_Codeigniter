<div class="col-lg-12 p-lg" ng-controller="cordersCtrl">
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
                                                  <span class="no-padding " style="display: inline-flex;">
                                                       <input ng-change="findorders()" aria-controls="order_list"  class="form-control input-sm" type="search" ng-model="orderslistdata.filter">
                                                  </span>

                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.service" ng-change="findorders()">
                                                       <option value="" disabled>services</option>
                                                       <option value="all">All</option>
                                                       <option ng-repeat="serv in filter_servicelist" value="{{serv.service_id}}">{{serv.service_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.status" ng-change="findorders()">
                                                       <option value="" disabled>status</option>
                                                       <option value="all">All</option>
                                                       <option ng-repeat="stat in filter_statuslist" value="{{stat.status_id}}">{{stat.display_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;" ng-show="org_dropdown">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.organisation"  
                                                          ng-options="org.org_id as org.org_name for org in orglist" ng-change="findorders()">
                                                       <option value=""><?= lang("order_filter_org") ?></option>
                                                  </select>
                                             </div>

                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="order_list" class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                         <th style="width:5%;"><?= lang('order_tracking_id') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.public_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.public_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.public_id.reverse == false}" class="pull-right" ng-click="sort('public_id')"></i>        
                         </th>
                         <th style="width:10%;"><?= lang('order_assigned_id') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.private_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.private_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.private_id.reverse == false}" class="pull-right" ng-click="sort('private_id')"></i>  
                         </th>
                         <th style="width:10%;"><?= lang('orders_table_username') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.username.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.username.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.username.reverse == false}" class="pull-right" ng-click="sort('username')"></i>                     
                         </th>
                         <th style="width:15%;"><?= lang('orders_table_services') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.service.reverse == false}" class="pull-right" ng-click="sort('service')"></i>  
                         </th>
                         <th style="width:15%;"><?= lang('orders_table_collection') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.collection_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.collection_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.collection_address.reverse == false}" class="pull-right" ng-click="sort('collection_address')"></i>          
                         </th>
                         <th style="width:15%;"><?= lang('orders_table_delivery') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.delivery_address.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.delivery_address.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.delivery_address.reverse == false}" class="pull-right" ng-click="sort('delivery_address')"></i>  
                         </th>
                         <th style="width:10%;" ng-if="!org_id"><?= lang('orders_table_organisation') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>  
                         </th>
                         <th style="width:10%;"><?= lang('orders_table_status') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':orderheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>   
                         </th>
                         <!--<th style="width:10%;"><?= lang('action') ?></th>-->
                    </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                        {{order.public_id}} 
                                                       </td>
                                                       <td> {{order.private_id}}</td>
                                                       <td> {{order.username}}</td>
                                                       <td>{{order.service}}</td>
                                                       <td>
                                                            {{order.collection_contact_name}}<br>
                                                            {{order.collection_address}}<br>
                                                            {{order.from_country}}<br>
                                                            {{order.collection_contact_number}}
                                                       </td>
                                                       <td>
                                                            {{order.delivery_contact_name}}<br>
                                                            {{order.delivery_address}}<br>
                                                            {{order.to_country}}<br>
                                                            {{order.delivery_contact_phone}}
                                                       </td>
                                                       <td ng-if="!org_id">{{order.org_name}}</td>
                                                       <td>{{order.status}}</td>
                                                       <!--                                                  <td>
                                                       <span ng-if="order.consignment_status_id == <?= C_DRAFT ?>">
                                                       <span ng-if="org_id">
                                                       <a ui-sref="organisation.orders.edit_order({order_id:order.public_id})">
                                                       <i class="fa fa-edit" title="edit"></i>
                                                       </a>
                                                       </span>
                                                       <span ng-if="!org_id">
                                                       <a ui-sref="delivery_orders.edit_order({order_id:order.public_id})">
                                                       <i class="fa fa-edit" title="edit"></i>
                                                       </a>
                                                       </span>
                                                       
                                                       <a ng-click="show_delete_warning(order.consignment_id)">
                                                       <i class="fa fa-trash" title="delete"></i>
                                                       </a>
                                                       </span>
                                                       </td>-->
                                                  </tr>
                                                  <!--                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="DO_delete_warning_popup"> 
                                                                                                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                                                                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                                                                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="deleteOrder(delete_id)" style=""><?= lang('yes') ?></span>
                                                                                                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                                                                    </div>
                                                                                               </div>-->
                                                  <tr class="no-data">
                                                       <td colspan="9"><?= lang('nothing_to_display') ?></td>
                                                  </tr>
                                             </tbody>
                                             <tbody id="orders_loading" class="loading">
                                                  <tr>                                                  
                                                       <td colspan="9" class="text-center">
                                                            <img src="<?php echo outer_base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
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