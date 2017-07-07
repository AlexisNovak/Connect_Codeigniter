<div class="col-lg-12 no-padding" ng-show="grouplist_content">
     <div class="col-md-12 no-padding search_toolbar">
          <div class="angular_popup add_group pull-right"  ng-show="add_group_form">  
               <h3><?= lang('add_new_group') ?><i class="fa fa-close pull-right" ng-click="cancel_add_group()"></i></h3>
               <div class="form-holder">
                    <form class="form-horizontal">
                         <fieldset>
                              <div class="form-group" style="margin-bottom: 10px !important;">

                                   <div class="col-sm-12">

                                        <input type="text" class="form-control" ng-model="searchgrpname.str" placeholder="group" ng-change="getall_grp()" required>
                                        <div ng-show="isSearch_grp" style="background-color: #fff;max-height: 170px;overflow:auto;">
                                             <ul class="list-group" style="margin-bottom: 0px !important;">
                                                  <li ng-repeat="grp in groups track by $index" ng-click="setGroup(grp)" class="list-group-item list-group-item-info" >
                                                       <a>{{grp.name}} ({{grp.code}})</a>
                                                  </li>

                                             </ul>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-sm-12 text-left">
                                   <p ng-show="isInvite_grp" class="text-danger"><?= lang('unknown_group') ?></p>
                                   <button type="button" ng-hide="isInvite_grp" class="btn btn-primary" ng-click="saveGroup()"><?= lang('add_btn') ?></button>
                                   <button type="button" class="btn btn-default" ng-click="cancel_add_group()"><?= lang('cancel_btn') ?></button>
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
                              <select class="form-control"  name="perpage" ng-model="groupslistdata.perpage"  
                                      ng-options="groupsperpages as groupsperpages.label for groupsperpages in groupsperpage" ng-change="perpagechange_group()">
                                   <option style="display:none" value class>10</option>
                              </select>
                              entries

                         </label>
                    </form>
               </div>
          </div>
          <div class="pull-right no-padding">  

               <div class="table_filter" id="groups_list_filter" style="display: inline-flex;">
                    <form>
                         <label class=" pull-left no-padding" style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                         <span class="no-padding" style="display: inline-flex;">
                              <input ng-change="findgroups()" aria-controls="groups_list"  class="form-control input-sm grp_input"  type="search" ng-model="groupslistdata.filter">
                         </span>
                    </form>
               </div>
               <div class="no-padding" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_group()"><?= lang('add_group') ?></span> </div>

          </div>

     </div>
     <div class="clr"></div>
     <div class="table-responsive">
          <table id="groups_list" class="table table-striped table-bordered table-hover ">
               <thead>
                    <tr>
                         <th><?= lang('group_detail_name') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':groupheaders.name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.name.reverse == false}" class="pull-right" ng-click="sort_grp('name')"></i> 
                         </th>
                         <th><?= lang('group_detail_code') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':groupheaders.code.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.code.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.code.reverse == false}" class="pull-right" ng-click="sort_grp('code')"></i> 
                         </th>                                                      

                         <th><?= lang('group_detail_status') ?>
                              <i ng-class="{'glyphicon glyphicon-sort':groupheaders.Status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.Status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.Status.reverse == false}" class="pull-right" ng-click="sort_grp('Status')"></i>
                         </th>
                         <th><?= lang('action') ?></th>
                    </tr>
               </thead>
               <tbody>
                    <tr ng-repeat="groups in groupslist|orderBy:orderByField_grp:reverseSort_grp">
                         <td>
                              {{groups.name}}
                         </td>
                         <td>{{groups.code}}</td>

                         <td ><span ng-if="groups.Status == 1" class="label label-success">Active</span>
                              <span ng-if="groups.Status == 2" class="label label-default">Suspended</span></td>
                         <td>
                              <i class="fa fa-ban" ng-click="suspend_warning_grp(groups)" style="color: #FFC414;" title="Suspend" ng-if="groups.Status == 1"></i>
                              <i class="fa fa-check" ng-click="suspend_warning_grp(groups)" style="color: #33cc00;" title="Activate" ng-if="groups.Status == 2"></i>
                              <i ng-click="delete_warning_grp(groups.group_id)" title="Remove" class="fa fa-minus-square" style="color: red;"> </i>
                         </td>
                    </tr>
               <div class="angular_popup pull-right warning_box popup_mid" ng-show="warning_popup_grp"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning_grp()"></i></h3>
                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="group_delete(delete_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning_grp()"><?= lang('no') ?></span>
                    </div></div>
               <div class="angular_popup pull-right warning_box popup_mid" ng-show="suspend_warning_popup_grp"> 
                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_grp()"></i></h3>
                    <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_group_confirm') ?></p>
                    <p style="text-align: center;" ng-if="suspendstatus == 2"><?= lang('activate_group_confirm') ?></p>
                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="group_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                         <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_grp()"><?= lang('no') ?></span>
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
                    page="groupslistdata.currentPage" 
                    page-size="groupslistdata.perpage_value" 
                    total="groupslistdata.total"
                    adjacent="{{adjacent}}"
                    dots="{{dots}}"
                    scroll-top="{{scrollTop}}" 
                    hide-if-empty="false"
                    ul-class="{{ulClass}}"
                    active-class="{{activeClass}}"
                    disabled-class="{{disabledClass}}"
                    show-prev-next="true"
                    paging-action="getGroups(page)">
               </paging> 
          </div>  
     </div>
</div>