<div class="content">
    <div  animate-panel>
        <div class="row">
            <div class="col-lg-12" ng-controller="tipCtrl">
                <!-- angular popup starts -->
                <div class="angular_popup create_org pull-right"  ng-show="new_tip_form">  
                    <h3><span ng-show='!edit'><?= lang("create_new_tip") ?></span><span ng-show='edit'><?= lang("update_new_tip") ?></span>
                        <i class="fa fa-close pull-right" ng-click="cancel_new_tip_form()"></i></h3>
                    <div class="form-holder">
                        <form name="newTip" class="form-horizontal" ng-submit="newTip.$valid && save_new_tip()">
                            <fieldset>

                                <div class="form-group">

                                    <div class="col-sm-12">
                                        <textarea class="form-control" ng-model="new_tip.content" rows="5" placeholder="<?= lang('tip_content') ?>"></textarea>
                                        <span class="help-block m-b-none text-danger">{{errors.content}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 text-left">
                                        <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                        <button type="reset" class="btn btn-default" ng-click="cancel_new_tip_form()"><?= lang('cancel_btn') ?></button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <!-- ends angular popup -->

                <div class="hpanel">

                    <div class="panel-body">
                        <div class="col-xs-12 no-padding">
                            <div class="pull-right no-padding">

                                <div class="no-padding table_filter" style="display: inline-flex;"><span ng-click="show_new_tip_form()" class="btn btn-sm btn-info"><?= lang('new_tip') ?></span></div>
                            </div>


                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="activity_list" class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <!--<th><?= lang('tip_id') ?></th>-->
                                        <th><?= lang('tip_content') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="tip in tiplist">

                                        <!--<td>{{tip.id}}</td>-->
                                        <td>{{tip.content}}</td>
                                        <td>
                                            <span ng-click='edit_tip(tip)'><i class="fa fa-edit"></i></span>
                                            <span ng-click="show_delete_warning(tip.id)"><i class="fa fa-trash"></i></span>
                                        </td>
                                    </tr>
                                    <tr class="no-data">
                                        <td colspan="3"><?= lang('nothing_to_display') ?></td>
                                    </tr>
                                <div class="angular_popup add_scheme pull-right warning_box" ng-show="delete_warning_popup"> 
                                    <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_delete_warning()"></i></h3>
                                    <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                    <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="delete_tip()" style=""><?= lang('yes') ?></span>
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
