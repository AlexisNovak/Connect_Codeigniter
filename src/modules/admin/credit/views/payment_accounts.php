<div class="content">
     <div animate-panel>
          <div class="hpanel">
               <div class="panel-body" ng-controller="orgpaymentCtrl"> 
                    <!-- angular popup starts -->
                    <script>
                                   var ORGANISATIONID = "<?= $org_id ?>";
                    </script>
                    <div class="angular_popup_overlay"  ng-show="create_account_form">
                         <div class="angular_popup create_account pull-right">  
                              <h3><?= lang("create_new_account") ?><i class="fa fa-close pull-right" ng-click="cancel_create_account()"></i></h3>
                              <div class="form-holder inputpaymentClass">
                                   <form name="newAccount" class="form-horizontal" ng-submit=" save_account()">
                                        <fieldset>

                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <label>This application for credit account is for</label>
                                                       <select  ng-model="account.account_holder" ng-init="account.account_holder = ORGANISATIONID"  class="form-control " placeholder="">
                                                            <option value="">Personal Credit Account</option>
                                                            <option value="{{org.org_id}}"  ng-selected="{{org.org_id == ORGANISATIONID}}" ng-repeat="org in orglist">{{org.org_name}}</option>
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

                                                       <select  ng-model="account.country_code" ng-init="account.country_code = 'sg'"  class="form-control " placeholder="<?= lang('account_country') ?>" required>
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
                                                       <input type="text" class="form-control" ng-model="account.comments" placeholder="<?= lang('account_comments') ?>"> 
                                                       <span class="help-block m-b-none text-danger" ng-show="errors.comments">{{errors.comments}}</span>

                                                  </div>                                             
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12 text-left">
                                                       <button type="submit" class="btn btn-primary" ng-disabled="isDisabled"><?= lang('save_btn') ?></button>
                                                       <button type="button" class="btn btn-default" ng-click="cancel_create_account()"><?= lang('cancel_btn') ?></button>
                                                  </div>
                                             </div>
                                        </fieldset>
                                   </form>
                              </div>
                         </div>
                    </div>
                    <!-- ends angular popup -->
                    <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                         <div class="clearfix"></div>

                         <div class="pull-right no-padding">
                              <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
                                   <button type="button" class="btn btn-default">Credit Card Payment Method</button>
                              </div>
                              <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
                                   <button type="button" class="btn btn-default" ng-click="create_account()">Postpaid Account Payment Method</button>
                              </div>
                         </div>


                    </div>
                    <div class="clearfix"></div>

                    <div class="clr"></div>
                    <div class="table-responsive">
                         <table id="account_list" class="table table-striped table-bordered table-responsive">
                              <thead>
                                   <tr>
                                        <th><?= lang('payment_name') ?>
                                             <i ng-class="{'glyphicon glyphicon-sort':accountheaders.name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': accountheaders.name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': accountheaders.name.reverse == false}" class="pull-right" ng-click="sort('name')"></i>  
                                        </th> 
                                        <th><?= lang('payment_balance') ?>
                                             <i ng-class="{'glyphicon glyphicon-sort':accountheaders.balance.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': accountheaders.balance.reverse == true, 'glyphicon glyphicon-sort-by-attributes': accountheaders.balance.reverse == false}" class="pull-right" ng-click="sort('balance')"></i>  
                                        </th>

                                        <th><?= lang('payment_status') ?>
                                             <i ng-class="{'glyphicon glyphicon-sort':accountheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': accountheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': accountheaders.status.reverse == false}" class="pull-right" ng-click="sort('status')"></i>  
                                        </th>
                                        <th><?= lang('action') ?>
                                        </th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <tr ng-repeat="account in accountlist|orderBy:orderByField:reverseSort">
                                        <td>
                                             <a ui-sref="accounts.view_account({account_id:account.account_id})" title="update" class="link_color">
                                                  {{account.account_name}}
                                             </a>
                                        </td>

                                        <td>SGD {{account.credit| number:2}}</td>
                                        <td>
                                             <span ng-if="account.status == 1" class="label label-info">We are processing your request</span>
                                             <span ng-if="account.status == 2" class="label label-success">Active</span>
                                        </td>                        
                                        <td>
                                             <span title="edit" ng-if="account.status == 1" ng-click="update_account(account)"><i  class="fa fa-edit"></i></span>
                                             <span title="delete" ng-if="account.status == 1" ng-click="show_delete_warning(account.account_id)"><i  class="fa fa-trash"></i></span>
                                        </td>
                                   </tr>
                                   <tr class="no-data">
                                        <td colspan="4"><?= lang('nothing_to_display') ?></td>
                                   </tr>
                              <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_delete_warning_popup"> 
                                   <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_delete_warning()"></i></h3>
                                   <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                   <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="delete_account()" style=""><?= lang('yes') ?></span>
                                        <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_delete_warning()"><?= lang('no') ?></span>
                                   </div>
                              </div>
                              </tbody>
                         </table>
                    </div>
                    <p><?= lang('payment_info') ?></p>
                    <div class="clearfix"></div>

                    <div class="clr"></div>
                    <div class="col-lg-12 no-padding m-t-lg">
                         <legend><?= lang('service_payment_terms') ?></legend>                                       
                         <div class="col-xs-12 col-sm-6">
                              <span style="float: left;margin:auto 10px;"> 
                                   <input type="checkbox" icheck ng-model="payment.credit"> 
                              </span>
                              <p><?= lang('credit_terms') ?></p>                                              

                         </div>
                         <!--<div class="col-xs-12 col-sm-6">-->
<!--                              <span style="float: left;margin:auto 10px;"> 
                                   <input type="checkbox" icheck ng-model="payment.credit_direct"> 
                              </span>
                              <p><?= lang('credit_terms_direct') ?></p> -->
                         <!--</div>-->
                         <div class="col-xs-12 col-sm-6">
                              <span style="float: left;margin:auto 10px;"> 
                                   <input type="checkbox" icheck ng-model="payment.sender"> 
                              </span>
                              <p><?= lang('cash_sender') ?></p> 
                         </div>
                         <div class="col-xs-12 col-sm-6">
                              <span style="float: left;margin:auto 10px;"> 
                                   <input type="checkbox" icheck ng-model="payment.recipient"> 
                              </span>
                              <p><?= lang('cash_recipient') ?></p> 
                         </div>
                         <div class="col-xs-12 text-right">
                              <button class="btn btn-info btn-sm" type="button" ng-click="update_payment()"><?= lang('save_btn') ?></button>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>