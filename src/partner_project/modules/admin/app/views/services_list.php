<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="servicesCtrl">
               <div class="hpanel">
                    <div class="panel-body">
                         <div class="col-lg-12" ng-show="servicelist_content"> <div class="col-xs-12 well m-b-lg">
                                   <span style="float: left;margin:auto 10px;"> 
                                        <input type="checkbox" icheck ng-model="use_public" ng-change="show_use_public_confirm()" ng-disabled="!total_services"> 

                                   </span>
                                   <p><?= lang('use_public_service_info') ?></p>                                              

                              </div>

                              <div class="angular_popup pull-right warning_box popup_mid" ng-show="show_confirm_popup"> 
                                   <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_use_public_confirm()"></i></h3>
                                   <p class="text-center p-sm" ng-if="use_public"><?= lang('allow_use_public_confirm') ?></p>
                                   <p class="text-center p-sm" ng-if="!use_public"><?= lang('not_allow_use_public_confirm') ?></p>
                                   <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="proceed()" style=""><?= lang('yes') ?></span>
                                        <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_use_public_confirm()"><?= lang('no') ?></span>
                                   </div>
                              </div>
                         </div>
                         <div class="clearfix"></div>
                         <div class="col-lg-12 no-padding" ng-show="show_table">                    


                              <div class="col-lg-12" style="padding:20px !important;"  ng-show="services_detail">
                                   <div class="angular_popup pull-right warning_box popup_mid" ng-show="show_limit_use_popup"> 
                                        <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_limit_use_confirm()"></i></h3>
                                        <p class="text-center p-sm" ng-if="limit_use"><?= lang('allow_limit_use_confirm') ?></p>
                                        <p class="text-center p-sm" ng-if="!limit_use"><?= lang('not_limit_use_confirm') ?></p>
                                        <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="update_limit_use()" style=""><?= lang('yes') ?></span>
                                             <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_limit_use_confirm()"><?= lang('no') ?></span>
                                        </div>
                                   </div>
                                   <legend><?= lang('service_detail_title') ?>
                                        <span class="btn btn-primary btn-md pull-right col-md-1 back_btn" style="margin-top: -10px;" ng-click="detail_back()">Back</span>
                                        <span class="clr"></span>
                                   </legend>
                                   <div class="clr"></div>
                                   <div class="col-xs-12 well m-b-md">
                                        <span style="float: left;margin:auto 10px;"> 
                                             <input type="checkbox" icheck ng-model="limit_use" ng-change="show_limit_use_confirm()"> 
                                        </span>
                                        <p><?= lang('limit_use') ?></p>                                       
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="panel panel-info">
                                        <div class="panel-heading">
                                             {{service_detail_data.display_name| uppercase}}
                                        </div>
                                        <div class="panel-body">

                                             <div class="row service_detail">
                                                  <div class="col-lg-6 border-right">
                                                       <strong><?= lang('service_detail_name') ?></strong>
                                                       <p>{{service_detail_data.display_name}}</p>
                                                       <strong><?= lang('service_detail_description') ?></strong>
                                                       <p>{{service_detail_data.description}}</p>
                                                       <strong><?= lang('service_start_time') ?></strong>
                                                       <p>{{service_detail_data.start_time}}</p>
                                                       <strong><?= lang('service_end_time') ?></strong>
                                                       <p>{{service_detail_data.end_time}}</p>  
                                                       <strong><?= lang('service_detail_price') ?></strong>
                                                       <p>{{service_detail_data.price}}</p>
                                                       <strong><?= lang('service_detail_threshold') ?></strong>
                                                       <p>
                                                            <?php
                                                              if ($is_admin) {
                                                                   ?>  <span title="click here to update threshold" onaftersave="savethreshold(service_detail_data.id,service_detail_data.threshold_price)" e-class="xedit_lg" e-required editable-text="service_detail_data.threshold_price" style="cursor: pointer;">{{service_detail_data.threshold_price||'Enter threshold price here'}}</span>      
                                                              <?php } else { ?>
                                                                   {{service_detail_data.threshold_price}}
                                                              <?php } ?>
                                                       </p> 
                                                  </div>
                                                  <div class="col-lg-6">
                                                       <strong><?= lang('service_detail_courier') ?></strong>
                                                       <p class="courier_name" ng-click="view_courier_info(service_detail_data.courier_id)">{{service_detail_data.courier_name}}</p>
                                                       <strong><?= lang('origin') ?></strong>
                                                       <p>{{service_detail_data.origin}}</p>
                                                       <strong><?= lang('destination') ?></strong>
                                                       <p>{{service_detail_data.destination}}</p> 
                                                       <strong><?= lang('service_working_days') ?></strong>
                                                       <p>{{service_detail_data.working_days}}</p> 
                                                       <strong><?= lang('service_detail_service_id') ?></strong>
                                                       <p>{{service_detail_data.service_id}}</p>
                                                       <strong><?= lang('service_detail_status') ?></strong>
                                                       <p><span class="label" ng-class="{'label-primary':service_detail_data.status == 1, 'label-default':service_detail_data.status == 2}">
                                                                 {{service_detail_data.statusText}}
                                                            </span>
                                                       </p>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="panel panel-info">
                                        <div class="panel-heading">

                                             <?= lang('service_groups') ?>
                                        </div>
                                        <div class="panel-body">
                                             <?php $this->load->view('service_groups'); ?> 
                                        </div>
                                   </div>
                                   <div class="panel panel-info">
                                        <div class="panel-heading">
                                             <?= lang('service_members') ?>
                                        </div>
                                        <div class="panel-body">
                                             <?php $this->load->view('service_members'); ?> 
                                        </div>
                                   </div>                                       
                              </div>
                              <div class="clearfix"></div>


                              <div class="col-lg-12" ng-show="servicelist_content">

                                   <div class="clearfix"></div> 
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar" >

                                        <div class="clearfix"></div>
                                        <div class="pull-left">    
                                             <div  class="dataTables_length">
                                                  <form>

                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="serviceslistdata.perpage"  
                                                                    ng-options="servicesperpages as servicesperpages.label for servicesperpages in servicesperpage" ng-change="perpagechange()">
                                                                 <option style="display:none" value class>15</option>
                                                            </select>
                                                            entries

                                                       </label>
                                                  </form>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">  

                                             <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;">
                                                  <form>
                                                       <label class=" pull-left no-padding" style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                       <span class="no-padding table_filter" style="display: inline-flex;">
                                                            <input ng-change="findservices()" aria-controls="services_list"  class="form-control input-sm mem_input"  type="search" ng-model="serviceslistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                        </div>

                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="services_list" class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th><?= lang('service_detail_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.display_name.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.display_name.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.display_name.reverse == false}" class="pull-right" ng-click="sort('display_name')"></i> 
                                                       </th>
                                                       <th><?= lang('service_detail_service_id') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service_id.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service_id.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.service_id.reverse == false}" class="pull-right" ng-click="sort('service_id')"></i>   
                                                       </th>
                                                       <th style="width: 40%"><?= lang('service_detail_description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.description.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.description.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                                                       </th>
                                                       <th><?= lang('service_detail_threshold') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.threshold_price.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.threshold_price.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.threshold_price.reverse == false}" class="pull-right" ng-click="sort('threshold_price')"></i>  
                                                       </th>
                                                       <th><?= lang('service_detail_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.org_status.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.org_status.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.org_status.reverse == false}" class="pull-right" ng-click="sort('org_status')"></i> 
                                                       </th>             
                                                       <?php if ($is_admin): ?>
                                                              <th><?= lang('action') ?></th>
                                                         <?php endif; ?>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="services in serviceslist| orderBy:orderByField:reverseSort">
                                                       <td><a ng-click="service_detail(services.id)" class="link_color"> {{services.display_name}}</a></td>
                                                       <td>{{services.service_id}}</td>
                                                       <td >{{services.description}}</td> 
                                                       <td>{{services.threshold_price}}</td>
                                                       <td>
                                                            <span ng-if="services.org_status == 1" class="label label-warning">New Service Pending Approval</span>
                                                            <span ng-if="services.org_status == 2" class="label label-success">Approved</span>
                                                            <span ng-if="services.org_status == 3" class="label label-default">Rejected</span>
                                                       </td>
                                                       <?php if ($is_admin): ?>
                                                              <td>
                                                                   <span ng-if="services.org_status == 1">
                                                                        <span class="label label-success link_pointer" ng-click="confirm_approve(services.id)">Approve</span>
                                                                        <span class="label label-danger link_pointer" ng-click="confirm_reject(services.id)">Reject</span>
                                                                   </span>
                                                                   <span ng-if="services.org_status == 2">
                                                                        <i ng-click="delete_warning(services.id)" title="Remove" class="fa fa-minus-square" style="color: red;"> </i>
                                                                   </span>
                                                                   <span ng-if="services.org_status == 3">
                                                                        <i ng-click="delete_warning(services.id)" title="Remove" class="fa fa-minus-square" style="color: red;"> </i>
                                                                   </span>
                                                              </td>
                                                         <?php endif; ?>
                                                  </tr>

                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="approve_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_approve()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('approve_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_approve()" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_approve()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="reject_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_reject()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('reject_confirm') ?></p>
                                                  <div class="col-lg-12">
                                                       <textarea rows="2" class="form-control" placeholder="Remarks" ng-model="reject_remark"></textarea>
                                                  </div>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_reject()" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_reject()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_delete(delete_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup pull-right warning_box popup_mid" ng-show="suspend_warning_popup_service"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_service()"></i></h3>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_service_confirm') ?></p>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 2"><?= lang('activate_service_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_service()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <tr class="no-data">
                                                  <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div class="col-lg-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                        </div> 
                                        <div class="col-md-8 text-right no-padding">

                                             <paging
                                                  class="small"
                                                  page="serviceslistdata.currentPage" 
                                                  page-size="serviceslistdata.perpage_value" 
                                                  total="serviceslistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getservices(page)">
                                             </paging> 
                                        </div>  
                                   </div>
                              </div>
                         </div>
                         <div ng-show="show_init">
                              <div class="init_div">
                                   <h2><?= lang('init_service') ?></h2>
                                   <p><?= lang('init_service_info') ?></p>
                                   <a ui-sref="tender_requests.service.new_request" class="btn btn-lg btn-info"><?= lang('create_new_srequest') ?></a>
                                   <div><a ui-sref="available_services" class="normal-link"><?= lang('use_link') ?></a></div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>