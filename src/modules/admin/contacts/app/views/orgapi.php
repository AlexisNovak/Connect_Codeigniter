<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="apiCtrl">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12">
                         <div class="hpanel dark">
                              <?php if ($is_admin): ?>
                                     <div class="alert alert-custom" style="color: #222;">
                                          <span style="float: left;margin:10px;"> 
                                               <input ng-model="allow_api" icheck type="checkbox" ng-change="show_allow_api_confirm()">
                                          </span>  
                                          <p style="font-weight: 600;"><?= lang('o_api_info') ?></p>
                                          <p><?= lang('o_api_info_sub') ?></p>
                                     </div>
                                <?php endif; ?> 
                              <div class="panel-body" style="padding:20px !important;">
                                   <?php if ($is_admin): ?>
                                          <div class="col-lg-12 no-padding">
                                               <p class="api_sub_title"><?= lang('access_key') ?></p>
                                               <p class="key_wrap api_wraper" id="access_key">{{accesskey}}</p>
                                               <button type="button" class="btn btn-sm btn-logo-yellow y_btn_s" ng-click="show_reset_confirm()"><?= lang('reset_btn') ?></button>
                                          </div>

                                          <div class="angular_popup pull-right warning_box popup_mid" ng-show="show_confirm_popup"> 
                                               <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_allow_api_confirm()"></i></h3>
                                               <p class="text-center p-sm" ng-if="allow_api"><?= lang('enable_api_confirm') ?></p>
                                               <p class="text-center p-sm" ng-if="!allow_api"><?= lang('disable_api_confirm') ?></p>
                                               <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="proceed()" style=""><?= lang('yes') ?></span>
                                                    <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_allow_api_confirm()"><?= lang('no') ?></span>
                                               </div>
                                          </div>
                                          <div class="angular_popup pull-right warning_box popup_mid" ng-show="reset_confirm_popup"> 
                                               <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_reset_confirm()"></i></h3>
                                               <p class="text-center p-sm"><?= lang('reset_access_key') ?></p>
                                               <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="reset_access_key()" style=""><?= lang('yes') ?></span>
                                                    <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_reset_confirm()"><?= lang('no') ?></span>
                                               </div>
                                          </div>

                                     <?php endif; ?>               
                                   <div class="col-lg-12 no-padding">
                                        <p class="api_sub_title"><?= lang('base_url') ?></p>
                                        <p class="api_wraper" id="request-url"><?= base_url() . 'order/' . $org_id . '/create' ?></p>
                                   </div>
                                   <div class="col-lg-12 no-padding">

                                        <div class="col-xs-12 no-padding m-b-lg">

                                             <div class="col-xs-12 no-padding">
                                                  <h3>DESCRIPTION</h3>
                                                  <div>
                                                       <p>This API is used for add new delivery request to the 6connect system. This API requires a number of parameter.</p>
                                                       <h5>Parameters</h5>
                                                       <table class="table table-striped table-bordered">
                                                            <thead>
                                                                 <tr>
                                                                      <th>Name</th>
                                                                      <th>Description/Purpose</th>
                                                                      <th>Optional</th>
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <tr><td> access_key</td><td>Access key </td><td></td></tr>
                                                                 <tr><td> assigned_service</td><td>Service ID if its a direct request </td><td></td></tr>

                                                                 <tr><td> breadth</td><td>Breadth of the item, must be provided if consignment type is 'custom' </td><td>Yes</td></tr>

                                                                 <tr><td> collect_contactname</td><td>Contact name for collection </td><td></td></tr>

                                                                 <tr><td> collect_country</td><td>Country code for collection</td><td></td></tr>

                                                                 <tr><td> collect_date</td><td>Period of collection </td><td></td></tr>

                                                                 <tr><td> collect_email</td><td>Email address of collection contact </td><td>Yes</td></tr>

                                                                 <tr><td> collect_from_l1</td><td>Collection address (Unit No, Floor No. etc)</td><td></td></tr>

                                                                 <tr><td> collect_from_l2</td><td>Collection address (City/Town name etc)</td><td></td></tr>

                                                                 <tr><td> collect_phone</td><td>Phone number for collection</td><td></td></tr>

                                                                 <tr><td> collect_timezone</td><td>Timezone info of collection </td><td></td></tr>

                                                                 <tr><td> collection_zipcode</td><td>Zipcode for collection </td><td>Yes</td></tr>

                                                                 <tr><td> deadline</td><td>Tender deadline if its an request for bidding</td><td></td></tr>

                                                                 <tr><td> deliver_date</td><td>Delivery period</td><td></td></tr>

                                                                 <tr><td> delivery_address_l1</td><td>Delivery address (Unit No, Floor No. etc)</td><td></td></tr>

                                                                 <tr><td> delivery_address_l2</td><td>Delivery address (City/Town name etc)</td><td></td></tr>

                                                                 <tr><td> delivery_contactname</td><td>Contact name for delivery</td><td></td></tr>

                                                                 <tr><td> delivery_country</td><td>Country code for delivery</td><td></td></tr>

                                                                 <tr><td> delivery_email</td><td>Email address for delivery </td><td>Yes</td></tr>

                                                                 <tr><td> delivery_is_assign</td><td>true if assigned for bidding, false if its direct request</td><td></td></tr>

                                                                 <tr><td> delivery_is_notify</td><td>true if keep recipient aware of the status via email once it has been collected, else false</td><td></td></tr>

                                                                 <tr><td> delivery_phone</td><td>Phone number for delivery</td><td></td></tr>

                                                                 <tr><td> delivery_timezone</td><td>Timezone info of delivery</td><td></td></tr>

                                                                 <tr><td> delivery_zipcode</td><td>Zipcode for delivery  </td><td>Yes</td></tr>

                                                                 <tr><td> height</td><td>Height of the item, must be provided if consignment type is 'custom' </td><td>Yes</td></tr>

                                                                 <tr><td> is_bulk</td><td> Boolean to indicate whether dimensions are providing or not. </td><td></td></tr>

                                                                 <tr><td> length</td><td>Length of the item, must be provided if consignment type is 'custom' </td><td>Yes</td></tr>

                                                                 <tr><td> open_bid</td><td>true if it is for open bidding else false</td><td></td></tr>

                                                                 <tr><td> quantity</td><td>Quantity</td><td></td></tr>

                                                                 <tr><td> remarks</td><td>Remarks about the item </td><td>Yes</td></tr>

                                                                 <tr><td> type</td><td>Consignment type ID</td><td></td></tr>

                                                                 <tr><td> volume</td><td>Volume of the item, must be provided if consignment type is 'custom' </td><td>Yes</td></tr>

                                                                 <tr><td> weight</td><td>Weight of the item, must be provided if consignment type is 'custom' </td><td>Yes</td></tr>
                                                            </tbody>
                                                       </table>
                                                  </div>

                                             </div>

                                        </div>             


                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
