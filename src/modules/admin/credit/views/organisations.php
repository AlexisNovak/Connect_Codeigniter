<div id="rightView" ng-hide="$state.current.name === 'accounts.organisations'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'accounts.organisations'">
     <div  animate-panel>
          <div class="row" ng-controller="accorganisationCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">                            
                              <div class="clearfix"></div>
                              <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                   <div class="pull-left">
                                        <div  class="dataTables_length">
                                             <form>
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="orglistdata.perpage"  
                                                               ng-options="orgperpages as orgperpages.label for orgperpages in orgperpage" ng-change="perpagechange()">
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
                                                       <input ng-change="findorg()" aria-controls="organisation_list"  class="form-control input-sm" type="search" ng-model="orglistdata.filter">
                                                  </span>
                                             </form>
                                        </div>
                                   </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="clr"></div>
                              <div class="table-responsive">
                                   <table id="organisation_list" class="table table-striped table-bordered table-responsive">
                                        <thead>
                                             <tr>
                                                  <th><?= lang('table_organisation_name') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.name.reverse == false}" class="pull-right" ng-click="sort('name')"></i>  
                                                  </th>
                                                  <th><?= lang('organisation_shortname') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.shortname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.shortname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.shortname.reverse == false}" class="pull-right" ng-click="sort('shortname')"></i>  
                                                  </th>
                                                  <th style="width:40%"><?= lang('organisation_description') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                                                  </th>
                                                  <th><?= lang('organisation_website') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.website.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.website.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.website.reverse == false}" class="pull-right" ng-click="sort('website')"></i>  
                                                  </th>
                                                  <th><?= lang('organisation_admins') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.adminusers.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.adminusers.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.adminusers.reverse == false}" class="pull-right" ng-click="sort('adminusers')"></i>   
                                                  </th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="org in orglist|orderBy:orderByField:reverseSort">
                                                  <td><a ui-sref="organisation.{{org.role_id}}({id:org.id,flag:0})" class="link_color"> {{org.name}}</a></td>
                                                  <td>{{org.shortname}}</td>
                                                  <td>{{org.description}}</td>
                                                  <td>{{org.website}}</td>
                                                  <td>{{org.adminusers}}</td>
                                             </tr>
                                             <tr class="no-data">
                                                  <td colspan="5"><?= lang('nothing_to_display') ?></td>
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
                                             page="orglistdata.currentPage" 
                                             page-size="orglistdata.perpage_value" 
                                             total="orglistdata.total"
                                             adjacent="{{adjacent}}"
                                             dots="{{dots}}"
                                             scroll-top="{{scrollTop}}" 
                                             hide-if-empty="false"
                                             ul-class="{{ulClass}}"
                                             active-class="{{activeClass}}"
                                             disabled-class="{{disabledClass}}"
                                             show-prev-next="true"
                                             paging-action="getOrganisations(page)">
                                        </paging> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
