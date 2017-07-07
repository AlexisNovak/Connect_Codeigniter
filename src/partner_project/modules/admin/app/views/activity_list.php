<div class="content inner_contents">
     <div  animate-panel>
          <div class="row" ng-controller="ActivityCtrl">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12" ng-show="activity_detail_page">
                         <div class="hpanel">
                              <div class="panel-body" style="padding:20px !important;">
                                   <div class="col-lg-12 no-padding" style="padding:20px !important;">
                                        <legend><?= lang('activity_detail_title') ?><span class="btn btn-primary btn-md pull-right col-md-1" style="margin-top: -10px;" ng-click="detail_back()">Back</span>
                                             <span class="clr"></span></legend>
                                        <div class="clr"></div>
                                        <div class="table-responsive">     
                                             <table class="table table-bordered details">
                                                  <tbody>
                                                       <tr>
                                                            <th><?= lang('activity_id') ?></th>
                                                            <td>{{activity.activity_id}}</td>
                                                       </tr>
                                                       <tr>
                                                            <th><?= lang('activity_detail_group') ?></th>
                                                            <td>{{activity.name}}</td>
                                                       </tr>
                                                       <tr>
                                                            <th><?= lang('activity_detail_remark') ?></th>
                                                            <td>{{activity.remark}}</td>
                                                       </tr>                                                 
                                                       <tr>
                                                            <th><?= lang('activity_detail_date') ?></th>
                                                            <td>{{activity.date}}</td>
                                                       </tr>                                                  
                                                       <tr>
                                                            <th><?= lang('activity_detail_updated') ?></th>
                                                            <td>{{activity.username}}</td>
                                                       </tr>                                                  
                                                  </tbody>
                                             </table>
                                        </div>
                                   </div>  
                              </div>
                         </div> 
                    </div> 

                    <div class="col-lg-12 no-padding" ng-show="activitylist_content">
                         <div class="hpanel">

                              <div class="panel-body">
                                   <div class="col-md-12 no-padding search_toolbar">
                                        <div class="clearfix"></div>
                                        <div class="pull-left">   
                                             <div  class="dataTables_length">
                                                  <form>

                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="activitylistdata.perpage"  
                                                                    ng-options="activityperpages as activityperpages.label for activityperpages in activityperpage" ng-change="perpagechange()">
                                                                 <option style="display:none" value class>15</option>
                                                            </select>
                                                            entries

                                                       </label>
                                                  </form>
                                             </div>
                                        </div>
                                        <div class="no-padding pull-right" >  
                                             <div class="table_filter" id="activity_list_filter" style="display: inline-flex;">
                                                  <form>
                                                       <label class="pull-left no-padding" style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                       <span class="no-padding" style="display: inline-flex;">
                                                            <input ng-change="findactivity()" aria-controls="activity_list"  class="form-control input-sm pull-right srch_activity" type="search" ng-model="activitylistdata.filter">

                                                       </span></form>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="activity_list" class="table table-striped table-bordered ">
                                             <thead>
                                                  <tr>
                                                       <th  ><?= lang('activity_id') ?>
                                                        <i ng-class="{
                          'glyphicon glyphicon-sort':activityheaders.id.reverse == undefined, 
                          'glyphicon glyphicon-sort-by-attributes-alt': activityheaders.id.reverse==true, 
                          'glyphicon glyphicon-sort-by-attributes': activityheaders.id.reverse==false
                      }" class="pull-right" ng-click="sort('id')"></i>   
                                                       </th>
                                                       <th  ><?= lang('activity_detail_group') ?>
                                                           <i ng-class="{
                          'glyphicon glyphicon-sort':activityheaders.name.reverse == undefined, 
                          'glyphicon glyphicon-sort-by-attributes-alt': activityheaders.name.reverse==true, 
                          'glyphicon glyphicon-sort-by-attributes': activityheaders.name.reverse==false
                      }" class="pull-right" ng-click="sort('name')"></i>  
                                                       </th>
                                                       <th  ><?= lang('activity_action') ?>
                                                         <i ng-class="{
                          'glyphicon glyphicon-sort':activityheaders.remark.reverse == undefined, 
                          'glyphicon glyphicon-sort-by-attributes-alt': activityheaders.remark.reverse==true, 
                          'glyphicon glyphicon-sort-by-attributes': activityheaders.remark.reverse==false
                      }" class="pull-right" ng-click="sort('remark')"></i>  
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="activity in activitylist|orderBy:orderByField:reverseSort" >

                                                       <td><a class="link_color" ng-click="activity_detail(activity.id)">{{activity.id}}</a></td>
                                                       <td>{{activity.name}}</td>
                                                       <td>{{activity.remark}}</td>
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="3"><?= lang('nothing_to_display') ?></td>
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
                                                  page="activitylistdata.currentPage" 
                                                  page-size="activitylistdata.perpage_value" 
                                                  total="activitylistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getActivities(page)">
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
