<div id="rightView" ng-hide="$state.current.name === 'service_requests'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'service_requests'">
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
                                                       <select class="form-control"  name="perpage" ng-model="requestlistdata.perpage"  
                                                               ng-options="requestperpages as requestperpages.label for requestperpages in requestperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <label class="pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findrequests()" aria-controls="order_list"  class="form-control input-sm" type="search" ng-model="requestlistdata.filter">
                                                  </span>

                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="requestlistdata.category" ng-change="findrequests()">
                                                       <option value="all">All Tenders</option>
                                                       <option value="open">Open</option>
                                                       <option value="closed">Closed</option>
                                                       <option value="awarded">Awarded</option>
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

                                                       <th style="width:10%;"><?= lang('s_r_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.name.reverse == false}" class="pull-right" ng-click="sort('name')"></i>        
                                                       </th>
                                                       <th style="width:20%;"><?= lang('description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.remarks.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.remarks.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.remarks.reverse == false}" class="pull-right" ng-click="sort('remarks')"></i>                     
                                                       </th>
                                                       <th style="width:10%;"><?= lang('s_r_delpermonth') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.delivery_p_m.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.delivery_p_m.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.delivery_p_m.reverse == false}" class="pull-right" ng-click="sort('delivery_p_m')"></i>          
                                                       </th>
                                                       <th style="width:10%;"><?= lang('s_r_duration') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service_duration.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service_duration.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.service_duration.reverse == false}" class="pull-right" ng-click="sort('service_duration')"></i>  
                                                       </th>
                                                       <th style="width:15%;"><?= lang('s_r_date') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.added_on.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.added_on.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.added_on.reverse == false}" class="pull-right" ng-click="sort('added_on')"></i>        
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_status') ?>
                                                            <!-- <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.request_stat.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.request_stat.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.request_stat.reverse == false}" class="pull-right" ng-click="sort('request_stat')"></i>    -->
                                                       </th>
                                                       <th style="width:10%;"><?= lang('orders_table_bid') ?>
                                                            <!-- <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.bid.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.bid.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.bid.reverse == false}" class="pull-right" ng-click="sort('bid')"></i>    -->
                                                       </th>
                                                       <th style="width:10%;"><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody id="orderslist_body">
                                                  <tr ng-repeat="req in requestlist">
                                                       <td>
                                                            <a ui-sref="service_requests.view_request({req_id:req.request_id})" class="link_color"> {{req.name}}</a>

                                                       </td>
                                                       <td> {{req.description}}</td>
                                                       <td>
                                                            {{req.deliveries_per_month}}</td>
                                                       <td>
                                                            {{req.service_duration}}    </td>
                                                       <td>{{req.added_on}}</td>
                                                       <td>{{req.status_name}} <span ng-if="req.expired">(Expired)</span></td>
                                                       <td> 
                                                            <a ui-sref="ownservices.view_service({os_id:req.service_id})" class="link_color" ng-if="req.service_id"> 
                                                            {{req.bid}}
                                                            </a>
                                                            </td>
                                                       <td>
                                                            <!--                                                         
                                                                              * -1 new
                                                                              * 0 withdrawn
                                                                              * 1 pending
                                                                              * 2 won
                                                                              * 3 lost
                                                            -->
                                                            <span ng-if="req.status == -1 && req.expired != 1" class="btn btn-default btn-sm" ng-click="show_bid(req.request_id)">Bid</span>
                                                            <span ng-if="req.status == 1 && req.expired != 1" class="btn btn-default btn-sm" ng-click="show_withdraw(req.bid_id)">Withdraw</span>
                                                            <span ng-if="req.status == 0 && req.expired != 1" class="btn btn-default btn-sm" ng-click="show_bid(req.request_id)"> Re-Bid</span>
                                                       </td>
                                                  </tr>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_bid_popup"> 
                                                  <h3><?= lang('bid_request_popup_title') ?><i class="fa fa-close pull-right" ng-click="cancel_bid()"></i></h3>
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label>Service</label>
                                                            <select ng-model="bid.service" class="form-control">
                                                                 <option value="">Select Service</option>
                                                                 <option ng-repeat="service in servicelist" value="{{service}}">{{service.display_name}}</option>
                                                            </select> 
                                                            <span class="help-block m-b-none text-danger" ng-show="bid.errors.service">{{bid.errors.service}}</span>

                                                       </div>
                                                       <!--                                                       <div class="form-group">
                                                                                                                   <label>Remarks</label>
                                                                                                                   <textarea ng-model="bid.remarks" class="form-control" placeholder="<?= lang('bid_remark_ph') ?>"></textarea>
                                                                                                                   <span class="help-block m-b-none text-danger" ng-show="bid.errors.remarks">{{bid.errors.remarks}}</span>
                                                                                                              </div>-->
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="bid_request()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_bid()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_withdraw_popup"> 
                                                  <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_withdraw()"></i></h3>
                                                  <div class="col-lg-12">
                                                       <p><?= lang('withdraw_bid') ?></p>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="withdraw_bid()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_withdraw()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <tr class="no-data">
                                                  <td colspan="8"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                             <tbody id="orders_loading" class="loading">
                                                  <tr>                                                  
                                                       <td colspan="8" class="text-center">
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
                                                  page="requestlistdata.currentPage" 
                                                  page-size="requestlistdata.perpage_value" 
                                                  total="requestlistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getRequests(page)">
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