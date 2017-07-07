<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="prebiddersCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="alert alert-custom">
                              <span class="icon_holder"><img src="<?= base_url("resource/images/info.png") ?>"></span>
                              <p><?= lang('pre_bidders_tab_info') ?></p>
                         </div>

                         <div class="panel-body">

                              <div class="col-lg-12 no-padding">

                                   <div class="col-xs-12 well m-b-lg">
                                        <span style="float: left;margin:auto 10px;"> 
                                             <input type="checkbox" icheck ng-model="closed_bidding" ng-change="open_bid_confirm()" ng-disabled="allow_check_box === false"> 
                                        </span>
                                        <p><?= lang('allow_colsed_bidding') ?></p>                                              

                                   </div> 
                                   <div class="clearfix"></div>
                                   <div class="angular_popup popup_mid pull-right warning_box" ng-show="remove_warning_popup"> 
                                        <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_remove_warning()"></i></h3>
                                        <p style="text-align: center;"><?= lang('remove_bidder_warning') ?></p>
                                        <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="remove_preapproved()" style=""><?= lang('yes') ?></span>
                                             <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_remove_warning()"><?= lang('no') ?></span>
                                        </div>
                                   </div>
                                   <div class="angular_popup pull-right warning_box popup_mid" ng-show="show_confirm_popup"> 
                                        <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="close_bid_confirm()"></i></h3>
                                        <p class="text-center p-sm" ng-if="closed_bidding"><?= lang('open_bid_confirm') ?></p>
                                        <p class="text-center p-sm" ng-if="!closed_bidding"><?= lang('closed_bid_confirm') ?></p>
                                        <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="proceed()" style=""><?= lang('yes') ?></span>
                                             <span class="btn btn-primary btn-sm margin_10" ng-click="close_bid_confirm()"><?= lang('no') ?></span>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div> 
                                   <div class="angular_popup add_member pull-right"  ng-show="add_courier_form">  
                                        <h3><?= lang('add_new_pre_bidder') ?><i class="fa fa-close pull-right" ng-click="cancel_add_courier()"></i></h3>
                                        <div class="form-holder">
                                             <form class="form-horizontal">
                                                  <fieldset>
                                                       <div class="form-group" style="margin-bottom: 10px !important;">

                                                            <div class="col-sm-12">

                                                                 <input type="text" class="form-control" ng-model="searchname" placeholder="eg@example.com" required  ng-change="getall_couriers()"> 
                                                                 <div style="background-color: #fff;max-height: 170px;overflow:auto;" ng-show="isSearch">
                                                                      <ul class="list-group" style="margin-bottom: 0px !important;">
                                                                           <li ng-repeat="courier in couriers track by $index" ng-click="setCourier(courier)" class="list-group-item list-group-item-info" >
                                                                                <a>{{courier.company_name}} ({{courier.email}})</a>
                                                                           </li>
                                                                      </ul>

                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="col-sm-12 text-left">
                                                            <p ng-show="unknown"><?= lang('unknown_courier') ?></p>
                                                            <button type="button" class="btn btn-primary" ng-click="invite()" ng-disabled="isDisabled"><?= lang('invite_member') ?></button>
                                                            <button type="button" class="btn btn-default" ng-click="cancel_add_courier()"><?= lang('cancel_btn') ?></button>
                                                       </div>

                                                  </fieldset>
                                             </form>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="col-lg-12 no-padding"  ng-show="show_table">
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="bidlistdata.perpage"  
                                                                    ng-options="bidperpages as bidperpages.label for bidperpages in bidperpage" ng-change="bidperpagechange()">
                                                                 <option style="display:none" value class>15</option>
                                                            </select>
                                                            entries
                                                       </label>
                                                  </form>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">
                                             <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
                                                  <form>
                                                       <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                       <span class="no-padding table_filter" style="display: inline-flex;">
                                                            <input ng-change="findBidders()" aria-controls="bid_list"  class="form-control input-sm" type="search" ng-model="bidlistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                             <?php if ($is_admin): ?>
                                                    <div class="no-padding table_filter" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_courier()"><?= lang('new_pre_bidder') ?></span> </div>
                                               <?php endif; ?>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clr"></div>
                                        <div class="table-responsive">
                                             <table id="bid_list" class="table table-striped table-bordered table-responsive">
                                                  <thead>
                                                       <tr>
                                                            <th><?= lang('p_a_b_courier_name') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.courier.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.courier.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.courier.reverse == false}" class="pull-right" ng-click="bidsort('courier')"></i> 
                                                            </th>
                                                            <th><?= lang('p_a_b_email') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.email.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.email.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.email.reverse == false}" class="pull-right" ng-click="bidsort('email')"></i>  
                                                            </th>
                                                            <th><?= lang('p_a_b_url') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.url.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.url.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.url.reverse == false}" class="pull-right" ng-click="bidsort('url')"></i>                 </th>
                                                            <th style="width:40%;"><?= lang('p_a_b_description') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.description.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.description.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.description.reverse == false}" class="pull-right" ng-click="bidsort('description')"></i>  
                                                            </th>
                                                            <th><?= lang('p_a_b_status') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.status.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.status.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.status.reverse == false}" class="pull-right" ng-click="bidsort('status')"></i>  
                                                            </th>
                                                            <th><?= lang('action') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':bidheaders.status.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.status.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.status.reverse == false}" class="pull-right" ng-click="bidsort('status')"></i>  
                                                            </th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <tr ng-repeat="bidder in bidlist| orderBy:orderByField_bid:reverseSort_bid">                   
                                                            <td>    
                                                                 <span ng-click="view_courier_info(bidder.courier_id)" class="courier_name">{{bidder.courier}}</span>
                                                            </td>
                                                            <td>{{bidder.email}}</td>
                                                            <td>{{bidder.url}}</td>
                                                            <td>{{bidder.description}}</td>
                                                            <td><span ng-if="bidder.status == 2" class="label label-success">Invited</span></td>
                                                            <td><span ng-click="show_remove_warning(bidder.courier_id)" title="Remove"><i class="fa fa-minus-square text-danger"></i></span></td>
                                                       </tr>
                                                       <tr class="no-data">
                                                            <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                             <div class="col-md-4 no-padding">
                                                  <div ng-show="bidcount.total" style="line-height: 35px;">Showing {{bidcount.start}} to {{bidcount.end}} of {{bidcount.total}} entries</div>
                                             </div> 
                                             <div class="col-md-8 text-right no-padding">

                                                  <paging
                                                       class="small"
                                                       page="bidlistdata.currentPage" 
                                                       page-size="bidlistdata.perpage_value" 
                                                       total="bidlistdata.total"
                                                       adjacent="{{adjacent}}"
                                                       dots="{{dots}}"
                                                       scroll-top="{{scrollTop}}" 
                                                       hide-if-empty="false"
                                                       ul-class="{{ulClass}}"
                                                       active-class="{{activeClass}}"
                                                       disabled-class="{{disabledClass}}"
                                                       show-prev-next="true"
                                                       paging-action="getBidders(page)">
                                                  </paging> 
                                             </div>
                                        </div>
                                   </div>
                                   <div ng-show="show_init">
                                        <div class="init_div">
                                             <h2><?= lang('init_couriers') ?></h2>
                                             <p><?= lang('init_couriers_info') ?></p>
                                             <span class="btn btn-lg btn-info" ng-click="add_courier()"><?= lang('invite_pre_bidder') ?></span> </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

