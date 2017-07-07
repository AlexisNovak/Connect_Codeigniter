<div class="content">
    <div  animate-panel>
        <div class="row">
            <div class="col-lg-12 no-padding">
                <div class="col-lg-12">
                    <div class="hpanel">
                        <div class="panel-body" ng-controller="viewDriver">
                            <div class="form-holder">
                                <div class="col-sm-12" style="padding:0">
                                    <h3 class="order_title">{{driverdata.name}}
                                        <span class="pull-right">
                                            <button type="submit" class="btn btn-danger" ng-click="deleteDriver(driverdata.id)">Delete</button>
                                            <button type="submit" class="btn btn-primary" ng-click="saveDriver(driverdata.id)">Save</button>
                                        </span>
                                    </h3>

                                </div>
                                <div class="col-sm-4 driver_profile">
                                    <h3>Driver Profile
                                        <a ui-sref="update_driver({id:driverdata.id})"><i class="fa fa-pencil pull-right"></i></a></h3>
                                    <div class="clearfix"></div><br>
                                    <p>Driver Name</p>
                                    <h4>{{driverdata.name}}</h4>
                                    <div class="clearfix"></div><br>
                                    <p>Mobile Number</p>
                                    <h4>{{driverdata.country_code + " " + driverdata.mobile_number}}</h4>
                                    <div class="clearfix"></div><br>
                                    <p>Date of Birth</p>
                                    <h4>{{driverdata.birthday}}</h4>
                                    <div class="clearfix"></div><br>
                                    <p>Identification ID</p>
                                    <h4>{{driverdata.identification_id}}</h4>
                                    <div class="clearfix"></div><br>
                                    <p>Email</p>
                                    <h4>{{driverdata.email}}</h4>
                                    <div class="clearfix"></div><br>
                                    <p>Status</p>
                                    <select class="form-control" placeholder="Country Code" required>
                                        <option>{{driverdata.status == 1 ? 'Active' : 'Suspended'}}</option>
                                    </select>
                                    <div class="clearfix"></div><br>
                                </div>
                                <div class="col-sm-8 send_driver_app">
                                    <p class="order_title">Send the Driver App to your Drivers
                                        <span class="pull-right">
                                            <button type="submit" class="btn btn-info">Send App via Email</button>
                                            <button type="submit" class="btn btn-primary">Send App via SMS</button>&emsp;
                                            <i class="fa fa-close"></i>
                                        </span>
                                    </p>
                                    <div class="clearfix"></div>
                                    <div class="activity_log">
                                        <h4>Activity Log</h4>
                                        <p>Below are the list of activities done by Driver such as updating of delivery order status etc.</p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Timestamp
                                                        </th>
                                                        <th>Log</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>15 Jul 2016 04:20 PM</td>
                                                        <td>Courier accepted the order with a remark </td>
                                                    </tr>
                                                    <tr>
                                                        <td>15 Jul 2016 04:20 PM</td>
                                                        <td>Courier accepted the order with a remark </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br><br>
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
