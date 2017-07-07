<div class="content">
    <div  animate-panel>
        <div class="row">
             <script>
                  var ID=0;
             </script>
            <div class="col-lg-12" ng-controller="custParcelCtrl">
                <!-- angular popup starts -->
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="col-xs-12 no-padding">
                            <div class="pull-right no-padding">

                                 <div class="no-padding table_filter" style="display: inline-flex;"><a ui-sref="new_parcel_type"><span  class="btn btn-sm btn-info">New Parcel Type</span></a></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="activity_list" class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th> Organisation </th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="ptype in paymentTypelist">

                                        <td>{{ptype.display_name}}</td>
                                        <td>{{ptype.description}}</td>
                                        <td>{{ptype.name}}</td>
                                        <td>
                                            <span ui-sref="edit_parcel_type({type_id:ptype.consignment_type_id})"><i class="fa fa-edit"></i></span>
                                            <span ng-click="show_delete_warning(ptype.consignment_type_id)"><i class="fa fa-trash"></i></span>
                                        </td>
                                    </tr>
                                    <tr class="no-data">
                                        <td colspan="3"><?= lang('nothing_to_display') ?></td>
                                    </tr>
                                <div class="angular_popup add_scheme pull-right warning_box" ng-show="delete_warning_popup"> 
                                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_delete_warning()"></i></h3>
                                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="delete_type()" style=""><?= lang('yes') ?></span>
                                        <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_delete_warning()"><?= lang('no') ?></span>
                                    </div></div>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
