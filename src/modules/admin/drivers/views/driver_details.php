<style type="text/css">
    .nopadding{
        padding: 0;
    }
</style>
<div id="rightView" ng-hide="$state.current.name === 'drivers'">
    <div ui-view></div>
</div>
<div class="content" ng-show="$state.current.name === 'drivers'" ng-controller="driverCtrl">
    <div animate-panel>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="col-lg-12  no-padding"
                     style="background: #fff; border: 1px solid #E4E5E7;">
                    <div class="hpanel">
                        <div class="panel-body" style="border: none !important;">
                            <div class="">
                                <div class="col-lg-2 pull-right nopadding" style="margin-bottom:10px;">
                                    <a ui-sref="adddriver" class="btn btn-primary form-control">Add Driver</a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-lg-5 nopadding">
                                    <input type="text" name="driverSearch" ng-model="drvlistdata.filter" ng-change="getdrivers(1)" class="form-control " placeholder="Search by name and phone no." />
                                </div>
                                <div class="col-lg-2 pull-right nopadding">
                                    <select name="status" class="pull-right form-control" ng-change="getdrivers(1)" ng-model="drvlistdata.status">
                                        <option value="1" class="active">Active</option>
                                        <option value="0">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table id="drivers_list" class="table table-striped table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width:20%;"><?= lang('driver_name') ?>
                                            </th>
                                            <th style="width:15%;"><?= lang('driver_mobile_number') ?>
                                            </th>
                                            <th style="width:20%;"><?= lang('driver_email') ?>
                                            </th>
                                            <th style="width:10%;"><?= lang('driver_status') ?>
                                            </th>
                                            <th style="width:20%;"><?= lang('driver_last_active') ?>
                                            </th>
                                            <th style="width:15%;" ng-if="!org_id"><?= lang('driver_action') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="driverslist_body">
                                        <tr ng-repeat="driver in driverslist|orderBy:orderByField:reverseSort">
                                            <td> <a ui-sref="view_driver({id:driver.id})">{{driver.name}}</a> </td>
                                            <td>{{driver.mobile_number}}</td>
                                            <td>
                                                {{driver.email}}
                                            </td>
                                            <td>
                                                {{driver.status==1 ? 'Active' : 'Suspended'}}
                                            </td>
                                            <td>{{driver.last_active}}</td>
                                            <td style="text-align:right">
                                                <span class="" ng-if="driver.status == 1" ng-click="suspendDrivers(driver.id)">
                                                    <i class="fa fa-pause"></i>
                                                </span>
                                                <span class="" ng-if="driver.status == 0" ng-click="activateDrivers(driver.id)">
                                                    <i class="fa fa-play"></i>
                                                </span>
                                                <span class="text-danger" ng-click="deleteDrivers(driver.id)">
                                                    <i class="fa fa-trash"></i>
                                                </span>
                                            </td>                                                 
                                        </tr>                                       
                                        <tr class="no-data">
                                            <td colspan="8"><?= lang('nothing_to_display') ?></td>
                                        </tr>
                                    </tbody>
                                    <tbody id="drivers_loading" class="loading">
                                        <tr>                                                  
                                            <td colspan="8" class="text-center">
                                                <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 no-padding" >
                                <div class="col-md-8 pull-right no-padding">

                                    <paging
                                        class="small pull-right"
                                        page="drvlistdata.currentPage" 
                                        page-size="drvlistdata.perpage_value" 
                                        total="drvlistdata.total"
                                        adjacent="{{adjacent}}"
                                        dots="{{dots}}"
                                        scroll-top="{{scrollTop}}" 
                                        hide-if-empty="false"
                                        ul-class="{{ulClass}}"
                                        active-class="{{activeClass}}"
                                        disabled-class="{{disabledClass}}"
                                        show-prev-next="true"
                                        paging-action="getDrivers(page)">
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