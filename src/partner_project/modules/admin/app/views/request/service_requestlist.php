<div id="rightView" ng-hide="$state.current.name === 'tender_requests.service'">
     <div ui-view></div>
</div>
<div class="content padding_0" ng-show="$state.current.name === 'tender_requests.service'">
     <div  animate-panel>
          <div class="row" ng-controller="srequestCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">                      
                         <div class="panel-body">                             
                              <div ng-show="show_table">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                        <div class="clearfix"></div>
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="reqlistdata.perpage"  
                                                                    ng-options="reqperpages as reqperpages.label for reqperpages in reqperpage" ng-change="perpagechange()">
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
                                                            <input ng-change="findreq()" aria-controls="srequest_list"  class="form-control input-sm" type="search" ng-model="reqlistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                             <div class="table_filter"  style="display: inline-flex;">
                                                  <select class="form-control input-sm" name="perpage" ng-model="reqlistdata.org_id"  
                                                          ng-options="org.org_id as org.org_name for org in adminorglist" ng-change="findreq()">
                                                       <option value=""><?= lang("order_filter_org") ?></option>
                                                  </select>
                                             </div> 
                                             <div class="no-padding table_filter" style="display: inline-flex;">
                                                  <a ui-sref="tender_requests.service.new_request" class="btn btn-sm btn-info"><?= lang('new_srequest') ?></a>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="srequest_list" class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                                                       <th><?= lang('srequest_title') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.title.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.title.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.title.reverse == false}" class="pull-right" ng-click="sort('title')"></i>  
                                                       </th>
<!--                                                       <th style="width:10%"><?= lang('srequest_type') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.type.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.type.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.type.reverse == false}" class="pull-right" ng-click="sort('type')"></i>  
                                                       </th>-->
                                                       <th style="width:20%"><?= lang('srequest_duration') ?> (<?= lang('months') ?>)
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.duration.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.duration.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.duration.reverse == false}" class="pull-right" ng-click="sort('duration')"></i>  
                                                       </th>
                                                       <th><?= lang('srequest_payment') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.payment.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.payment.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.payment.reverse == false}" class="pull-right" ng-click="sort('payment')"></i>  
                                                       </th>
                                                       <th style="width:20%"><?= lang('srequest_description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                                                       </th>
                                                       <th><?= lang('srequest_org') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>  
                                                       </th>
                                                       <th><?= lang('srequest_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':reqheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': reqheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': reqheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>  
                                                       </th>
     <!--                                                  <th><?= lang('action') ?>
     
                                                       </th>-->
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="req in reqlist|orderBy:orderByField:reverseSort">
                                                       <td><a ui-sref="tender_requests.service.view_request({request_id:req.req_id})" class="link_color">{{req.title}}</a></td>
                                                       <!--<td>{{req.type}}</td>-->
                                                       <td>{{req.duration}}</td>
                                                       <td>{{req.payment}}</td>
                                                       <td>{{req.description}}</td>
                                                       <td>{{req.org_name}}</td>
                                                       <td>
                                                            <span ng-if="req.status == 1 && req.bid_count == 0" class="label label-primary">New</span>
                                                            <span ng-if="req.status == 1 && req.bid_count != 0" class="label label-info">Pending Bids</span>
                                                            <span ng-if="req.status == 0 && req.bid_count != 0" class="label label-success">Accepted</span>

                                                       </td>
     <!--                                                  <td></td>-->
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="7"><?= lang('nothing_to_display') ?></td>
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
                                                  page="reqlistdata.currentPage" 
                                                  page-size="reqlistdata.perpage_value" 
                                                  total="reqlistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getSrequests(page)">
                                             </paging> 
                                        </div>
                                   </div>
                              </div>
                              <div ng-show="show_init">
                                   <div class="init_div">
                                        <h2><?= lang('init_service_tender') ?></h2>
                                        <p><?= lang('init_service_tender_info') ?></p>
                                        <a ui-sref="tender_requests.service.new_request" class="btn btn-lg btn-info"><?= lang('new_service_tender') ?></a>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>