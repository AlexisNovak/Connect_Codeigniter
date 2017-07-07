<form name="new_order_p1" class="form-horizontal" ng-submit=" show_part_two()">
     <h3 class="order_title"><?= lang("multi_order_title") ?>
          <span class="pull-right">
               <span ng-if="org_dropdown">
                    <a ui-sref="delivery_orders" class="btn-cancel"><?= lang('cancel_btn') ?></a>
               </span>
               <span ng-if="!org_dropdown">
                    <a ui-sref="organisation.orders({id:org_id})" class="btn-cancel"><?= lang('cancel_btn') ?></a>

               </span>
               <button type="submit" class="btn btn-primary btn-sm">
                    <?= lang('next') ?>                                                  
               </button>         
          </span>
     </h3>   
     <legend>A. <?= lang('order_caption_c_d') ?></legend>
     <fieldset>
          <div class="col-sm-12 no-padding clearfix" style="position: relative;" >
               <div class="col-sm-6 no-padding">
                    <div class="a-box a-box-left">
                         <h2><?= lang('collect_from') ?><img src="<?php echo base_url(); ?>resource/images/address.jpg" class="mycontact" ng-click="show_contact_popup('collect')"></h2>
                         <div class="col-sm-12 address_box">
                              <div class="col-sm-12 no-padding">
                                   <div class="lock_info">
                                        <img src="<?php echo base_url(); ?>resource/images/lock.png" class="lock_img">
                                        <span>Fields or section with this icon will be displayed only to assigned or awarded courier</span>
                                   </div>
                              </div>
                              <div class="col-sm-12 no-padding">
                                   <div class="form-group">
                                        <input type="text" ng-model="neworder.collect_from_l2" class="form-control" ng-class="{error:errors.collect_from_l2}" placeholder="<?= lang('collect_add_l2') ?>" ng-autocomplete="mapc.result1" details="mapc.details1" options="mapc.options1" callback-fn="set_cpin()" required>
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_from_l2">{{errors.collect_from_l2}}</span>
                                   </div>
                              </div>
                              <div class="col-sm-12 no-padding">
                                   <div class="form-group">
                                        <input type="text" ng-model="neworder.collect_from_l1" class="form-control required" ng-class="{
                                                       error:errors.collect_from_l1
                                                  }" placeholder="<?= lang('collect_add_l1') ?>" required>
                                        <img src="<?php echo base_url(); ?>resource/images/lock.png" class="lock_img">
                                        <!-- <span class="help-block m-b-none m-t-none m-l-xs"><?= lang('order_add_l1_info') ?></span> -->
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_from_l1">{{errors.collect_from_l1}}</span>
                                   </div>
                              </div>


                              <div class="col-sm-6 no-padding">
                                   <div class="form-group">
                                        <input type="text"  ng-model="neworder.collection_zipcode" class="form-control" ng-class="{
                                                       error:errors.collection_zipcode
                                                  }" placeholder="<?= lang('collect_zipcode') ?>">
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collection_zipcode">{{errors.collection_zipcode}}</span>
                                   </div>
                              </div> 

                              <div class="col-sm-8 no-padding">
                                   <div class="form-group">
                                        <select  ng-model="neworder.collect_country" class="form-control " ng-class="{
                                                       error:errors.collect_country
                                                  }" placeholder="<?= lang('collect_country') ?>" ng-options="country.code as country.country for country in countrylist" ng-change="getCTimezones(true)" required>
                                             <option value="">--select country--</option>
                                        </select>
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_country">{{errors.collect_country}}</span>
                                   </div>
                                   <div class="clearfix chk"> 
                                        <span style="float: left;margin:5px;"> 
                                             <input ng-model="neworder.is_c_restricted_area" type="checkbox" icheck>
                                        </span> 
                                        <p><?= lang('collect_restrict') ?></p>
                                   </div>
                              </div> 

                              <p class="sub_h"><?= lang('contact_person') ?>

                                   <img src="<?php echo base_url(); ?>resource/images/lock.png" class="lock_img"></p>

                              <div class="col-sm-12 no-padding">
                                   <div class="form-group">
                                        <input type="text" class="form-control " ng-class="{
                                                       error:errors.collect_contactname
                                                  }" ng-model="neworder.collect_contactname" placeholder="<?= lang('contact_name') ?>" required>
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_contactname">{{errors.collect_contactname}}</span>
                                   </div>
                              </div>

                              <div class="col-sm-8 no-padding">
                                   <div class="form-group">
                                        <input type="text" class="form-control " ng-class="{
                                                       error:errors.collect_phone
                                                  }" ng-model="neworder.collect_phone"  placeholder="<?= lang('phone_number') ?>" required>
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_phone">{{errors.collect_phone}}</span>
                                   </div>
                              </div> 

                              <div class="col-sm-8 no-padding">
                                   <div class="form-group">
                                        <input type="email" class="form-control" name="collect_email" ng-class="{
                                                       error:errors.collect_email || new_order.collect_email.$invalid
                                                  }" ng-model="neworder.collect_email"  placeholder="<?= lang('order_email') ?>">
                                        <span class="help-block m-b-none text-danger" ng-show="errors.collect_email">{{errors.collect_email}}</span>
                                   </div>
                              </div> 
                              <div class="col-sm-8 no-padding">
                                   <div class="form-group">
                                        <input type="text" class="form-control" ng-model="neworder.collect_company"  placeholder="<?= lang('collection_company_name') ?>">
                                   </div>
                              </div>
                              <div class="col-sm-12 no-padding">
                                   <div class="form-group">
                                        <div class="clearfix chk">  <span style="float: left;margin:5px;"> 
                                                  <input ng-model="selected_contact.save_collect_contact" type="checkbox" icheck>
                                             </span> 
                                             <p><?= lang('save_contact_to_my_contact') ?></p>
                                        </div>
                                        <div class="adjust_height">                                       
                                        </div>
                                   </div>
                              </div>
                              <p class="sub_h"><?= lang('pickup_time') ?></p>
                              <p class="small-f"><?= lang('pickup_time_info') ?></p>
                              <div class="col-sm-12 no-padding">

                                   <div class="angular_popup collection_timezone_pop pull-right" draggable="true" ng-show="view_collect_timezone_list">  
                                        <h3><?= lang('timezone') ?>
                                             <i class="fa fa-close pull-right" ng-click="cancel_collection_timezone()"></i>
                                        </h3>
                                        <div class="form-holder">                                                                                
                                             <div class="form-group">
                                                  <div class="col-sm-12"> 
                                                       <select  class="form-control required" ng-model="neworder.collect_timezone" ng-change="cancel_collection_timezone()" ng-options="zone as zone.zoneinfo +' (' + zone.offset + ')' for zone in timezones track by zone.zoneinfo">

                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="form-group" ng-class="{
                                                  hidden:neworder.collect_country
                                             }">
                                        <div class="col-xs-12 col-sm-5 no-padding"> 
                                             <div class="form-control required datepicker">
                                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                  <span>Collection date & time</span> 
                                             </div> 
                                        </div>
                                        <div class="col-sm-1 hidden-xs dseparator">_</div>
                                        <div class="col-xs-12 col-sm-5 no-padding"> 
                                             <div class="form-control required datepicker">
                                                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                  <span>Collection date & time</span> 
                                             </div> 
                                        </div>
                                   </div>
                                   <div class="form-group" ng-class="{
                                                  hidden:!neworder.collect_country
                                             }">  
                                        <div class="col-xs-12 no-padding">
                                             <select ng-model="collect_shortcuts" class="form-control"  ng-change="setCRange()" ng-options="sc as sc.name for sc in csclist">
                                                  <option value=""><?= lang('custom-collection-period') ?></option>
                                             </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-5 no-padding"> 
                                             <label><?= lang('from') ?></label>
                                             <input type="text" id="collectdate1" ng-model="neworder.collect_date1" name="daterange" class="form-control o-datepicker" ng-disabled="collect_shortcuts"  ng-change="setCTime()" placeholder="<?= lang('collect_date') ?>"  required>

                                             <span class="help-block m-b-none text-danger" ng-show="errors.collect_date1">{{errors.collect_date1}}</span>
                                        </div>
                                        <div class="col-sm-1 hidden-xs dseparator">_</div>
                                        <div class="col-xs-12 col-sm-5 no-padding"> 
                                             <label><?= lang('to') ?></label>
                                             <input type="text" id="collectdate2" ng-model="neworder.collect_date2" name="daterange" class="form-control o-datepicker" ng-disabled="collect_shortcuts" ng-change="setCTime()" placeholder="<?= lang('collect_date') ?>"  required>
                                             <span class="help-block m-b-none text-danger" ng-show="errors.collect_date2">{{errors.collect_date2}}</span>
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="date_info"><?= lang('based_on') ?>
                                             <span class="timezone"> {{neworder.collect_timezone.zoneinfo + '(' + neworder.collect_timezone.offset + ')'}}</span>
                                             (<a class="change_timezone" ng-click="show_collection_timezone()" ><?= lang('change_timezone') ?></a>)
                                             <span class="m-b-none text-danger" ng-show="errors.collect_timezone">{{errors.collect_timezone}}</span>
                                        </p>
                                        <p class="date_info">The selected collection period is from <span class="timezone">{{neworder.cdate_convert|| 'XXX to YYY'}}</span> for the selected country</p>
                                   </div>
                              </div>                         
                         </div>
                    </div>
               </div>
               <div class="col-sm-6 no-padding">
                    <div class="a-box a-box-right">
                         <h2><?= lang('deliver_to') ?></h2>
                         <div class="col-sm-12 address_box">
                              <div class="col-sm-12 no-padding" style="visibility: hidden;">
                                   <div class="lock_info">
                                        <img src="<?php echo base_url(); ?>resource/images/lock.png" class="lock_img">
                                        <span>Fields or section with this icon will be displayed only to assigned or awarded courier</span>
                                   </div>
                              </div>
                              <div class="col-sm-12 no-padding">
                                   <div class="upload-h">
                                        <h3><?= lang('m_upload_title') ?></h3>
                                        <p><?= lang('m_upload_sub') ?><a class="link_color" href="<?php echo site_url('orders/multiorders/mass_consignment_template'); ?>">here</a></p>
                                   </div>
                                   <div class="col-sm-10 col-sm-offset-1 no-padding">
                                        <div class="form-group">
                                             <select ng-model="neworder.template_type" id="template_type" class="form-control">
                                                  <option value="1">6Connect default template</option>
                                                  <option value="2">Custom template</option>
                                             </select>
                                        </div>
                                   </div>
                                   <div class="col-sm-10 col-sm-offset-1 no-padding upload" id="morder_upload">
                                        <input type="text" ng-model="neworder.upload" id="uploaded_image" class="hidden" required>
                                        <div class="dropzone_wrap">
                                             <div class="dropzone" id="order_template">
                                             </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <span class="m-b-none text-danger" ng-show="errors.upload">{{errors.upload}}</span>
                                        <span class="m-b-none text-danger" id="upload_err"></span>                                       
                                   </div> 
                              </div> 
                              <div class="col-sm-10 col-sm-offset-1 no-padding verification">
                                   <div id="verify_block" style="display: none;">
                                        <h3><?= lang('verification') ?></h3>
                                        <p><?= lang('verfication_suc') ?></p>
                                        <p class="v_suc"><strong class="count"></strong> <?= lang('delveries_found') ?> </p>
                                        <p class="v_err" style="display: none;"><strong class="count"></strong> <span class="link"></span></p>
                                        <p class="v_custom" style="display: none;">Custom template found. ID: <strong class="count"></strong></p>
                                        <p><?= lang('error_log_proceed') ?></p>
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-10 col-sm-offset-1 no-padding">
                                   <div class=" get_quote">
                                        <h3><?= lang('getting_quote') ?></h3>
                                        <p><?= sprintf(lang('getting_quote_info'), '<a ui-sref="tender_requests.service.new_request">service tender</a>') ?></p>
                                        <p><?= lang('getting_quote_info_2') ?></p>
                                        <p><?= lang('thank_you') ?></p>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

     </fieldset>
     <div class="col-sm-12 no-padding margin_top_10">
          <h3 class="order_title">
               <span class="pull-right">
                    <span ng-if="org_dropdown">
                         <a ui-sref="delivery_orders" class="btn-cancel"><?= lang('cancel_btn') ?></a>
                    </span>
                    <span ng-if="!org_dropdown">
                         <a ui-sref="organisation.orders({id:org_id})" class="btn-cancel"><?= lang('cancel_btn') ?></a>

                    </span>
                    <button type="submit" class="btn btn-primary btn-sm">
                         <?= lang('next') ?>                                                  
                    </button>         
               </span>
          </h3>
     </div>
</form>
<?php
  $this->load->view('script_footer')?>