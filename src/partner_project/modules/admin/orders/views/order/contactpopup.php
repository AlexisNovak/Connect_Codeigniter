<!-- angular popup starts -->
<div class="angular_popup_overlay"  ng-show="my_contacts_popup">
     <div class="angular_popup create_contact pull-right" ng-controller="mycontactsCtrl">  
          <h3><?= lang("select_from_mycontacts") ?>
               <i class="fa fa-close pull-right" ng-click="cancel_contact_popup()"></i>
          </h3>
          <div class="form-holder">
               <div class=" col-sm-12">
                    <div class="pull-left">
                         <div  class="dataTables_length">
                              <form>
                                   <label>
                                        Show
                                        <select class="form-control input-sm"  name="perpage" ng-model="contactlistdata.perpage"  
                                                ng-options="contactperpages as contactperpages.label for contactperpages in contactperpage" ng-change="perpagechange()">
                                             <option style="display:none" value class>15</option>
                                        </select>
                                        entries
                                   </label>
                              </form>
                         </div>
                    </div>
                    <div class="pull-right">
                         <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
                              <form>
                                   <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                   <span class="no-padding " style="display: inline-flex;">
                                        <input ng-change="findcontact()" aria-controls="contact_list"  class="form-control input-sm" type="search" ng-model="contactlistdata.filter">
                                   </span>
                              </form>
                         </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="clr"></div>
                    <div class="table-responsive" ng-show="selected_contact.popup_for_d && recentlist.length">
                         <p class="sub_head"><?php echo lang('recent_conatclist_subhead'); ?></p>
                         <table id="myrecent_list" class="table table-hover table-bordered contact_list">
                              <thead>
                                   <tr>
                                        <th style="width:20%"><?= lang('contact_name') ?>
                                        </th>
                                        <th style="width:20%"><?= lang('contact_company_name') ?>
                                        </th>
                                        <th style="width:20%"><?= lang('contact_phone') ?>
                                        </th>
                                        <th style="width:40%"><?= lang('contact_address') ?>
                                        </th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <tr ng-repeat="recent in recentlist|orderBy:orderByField:reverseSort" ng-click="set_contact_temp(recent)">
                                        <td>{{recent.contact_name}}</td>
                                        <td>{{recent.company_name}}
                                             <span ng-if="recent.dept_name">({{recent.dept_name}})</span>
                                        </td>
                                        <td>{{recent.phone_number}}</td>
                                        <td>{{recent.address_line1 + ' ' + recent.address_line1}}, {{recent.postal_code + ', ' + recent.country_name}}</td>
                                   </tr>
                                   <tr class="no-data">
                                        <td colspan="4"><?= lang('nothing_to_display') ?></td>
                                   </tr>
                              </tbody>
                         </table>
                    </div>
                    <div class="clr"></div>
                    <div class="table-responsive">
                         <p class="sub_head"><?php echo lang('conatclist_subhead'); ?></p>
                         <table id="mycontact_list" class="table table-hover table-bordered contact_list">
                              <thead>
                                   <tr>
                                        <th style="width:20%"><?= lang('contact_name') ?>
                                        </th>
                                        <th style="width:20%"><?= lang('contact_company_name') ?>
                                        </th>
                                        <th style="width:20%"><?= lang('contact_phone') ?>
                                        </th>
                                        <th style="width:40%"><?= lang('contact_address') ?>
                                        </th>
                                   </tr>
                              </thead> <tbody>
                                   <tr ng-repeat="contact in contactlist|orderBy:orderByField:reverseSort" ng-click="set_contact_temp(contact)">
                                        <td>{{contact.contact_name}}</td>
                                        <td>{{contact.company_name}}
                                             <span ng-if="contact.dept_name">({{contact.dept_name}})</span>
                                        </td>
                                        <td>{{contact.phone_number}}</td>
                                        <td>{{contact.address_line1 + ' ' + contact.address_line1}}, {{contact.postal_code + ', ' + contact.country_name}}</td>
                                   </tr>
                                   <tr class="no-data">
                                        <td colspan="4"><?= lang('nothing_to_display') ?></td>
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
                                   page="contactlistdata.currentPage" 
                                   page-size="contactlistdata.perpage_value" 
                                   total="contactlistdata.total"
                                   adjacent="{{adjacent}}"
                                   dots="{{dots}}"
                                   scroll-top="{{scrollTop}}" 
                                   hide-if-empty="false"
                                   ul-class="{{ulClass}}"
                                   active-class="{{activeClass}}"
                                   disabled-class="{{disabledClass}}"
                                   show-prev-next="true"
                                   paging-action="getContacts(page)">
                              </paging> 
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>
<!-- ends angular popup -->