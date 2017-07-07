<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>-->

                    <!-- angular popup starts -->
                    <div class="angular_popup_overlay"  ng-show="create_contact_form">
                         <div class="angular_popup create_contact pull-right">  
                              <h3><?= lang("create_new_contact") ?><i class="fa fa-close pull-right" ng-click="cancel_create_contact()"></i></h3>
                              <div class="form-holder">
                                   <form name="newContact" class="form-horizontal" ng-submit="newContact.$valid && save()">
                                        <fieldset>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="text" class="form-control" ng-model="contact.contact_name" placeholder="<?= lang('contact_name') ?>" required> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.contact_name">{{errors.contact_name}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="text" class="form-control" ng-model="contact.phone_number" placeholder="<?= lang('contact_phone') ?>" required> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.phone_number">{{errors.phone_number}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="email" class="form-control" ng-model="contact.email" placeholder="<?= lang('contact_email') ?>"> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.email">{{errors.email}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-6">
                                                       <input type="text" class="form-control" ng-model="contact.company_name" placeholder="<?= lang('contact_company_name') ?>"> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.company_name">{{errors.company_name}}</span>
                                                  </div>
                                                  <div class="col-sm-6">
                                                       <input type="text" class="form-control" ng-model="contact.dept_name" placeholder="<?= lang('contact_dept_name') ?>"> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.dept_name">{{errors.dept_name}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="text" class="form-control" ng-model="contact.address_line2" googleplace placeholder="<?= lang('contact_address_line2') ?>"> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.address_line2">{{errors.address_line2}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="text" class="form-control" ng-model="contact.address_line1"  placeholder="<?= lang('contact_address_line1') ?>" required> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.address_line1">{{errors.address_line1}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-4">
                                                       <input type="text" class="form-control" ng-model="contact.postal_code" placeholder="<?= lang('contact_postal_code') ?>" required> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.postal_code">{{errors.postal_code}}</span>
                                                  </div>
                                                  <div class="col-sm-8" id="countryid">
                                                       <select  ng-model="contact.country_code" class="form-control " placeholder="<?= lang('contact_country') ?>" ng-options="country.code as country.country for country in countrylist" required>
                                                            <option value="">--select country--</option>
                                                       </select>
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.country_code">{{errors.country_code}}</span>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <input type="checkbox" icheck ng-model="contact.share_contact" style="margin-right: 5px;" ng-change='show_orgs()'> <?= lang('contact_share_info') ?>
                                                       <div  style="margin-top:10px;" ng-show="show_org_list">                                                     
                                                            <ui-select multiple ng-model="contact.orgs" theme="bootstrap" ng-disabled="disabled">
                                                                 <ui-select-match placeholder="Select organisations">{{$item.org_name}}</ui-select-match>
                                                                 <ui-select-choices repeat="org in orglist | filter:$select.search">
                                                                      {{org.org_name}}
                                                                 </ui-select-choices>
                                                            </ui-select>
                                                       </div>      
                                                  </div>                                             
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12 text-left">
                                                       <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                                       <button type="button" class="btn btn-default" ng-click="cancel_create_contact()"><?= lang('cancel_btn') ?></button>
                                                  </div>
                                             </div>
                                        </fieldset>
                                   </form>
                              </div>
                         </div>
                    </div>
                    <!-- ends angular popup -->


                    <div class="hpanel">
                         <div class="panel-body">                             
                              <div ng-show="show_table">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">

                                        <div class="clearfix"></div>

                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="contactlistdata.perpage"  
                                                                    ng-options="contactperpages as contactperpages.label for contactperpages in contactperpage" ng-change="perpagechange()">
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
                                                            <input ng-change="findcontact()" aria-controls="contact_list"  class="form-control input-sm" type="search" ng-model="contactlistdata.filter">
                                                       </span>
                                                  </form>
                                             </div>
                                             <div class="no-padding table_filter" style="display: inline-flex;"><span ng-click="create_contact()" class="btn btn-sm btn-info"><?= lang('new_contact') ?></span></div>
                                        </div>


                                   </div>
                                   <div class="clearfix"></div>

                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="contact_list" class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                                                       <th><?= lang('contact_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':contactheaders.contact_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': contactheaders.contact_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': contactheaders.contact_name.reverse == false}" class="pull-right" ng-click="sort('contact_name')"></i>  
                                                       </th>
                                                       <th><?= lang('contact_company_name') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':contactheaders.company_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': contactheaders.company_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': contactheaders.company_name.reverse == false}" class="pull-right" ng-click="sort('company_name')"></i>  
                                                       </th>
                                                       <th><?= lang('contact_phone') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':contactheaders.phone_number.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': contactheaders.phone_number.reverse == true, 'glyphicon glyphicon-sort-by-attributes': contactheaders.phone_number.reverse == false}" class="pull-right" ng-click="sort('phone_number')"></i>  
                                                       </th>
                                                       <th style="width:40%"><?= lang('contact_address') ?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':contactheaders.address_line1.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': contactheaders.address_line1.reverse == true, 'glyphicon glyphicon-sort-by-attributes': contactheaders.address_line1.reverse == false}" class="pull-right" ng-click="sort('address_line1')"></i>  
                                                       </th>
                                                       <th><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="contact in contactlist|orderBy:orderByField:reverseSort">
                                                       <td>
                                                            <span ng-if="!contact.is_shared">
                                                                 <span ng-click="update(contact)" class="link_color">{{contact.contact_name}}
                                                                 </span>
                                                            </span>
                                                            <span ng-if="contact.is_shared">{{contact.contact_name}}
                                                            </span>
                                                       </td>
                                                       <td>{{contact.company_name}}
                                                            <span ng-if="contact.dept_name">({{contact.dept_name}})</span>
                                                       </td>
                                                       <td>{{contact.phone_number}}</td>
                                                       <td>{{contact.address_line1 + ' ' + contact.address_line2}}<br>{{contact.postal_code + ', ' + contact.country_name}}</td>
                                                       <td>
                                                            <span ng-if="contact.is_shared">
                                                                 <i class="fa fa-share-alt" title="Shared with you"></i>
                                                            </span>
                                                            <span ng-if="!contact.is_shared">
                                                                 <span ng-click="show_delete_warning(contact.id)"><i class="fa fa-trash"></i></span>

                                                            </span>
                                                            <span ng-click="sent_to_this(contact)" title="Sent something to {{contact.contact_name}}"><i class="fa fa-send-o"></i></span>
                                                            <span ng-click="collect_from_this(contact)" title="Collect something from {{contact.contact_name}}"><i class="fa fa-gift"></i></span>

                                                       </td>
                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="5"><?= lang('nothing_to_display') ?></td>
                                                  </tr>
                                             <div class="angular_popup add_scheme pull-right warning_box" ng-show="delete_warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_delete_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="delete()" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_delete_warning()"><?= lang('no') ?></span>
                                                  </div></div>
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
                              <div ng-show="show_init">
                                   <div class="init_div">
                                        <h2><?= lang('init_contacts') ?></h2>
                                        <p><?= lang('init_contacts_info') ?></p>
                                        <span ng-click="create_contact()" class="btn btn-lg btn-info"><?= lang('new_contact') ?></span>

                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>
