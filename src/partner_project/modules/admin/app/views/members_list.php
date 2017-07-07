<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="membersCtrl">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12" ng-show="members_detail">
                         <div class="hpanel" >
                              <div class="panel-body" style="padding:20px !important;">
                                   <div class="col-lg-12" style="padding:20px !important;">
                                        <legend><?= lang('member_details_title') ?><span class="btn btn-primary btn-md pull-right col-md-1 back_btn" style="margin-top: -10px;" ng-click="detail_back()">Back</span>
                                             <span class="clr"></span></legend>
                                        <div class="clr"></div>
                                        <div class="table-responsive">
                                             <table class="table table-bordered details">
                                                  <tbody>
                                                       <tr><th><?= lang('member_fullname') ?></th><td>{{member_detail_data.fullname}}</td></tr>
                                                       <tr><th><?= lang('member_email') ?></th><td>{{member_detail_data.email}}</td></tr>

                                                       <tr><th><?= lang('member_note') ?></th><td>{{member_detail_data.note}}</td></tr>
                                                       <tr><th><?= lang('member_group') ?></th><td>{{member_detail_data.groupname}}</td></tr>
                                                       <tr><th><?= lang('member_status') ?></th><td>{{member_detail_data.status}}</td></tr>
                                                       <tr><th><?= lang('member_phone_number') ?></th><td>{{member_detail_data.phone_no}}</td></tr>
                                                       <tr><th><?= lang('member_fax_no') ?></th><td>{{member_detail_data.fax_no}}</td></tr>
                                                       <tr><th><?= lang('member_country') ?></th><td>{{member_detail_data.country}}</td></tr>
                                                       <tr><th><?= lang('member_description') ?></th><td>{{member_detail_data.description}}</td></tr>
                                                       <tr><th><?= lang('member_role') ?></th><td>{{member_detail_data.role_name}}</td></tr>
                                                  </tbody>

                                             </table>
                                        </div>

                                   </div>  

                              </div>  </div>  </div> 
                    <?php if ($is_admin): ?>
                           <div class="col-lg-12" ng-show="members_edit">
                                <div class="hpanel">
                                     <div class="panel-body">
                                          <div class="col-lg-12" style="margin-left: 0px !important;margin-right: 0px !important;">
                                               <legend><h3><?= lang('member_edit') ?></h3></legend>

                                               <div class="form-holder">
                                                    <form name="editmembers" class="form-horizontal" ng-submit="editmembers.$valid && update(member_id)">
                                                         <fieldset>
                                                              <div class="form-group col-sm-6">

                                                                   <div class="col-sm-12">
                                                                        <label><?= lang('member_note') ?></label>
                                                                        <input type="text" class="form-control" ng-model="editmember.note"  placeholder="<?= lang('member_note') ?>" > 
                                                                        <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                                   </div>
                                                              </div>

                                                              <div class="form-group col-sm-6">
                                                                   <div class="col-sm-12">
                                                                        <label><?= lang('member_group') ?></label>
                                                                        <select class="form-control" ng-model="editmember.group">
                                                                             <option value="0">Select Group</option>
                                                                             <option ng-selected="{{s.group_id == editmember.group}}" ng-repeat="s in grouplist" value="{{s.group_id}}">{{s.group_name}}</option>
                                                                        </select>
                                                                        <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                                   </div>
                                                              </div>
                                                              <div class="form-group col-sm-6">

                                                                   <div class="col-sm-12" ng-if="editmember.is_superadmin == 0 && editmember.current_user_id != editmember.user_id">
                                                                        <label><?= lang('member_status') ?></label>
                                                                        <select class="form-control" ng-model="editmember.status" >
                                                                             <option value="active">Active</option>
                                                                             <option value="Not Active">Not Active</option>
                                                                             <option value="Denied">Denied</option>
                                                                        </select>
                                                                        <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                                   </div>
                                                              </div>
                                                              <div class="form-group col-sm-6">

                                                                   <div class="col-sm-12" ng-if="editmember.is_superadmin == 0 && editmember.current_user_id != editmember.user_id">
                                                                        <label><?= lang('member_role') ?></label>
                                                                        <select class="form-control" ng-model="editmember.role_id" >
                                                                             <option value="1">Admin</option>
                                                                             <option value="2">Member</option>
                                                                        </select>
                                                                        <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                                   </div>
                                                              </div>
                                                              <div class="col-sm-12 text-left">
                                                                   <button type="submit" class="btn btn-primary" ng-class="{
                                                                                                                                                                          disabled:editmembers.$invalid
                                                                                                                                                                      }"><?= lang('save_btn') ?></button>
                                                                   <button type="reset" class="btn btn-default" ng-click="cancel_edit_member()"><?= lang('cancel_btn') ?></button>
                                                              </div>

                                                         </fieldset>
                                                    </form>
                                               </div>
                                          </div>  

                                     </div>  </div>  </div> 
                      <?php endif; ?>

                    <div class="col-lg-12" ng-show="memberlist_content">

                         <div class="hpanel">
                              <div class="alert alert-custom">
                                   <span class="icon_holder"><img src="<?= outer_base_url("resource/images/info.png") ?>"></span>
                                   <p><?= lang('members_tab_info') ?></p>
                              </div>
                              <div class="panel-body">
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">
                                        <div class="angular_popup add_member pull-right"  ng-show="add_member_form">  
                                             <h3><?= lang('new_member') ?><i class="fa fa-close pull-right" ng-click="cancel_add_member()"></i></h3>
                                             <div class="form-holder">
                                                  <form class="form-horizontal">
                                                       <fieldset>
                                                            <div class="form-group" style="margin-bottom: 10px !important;">

                                                                 <div class="col-sm-12">
                                                                      <input type="text" class="form-control" ng-model="searchname" placeholder="eg@example.com" required  ng-change="getall()"> 
                                                                      <div ng-show="isSearch" style="background-color: #fff;max-height: 170px;overflow:auto;">
                                                                           <ul class="list-group" style="margin-bottom: 0px !important;">
                                                                                <li ng-repeat="mem in members track by $index" ng-click="setMember(mem)" class="list-group-item list-group-item-info" >
                                                                                     <a>{{mem.Username}} ({{mem.Email}})</a>
                                                                                </li>

                                                                           </ul>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-sm-12 no-padding" ng-if="validemail">
                                                                 <div class="invite_info">
                                                                      <p><strong><?= lang('invite_title') ?></strong></p>
                                                                      <p><?= lang('invite_info') ?></p>
                                                                 </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                 <button type="button" ng-show="isInvite" class="btn btn-primary" ng-click="invite()" ng-disabled="isDisabled"><?= lang('invite_member') ?></button>
                                                                 <button type="button" ng-hide="isInvite" class="btn btn-primary" ng-click="save()" ng-disabled="isDisabled"><?= lang('add_btn') ?></button>
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
                                             <?php if ($is_admin): ?>
                                                    <div class="no-padding table_filter" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_member()"><?= lang('new_member') ?></span> </div>
                                               <?php endif; ?>
                                        </div>

                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="members_list"   class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th style="width:20%"><?= lang('member_email') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.Email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.Email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.Email.reverse == false}" class="pull-right" ng-click="sort('Email')"></i> 
                                                       </th>
                                                       <th style="width:20%"><?= lang('member_fullname') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.FullName.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.FullName.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.FullName.reverse == false}" class="pull-right" ng-click="sort('FullName')"></i> 
                                                       </th>
<!--                                                       <th><?= lang('member_note') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.Note.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.Note.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.Note.reverse == false}" class="pull-right" ng-click="sort('Note')"></i>   
                                                       </th>-->
                                                       <th><?= lang('member_group') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.groupname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.groupname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.groupname.reverse == false}" class="pull-right" ng-click="sort('groupname')"></i>  
                                                       </th>
                                                       <th>Role
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.role.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.role.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.role.reverse == false}" class="pull-right" ng-click="sort('role')"></i>    
                                                       </th>
                                                       <th><?= lang('member_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.Status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.Status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.Status.reverse == false}" class="pull-right" ng-click="sort('Status')"></i>
                                                       </th>
                                                       <?php if ($is_admin): ?>
                                                              <th></th>
                                                         <?php endif; ?>
                                                  </tr>
                                             </thead>
                                             <tbody id="orgmembers_body">
                                                  <tr ng-repeat="members in memberslist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <span ng-if="org.user_id != members.id || memberslistdata.current_user_id == members.id">
                                                                 <a ng-click="member_edit(members.id)" class="link_color"> {{members.Email}}</a>
                                                            </span>
                                                            <span ng-if="org.user_id == members.id && memberslistdata.current_user_id != members.id">
                                                                 <a ng-click="member_detail(members.id)" class="link_color"> {{members.Email}}</a>
                                                            </span>
                                                       </td>
                                                       <td>{{members.FullName}}</td>
                                                       <!--<td>{{members.Note}}</td>-->
                                                       <td>{{members.groupname}}</td>
                                                       <td>
                                                            <span ng-if="members.role == 1">Admin</span>
                                                            <span ng-if="members.role == 2">Member</span>
                                                       </td>
                                                       <td ><span ng-if="members.Status == 'active'" class="label label-success">{{members.Status}}</span>
                                                            <span ng-if="members.Status !== 'active'" class="label label-default">{{members.Status}}</span></td>
                                                       <?php if ($is_admin): ?>
                                                              <td>
                                                                   <i class="fa fa-edit"  style="color: #6A6C6F;" title="Edit" ng-if="org.user_id == members.id && memberslistdata.current_user_id != members.id"></i>
                                                                   <i class="fa fa-edit " ng-click="member_edit(members.id)" style="color: #FFC414;" title="Edit" ng-if="org.user_id != members.id || memberslistdata.current_user_id == members.id"></i>
                                                                   <i ng-click="delete_warning(members.id)" title="Remove" class="fa fa-minus-square" style="color: red;" ng-if="org.user_id != members.id && memberslistdata.current_user_id != members.id"> </i>
                                                                   <i class="fa fa-minus-square" ng-if="org.user_id == members.id || memberslistdata.current_user_id == members.id"></i>
                                                              </td>
                                                         <?php endif; ?>
                                                  </tr>
                                             <div class="angular_popup pull-right warning_box popup_mid" ng-show="warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="member_delete(delete_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                  </div></div>
                                             <tr class="no-data">
                                                  <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                             <tbody id="orgmembers_loading" class="loading">
                                                  <tr>
                                                       <td colspan="6" class="text-center">
                                                            <img src="<?php echo outer_base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
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
</div>