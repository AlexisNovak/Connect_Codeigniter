<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12" ng-controller="holidayCtrl">
                    <style>
                         .daterangepicker.dropdown-menu{
                              z-index: 9000!important;
                         }
                    </style>
                    <!-- angular popup starts -->
                    <div class="angular_popup create_org pull-right"  ng-show="new_holiday_form">  
                         <h3><span >Add New Holiday</span>
                              <i class="fa fa-close pull-right" ng-click="cancel_new_holiday_form()"></i></h3>
                         <div class="form-holder">
                              <form name="newHoliday" class="form-horizontal" ng-submit="newHoliday.$valid && save_new_holiday()">
                                   <fieldset>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <input class="form-control" ng-model="new_holiday.name" placeholder="Name">
                                                  <span class="help-block m-b-none text-danger">{{errors.name}}</span>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="col-sm-12">
                                                  <input id="datepickerHoliday" class="form-control" ng-model="new_holiday.date" placeholder="Date">
                                                  <span class="help-block m-b-none text-danger">{{errors.date}}</span>
                                             </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="form-group">
                                             <div class="col-sm-12 text-left">
                                                  <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                                  <button type="reset" class="btn btn-default" ng-click="cancel_new_holiday_form()"><?= lang('cancel_btn') ?></button>
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

                                        <div class="no-padding table_filter" style="display: inline-flex;"><span ng-click="show_new_holiday_form()" class="btn btn-sm btn-info">Add New Holiday</span></div>
                                   </div>


                              </div>
                              <div class="clearfix"></div>
                              <div class="table-responsive">
                                   <table id="activity_list" class="table table-striped table-bordered ">
                                        <thead>
                                             <tr>
                                                  <th>Name</th>
                                                  <th>Date</th>
                                                  <th><?= lang('action') ?></th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="holi in holiday_list">

                                                  <td>{{holi.name}}</td>
                                                  <td>{{holi.date}}</td>
                                                  <td>
                                                       <span ng-click="show_delete_warning(holi.id)"><i class="fa fa-trash"></i></span>
                                                  </td>
                                             </tr>
                                             <tr class="no-data">
                                                  <td colspan="3"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                        <div class="angular_popup add_scheme pull-right warning_box" ng-show="delete_warning_popup"> 
                                             <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_delete_warning()"></i></h3>
                                             <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                             <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="delete_holiday()" style=""><?= lang('yes') ?></span>
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
