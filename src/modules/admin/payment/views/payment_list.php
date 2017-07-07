<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <!-- angular popup starts -->
                              <!-- ends angular popup -->
                              <div class="clearfix"></div>
                              <div ng-show="show_table">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="paylistdata.perpage"  
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
                                                            <input ng-change="findpayments()" aria-controls="organisation_list" placeholder="Search"  class="form-control input-sm" type="search" ng-model="paylistdata.filter">
                                                       </span>
                                                       <div class="table_filter"  style="display: inline-flex;">
                                                            <select class="form-control input-sm" name="holder_type" ng-model="paylistdata.holder_type" ng-change="findpayments()">
                                                                 <option value="">All Holder Types</option>
                                                                 <option value="1">Individual</option>
                                                                 <option value="2">Organisation</option>
                                                            </select>
                                                       </div>
                                                       <div class="table_filter"  style="display: inline-flex;">
                                                            <select class="form-control input-sm" name="account_type" ng-model="paylistdata.account_type" ng-change="findpayments()">
                                                                 <option value="">All Account Types</option>                                                                 
                                                                 <option value="1">Pre-Paid</option>
                                                                 <option value="2">Post-Paid</option>
                                                            </select>
                                                       </div>
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
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.account_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.account_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.account_name.reverse == false}" class="pull-right" ng-click="sort('account_name')"></i>  
                                                       </th>
                                                       <th><?= lang('organisation_user') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.parent_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.parent_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.parent_name.reverse == false}" class="pull-right" ng-click="sort('parent_name')"></i>  
                                                       </th>                                                   
                                                       <th><?= lang('account_type') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.account_typename.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.account_typename.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.account_typename.reverse == false}" class="pull-right" ng-click="sort('account_typename')"></i>  
                                                       </th>
                                                       <th><?= lang('holder_type') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.holder_typename.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.holder_typename.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.holder_typename.reverse == false}" class="pull-right" ng-click="sort('holder_typename')"></i>  
                                                       </th>    
                                                       <th style="width:10%"><?= lang('payment_balance') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.credit.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.credit.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.credit.reverse == false}" class="pull-right" ng-click="sort('credit')"></i>  
                                                       </th>
                                                       <th><?= lang('status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>   
                                                       </th>
                                                       <th><?= lang('action') ?>
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="pay in paymentlist|orderBy:orderByField:reverseSort">
                                                       <td><a ui-sref="accounts.view_account({account_id:pay.account_id})" title="update" class="link_color"> {{pay.account_name}}</a></td>
                                                       <td>{{pay.parent_name}}</td>
                                                       <td>{{pay.account_typename}}</td>
                                                       <td>{{pay.holder_typename}}</td>
                                                       <td>SGD {{pay.credit| number:2}}</td>
                                                       <td>
                                                            <span title="Approve" ng-if="pay.status == 1" class="label label-info" style="cursor: pointer">Pending Approval</span>
                                                            <span title="Approved" ng-if="pay.status == 2" class="label label-success">Approved</span>
                                                       </td>
                                                       <td>
                                                            <button class="btn btn-default btn-sm"  ng-if="pay.status == 1" ng-click="activate_account(pay)">Accept</button>
                                                       </td>
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="7"><?= lang('nothing_to_display') ?></td>
                                                  </tr>  
                                             </tbody>
                                        </table>


                                        <div class="angular_popup popup_sm pull-right warning_box" ng-show="DO_accept_warning_popup"> 
                                             <h3><?= lang('accept_pay') ?><i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>
                                             <p style="text-align: center;"><?= lang('accept_confirm') ?></p>
                                             <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="acceptPaymentType(selected_payment_id)" style=""><?= lang('yes') ?></span>
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_accept()"><?= lang('no') ?></span>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                        </div> 
                                        <div class="col-md-8 text-right no-padding">
                                             <paging
                                                  class="small"
                                                  page="paylistdata.currentPage" 
                                                  page-size="paylistdata.perpage_value" 
                                                  total="paylistdata.total"
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
</div>
