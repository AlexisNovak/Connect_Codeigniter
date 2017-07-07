


<div class="col-lg-12 no-padding" ng-show="memberlist_content">

     <div class="col-md-12 no-padding search_toolbar" >
          <div class="angular_popup add_member pull-right"  ng-show="add_member_form">  
               <h3><?= lang('add_new_member') ?><i class="fa fa-close pull-right" ng-click="cancel_add_member()"></i></h3>
               <div class="form-holder">
                    <form class="form-horizontal">
                         <fieldset>
                              <div class="form-group" style="margin-bottom: 10px !important;">

                                   <div class="col-sm-12">

                                        <input type="text" class="form-control" ng-model="searchname.str" placeholder="eg@example.com" ng-change="getall()" required>
                                        <div ng-show="isSearch" style="background-color: #fff;max-height: 170px;overflow:auto;">
                                             <ul class="list-group" style="margin-bottom: 0px !important;">
                                                  <li ng-repeat="mem in members track by $index" ng-click="setMember(mem)" class="list-group-item list-group-item-info" >
                                                       <a>{{mem.Username}} ({{mem.Email}})</a>
                                                  </li>

                                             </ul>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-sm-12 text-left">
                                   <p ng-show="isInvite" class="text-danger"><?= lang('unknown_member') ?></p>
                                   <button type="button" ng-hide="isInvite" class="btn btn-primary" ng-click="saveMember()"><?= lang('add_btn') ?></button>
                                   <button type="button" class="btn btn-default" ng-click="cancel_add_member()"><?= lang('cancel_btn') ?></button>
                              </div>

                         </fieldset>
                    </form>
               </div>
          </div>
          <div class="clearfix"></div>
          <div class="pull-left">    
               <div  class="dataTables_length">
                    <form>

                         <label>
                              Show
                              <select class="form-control"  name="perpage" ng-model="memberslistdata.perpage"  
                                      ng-options="membersperpages as membersperpages.label for membersperpages in membersperpage" ng-change="perpagechange_member()">
                                   <option style="display:none" value class>10</option>
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
                         <span class="no-padding" style="display: inline-flex;">
                              <input ng-change="findmembers()" aria-controls="members_list"  class="form-control input-sm mem_input"  type="search" ng-model="memberslistdata.filter">
                         </span>
                    </form>
               </div>
               <div class="no-padding" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_member()"><?= lang('add_member') ?></span> </div>

          </div>

     </div>
     <div class="clr"></div>
     <div class="table-responsive">
          <table id="members_list" class="table table-striped table-bordered table-hover ">
               <thead>
                    <tr>
                         <th><?= lang('member_email') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':memberheaders.Email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.Email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.Email.reverse == false}" class="pull-right" ng-click="sort_mem('Email')"></i> 
                         </th>
                         <th><?= lang('member_fullname') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':memberheaders.FullName.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.FullName.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.FullName.reverse == false}" class="pull-right" ng-click="sort_mem('FullName')"></i> 
                         </th>                                                      

                         <th><?= lang('member_status') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':memberheaders.Status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.Status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.Status.reverse == false}" class="pull-right" ng-click="sort_mem('Status')"></i>
                         </th>
                         <th><?= lang('action') ?></th>
                    </tr>
               </thead>
               <tbody>
                    <tr ng-repeat="members in memberslist|orderBy:orderByField_mem:reverseSort_mem">
                         <td>
                              {{members.Email}}
                         </td>
                         <td>{{members.FullName}}</td>

                         <td ><span ng-if="members.Status == 1" class="label label-success">Active</span>
                              <span ng-if="members.Status == 2" class="label label-default">Suspended</span></td>
                         <td>
                              <i class="fa fa-ban" ng-click="suspend_warning_mem(members)" style="color: #FFC414;" title="Suspend" ng-if="members.Status == 1"></i>
                              <i class="fa fa-check" ng-click="suspend_warning_mem(members)" style="color: #33cc00;" title="Activate" ng-if="members.Status == 2"></i>
                              <i ng-click="delete_warning_mem(members.id)" title="Remove" class="fa fa-minus-square" style="color: red;"> </i>
                         </td>
                    </tr>
               <div class="angular_popup pull-right warning_box popup_mid" ng-show="warning_popup_mem"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning_mem()"></i></h3>
                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="member_delete(delete_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning_mem()"><?= lang('no') ?></span>
                    </div></div>

               <div class="angular_popup pull-right warning_box popup_mid" ng-show="suspend_warning_popup_mem"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_mem()"></i></h3>
                    <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_member_confirm') ?></p>
                    <p style="text-align: center;" ng-if="suspendstatus == 2"><?= lang('activate_member_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="member_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_mem()"><?= lang('no') ?></span>
                    </div></div>
               <tr class="no-data"><td colspan="5"><?= lang('nothing_to_display') ?></td></tr>
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