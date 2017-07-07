<div id="rightView" ng-hide="$state.current.name === 'accounts.members'">
     <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'accounts.members'">
     <div  animate-panel>
          <div class="row" ng-controller="accmembersCtrl">
               <div class="col-lg-12">                    
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                   <div class="pull-left">
                                        <div  class="dataTables_length">
                                             <form>
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="memberslistdata.perpage"  
                                                               ng-options="membersperpages as membersperpages.label for membersperpages in membersperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries

                                                  </label>
                                             </form>
                                        </div>
                                   </div>
                                   <div class="pull-right no-padding">  

                                        <div class="table_filter" id="members_list_filter" style="display: inline-flex;">
                                             <form>
                                                  <label class=" pull-left no-padding" style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findmembers()" aria-controls="members_list"  class="form-control input-sm mem_input"  type="search" ng-model="memberslistdata.filter">
                                                  </span>
                                             </form>
                                        </div>
                                   </div>

                              </div>
                              <div class="clr"></div>
                              <div class="table-responsive">
                                   <table id="members_list"   class="table table-striped table-bordered table-hover ">
                                        <thead>
                                             <tr>
                                                  <th style="width:18%"><?= lang('member_email') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.email.reverse == false}" class="pull-right" ng-click="sort('email')"></i> 
                                                  </th>
                                                  <th style="width:18%"><?= lang('member_fullname') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.fullname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.fullname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.fullname.reverse == false}" class="pull-right" ng-click="sort('fullname')"></i> 
                                                  </th>
                                                  <th style="min-width:20%"><?= lang('organizations') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.organization.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.organization.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.organization.reverse == false}" class="pull-right" ng-click="sort('organization')"></i> 
                                                  </th>
                                                  <th style="width:17%"><?= lang('settings_country') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.country.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.country.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.country.reverse == false}" class="pull-right" ng-click="sort('country')"></i> 
                                                  </th>
                                                  <th><?= lang('settings_phone') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.phone_no.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.phone_no.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.phone_no.reverse == false}" class="pull-right" ng-click="sort('phone_no')"></i>  
                                                  </th>
                                                  <th><?= lang('settings_fax') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.fax_no.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.fax_no.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.fax_no.reverse == false}" class="pull-right" ng-click="sort('fax_no')"></i>    
                                                  </th>
                                                  <th><?= lang('settings_reg_date') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':memberheaders.createdtime.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.createdtime.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.createdtime.reverse == false}" class="pull-right" ng-click="sort('createdtime')"></i>
                                                  </th>
                                                  <th></th>
                                             </tr>
                                        </thead>
                                        <tbody id="orgmembers_body">
                                             <tr ng-repeat="member in memberslist|orderBy:orderByField:reverseSort">
                                                  <td>
                                                       <a ui-sref="accounts.members.view({mem_id:member.id})" class="link_color"> {{member.email}}</a>
                                                  </td>
                                                  <td>{{member.fullname}}</td>
                                                  <td><span ng-repeat="org in member.organization" style="display: block">{{org.name}}</span></td>
                                                  <td>{{member.country}}</td>
                                                  <td>
                                                       {{member.phone_no}}
                                                  </td>
                                                  <td >{{member.fax_no}}
                                                  </td>
                                                  <td>
                                                       {{(member.createdtime*1000)|date}}
                                                  </td>
                                                  <td> <i class="fa fa-key small" title="Login as {{member.fullname}}" ng-click="loginWithId(member.id)"> </td>
                                             </tr>                                       
                                             <tr class="no-data">
                                                  <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                        </tbody>
                                        <tbody id="orgmembers_loading" class="loading">
                                             <tr>
                                                  <td colspan="6" class="text-center">
                                                       <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
                                                  </td>
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
                                             page="memberslistdata.currentPage" 
                                             page-size="memberslistdata.perpage_value" 
                                             total="memberslistdata.total"
                                             adjacent="{{adjacent}}"
                                             dots="{{dots}}"
                                             scroll-top="{{scrollTop}}" 
                                             hide-if-empty="false"
                                             ul-class="{{ulClass}}"
                                             active-class="{{activeClass}}"
                                             disabled-class="{{disabledClass}}"
                                             show-prev-next="true"
                                             paging-action="getMembers(page)">
                                        </paging> 
                                   </div>  
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>