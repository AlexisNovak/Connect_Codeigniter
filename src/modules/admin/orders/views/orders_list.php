<div id="rightView" ng-hide="$state.current.name === 'organisation.orders' || $state.current.name === 'delivery_orders'">
     <div ui-view></div>
</div>
<div class="content" ng-class="{'padding_0':$state.current.name === 'organisation.orders'}">
     <div  animate-panel>
          <div class="row" ng-controller="orderCtrl" ng-show="$state.current.name === 'organisation.orders' || $state.current.name === 'delivery_orders'">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="alert alert-custom" ng-if="$state.current.name === 'organisation.orders'">
                              <span class="icon_holder"><img src="<?= base_url("resource/images/info.png") ?>"></span>
                              <p><?= lang('orders_tab_info') ?></p>
                         </div> 
                         <div class="panel-body">                            
                              <div ng-show="show_table">                              
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                                        <div class="clearfix"></div>
                                        <div class="pull-right no-padding" style="margin-left: 5px;">
                                             <div class="table_filter"  style="display: inline-flex;" >
                                                  <!-- Split button -->
                                                  <div class="btn-group">
                                                       <a ui-sref="delivery_orders.new_order" class="btn btn-info"><?= lang('new_order_title') ?></a>
                                                       <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                       </button>
                                                       <ul class="dropdown-menu">
                                                            <li><a ui-sref="delivery_orders.new_order"><?= lang('new_order_title') ?></a></li>
                                                            <li><a ui-sref="delivery_orders.multiple_order"><?= lang('multiple_order') ?></a></li>
                                                       </ul>
                                                  </div>
                                             </div>
                                        </div>
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
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.service" ng-change="findorders()">
                                                       <option value="all">All Services</option>
                                                       <option ng-repeat="serv in filter_servicelist" value="{{serv.service_id}}">{{serv.service_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="orderslistdata.status" ng-change="findorders()">
                                                       <option value="all">All Statuses</option>
                                                       <option ng-selected="orderslistdata.status == stat.status_id" ng-repeat="stat in filter_statuslist" value="{{stat.status_id}}">{{stat.display_name}}</option>
                                                  </select>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;" ng-show="org_dropdown">
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
                                                       <th style="width:10%;"><?= lang('order_assigned_id') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.private_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.private_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.private_id.reverse == false}" class="pull-right" ng-click="sort('private_id')"></i>  
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_services') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orderheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orderheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orderheaders.service.reverse == false}" class="pull-right" ng-click="sort('service')"></i>  
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
                                                       <th style="width:10%;"><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <span ng-if="org_id">                                                                   
                                                                 <a ui-sref="organisation.orders.view_order({order_id:order.public_id})" class="link_color"> {{order.public_id}}</a>
                                                            </span>
                                                            <span ng-if="!org_id">
                                                                 <a ui-sref="delivery_orders.view_order({order_id:order.public_id})" class="link_color"> {{order.public_id}}</a>
                                                            </span>
                                                       </td>
                                                       <td> {{order.private_id}}</td>
                                                       <td> 
                                                            <span ng-if="order.courier_id">
                                                                 {{order.service}}<br>
                                                                 <span ng-click="view_courier_info(order.courier_id)" class="courier_name">{{order.courier_name}}</span>
                                                            </span>  
                                                            <span ng-if="order.is_third_party == 1">
                                                                 <?= lang('external') ?> <br>
                                                                 ({{order.third_party_email}})
                                                            </span>
                                                       </td>
                                                       <td>
                                                            {{order.collection_contact_name}}<br>
                                                            {{order.collection_address}}<br>
                                                            {{order.from_country}}<br>
                                                            {{order.collection_contact_number}}
                                                            <span ng-if="order.crestrict != 0" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td>
                                                            {{order.delivery_contact_name}}<br>
                                                            {{order.delivery_address}}<br>
                                                            {{order.to_country}}<br>
                                                            {{order.delivery_contact_phone}}
                                                            <span ng-if="order.drestrict != 0" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                       </td>
                                                       <td><strong>{{order.status}}</strong></td>
                                                       <td ng-if="!org_id">{{order.org_name|| "-"}}
                                                            <span ng-if="order.group_name">({{order.group_name}})</span>
                                                       </td>
                                                       <td>{{order.cdate}}</td>
                                                       <td>
                                                            <span ng-if="(order.consignment_status_id == 7 || order.consignment_status_id == 11)">
                                                                 <a ui-sref="delivery_orders.edit_order({order_id:order.public_id})">
                                                                      <i class="fa fa-edit" title="edit"></i>
                                                                 </a>
                                                                 <a ng-click="show_delete_warning(order.consignment_id)">
                                                                      <i class="fa fa-trash" title="delete"></i>
                                                                 </a>
                                                            </span>
                                                            <span ng-if="order.consignment_status_id != <?= C_DRAFT ?> && order.consignment_status_id != <?= C_CANCELLED ?>">
                                                                 <a ng-href="<?php echo site_url('orders/printOrder') . '/'; ?>{{order.consignment_id}}" title="<?= lang('print_btn') ?>" ng-if="order.is_for_bidding == 0 || order.is_confirmed == 1">
                                                                      <i class="fa fa-print"></i> 
                                                                 </a>
                                                                 <a ng-click="show_cancel_warning(order.consignment_id)" ng-if="order.cancel_request == 0">
                                                                      <i class="fa fa-remove" title="cancel"></i>
                                                                 </a>
                                                                 <span class="label label-default" ng-if="order.cancel_request == 1">Cancel request sent</span>
                                                            </span>
                                                            <span>
                                                                 <a ng-if="order.consignment_status_id == 19" ng-click="show_delete_warning(order.consignment_id)">
                                                                      <i class="fa fa-trash" title="delete"></i>
                                                                 </a>
                                                            </span>
                                                       </td>
                                                  </tr>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="DO_delete_warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="deleteOrder(delete_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>  
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="cancel_warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="hide_cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('cancel_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="cancelOrder(cancel_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="hide_cancel_warning()"><?= lang('no') ?></span>
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
                              <div ng-show="show_init">
                                   <div class="init_div init_div_home" style="margin: 0;">
                                        <h2 style="letter-spacing: 3px;font-weight: bold;margin: 3px auto;"><?= lang('6con_mak_del') ?> <br>
                                             <?= lang('fast_and_easy') ?>
                                        </h2>
                                        <div style="margin:0 -20px;margin-bottom: -20px">
                                             <div class="init-home-courier" style="text-align: left">
                                                  <p><?php // echo lang('init_new_delivery_info')   ?></p>
                                                  <a ui-sref="delivery_orders.new_order" class="make_delivery make_delivery1">
                                                       <b><?= lang('make_first_delivery') ?></b> &nbsp;&nbsp; <img height="45" src="<?= base_url('resource/images/right-arrow.png') ?>">
                                                  </a>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>
          </div>
     </div>
</div>

<script>

     $(window).resize(function () {
          var height = $('.init-home-courier').width() / 1.87;
          $('.init-home-courier').css('min-height', height);
     });

     $(document).ready(function () {
          var height = $('.init-home-courier').width() / 1.87;
          $('.init-home-courier').css('min-height', height);
     });
</script>