<div id="rightView" ng-hide="$state.current.name === 'available_service_requests'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'available_service_requests'">
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
                                        </div>
                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="services_list" class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 50%"><?= lang('a_s_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.service.reverse == false}" class="pull-right" ng-click="sort('service')"></i>  
                                                       </th>
                                                       <th style="width: 50%"><?=lang('no_of_req')?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':serviceheaders.count.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': serviceheaders.count.reverse == true, 'glyphicon glyphicon-sort-by-attributes': serviceheaders.count.reverse == false}" class="pull-right" ng-click="sort('count')"></i>  
                                                       </th>                                                       
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="service in serviceslist| orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <a ui-sref="available_service_requests.view_request({asreq_id:service.id})" class="link_color"> 
                                                                 {{service.service}}
                                                            </a>
                                                       </td>
                                                       <td><span class="label label-danger">{{service.count}}</span></td>                                                      
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="2"><?= lang('nothing_to_display') ?></td>
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