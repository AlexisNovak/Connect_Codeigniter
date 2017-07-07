<div class="content">
     <div  animate-panel>
          <div class="row" ng-controller="organisationCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <!-- angular popup starts -->
                              <div class="angular_popup create_org pull-right"  ng-show="create_org_form">  
                                   <h3><?= lang("create_new_organisation") ?><i class="fa fa-close pull-right" ng-click="cancel_create_org()"></i></h3>
                                   <div class="form-holder">
                                        <form name="newOrganisation" class="form-horizontal" ng-submit="newOrganisation.$valid && save()">
                                             <fieldset>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <input type="text" class="form-control" ng-model="org.name" placeholder="<?= lang('organisation_name') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <input type="text" class="form-control" ng-model="org.shortname" placeholder="<?= lang('organisation_shortname') ?>" required> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.shortname_error">{{errors.shortname_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <input type="text" class="form-control" ng-model="org.website" placeholder="<?= lang('organisation_website') ?>"> 
                                                            <span class="help-block m-b-none text-danger" ng-show="errors.website_error">{{errors.website_error}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12">
                                                            <textarea class="form-control" ng-model="org.description" rows="5" placeholder="<?= lang('organisation_description') ?>"></textarea>
                                                            <span class="help-block m-b-none"></span>
                                                       </div>
                                                  </div>
                                                  <div class="form-group">
                                                       <div class="col-sm-12 text-left">
                                                            <button type="submit" class="btn btn-primary"><?= lang('create_organisation_save') ?></button>
                                                            <button type="reset" class="btn btn-default" ng-click="cancel_create_org()"><?= lang('create_organisation_cancel') ?></button>
                                                       </div>
                                                  </div>
                                             </fieldset>
                                        </form>
                                   </div>
                              </div>
                              <!-- ends angular popup -->
                              <div class="clearfix"></div>
                              <div ng-show="show_table">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="orglistdata.perpage"  
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
                                                            <input ng-change="findorg()" aria-controls="organisation_list"  class="form-control input-sm" type="search" ng-model="orglistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                             <div class="no-padding table_filter" style="display: inline-flex;">
                                                  <span ng-click="create_org()" class="btn btn-sm btn-info"><?= lang('new_organisation') ?></span>
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
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.org_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.org_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.org_name.reverse == false}" class="pull-right" ng-click="sort('org_name')"></i>  
                                                       </th>
                                                       <th><?= lang('organisation_shortname') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.org_shortname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.org_shortname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.org_shortname.reverse == false}" class="pull-right" ng-click="sort('org_shortname')"></i>  
                                                       </th>
                                                       <th style="width:40%"><?= lang('organisation_description') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.Description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.Description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.Description.reverse == false}" class="pull-right" ng-click="sort('Description')"></i>  
                                                       </th>
                                                       <th><?= lang('organisation_website') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.Website.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.Website.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.Website.reverse == false}" class="pull-right" ng-click="sort('Website')"></i>  
                                                       </th>
                                                       <th><?= lang('organisation_admins') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.Adminusers.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.Adminusers.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.Adminusers.reverse == false}" class="pull-right" ng-click="sort('Adminusers')"></i>   
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="org in orglist|orderBy:orderByField:reverseSort">
                                                       <td><a ui-sref="organisation.{{org.role_id}}({id:org.id,flag:0})" class="link_color"> {{org.org_name}}</a></td>
                                                       <td>{{org.org_shortname}}</td>
                                                       <td>{{org.Description}}</td>
                                                       <td>{{org.Website}}</td>
                                                       <td>{{org.Adminusers}}</td>
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="5"><?= lang('nothing_to_display') ?></td>
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
                                                  page="orglistdata.currentPage" 
                                                  page-size="orglistdata.perpage_value" 
                                                  total="orglistdata.total"
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
                              <div ng-show="show_init">
                                   <div class="init_div">
                                        <h2><?= lang('init_new_org') ?></h2>
                                        <p><?= lang('init_new_org_info') ?></p>
                                        <span ng-click="create_org()" class="btn btn-lg btn-info"><?= lang('new_organisation') ?></span>

                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
