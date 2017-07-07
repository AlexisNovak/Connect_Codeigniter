<div class="content padding_0">
    <div  animate-panel>
        <div class="row" ng-controller="parcelsCtrl">
            <div class="col-lg-12 no-padding">

                <div class="col-lg-12" ng-show="members_edit">
                    <div class="hpanel">
                        <div class="panel-body">
                            <div class="col-lg-12" style="margin-left: 0px !important;margin-right: 0px !important;">
                                <legend><h3>Edit Parcel Type</h3></legend>

                                <div class="form-holder">
                                    <form name="editmembers" class="form-horizontal" ng-submit="editmembers.$valid && update(member_id)">
                                        <fieldset>
                                            <div class="form-group col-sm-12">

                                                <div class="col-sm-12">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" ng-model="editmember.display_name"  placeholder="Name" > 
                                                    <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.display_name_error}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <div class="col-sm-12">
                                                    <label>Description</label>
                                                    <textarea class="form-control" ng-model="editmember.description"></textarea>
                                                    <span class="help-block m-b-none" ng-show="errors.name_error">{{errors.description_error}}</span>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 text-left">
                                                <button type="submit" class="btn btn-primary" ng-class="{disabled:editmembers.$invalid}"><?= lang('save_btn') ?></button>
                                                <button type="reset" class="btn btn-default" ng-click="cancel_edit_member()"><?= lang('cancel_btn') ?></button>
                                            </div>

                                        </fieldset>
                                    </form>
                                </div>
                            </div>  

                        </div>  
                    </div>  
                </div> 
                <div class="col-lg-12" ng-show="memberlist_content">

                    <div class="hpanel">                              
                        <div class="panel-body">
                            <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">
                                <div class="angular_popup add_member pull-right"  ng-show="add_member_form">  
                                    <h3>New Parcel Type<i class="fa fa-close pull-right" ng-click="cancel_add_member()"></i></h3>
                                    <div class="form-holder">
                                        <form class="form-horizontal">
                                            <fieldset>
                                                <div class="form-group" style="margin-bottom: 10px !important;">

                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" ng-model="newtype.name" placeholder="Name" required > 
                                                        
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <textarea  class="form-control" ng-model="newtype.description" placeholder="Description" required ></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-primary" ng-click="save()" ng-disabled="isDisabled"><?= lang('add_btn') ?></button>
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
                                        <div class="no-padding table_filter" style="display: inline-flex;"> <span class="btn btn-sm btn-info" ng-click="add_member()">Add Parcels Type</span> </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div class="clr"></div>
                            <div class="table-responsive">
                                <table id="members_list"   class="table table-striped table-bordered table-hover ">
                                    <thead>
                                        <tr>

                                            <th style="width:20%">Name
                                                <i ng-class="{'glyphicon glyphicon-sort':memberheaders.FullName.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.FullName.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.FullName.reverse == false}" class="pull-right" ng-click="sort('FullName')"></i> 
                                            </th>

                                            <th> Description
                                                <i ng-class="{'glyphicon glyphicon-sort':memberheaders.groupname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.groupname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.groupname.reverse == false}" class="pull-right" ng-click="sort('groupname')"></i>  
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="orgmembers_body">
                                        <tr ng-repeat="members in memberslist|orderBy:orderByField:reverseSort">

                                            <td>{{members.display_name}}</td>
                                            <!--<td>{{members.Note}}</td>-->
                                            <td>{{members.description}}</td>


                                            <td>
                                                <i class="fa fa-edit " ng-click="member_edit(members.consignment_type_id)" style="color: #FFC414;" title="Edit" ></i>
                                                <i ng-click="delete_warning(members.consignment_type_id)" title="Remove" class="fa fa-minus-square" style="color: red;" > </i>
                                            </td>
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
</div>