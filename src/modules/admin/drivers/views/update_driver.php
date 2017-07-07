<div class="content">
    <div  animate-panel>
        <div class="row">
            <div class="col-lg-12 no-padding">
                <div class="col-lg-12">
                    <div class="hpanel">
                        <div class="panel-body" ng-controller="updateDriver">
                            <div class="form-holder">
                                <form class="form-horizontal" name="new_driver_form" ng-submit="new_driver_form.$valid && save()" id="new_c_driver_form">
                                    <div class="col-sm-12" style="padding:0">
                                        <h3 class="order_title">Update Driver
                                            <span class="pull-right">
                                                <a ui-sref="view_driver({id:new_driver.id})"><button type="button" class="btn btn-default"><?= lang('cancel_btn') ?></button></a>
                                            </span>
                                            <span class="pull-right">
                                                <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                            </span>
                                        </h3>

                                    </div>
                                    <fieldset>

                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label"><?= lang('driver_name') ?></label>
                                                <input type="text" class="form-control" ng-model="new_driver.name"  placeholder="<?= lang('driver_name_ph') ?>" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label"><?= lang('passcode') ?></label>
                                                <input type="text" class="form-control" ng-model="new_driver.passcode"  placeholder="<?= lang('passcode_ph') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6" style="padding:0">
                                                <div class="col-sm-12">
                                                    <label class="control-label"><?= lang('mobile_number') ?></label>
                                                </div>
                                                <div class="col-xs-6 col-sm-3">
                                                    <select class="form-control" ng-model="new_driver.country_code" placeholder="Country Code" required>
                                                        <option  value="{{code.value}}" ng-selected="new_driver.country_code == code.value" ng-repeat="code in countrycodelist">{{code.value}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-6 col-sm-9">
                                                    <input type="text" class="form-control" ng-model="new_driver.mobile_number" placeholder="<?= lang('driver_mobile_number_ph') ?>" required />
                                                    <span class="text-danger" style="font-size: 12px;">{{error.mobile_number}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <div class="col-sm-6 no-padding">
                                                    <label class="control-label">Date of Birth</label>
                                                    <input type="text" id="collectdate2" ng-model="new_driver.d_o_b" name="daterange" class="form-control o-datepicker" ng-disabled="collect_shortcuts" ng-change="calculateAge()"> 
                                                </div> 
                                                <!-- <div class="col-sm-6">
                                                        <label class="control-label">&nbsp;</label>
                                                        <p><?= lang('driver_d_o_b_sample') ?>{{new_driver.age}}</p>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="control-label" style="text-align:left"><?= lang('driver_identification_id') ?></label>
                                                <input type="text" class="form-control" ng-model="new_driver.identification_id" placeholder="<?= lang('driver_id_ph') ?>">
                                            </div>
                                        </div>          
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="control-label"><?= lang('driver_email') ?></label>
                                                <input type="text" class="form-control" ng-model="new_driver.email" placeholder="<?= lang('driver_email_ph') ?>">
                                                <span class="text-danger" style="font-size: 12px;">{{error.driver_email}}</span>
                                            </div>
                                        </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
