<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="groupsCtrl">
               <div class="angular_popup add_group pull-right"  ng-show="add_group_form">  
                    <h3><?= lang('add_new_group') ?><i class="fa fa-close pull-right" ng-click="cancel_add_group()"></i></h3>
                    <div class="form-holder">
                         <form class="form-horizontal">
                              <fieldset>
                                   <div class="form-group">
                                        <div class="col-sm-12">
                                             <input type="text" class="form-control" ng-model="new_group.name" placeholder="<?= lang('group_name_ph') ?>"> 
                                             <span class="help-block m-b-none text-danger">{{errors.name}}</span>
                                        </div>
                                   </div>
                                   <div class="form-group">
                                        <div class="col-sm-12">
                                             <input type="text" class="form-control" ng-model="new_group.code" placeholder="<?= lang('group_code_ph') ?>"> 
                                             <span class="help-block m-b-none text-danger">{{errors.code}}</span>
                                        </div>
                                   </div>
                                   <div class="form-group">
                                        <div class="col-sm-12">
                                             <textarea class="form-control" ng-model="new_group.description" rows="3" placeholder="<?= lang('group_description_ph') ?>"></textarea>
                                             <span class="help-block m-b-none"></span>
                                        </div>
                                   </div>
                                   <div class="col-sm-12 text-left">
                                        <button type="button" ng-show="isInvite" class="btn btn-primary" ng-click="invite()"><?= lang('invite_group') ?></button>
                                        <button type="button" ng-hide="isInvite" class="btn btn-primary" ng-click="save()"><?= lang('add_btn') ?></button>
                                        <button type="button" class="btn btn-default" ng-click="cancel_add_group()"><?= lang('cancel_btn') ?></button>
                                   </div>

                              </fieldset>
                         </form>
                    </div>
               </div>
               <div class="col-lg-12 no-padding"  ng-show="show_table">
                    <div class="col-lg-12" ng-show="groups_detail">
                         <div class="hpanel">
                              <div class="panel-body" style="padding:20px !important;">
                                   <div class="col-lg-12" style="padding:20px !important;">
                                        <legend><?= lang('group_detail_title') ?><span class="btn btn-primary btn-md pull-right col-md-1 back_btn" style="margin-top: -10px;" ng-click="detail_back()">Back</span>
                                             <span class="clr"></span></legend>
                                        <div class="clr"></div>
                                        <div class="table-responsive">
                                             <table class="table table-bordered details equal_columns">
                                                  <tbody>
                                                       <tr>
                                                            <th><?= lang('group_detail_name') ?></th>
                                                            <td>{{group_detail_data.name}}</td>
                                                            <th><?= lang('group_detail_code') ?></th>
                                                            <td>{{group_detail_data.code}}</td>
                                                       </tr>
                                                       <tr>
                                                            <th><?= lang('group_detail_status') ?></th>
                                                            <td>
                                                                 <span class="label" ng-class="{'label-primary':group_detail_data.status == 1,'label-default':group_detail_data.status == 2}">
                                                                      {{group_detail_data.statusText}}
                                                                 </span>
                                                            </td>
                                                            <th><?= lang('group_detail_description') ?></th>
                                                            <td>{{group_detail_data.description}}</td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div>
                                        <div class="clr" style="margin-top: 30px;"></div>
                                        <legend><?php echo lang('assigned_services_to_group'); ?></legend>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-bordered table-hover ">
                                                  <thead>
                                                       <tr>
                                                            <th><?= lang('service_detail_name') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedservices.display_name.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': assignedservices.display_name.reverse == true,'glyphicon glyphicon-sort-by-attributes': assignedservices.display_name.reverse == false}" class="pull-right" ng-click="ssort('display_name')"></i> 
                                                            </th>
                                                            <th><?= lang('service_detail_service_id') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedservices.service_id.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': assignedservices.service_id.reverse == true,'glyphicon glyphicon-sort-by-attributes': assignedservices.service_id.reverse == false}" class="pull-right" ng-click="ssort('service_id')"></i>   
                                                            </th>
                                                            <th style="width: 50%"><?= lang('service_detail_description') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedservices.description.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': assignedservices.description.reverse == true,'glyphicon glyphicon-sort-by-attributes': assignedservices.description.reverse == false}" class="pull-right" ng-click="ssort('description')"></i>  
                                                            </th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <tr ng-repeat="services in aslist| orderBy:orderBySfield:reverseSsort">
                                                            <td>
                                                                 <!--<a ng-click="service_detail(services.id)" class="link_color">-->
                                                                 {{services.display_name}}
                                                                 <!--</a>-->
                                                            </td>
                                                            <td>{{services.service_id}}</td>
                                                            <td >{{services.description}}</td> 
                                                       </tr>
                                                       <tr class="no-data">
                                                            <td colspan="5"><?= lang('nothing_to_display') ?></td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div>
                                        <div class="clr" style="margin-top: 30px;"></div>

                                        <legend><?php echo lang('assigned_members_to_group'); ?></legend>
                                        <div class="col-lg-12 no-padding">
                                             <div class="pull-right no-padding">                                           
                                                  <?php if ($is_admin): ?>
                                                         <div class="angular_popup add_member pull-right"  ng-show="add_member_form">  
                                                              <h3><?= lang('add_member') ?><i class="fa fa-close pull-right" ng-click="cancel_add_member()"></i></h3>
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
                                                                             <div class="col-sm-12">
                                                                                  <span ng-show="isInvite" class="field_error"><?= lang('unknown_member') ?></span>
                                                                                  <button type="button" ng-hide="isInvite" class="btn btn-primary" ng-click="save_member()" ng-disabled="isDisabled"><?= lang('add_btn') ?></button>
                                                                                  <button type="button" class="btn btn-default" ng-click="cancel_add_member()"><?= lang('cancel_btn') ?></button>
                                                                             </div>
                                                                        </fieldset>
                                                                   </form>
                                                              </div>
                                                         </div>
                                                         <div class="no-padding table_filter" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_member()"><?= lang('add_member') ?></span> </div>
                                                    <?php endif; ?>
                                             </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="table-responsive">
                                             <table class="table table-striped table-bordered table-hover ">
                                                  <thead>
                                                       <tr>
                                                            <th style="width:20%"><?= lang('member_email') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedmembers.Email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': assignedmembers.Email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': assignedmembers.Email.reverse == false}" class="pull-right" ng-click="Msort('Email')"></i> 
                                                            </th>
                                                            <th style="width:20%"><?= lang('member_fullname') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedmembers.FullName.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': assignedmembers.FullName.reverse == true, 'glyphicon glyphicon-sort-by-attributes': assignedmembers.FullName.reverse == false}" class="pull-right" ng-click="Msort('FullName')"></i> 
                                                            </th>
                                                            <th>Role
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedmembers.role.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': assignedmembers.role.reverse == true, 'glyphicon glyphicon-sort-by-attributes': assignedmembers.role.reverse == false}" class="pull-right" ng-click="Msort('role')"></i>    
                                                            </th>
                                                            <th><?= lang('member_status') ?>
                                                                 <i ng-class="{'glyphicon glyphicon-sort':assignedmembers.Status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': assignedmembers.Status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': assignedmembers.Status.reverse == false}" class="pull-right" ng-click="Msort('Status')"></i>
                                                            </th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <tr ng-repeat="members in amlist| orderBy:orderByMfield:reverseMsort">
                                                            <td>{{members.Email}}</td>
                                                            <td>{{members.FullName}}</td>
                                                            <td>
                                                                 <span ng-if="members.role == 1">Admin</span>
                                                                 <span ng-if="members.role == 2">Member</span>
                                                            </td>
                                                            <td>
                                                                 <span ng-if="members.Status == 'active'" class="label label-success">{{members.Status}}</span>
                                                                 <span ng-if="members.Status !== 'active'" class="label label-default">{{members.Status}}</span>
                                                            </td>
                                                       </tr>
                                                       <tr class="no-data">
                                                            <td colspan="4"><?= lang('nothing_to_display') ?></td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div>
                                   </div>  

                              </div>  </div>  </div> 
                    <?php if ($is_admin): ?>
                           <div class="col-lg-12" ng-show="groups_edit">
                                <div class="hpanel">
                                     <div class="panel-body">
                                          <div class="col-lg-12" style="margin-left: 0px !important;margin-right: 0px !important;">
                                               <legend><h3><?= lang('group_detail_edit') ?></h3></legend>

                                               <div class="form-holder">
                                                    <form name="editgroups" class="form-horizontal" ng-submit="editgroups.$valid && update(group_id)">
                                                         <fieldset>
                                                              <div class="form-group">
                                                                   <div class="col-sm-6">
                                                                        <label><?= lang('group_name_ph') ?></label>
                                                                        <input type="hidden" class="form-control" ng-model="editgroup.org_id"> 
                                                                        <input type="hidden" class="form-control" ng-model="editgroup.id"> 
                                                                        <input type="text" class="form-control" ng-model="editgroup.name" placeholder="<?= lang('group_name_ph') ?>"> 
                                                                        <span class="help-block m-b-none text-danger">{{errors.name}}</span>
                                                                   </div>
                                                                   <div class="col-sm-6">
                                                                        <label><?= lang('group_code_ph') ?></label>
                                                                        <input type="text" class="form-control" ng-model="editgroup.code" placeholder="<?= lang('group_code_ph') ?>"> 
                                                                        <span class="help-block m-b-none text-danger">{{errors.code}}</span>
                                                                   </div>
                                                              </div>
                                                              <div class="form-group">
                                                                   <div class="col-sm-6">
                                                                        <label><?= lang('group_description_ph') ?></label>
                                                                        <textarea class="form-control" ng-model="editgroup.description" rows="3" placeholder="<?= lang('group_description_ph') ?>"></textarea>
                                                                        <span class="help-block m-b-none"></span>
                                                                   </div>
                                                              </div>                                                            
                                                              <div class="col-sm-12 text-left">
                                                                   <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                                                   <button type="reset" class="btn btn-default" ng-click="cancel_edit_group()"><?= lang('cancel_btn') ?></button>
                                                              </div>

                                                         </fieldset>
                                                    </form>
                                               </div>
                                          </div>  

                                     </div>  </div>  </div> 
                      <?php endif; ?>

                    <div class="col-lg-12" ng-show="grouplist_content">

                         <div class="hpanel">
                              <div class="alert alert-custom">
                                   <span class="icon_holder"><img src="<?= base_url("resource/images/info.png") ?>"></span>
                                   <p><?= lang('groups_tab_info') ?></p>
                              </div>
                              <div class="panel-body">
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar" >

                                        <div class="clearfix"></div>
                                        <div class="pull-left">    
                                             <div  class="dataTables_length">
                                                  <form>

                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="groupslistdata.perpage"  
                                                                    ng-options="groupsperpages as groupsperpages.label for groupsperpages in groupsperpage" ng-change="perpagechange()">
                                                                 <option style="display:none" value class>15</option>
                                                            </select>
                                                            entries

                                                       </label>
                                                  </form>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">  

                                             <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;">
                                                  <form>
                                                       <label class=" pull-left no-padding" style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                       <span class="no-padding table_filter" style="display: inline-flex;">
                                                            <input ng-change="findgroups()" aria-controls="groups_list"  class="form-control input-sm mem_input"  type="search" ng-model="groupslistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                             <?php if ($is_admin): ?>
                                                    <div class="no-padding table_filter" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_group()"><?= lang('new_group') ?></span> </div>
                                               <?php endif; ?>
                                        </div>

                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="groups_list"   class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>

                                                       <th><?= lang('group_detail_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':groupheaders.name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.name.reverse == false}" class="pull-right" ng-click="sort('name')"></i> 
                                                       </th>
                                                       <th><?= lang('group_detail_code') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':groupheaders.code.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.code.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.code.reverse == false}" class="pull-right" ng-click="sort('code')"></i>   
                                                       </th>
                                                       <th style="width: 50%"><?= lang('group_detail_description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':groupheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                                                       </th>
                                                       <th><?= lang('group_detail_status') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':groupheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': groupheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': groupheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>  
                                                       </th>
                                                       <?php if ($is_admin): ?>
                                                              <th><?= lang('action') ?></th>
                                                         <?php endif; ?>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="groups in groupslist|orderBy:orderByField:reverseSort">
                                                       <td><a ng-click="group_detail(groups.id)" class="link_color"> {{groups.name}}</a></td>
                                                       <td>{{groups.code}}</td>
                                                       <td >{{groups.description}}</td> 
                                                       <td>
                                                            <span ng-if="groups.status == 1" class="label label-success">Active</span>
                                                            <span ng-if="groups.status == 2" class="label label-default">Suspended</span></td>

                                                       </td>
                                                       <?php if ($is_admin): ?>
                                                              <td>
                                                                   <i class="fa fa-ban" ng-click="suspend_warning_grp(groups)" style="color: #FFC414;" title="Suspend" ng-if="groups.status == 1"></i>
                                                                   <i class="fa fa-check" ng-click="suspend_warning_grp(groups)" style="color: #33cc00;" title="Activate" ng-if="groups.status == 2"></i>
                                                                   <i class="fa fa-edit" ng-click="group_edit(groups.id)" style="color: #FFC414;" title="Edit"></i>
                                                                   <i ng-click="delete_warning(groups.id)" title="Remove" class="fa fa-minus-square" style="color: red;"> </i>
                                                              </td>
                                                         <?php endif; ?>
                                                  </tr>
                                             <div class="angular_popup pull-right warning_box popup_mid" ng-show="suspend_warning_popup_grp"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_suspend_warning_grp()"></i></h3>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 1"><?= lang('suspend_group_confirm') ?></p>
                                                  <p style="text-align: center;" ng-if="suspendstatus == 2"><?= lang('activate_group_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="group_suspend(suspend_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_suspend_warning_grp()"><?= lang('no') ?></span>
                                                  </div></div>
                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="group_delete(delete_id)" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
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
                                                  paging-action="getgroups(page)">
                                             </paging> 
                                        </div>  
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <div ng-show="show_init">
                    <div class="init_div">
                         <h2><?= lang('init_team') ?></h2>
                         <p><?= lang('init_team_info') ?></p>
                         <span class="btn btn-lg btn-info" ng-click="add_group()"><?= lang('new_group') ?></span>
                    </div>
               </div>
          </div>
     </div>
</div>