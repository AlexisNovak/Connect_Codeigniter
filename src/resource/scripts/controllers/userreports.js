
angular
        .module('6connect')
        .controller('reportsCtrl', reportsCtrl)
        .controller('userperformCtrl', userperformCtrl);

function reportsCtrl($scope) {

}
function userperformCtrl($scope, $http, $stateParams, $state, $timeout) {
     $scope.uperform = {"type": "day", "org_id": $stateParams.id};
     $scope.performance = {};
     $scope.daytrend = {};
     $scope.weektrend = {};
     $scope.general = {};
     $scope.today = {};
     $scope.person = {};
     $scope.people = [];
     $scope.uperform.date = moment().subtract(7, 'days').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
     $http.post(BASE_URL + 'app/members/orgmemberslistwithgroup/', {"search": "", "org_id": $stateParams.id})
             .success(function (data) {
                  $scope.people = data;
             });
     $scope.setuperformtype = function (type) {
          $scope.uperform.type = type;
          $scope.drawuserperformance_change();
     };
     $scope.upConfig = {
          options: {
               chart: {
                    type: 'area'
               },
               title: {
                    text: 'User Usage Report'
               },
               subtitle: {
                    text: ''
               },
               xAxis: {
                    categories: [],
                    crosshair: false
               },
               yAxis: {
                    min: 0,
                    title: {
                         text: 'Deliveries '
                    }
               },
               tooltip: {
                    shared: true
               },
               credits: {
                    enabled: false
               },
               plotOptions: {
                    area: {
                         pointPadding: 0.2,
                         borderWidth: 0,
                         cursor: 'pointer'
                    }
               }},
          series: []
     };
     $scope.updayConfig = {
          options: {
               chart: {
                    type: 'column'
               },
               title: {
                    text: 'Order Placement Day Trend'
               },
               subtitle: {
                    text: ''
               },
               xAxis: {
                    categories: [],
                    crosshair: false
               },
               yAxis: {
                    min: 0,
                    title: {
                         text: 'Deliveries '
                    }
               },
               tooltip: {
                    shared: true
               },
               credits: {
                    enabled: false
               },
               plotOptions: {
                    column: {
                         pointPadding: 0.2,
                         borderWidth: 0,
                         cursor: 'pointer'
                    }
               }},
          series: []
     };
     $scope.upweekConfig = {
          options: {
               chart: {
                    type: 'column'
               },
               title: {
                    text: 'Order Placement Week Trend'
               },
               subtitle: {
                    text: ''
               },
               xAxis: {
                    categories: [],
                    crosshair: false
               },
               yAxis: {
                    min: 0,
                    title: {
                         text: 'Deliveries '
                    }
               },
               tooltip: {
                    shared: true
               },
               credits: {
                    enabled: false
               },
               plotOptions: {
                    column: {
                         pointPadding: 0.2,
                         borderWidth: 0,
                         cursor: 'pointer'
                    }
               }},
          series: []
     };
     $scope.polarData = {};
     $scope.piechartConfig = {
          options: {
               //This is the Main Highcharts chart config. Any Highchart options are valid here.
               //will be overriden by values specified below.
               chart: {
                    type: 'pie'
               },
               plotOptions: {
                    pie: {
                         allowPointSelect: true,
                         cursor: 'pointer',
                         dataLabels: {
                              enabled: false
                         },
                         showInLegend: true
                    }
               },
               tooltip: {
                    style: {
                         padding: 10,
                         fontWeight: 'bold'
                    }
               }
          },
          //The below properties are watched separately for changes.

          //Series object (optional) - a list of series using normal highcharts series options.
          series: [{
                    data: [100]
               }],
          //Title configuration (optional)
          title: {
               text: ''
          },
          //Boolean to control showng loading status on chart (optional)
          //Could be a string if you want to show specific loading text.
          loading: false,
          //Configuration for the xAxis (optional). Currently only one x axis can be dynamically controlled.
          //properties currentMin and currentMax provied 2-way binding to the chart's maximimum and minimum
          xAxis: {
               currentMin: 0,
               currentMax: 20,
               title: {text: 'values'}
          },
          //Whether to use HighStocks instead of HighCharts (optional). Defaults to false.
          useHighStocks: false,
          //size (optional) if left out the chart will default to size of the div or something sensible.
          size: {
               height: 300
          },
          //function (optional)
          func: function (chart) {
               //setup some logic for the chart
          }
     };
     $scope.drawuserperformance = function () {
          $http.post(BASE_URL + 'reports/user_reports/getuserperformancedata', {"user": $scope.uperform.user, "date": $scope.uperform.date, "type": $scope.uperform.type, "updateall": true, "org_id": $stateParams.id})
                  .success(function (data) {
                       $scope.performance = data.performance;
                       $scope.daytrend = data.daytrend;
                       $scope.weektrend = data.weektrend;
                       $scope.polar = data.general.services;
                       $scope.general = data.general;
                       $scope.servicebreakdown = data.servicebreakdown;
                       $scope.today = data.general.today;
                       $timeout(function () {
                            $scope.polarData = $scope.polar;
                            $scope.piechartConfig.series = [{
                                      name: "Deliveries",
                                      data: $scope.servicebreakdown
                                 }];
                            $scope.upConfig.options.xAxis.categories = $scope.performance.label;
                            $scope.upConfig.series = [
                                 {
                                      name: "Success Delivery",
                                      color: "rgba(98,202,49,.8)",
                                      data: $scope.performance.success
                                 }, {
                                      name: "Failed Delivery",
                                      color: "rgba(198,102,49,.8)",
                                      data: $scope.performance.failed
                                 }
                            ];                           
                            $scope.updayConfig.options.xAxis.categories = $scope.daytrend.label;
                            $scope.updayConfig.series = $scope.daytrend.datasets;
                            $scope.upweekConfig.options.xAxis.categories = $scope.weektrend.label;
                            $scope.upweekConfig.series = $scope.weektrend.datasets;
                       }, 1000);
                  });
     };
     $scope.drawuserperformance_change = function () {
          $http.post(BASE_URL + 'reports/user_reports/getuserperformancedata', {"user": $scope.uperform.user, "date": $scope.uperform.date, "type": $scope.uperform.type, "updateall": false, "org_id": $stateParams.id})
                  .success(function (data) {
                       $scope.performance = data.performance;
                       $timeout(function () {
                            $scope.upConfig.options.xAxis.categories = $scope.performance.label;
                            $scope.upConfig.series = [
                                 {
                                      name: "Success Delivery",
                                      color: "rgba(98,202,49,.8)",
                                      data: $scope.performance.success
                                 }, {
                                      name: "Failed Delivery",
                                      color: "rgba(198,102,49,.8)",
                                      data: $scope.performance.failed
                                 }
                            ];

                       }, 1000);
                  });
     };
     $scope.drawuserperformance();

}
;
