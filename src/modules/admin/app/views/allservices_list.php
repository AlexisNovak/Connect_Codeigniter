<div id="rightView" ng-hide="$state.current.name === 'rootservices'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'rootservices'">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="col-lg-12 no-padding">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">

                                        <div class="pull-left no-padding">    
                                             <div  class="dataTables_length">
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="serviceslistdata.perpage"  
                                                               ng-options="servicesperpages as servicesperpages.label for servicesperpages in servicesperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">  
                                             <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;">
                                                  <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findservices()" aria-controls="services_list"  class="form-control input-sm mem_input"  type="search" ng-model="serviceslistdata.filter">
                                                  </span>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="serviceslistdata.category" ng-change="findservices()">
                                                       <option value="0">All Services</option>
                                                       <option value="1">Active</option>
                                                       <option value="2">Suspended</option>
                                                       <option value="3">Archived</option>
                                                  </select>
                                             </div>
                                             <div class="no-padding table_filter" style="display: inline-flex;">
                                                  <!--<span class="btn btn-sm btn-info" ng-click="add_service()"><?= lang('new_service') ?></span>--> 
                                                  <a ui-sref="rootservices.newservice" class="btn btn-sm btn-info"><?= lang('new_service') ?></a>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="services_list"   class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 15%"><?= lang('a_s_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.service.reverse == false}" class="pull-right" ng-click="sort('service')"></i>  
                                                       </th>                                                     
                                                       <th style="width:20%"><?= lang('a_s_description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>   
                                                       </th>
                                                       <th style="width:10%"><?= lang('destination') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.destination.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.destination.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.destination.reverse == false}" class="pull-right" ng-click="sort('destination')"></i>  
                                                       </th>
                                                       <th style="width: 15%"><?= lang('a_s_days') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.days.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.days.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.days.reverse == false}" class="pull-right" ng-click="sort('days')"></i>   
                                                       </th>
                                                       <th style="width: 15%"><?= lang('a_s_cutoff') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.cutoff.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.cutoff.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.cutoff.reverse == false}" class="pull-right" ng-click="sort('cutoff')"></i>   
                                                       </th>                                                 
                                                       <th style="width: 10%"><?= lang('service_detail_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.status.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.status.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i> 
                                                       <th><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="service in serviceslist| orderBy:orderByField:reverseSort">
                                                       <td>
                                                            

                                                            <a ui-sref="rootservices.edit_service({os_id:service.id})" class="link_color"> 
                                                                 {{service.service}}
                                                            </a>
                                                            <div ng-show="service.org_name">({{ service.org_name}})</div>
                                                       </td>
                                                       <td>{{service.description}}</td>
                                                       <!--<td><span ng-click="view_courier_info(service.courier_id)" class="courier_name">{{service.courier}}</span></td>-->
                                                       <td>{{service.destination}}</td>
                                                       <td>{{service.days}}</td>
                                                       <td>{{service.cutoff}}</td>
                                                       <td>
                                                            <span ng-if="service.status == 1 && service.is_archived == 0" class="label label-success">Active</span>
                                                            <span ng-if="service.status == 3 && service.is_archived == 0" class="label label-warning">Suspended</span>
                                                            <span ng-if=" service.is_archived == 1" class="label label-info">Archived</span>
                                                       </td>
                                                       <td>
                                                            <span class="btn btn-sm btn-default m-b-xs" ng-click="suspend_warning_service(service)" ng-show="service.status == 1 && service.is_archived == 0">Suspend</span>
                                                            <span class="btn btn-sm btn-default m-b-xs" ng-click="suspend_warning_service(service)" ng-show="service.status == 3 && service.is_archived == 0">Activate</span>
                                                            <!--<a  ui-sref="ownservices.edit_service({os_id:service.id})" class="btn btn-sm btn-default m-b-xs" ng-if="service.is_new == 1 && service.is_archived == 0">Edit</a>-->
                                                       </td>
                                                  </tr>



                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_delete(delete_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup pull-right warning_box popup_mid" ng-show="suspend_warning_popup_service"> 
                                                  <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_service()"></i></h3>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_service_confirm') ?></p>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 3"><?= lang('activate_service_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_service()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <tr class="no-data">
                                                  <td colspan="8"><?= lang('nothing_to_display') ?></td>
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
                    </div>

               </div>
          </div>
     </div>
</div>