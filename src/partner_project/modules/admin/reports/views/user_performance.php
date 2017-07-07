<div ng-controller="userperformCtrl" id="userperform">
     <div class="panel panel-default">
          <div class="panel-heading">
               <h3><?= lang('user_usage_report') ?></h3>
          </div>
          <div class="panel-body">

               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12 no-padding">
                         <div class="col-lg-4 no-padding">
                              <ui-select ng-model="uperform.user" theme="bootstrap" ng-disabled="disabled" ng-change="drawuserperformance()" required>
                                   <ui-select-match placeholder="All Users" allow-clear="true">{{$select.selected.Username}}</ui-select-match>
                                   <ui-select-choices repeat="person in people | filter: {Username: $select.search}" group-by="'groupname'">
                                        <div class="select_mem" ng-bind-html="person.Username+' ('+person.Email+')' | highlight: $select.search"></div>                             
                                   </ui-select-choices>
                              </ui-select>    

                         </div>
                         <div class="col-lg-4 no-padding text-center">
                              <form action="<?= site_url('reports/user_reports/get_user_performance_pdf') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="user" ng-model="uperform.user.Userid">
                                        <input type="text" name="username" ng-model="uperform.user.Username">
                                        <input type="text" name="date" ng-model="uperform.date">
                                        <input type="text" name="org_id" ng-model="uperform.org_id">
                                        <input type="text" name="type" ng-model="uperform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?=  lang('export_pdf')?></button>
                              </form>
                              <form action="<?= site_url('reports/user_reports/get_user_performance_excel') ?>" method="post" class="inline_form">
                                   <span class="hidden">
                                        <input type="text" name="user" ng-model="uperform.user.Userid">
                                        <input type="text" name="username" ng-model="uperform.user.Username">
                                        <input type="text" name="date" ng-model="uperform.date">
                                        <input type="text" name="org_id" ng-model="uperform.org_id">
                                        <input type="text" name="type" ng-model="uperform.type">
                                   </span>
                                   <button type="submit" class="btn btn-default"><?=  lang('export_excel')?></button>
                              </form>

                         </div>
                         <div class="col-lg-4 no-padding pull-right">
                              <input type="text" id="upreportdate_input" ng-model="uperform.date" name="daterange" class="hidden" ng-change="drawuserperformance()">
                              <div id="upreportdate" class="form-control" ng-class="{error:errors.deliver_date}" >
                                   <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                   <span></span> 
                              </div>
                              <script>
                                             $(function () {                                                  
                                                  $('#upreportdate span').html(moment().subtract(7, 'days').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY'));
                                                  $('#upreportdate').daterangepicker({
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
                                                       $('#upreportdate span').html(result);
                                                       $("#upreportdate_input").val(result);
                                                       $("#upreportdate_input").trigger('input');
                                                  });
                                             });</script>
                         </div>

                    </div>
                    <div class="col-lg-12 no-padding">

                         <div class="col-lg-4 no-padding pull-right">
                              <div class="btn-group btn-group-justified">
                                   <span class="btn btn-default" ng-click="setuperformtype('hourly')" ng-class="{active:uperform.type == 'hourly'}">Hourly</span>
                                   <span class="btn btn-default"  ng-click="setuperformtype('day')" ng-class="{active:uperform.type == 'day'}">Day</span>
                                   <span class="btn btn-default"  ng-click="setuperformtype('week')" ng-class="{active:uperform.type == 'week'}">Week</span>
                                   <span class="btn btn-default"  ng-click="setuperformtype('month')" ng-class="{active:uperform.type == 'month'}">Month</span>
                              </div>
                         </div>
                    </div>
                   
                      <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                         <highchart config="upConfig"></highchart>
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

                    <div class="col-lg-7 no-padding">
                         <div class="service_break">
                              <div class="sb_title"><?= lang('services_breakdown') ?></div>
                              <div class="col-lg-6 no-padding">
                                   <highchart config="piechartConfig"></highchart>
                              </div>
                              <div class="col-lg-6 no-padding">
                                   <table class="table table-striped">
                                        <tr ng-repeat="service in polarData">
                                             <td>{{service.label}}</td>
                                             <td>{{service.percentage}}%</td>
                                             <td>{{service.value}} deliveries</td>
                                        </tr>
                                   </table>
                              </div>
                         </div>
                    </div>
                    <div class="col-lg-5">
                         <table class="table table-condensed">
                              <tr>
                                   <td style="width: 60%">
                                        <span class="td_title">Total</span>
                                        <span class="td_sub">Total deliveries sent</span>
                                   </td>
                                   <td>
                                        <span class="td_value">{{general.total_delivery}}</span>
                                   </td>
                              </tr>
                              <tr>
                                   <td style="width: 60%">
                                        <span class="td_title">Average</span>
                                        <span class="td_sub">Average deliveries per day</span>
                                   </td>
                                   <td>
                                        <span class="td_value">{{general.average}}</span>
                                   </td>
                              </tr>
                              <tr>
                                   <td style="width:70%">
                                        <span class="td_title">Most Active Day</span>
                                        <span class="td_sub">Day with the most deliveries</span>
                                   </td>
                                   <td>
                                        <span class="td_value">{{general.active_day}}</span>
                                   </td>
                              </tr>
                              <tr>
                                   <td style="width: 70%">
                                        <span class="td_title">Active Week Day</span>
                                        <span class="td_sub">On average, the weekday that has the most # deliveries</span>
                                   </td>
                                   <td>
                                        <span class="td_value">{{general.active_week}}</span>
                                   </td>
                              </tr>
                         </table>
                    </div>
               </div>
               <div class="col-lg-12 no-padding margin_bottom_10">

                    <h3><?=  lang('day_trend')?></h3>
                    
                    <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                         <highchart config="updayConfig"></highchart>
                         </div>
                    </div>
               </div>
               <div class="col-lg-12 no-padding margin_bottom_10">

                    <h3><?=  lang('week_trend')?></h3>
<!--                    <div class="col-lg-12 no-padding chart_wrap">
                         <div>
                              <canvas barchart options="userPerformanceWeekOptions" data="userPerformanceWeekData" legend="true" height="200" responsive=true ></canvas>    
                         </div>
                    </div>-->
                     <div class="col-lg-12 no-padding chart_wraper">
                         <div>
                         <highchart config="upweekConfig"></highchart>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
