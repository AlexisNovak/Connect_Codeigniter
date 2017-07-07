<div ng-controller="overallperformCtrl" id="overallperform">
     <div class="panel panel-default">
          <div class="panel-heading">
               <h3><?= lang('overall_usage_report') ?></h3>
          </div>
          <div class="panel-body">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12 no-padding">
                         <div class="col-lg-4 no-padding">
                              <form action="<?= site_url('reports/overall_reports/get_overall_performance_pdf') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="date" ng-model="operform.date">
                                        <input type="text" name="org_id" ng-model="operform.org_id">
                                        <input type="text" name="type" ng-model="operform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?=  lang('export_pdf')?></button>
                              </form>
                              <form action="<?= site_url('reports/overall_reports/get_overall_performance_excel') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="date" ng-model="operform.date">
                                        <input type="text" name="org_id" ng-model="operform.org_id">
                                        <input type="text" name="type" ng-model="operform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?=  lang('export_excel')?></button>
                              </form>

                         </div>
                         <div class="col-lg-4 no-padding pull-right">
                              <input type="text" id="opreportdate_input" ng-model="operform.date" name="daterange" class="hidden" ng-change="drawoverallperformance()">
                              <div id="opreportdate" class="form-control" ng-class="{error:errors.deliver_date}" >
                                   <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                   <span></span> 
                              </div>
                              <script>
                                             $(function () {

                                                  $('#opreportdate span').html(moment().subtract(7, 'days').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY'));

                                                  $('#opreportdate').daterangepicker({
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
                                                       $('#opreportdate span').html(result);
                                                       $("#opreportdate_input").val(result);
                                                       $("#opreportdate_input").trigger('input');
                                                  });
                                             });
                              </script>
                         </div>
                    </div>
                    <div class="col-lg-12 no-padding">

                         <div class="col-lg-4 no-padding pull-right">
                              <div class="btn-group btn-group-justified">
                                    <span class="btn btn-default" ng-click="setoperformtype('hourly')" ng-class="{active:operform.type == 'hourly'}">Hourly</span>
                                   <span class="btn btn-default"  ng-click="setoperformtype('day')" ng-class="{active:operform.type == 'day'}">Day</span>
                                   <span class="btn btn-default"  ng-click="setoperformtype('week')" ng-class="{active:operform.type == 'week'}">Week</span>
                                   <span class="btn btn-default"  ng-click="setoperformtype('month')" ng-class="{active:operform.type == 'month'}">Month</span>
                              </div>
                         </div>
                    </div>
                     <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                         <highchart config="opConfig"></highchart>
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
                                        <td class="count">${{today.spendings || 0}}</td>
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
                              <div class="sb_title"><?= lang('groups_breakdown') ?></div>
                              <div class="col-lg-6 no-padding">
                                   <highchart config="grouppiechartConfig"></highchart>
                              </div>
                              <div class="col-lg-6 no-padding">
                                   <table class="table table-striped">
                                        <tr ng-repeat="group in grouppolarData">
                                             <td>{{group.label}}</td>
                                             <td>{{group.percentage}}%</td>
                                             <td>{{group.value}} deliveries</td>
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
                         <highchart config="opdayConfig"></highchart>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding margin_bottom_10">

                    <h3><?= lang('week_trend') ?></h3>
                    <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                         <highchart config="opweekConfig"></highchart>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

