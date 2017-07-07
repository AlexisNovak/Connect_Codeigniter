<div class="content">
     <div animate-panel>
          <div class="hpanel">
               <div class="panel-body" ng-controller="singlepaymentCtrl"> 
                    <!-- angular popup starts -->
                    <script>
                                   var ORGANISATIONID = "<?= $account->account_holder ?>";
                                   var ACCOUNT_ID = "<?= $account->id ?>";
                    </script>
                    <div class="form-holder inputpaymentClass">
                         <form name="newAccount" class="form-horizontal" ng-submit="save_account()">
                              <h3 class="order_title">Payment Account Details
                                   <span class="pull-right">   
                                        <span class="btn-cancel" ng-click="cancel_create_account()"><?= lang('cancel_btn') ?></span> 
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a ui-sref="accounts.exporttransaction({account_id:account.id})"><span class="btn btn-primary">Export</span></a>
                                   </span>
                              </h3>
                              <fieldset>
                                   <div class="col-md-12">
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Account Type</label>
                                                  <select class="form-control" ng-model="account.account_type">
                                                       <option value="1">Pre-paid Account</option>
                                                       <option value="2">Post-paid Account</option>
                                                  </select>
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.account_type">{{errors.account_type}}</span>
                                             </div>                                             
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Account Status</label>
                                                  <select class="form-control" ng-model="account.status">
                                                       <option value="1">New</option>
                                                       <option value="2">Approved</option>
                                                  </select>
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.account_type">{{errors.account_type}}</span>
                                             </div>                                             
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Name</label>
                                                  <input type="text" class="form-control" ng-model="account.contact_name" placeholder="<?= lang('account_name') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.contact_name">{{errors.contact_name}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Contact No.</label>
                                                  <input type="text" class="form-control" ng-model="account.contact_number" placeholder="<?= lang('account_phone') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.contact_number">{{errors.contact_number}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Address 1</label>
                                                  <input type="text" class="form-control" ng-model="account.address_line1" placeholder="<?= lang('account_address_line1') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.address_line1">{{errors.address_line1}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Address 2</label>
                                                  <input type="text" class="form-control" ng-model="account.address_line2" placeholder="<?= lang('account_address_line2') ?>"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.address_line2">{{errors.address_line2}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>City</label>
                                                  <input type="city" class="form-control" ng-model="account.city" placeholder="<?= lang('account_city') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.city">{{errors.city}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-4">
                                                  <label>Pin Code</label>
                                                  <input type="text" class="form-control" ng-model="account.postal_code" placeholder="<?= lang('account_postal_code') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.postal_code">{{errors.postal_code}}</span>
                                             </div>
                                             <div class="col-sm-8">

                                                  <label>Country</label>
                                                  <select  ng-model="account.country_code" ng-init="account.country_code"  class="form-control " placeholder="<?= lang('account_country') ?>" required>
                                                       <option value="{{country.code}}" ng-repeat="country in countrylist">{{country.country}}</option>
                                                  </select>
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.country_code">{{errors.country_code}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Invoice Attention to</label>
                                                  <input type="text" class="form-control" ng-model="account.invoice_attention" placeholder="<?= lang('account_attention') ?>" required> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.invoice_attention">{{errors.invoice_attention}}</span>
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Company Reg. No.</label>
                                                  <input type="text" class="form-control" ng-model="account.company_reg_no" placeholder="<?= lang('account_reg_no') ?>"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.company_reg_no">{{errors.company_reg_no}}</span>
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Estimated No. of deliveries</label>
                                                  <input type="text" class="form-control" ng-model="account.deliveries_per_month" placeholder="<?= lang('account_deli_per_mnth') ?>"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.deliveries_per_month">{{errors.deliveries_per_month}}</span>
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Additional Comments</label>
                                                  <input type="text" class="form-control" ng-model="account.comments" placeholder="<?= lang('account_comments') ?>"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.comments">{{errors.comments}}</span>

                                             </div>                                             
                                        </div>
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Credit</label>
                                                  <input type="text" class="form-control" ng-model="account.credit" placeholder="Credit"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.credit">{{errors.credit}}</span>
                                             </div>                                             
                                        </div>
                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <label>Deposit</label>
                                                  <input type="text" class="form-control" ng-model="account.deposit" placeholder="Deposit"> 
                                                  <span class="help-block m-b-none text-danger" ng-show="errors.deposit">{{errors.deposit}}</span>
                                             </div>                                             
                                        </div>
                                   </div>
                              </fieldset>

                              <div class="clearfix"></div>
                              <br>
                              <br>

                              <h3 class="order_title">
                                   <span class="pull-right">   
                                        <span class="btn-cancel" ng-click="cancel_create_account()"><?= lang('cancel_btn') ?></span> 
                                        <button type="submit" class="btn btn-primary">Update</button>
                                   </span>
                              </h3>
                         </form>
                         <div class="clearfix"></div>
                    </div>
               </div>
          </div>
     </div>
</div>