<div ng-controller="groupperformCtrl" id="groupperform">
     <div class="panel panel-default">
          <div class="panel-heading">
               <h3><?= lang('group_usage_report') ?></h3>
          </div>
          <div class="panel-body">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12 no-padding">
                         <div class="col-lg-4 no-padding">
                              <ui-select ng-model="gperform.group" theme="bootstrap" ng-disabled="disabled" ng-change="drawgroupperformance()" required>
                                   <ui-select-match placeholder="All Groups" allow-clear="true">{{$select.selected.group_name}}</ui-select-match>
                                   <ui-select-choices repeat="group in groups | filter: {group_name: $select.search}">
                                        <div class="select_mem" ng-bind-html="group.group_name | highlight: $select.search"></div>                             
                                   </ui-select-choices>
                              </ui-select>    

                         </div>
                         <div class="col-lg-4 no-padding text-center">
                              <form action="<?= site_url('reports/group_reports/get_group_performance_pdf') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="group" ng-model="gperform.group.group_id">
                                        <input type="text" name="groupname" ng-model="gperform.group.group_name">
                                        <input type="text" name="date" ng-model="gperform.date">
                                        <input type="text" name="org_id" ng-model="gperform.org_id">
                                        <input type="text" name="type" ng-model="gperform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?= lang('export_pdf') ?></button>
                              </form>
                              <form action="<?= site_url('reports/group_reports/get_group_performance_excel') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="group" ng-model="gperform.group.group_id">
                                        <input type="text" name="groupname" ng-model="gperform.group.group_name">
                                        <input type="text" name="date" ng-model="gperform.date">
                                        <input type="text" name="org_id" ng-model="gperform.org_id">
                                        <input type="text" name="type" ng-model="gperform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?= lang('export_excel') ?></button>
                              </form>

                         </div>
                         <div class="col-lg-4 pull-right no-padding">
                              <input type="text" id="gpreportdate_input" ng-model="gperform.date" name="daterange" class="hidden" ng-change="drawgroupperformance()">
                              <div id="gpreportdate" class="form-control" ng-class="{error:errors.deliver_date}" >
                                   <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                   <span></span> 
                              </div>
                              <script>
                                             $(function () {

                                                  $('#gpreportdate span').html(moment().subtract(7, 'days').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY'));
                                                  $('#gpreportdate').daterangepicker({
                                                       timePicker: false,
                                                       format: 'MM/DD/YYYY',
                                                       startDate: moment().subtract(7, 'days'),
                                                       endDate: moment(),
                                                       minDate: '01/01/2012',
                                                       maxDate: moment().format('MM/DD/YYYY'),
                                                       timePickerIncrement: 30,
                                                       timePicker12Hour: true,
                                                       timePickerSeconds: false

                                                  }, function (start, end, label) {
                                                       var result = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
                                                       $('#gpreportdate span').html(result);
                                                       $("#gpreportdate_input").val(result);
                                                       $("#gpreportdate_input").trigger('input');
                                                  });
                                             });</script>
                         </div>
                    </div>
                    <div class="col-lg-12 no-padding">

                         <div class="col-lg-4 no-padding pull-right">
                              <div class="btn-group btn-group-justified">
                                   <span class="btn btn-default" ng-click="setgperformtype('hourly')" ng-class="{active:gperform.type == 'hourly'}">Hourly</span>
                                   <span class="btn btn-default"  ng-click="setgperformtype('day')" ng-class="{active:gperform.type == 'day'}">Day</span>
                                   <span class="btn btn-default"  ng-click="setgperformtype('week')" ng-class="{active:gperform.type == 'week'}">Week</span>
                                   <span class="btn btn-default"  ng-click="setgperformtype('month')" ng-class="{active:gperform.type == 'month'}">Month</span>
                              </div>
                         </div>
                    </div>
                    <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                              <highchart config="gpConfig"></highchart>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-4 col-md-6 col-xs-12 no-padding">
                         <div class="today_wrap">
                              <table>
                                   <tr>
                                        <td>
                                             <span class="head">Deliveries</span>
                                             <span>Today's deliveries</span>
                                        </td>
                                        <td class="count">{{today.total}}</td>
                                   </tr>
                              </table>
                         </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xs-12 no-padding">
                         <div class="today_wrap mid_wrap">
                              <table>
                                   <tr>
                                        <td>
                                             <span class="head">Failed</span>
                                             <span>Failed to deliver</span>
                                        </td>
                                        <td class="count">{{today.failed}}</td>
                                   </tr>
                              </table>
                         </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xs-12 no-padding">
                         <div class="today_wrap spending">
                              <table>
                                   <tr>
                                        <td>
                                             <span class="head">Spending</span>
                                             <span>Today's spending</span>
                                        </td>
                                        <td class="count">${{today.spendings|| 0}}</td>
                                   </tr>
                              </table>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding clearfix">

                    <div class="col-lg-6 no-padding">
                         <div class=" service_break service_break_left">
                              <div class="sb_title"><?= lang('services_breakdown') ?></div>
                              <div class="col-lg-6 no-padding">
                                   <highchart config="servicepiechartConfig"></highchart>
                              </div>
                              <div class="col-lg-6 no-padding">
                                   <table class="table table-striped">
                                        <tr ng-repeat="service in servicepolarData">
                                             <td>{{service.label}}</td>
                                             <td>{{service.percentage}}%</td>
                                             <td>{{service.value}} deliveries</td>
                                        </tr>
                                   </table>
                              </div>
                         </div>
                    </div>
                    <div class="col-lg-6 no-padding">
                         <div class=" service_break service_break_right">
                              <div class="sb_title"><?= lang('users_breakdown') ?></div>
                              <div class="col-lg-6 no-padding">
                                   <highchart config="userspiechartConfig"></highchart>
                              </div>
                              <div class="col-lg-6 no-padding">
                                   <table class="table table-striped">
                                        <tr ng-repeat="users in userspolarData">
                                             <td>{{users.label}}</td>
                                             <td>{{users.percentage}}%</td>
                                             <td>{{users.value}} deliveries</td>
                                        </tr>
                                   </table>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding margin_bottom_10">

                    <h3><?= lang('day_trend') ?></h3>
                    <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                              <highchart config="gpdayConfig"></highchart>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding margin_bottom_10">

                    <h3><?= lang('week_trend') ?></h3>
                    <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                              <highchart config="gpweekConfig"></highchart>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
