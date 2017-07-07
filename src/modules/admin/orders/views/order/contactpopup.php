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
                                   <span class="no-padding table_filter" style="display: inline-flex;">
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
                                        <th style="width:20%">
                                             <?= lang('contact_name') ?>
                                        </th>
                                        <th style="width:20%">
                                             <?= lang('contact_company_name') ?>
                                        </th>
                                        <th style="width:20%">
                                             <?= lang('contact_phone') ?>
                                        </th>
                                        <th style="width:40%">
                                             <?= lang('contact_address') ?>
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
                                        <th style="width:20%">
                                             <?= lang('contact_name') ?>
                                        </th>
                                        <th style="width:20%">
                                             <?= lang('contact_company_name') ?>
                                        </th>
                                        <th style="width:20%">
                                             <?= lang('contact_phone') ?>
                                        </th>
                                        <th style="width:40%">
                                             <?= lang('contact_address') ?>
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


<!-- angular popup starts -->
<div class="angular_popup_overlay"  ng-show="create_account_form">
     <div class="angular_popup create_account pull-right">  
          <h3><?= lang("create_new_account") ?><i class="fa fa-close pull-right" ng-click="cancel_create_account()"></i></h3>
          <div class="form-holder inputpaymentClass">
               <form name="newAccount" class="form-horizontal" ng-submit=" save_account()">
                    <fieldset>

                         <div class="form-group">
                              <div class="col-sm-12">
                                   <label>This application for credit account is for</label>
                                   <select  ng-model="account.account_holder"  class="form-control " placeholder="">
                                        <option value="">Personal Credit Account</option>
                                        <option value="{{org.org_id}}" ng-repeat="org in orglist">{{org.org_name}}</option>
                                   </select>
                              </div>
                         </div>
                         <hr>

                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.account_name" placeholder="<?= lang('account_name') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.account_name">{{errors.account_name}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.phone_number" placeholder="<?= lang('account_phone') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.phone_number">{{errors.phone_number}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.address_line1" placeholder="<?= lang('account_address_line1') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.address_line1">{{errors.address_line1}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.address_line2" placeholder="<?= lang('account_address_line2') ?>"> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.address_line2">{{errors.address_line2}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="city" class="form-control" ng-model="account.city" placeholder="<?= lang('account_city') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.city">{{errors.city}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-4">
                                   <input type="text" class="form-control" ng-model="account.postal_code" placeholder="<?= lang('account_postal_code') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.postal_code">{{errors.postal_code}}</span>
                              </div>
                              <div class="col-sm-8">
                                   <select  ng-model="account.country_code"  class="form-control " placeholder="<?= lang('account_country') ?>" required>
                                        <option value="{{country.code}}" ng-repeat="country in countrylist">{{country.country}}</option>
                                   </select>
                                   <span class="help-block m-b-none text-danger" ng-show="errors.country_code">{{errors.country_code}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.attention" placeholder="<?= lang('account_attention') ?>" required> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.attention">{{errors.attention}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.reg_no" placeholder="<?= lang('account_reg_no') ?>"> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.reg_no">{{errors.reg_no}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <input type="text" class="form-control" ng-model="account.deli_per_mnth" placeholder="<?= lang('account_deli_per_mnth') ?>"> 
                                   <span class="help-block m-b-none text-danger" ng-show="errors.deli_per_mnth">{{errors.deli_per_mnth}}</span>
                              </div>
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12">
                                   <textarea  class="form-control" ng-model="account.comments" placeholder="<?= lang('account_comments') ?>"></textarea>
                                   <span class="help-block m-b-none text-danger" ng-show="errors.comments">{{errors.comments}}</span>

                              </div>                                             
                         </div>
                         <div class="form-group">
                              <div class="col-sm-12 text-left">
                                   <button type="submit" class="btn btn-primary" ng-disabled="isDisabled"><?= lang('submit_btn') ?></button>
                                   <button type="button" class="btn btn-default" ng-click="cancel_create_account()"><?= lang('cancel_btn') ?></button>
                              </div>
                         </div>
                    </fieldset>
               </form>
          </div>
     </div>
</div>
<!-- ends angular popup -->

<!-- angular popup starts -->
<div class="angular_popup_overlay"  ng-show="show_restrict_info" ng-click="view_restricted_info()">
     <div class="angular_popup restrict_info pull-right">  
          <h3>Info <i class="fa fa-close pull-right"></i></h3>
          <div class="form-holder p-sm">
               <p><strong>Additional charges</strong> will be added if the pick-up or drop-off location are in one of the following areas:</p>
               <ul class="sr_list">
                    <li>Airport Cargo Complex - Airline Road, Airport Cargo Road, Airport Logistics Park of Singapore(ALPS)</li>
                    <li>Airport Zone - Airport Police Station, SATS, Aerospaces</li>
                    <li>Camp - Army Camps, Police Base</li>
                    <li>Constuction Sites</li>
                    <li>Jurong Island</li>
                    <li>Jurong Port</li>
                    <li>Shipyards - Jurong Shipyard, Sembawang Shipyard</li>
                    <li>Supply Bases - Loyang Supply Base</li>
                    <li>International Schools e.g Singapore American School, Australian International School</li>
               </ul>
          </div>
     </div>
</div>
<!-- ends angular popup -->



<!-- angular popup starts -->
<div class="angular_popup_overlay"  ng-show="show_tuas_info" ng-click="view_tuas_info()">
     <div class="angular_popup restrict_info pull-right" style="min-height: 120px">  
          <h3>Info <i class="fa fa-close pull-right"></i></h3>
          <div class="form-holder p-sm">
               <p>Jalan Ahmad Ibrahim not included</p>
               
          </div>
     </div>
</div>
<!-- ends angular popup -->
