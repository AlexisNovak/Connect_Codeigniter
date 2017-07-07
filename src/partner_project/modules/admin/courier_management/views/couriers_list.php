<div id="rightView" ng-hide="$state.current.name === 'couriers'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'couriers'">
     <div animate-panel>
          <div class="row" ng-controller="courierMgmtCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body"> 
                              <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                                   <div class="clearfix"></div>
                                   <div class="pull-left">
                                        <div  class="dataTables_length">
                                             <form>
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="courierlistdata.perpage"  
                                                               ng-options="courierperpages as courierperpages.label for courierperpages in courierperpage" ng-change="perpagechange()">
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
                                                       <input ng-change="findcourier()" aria-controls="courier_list"  class="form-control input-sm" type="search" ng-model="courierlistdata.filter">
                                                  </span>
                                             </form>
                                        </div>
                                   </div>


                              </div>
                              <div class="clearfix"></div>
                              <div class="clr"></div>
                              <div class="table-responsive">
                                   <table id="courier_list" class="table table-striped table-bordered table-responsive">
                                        <thead>
                                             <tr>
                                                  <th style="width:18%"><?= lang('courier_company_name') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.company_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.company_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.company_name.reverse == false}" class="pull-right" ng-click="sort('company_name')"></i>  
                                                  </th> 
                                                  <th style="width:18%"><?= lang('courier_email') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.email.reverse == false}" class="pull-right" ng-click="sort('email')"></i>  
                                                  </th>

                                                  <th style="width:15%"><?= lang('courier_access_key') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.access_key.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.access_key.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.access_key.reverse == false}" class="pull-right" ng-click="sort('access_key')"></i>  
                                                  </th>
                                                  <th style="width:15%"><?= lang('courier_url') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.url.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.url.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.url.reverse == false}" class="pull-right" ng-click="sort('url')"></i>  
                                                  </th>
                                                  <th style="width:10%">
                                                       <?= lang('courier_email_verified') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.verified.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.verified.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.verified.reverse == false}" class="pull-right" ng-click="sort('verified')"></i></th>
                                                  <th style="width:15%">
                                                       <?= lang('courier_account_approved') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':courierheaders.approved.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': courierheaders.approved.reverse == true, 'glyphicon glyphicon-sort-by-attributes': courierheaders.approved.reverse == false}" class="pull-right" ng-click="sort('approved')"></i></th>
                                                  <th><?= lang('action') ?>
                                                  </th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="courier in courierlist|orderBy:orderByField:reverseSort">
                                                  <td>
                                                       <a ui-sref="couriers.update_courier({courier_id:courier.courier_id})" title="update" class="link_color">
                                                            {{courier.company_name}}
                                                       </a>
                                                  </td>

                                                  <td>{{courier.email}}</td>
                                                  <td>{{courier.access_key}}</td>
                                                  <td>{{courier.url}}</td>
                                                  <td> 
                                                       <span class="label label-success" ng-if="courier.verified">Verified</span>
                                                       <span class="label label-danger" ng-if="!courier.verified">Not Verified</span>
                                                  </td>
                                                  <td>
                                                       <span class="label label-success" ng-if="courier.approved">Approved</span>
                                                       <span class="label label-danger" ng-if="!courier.approved">Not Approved</span>
                                                  </td>
                                                  <td>
                                                       <span title="approve" ng-if="!courier.approved" ng-click="show_approve_confirm(courier.courier_id)"><i  class="fa fa-check-square-o" style="color:#62cb31"></i></span>
                                                  </td>
                                             </tr>
                                             <tr class="no-data">
                                                  <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                        <div class="angular_popup popup_sm pull-right warning_box" ng-show="approve_confirm_popup"> 
                                             <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_approve()"></i></h3>
                                             <p style="text-align: center;"><?= lang('approve_confirm') ?></p>
                                             <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="approve()" style=""><?= lang('yes') ?></span>
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_approve()"><?= lang('no') ?></span>
                                             </div>
                                        </div>
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
                                             page="courierlistdata.currentPage" 
                                             page-size="courierlistdata.perpage_value" 
                                             total="courierlistdata.total"
                                             adjacent="{{adjacent}}"
                                             dots="{{dots}}"
                                             scroll-top="{{scrollTop}}" 
                                             hide-if-empty="false"
                                             ul-class="{{ulClass}}"
                                             active-class="{{activeClass}}"
                                             disabled-class="{{disabledClass}}"
                                             show-prev-next="true"
                                             paging-action="getCouriers(page)">
                                        </paging> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>