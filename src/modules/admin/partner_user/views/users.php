<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="pusersCtrl">
               <div class="col-lg-12 no-padding">   
                    <div class="col-lg-12">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">                                     
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
                                        </div>

                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="members_list"   class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th style="width:20%"><?= lang('p_user_email') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.email.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.email.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.email.reverse == false}" class="pull-right" ng-click="sort('email')"></i> 
                                                       </th>
                                                       <th style="width:20%"><?= lang('p_user_fullname') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.fullname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.fullname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.fullname.reverse == false}" class="pull-right" ng-click="sort('fullname')"></i> 
                                                       </th>
                                                       <th><?= lang('p_user_bio') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.description.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.description.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.description.reverse == false}" class="pull-right" ng-click="sort('description')"></i>  
                                                       </th>
                                                       <th><?= lang('p_user_phone') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.phone.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.phone.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.phone.reverse == false}" class="pull-right" ng-click="sort('phone')"></i>    
                                                       </th>
                                                       <th><?= lang('p_user_fax') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.fax.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.fax.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.fax.reverse == false}" class="pull-right" ng-click="sort('fax')"></i>
                                                       </th>
                                                       <th><?= lang('p_user_country') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':memberheaders.country.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': memberheaders.country.reverse == true, 'glyphicon glyphicon-sort-by-attributes': memberheaders.country.reverse == false}" class="pull-right" ng-click="sort('country')"></i>
                                                       </th>                                                       
                                                  </tr>
                                             </thead>
                                             <tbody id="orgmembers_body">
                                                  <tr ng-repeat="user in userslist|orderBy:orderByField:reverseSort">
                                                       <td>{{user.email}}</td>
                                                       <td>{{user.fullname}}</td>
                                                       <td>{{user.description}}</td>
                                                       <td>{{user.phone}}</td>
                                                       <td>{{user.fax}}</td>
                                                       <td>{{user.country}}</td>
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
</div>