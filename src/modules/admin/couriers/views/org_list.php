<div class="content">
     <div  animate-panel>
          <div class="row">
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
                              <div class="clearfix"></div>
                              </div>
                              <div class="clr"></div>
                              <div class="table-responsive">
                                   <table id="organisation_list"   class="table table-striped table-bordered table-responsive">
                                        <thead>
                                             <tr>
                                                  <th style="width: 20%"><?= lang('organisation_name') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>  
                                                  </th>
                                                  <th style="width:20%"><?= lang('organisation_description') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.Description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.Description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.Description.reverse == false}" class="pull-right" ng-click="sort('Description')"></i>  
                                                  </th>
                                                  <th style="width: 20%"><?= lang('organisation_website') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.Website.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.Website.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.Website.reverse == false}" class="pull-right" ng-click="sort('Website')"></i>  
                                                  </th>
                                                  <th style="width: 40%"><?= lang('organisation_pre_services') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':orgheaders.preservices.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.preservices.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.preservices.reverse == false}" class="pull-right" ng-click="sort('preservices')"></i>   
                                                  </th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="org in orglist|orderBy:orderByField:reverseSort">
                                                  <td>{{org.org_name}}</td>
                                                  <td>{{org.Description}}</td>
                                                  <td>{{org.Website}}</td>
                                                  <td> <span class="label label-info srv-label" ng-repeat="s in org.preservices track by $index">{{s}}</span></td>
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
