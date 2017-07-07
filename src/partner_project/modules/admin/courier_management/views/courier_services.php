<div class="col-lg-12 p-lg" ng-controller="cservicesCtrl">
     <div class="col-md-12 no-padding margin_bottom_10" >

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
                         <span class="no-padding" style="display: inline-flex;">
                              <input ng-change="findservices()" aria-controls="services_list"  class="form-control input-sm mem_input"  type="search" ng-model="serviceslistdata.filter">
                         </span>
                    </form>
               </div>
               <!--<div class="no-padding" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_service()"><?= lang('new_service') ?></span> </div>-->
          </div>

     </div>
     <div class="clr"></div>
     <div class="table-responsive">
          <table id="services_list"   class="table table-striped table-bordered table-hover ">
               <thead>
                    <tr>
                         <th><?= lang('service_detail_name') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.display_name.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.display_name.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.display_name.reverse == false}" class="pull-right" ng-click="sort('display_name')"></i> 
                         </th>
                         <th><?= lang('service_detail_service_id') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service_id.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service_id.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.service_id.reverse == false}" class="pull-right" ng-click="sort('service_id')"></i>   
                         </th>
                         <th>
                              <?= lang('service_detail_org_name') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.org_name.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.org_name.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i> 

                         </th>
                          <th style="width:30%"><?= lang('service_detail_description') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.description.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.description.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                         </th>
                         <th><?= lang('service_detail_public') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.is_public.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.is_public.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.is_public.reverse == false}" class="pull-right" ng-click="sort('is_public')"></i>  
                         </th>
                         <th><?= lang('service_detail_auto_approve') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.auto_approve.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.auto_approve.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.auto_approve.reverse == false}" class="pull-right" ng-click="sort('auto_approve')"></i>  
                         </th>
                         <th><?= lang('service_detail_status') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.org_status.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.org_status.reverse == true,'glyphicon glyphicon-sort-by-attributes': serviceheaders.org_status.reverse == false}" class="pull-right" ng-click="sort('org_status')"></i> 
                         <!--<th><?= lang('action') ?></th>-->
                    </tr>
               </thead>
               <tbody>
                    <tr ng-repeat="services in serviceslist| orderBy:orderByField:reverseSort">
                         <td>
                              <!--<a ng-click="service_detail(services.id)" class="link_color">--> 
                              {{services.display_name}}
                              <!--</a>-->
                         </td>
                         <td>{{services.service_id}}</td>                                                       
                         <td>
                              <span class="badge" ng-if="services.org_name == null"><?= lang('bidding_badge') ?></span>
                              {{services.org_name}}</td> 
                         <td>{{services.description}}</td> 
                         <td>
                              <span ng-if="services.is_public === 1" class="text-success"><i class="fa fa-check"></i></span>
                              <span ng-if="services.is_public === 0" class="text-danger"><i class="fa fa-close"></i></span>
                         </td> 
                         <td>
                              <span ng-if="services.is_public === 1 && services.auto_approve === 1" class="text-success"><i class="fa fa-check"></i></span>
                              <span ng-if="services.is_public === 0 || services.auto_approve === 0" class="text-danger"><i class="fa fa-close"></i></span>
                         </td>  
                         <td>
                              <span ng-if="services.status == 1" class="label label-success">Active</span>
                              <span ng-if="services.status == 2" class="label label-warning">Removed</span>
                         </td>
<!--                                                       <td>

                         </td>-->
                    </tr>



               <div class="angular_popup add_scheme pull-right warning_box" ng-show="warning_popup"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_delete(delete_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                    </div>
               </div>
               <div class="angular_popup pull-right warning_box add_service" ng-show="suspend_warning_popup_service"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_service()"></i></h3>
                    <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_service_confirm') ?></p>
                    <p style="text-align: center;" ng-if="suspendstatus == 2"><?= lang('activate_service_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="service_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_service()"><?= lang('no') ?></span>
                    </div>
               </div>
               <tr class="no-data">
                    <td colspan="5"><?= lang('nothing_to_display') ?></td>
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