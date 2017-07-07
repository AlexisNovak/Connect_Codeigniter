<div id="rightView" ng-hide="$state.current.name === 'available_services'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'available_services'">

     <div  animate-panel>
          <div class="row">
               <div class="col-md-12" ng-controller="avail_serviceCtrl">
                    <div class="hpanel">
                         <div class="panel-body">                            
                              <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">
                                   <div class="clearfix"></div>
                                   <div class="pull-left">
                                        <div class="dataTables_length">
                                             <form>
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="servicelistdata.perpage"  
                                                               ng-options="serviceperpages as serviceperpages.label for serviceperpages in serviceperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </form>
                                        </div>
                                   </div>
                                   <div class="pull-right no-padding">                                        
                                        <div class="table_filter" style="display: inline-flex;">
                                             <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                             <span class="no-padding table_filter" style="display: inline-flex;">
                                                  <input ng-change="findservice()" aria-controls="service_list"  class="form-control input-sm" type="search" ng-model="servicelistdata.filter">
                                             </span>
                                        </div>
                                        <div class="table_filter"  style="display: inline-flex;">
                                             <select class="form-control input-sm" name="perpage" ng-model="servicelistdata.type" ng-change="findservice()">
                                                  <option value="0">All Services</option>
                                                  <option value="1">Pre-approved Services</option>
                                             </select>
                                        </div>
                                   </div>

                              </div>
                              <div class="clearfix"></div>
                              <div class="clr"></div>
                              <div class="table-responsive">
                                   <table id="service_list"   class="table table-striped table-bordered table-responsive">
                                        <thead>
                                             <tr>
                                                  <th style="width: 15%"><?= lang('a_s_name') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.service.reverse == false}" class="pull-right" ng-click="sort('service')"></i>  
                                                  </th>
<!--                                                  <th style="width: 10%"><?= lang('a_s_type') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.type.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.type.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.type.reverse == false}" class="pull-right" ng-click="sort('type')"></i>  
                                                  </th>-->
                                                  <th style="width: 10%"><?= lang('a_s_price') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.price.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.price.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.price.reverse == false}" class="pull-right" ng-click="sort('price')"></i>  
                                                  </th>
                                                  <th style="width:15%"><?= lang('a_s_description') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>   
                                                  </th>
                                                  <th style="width:10%"><?= lang('a_s_org') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.org.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.org.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.org.reverse == false}" class="pull-right" ng-click="sort('org')"></i>   
                                                  </th>
                                                  <th style="width:10%"><?= lang('a_s_courier') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.courier.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.courier.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.courier.reverse == false}" class="pull-right" ng-click="sort('courier')"></i>  
                                                  </th>
                                                  <th style="width: 15%"><?= lang('a_s_days') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.days.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.days.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.days.reverse == false}" class="pull-right" ng-click="sort('days')"></i>   
                                                  </th>
                                                  <th style="width: 15%"><?= lang('a_s_cutoff') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.cutoff.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.cutoff.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.cutoff.reverse == false}" class="pull-right" ng-click="sort('cutoff')"></i>   
                                                  </th>                                                 
                                                  <th><?= lang('action') ?></th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="service in servicelist|orderBy:orderByField:reverseSort">
                                                  <td>
                                                       <a ui-sref="available_services.view_service({as_id:service.id})" class="link_color">
                                                            {{service.service}}
                                                       </a></td>
                                                  <!--<td>{{service.type}}</td>-->
                                                  <td>{{service.price}} SGD</td>
                                                  <td>{{service.description}}</td>
                                                  <td>{{service.org|| "-"}}</td>
                                                  <td><span ng-click="view_courier_info(service.courier_id)" class="courier_name">{{service.courier}}</span></td>
                                                  <td>{{service.days}}</td>
                                                  <td>{{service.cutoff}}</td>
                                                  <td>
                                                       <span class="btn btn-sm btn-default m-b-xs" ng-click="confirm_request_service(service.id, service.courier_id)" ng-show="adminorglist.length > 0 && service.request_status != 1 && service.is_public == 1">Request</span>
                                                       <span class="label label-success" ng-show="service.request_status == 1">Request sent</span>
                                                       <span class="label label-danger" ng-show="service.request_status == 0">Request rejected</span>
                                                       <span class="btn btn-sm btn-default" ng-click="show_use_this_popup(service.id, service.courier_id)">Use Service</span>
                                                       <?php
                                                         if ($root) {
                                                              ?>
                                                              <a ui-sref="available_services.edit_service({as_id:service.id})" class="btn btn-sm btn-default m-t-xs">Edit</a>
                                                              <?php
                                                         }
                                                       ?>
                                                  </td>
                                             </tr>
                                             <tr class="no-data">
                                                  <td colspan="8"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             <!--angular popup for request service-->
                                        <div class="angular_popup pull-right warning_box popup_mid" ng-show="request_confirm_popup"> 
                                             <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_request()"></i></h3>
                                             <div class="form-group" style="margin: auto 10px;">
                                                  <select ng-model="admin_org_id" class="input-sm form-control" ng-options="org.org_id as org.org_name for org in adminorglist">
                                                       <option value="">Select Organisation</option>                                                                            
                                                  </select>
                                                  <span class="help-block m-b-none text-danger">{{errors.org_id}}</span>

                                             </div>
                                             <p style="text-align: center;padding: 10px;"><?= lang('request_service_confirm') ?></p>
                                             <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="request_service()" ng-disabled="isDisabled" ><?= lang('yes') ?></span>
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_request()"><?= lang('no') ?></span>
                                             </div>
                                        </div>
                                        <!--end of angular popup-->

                                        <!--angular popup for use this service-->
                                        <div class="angular_popup pull-right warning_box popup_mid" ng-show="use_this_popup"> 
                                             <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_use_this_popup()"></i></h3>

                                             <div ng-show="!proceed">
                                                  <div class="form-group" style="margin: auto 10px;">
                                                       <select ng-model="org_id" class="input-sm form-control" ng-options="org.org_id as org.org_name for org in orglist">
                                                            <option value="">Select Organisation</option>                                                                            
                                                       </select>
                                                       <span class="help-block m-b-none text-danger">{{errors.org_id}}</span>

                                                  </div>
                                                  <p style="text-align: center;padding: 10px;"><?= lang('use_service_confirm') ?></p>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="use_this_service()" ng-disabled="isDisabled" ><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_use_this_popup()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div ng-show="proceed">
                                                  <p style="text-align: center;padding: 10px;"><?= lang('proceed_info') ?></p>
                                                  <div class="btn-holder text-center">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="proceed_use_this()"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_use_this_popup()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                        </div>
                                        <!--end of angular popup-->

                                        <!--angular popup for use this service-->
                                        <div class="angular_popup pull-right warning_box popup_mid" ng-show="no_org_popup"> 
                                             <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_use_this_popup()"></i></h3>
                                             <p style="text-align: center;padding: 10px;"><?= lang('a_s_no_org_info') ?></p>
                                             <div class="btn-holder text-center">
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_use_this_popup()"><?= lang('ok') ?></span>
                                             </div>
                                        </div>
                                        <!--end of angular popup-->

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
                                             page="servicelistdata.currentPage" 
                                             page-size="servicelistdata.perpage_value" 
                                             total="servicelistdata.total"
                                             adjacent="{{adjacent}}"
                                             dots="{{dots}}"
                                             scroll-top="{{scrollTop}}" 
                                             hide-if-empty="false"
                                             ul-class="{{ulClass}}"
                                             active-class="{{activeClass}}"
                                             disabled-class="{{disabledClass}}"
                                             show-prev-next="true"
                                             paging-action="getServices(page)">
                                        </paging> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>
