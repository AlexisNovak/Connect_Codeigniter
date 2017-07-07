angular
        .module('6connect')
        .controller('overallperformCtrl', overallperformCtrl);

function overallperformCtrl($scope, $http, $stateParams, $state, $timeout) {
     $scope.operform = {"type": "day", "org_id": $stateParams.id};
     $scope.performance = {};
     $scope.daytrend = {};
     $scope.weektrend = {};
     $scope.general = {};
     $scope.today = {};
     $scope.group = {};
     $scope.groups = [];
     $scope.operform.date = moment().subtract(7, 'days').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
     $http.post(BASE_URL + 'app/groups/get_all_groups_org/' + $stateParams.id)
             .success(function (data) {
                  // alert(JSON.stringify(data));
                  $scope.groups = data;
             });
     $scope.setoperformtype = function (type) {
          $scope.operform.type = type;
          $scope.drawoverallperformance_change();
     }
     $scope.opConfig = {
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
     $scope.opdayConfig = {
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
     $scope.opweekConfig = {
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
     $scope.servicepiechartConfig = {
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
     $scope.grouppiechartConfig = {
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
     $scope.drawoverallperformance = function () {
          $http.post(BASE_URL + 'reports/overall_reports/getoverallperformancedata', {"group": $scope.operform.group, "date": $scope.operform.date, "type": $scope.operform.type, "updateall": true, "org_id": $stateParams.id})
                  .success(function (data) {
                       //alert(JSON.stringify(data));
                       $scope.performance = data.performance;
                       $scope.daytrend = data.daytrend;
                       $scope.weektrend = data.weektrend;
                       $scope.servicepolar = data.general.services;
                       $scope.grouppolar = data.general.groups;
                       $scope.general = data.general;
                       $scope.servicebreakdown = data.servicebreakdown;
                       $scope.groupbreakdown = data.groupbreakdown;
                       $scope.today = data.general.today;
                       $timeout(function () {
                            $scope.servicepolarData = $scope.servicepolar;
                            $scope.servicepiechartConfig.series = [{
                                      name: "Deliveries",
                                      data: $scope.servicebreakdown
                                 }];
                            $scope.grouppolarData = $scope.grouppolar;
                            $scope.grouppiechartConfig.series = [{
                                      name: "Deliveries",
                                      data: $scope.groupbreakdown
                                 }];
                            $scope.opConfig.options.xAxis.categories = $scope.performance.label;
                            $scope.opConfig.series = [
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

                            $scope.opdayConfig.options.xAxis.categories = $scope.daytrend.label;
                            $scope.opdayConfig.series = $scope.daytrend.datasets;
                            $scope.opweekConfig.options.xAxis.categories = $scope.weektrend.label;
                            $scope.opweekConfig.series = $scope.weektrend.datasets;



                       }, 1000);
                  });
     };
     $scope.drawoverallperformance_change = function () {
          $http.post(BASE_URL + 'reports/overall_reports/getoverallperformancedata', {"group": $scope.operform.group, "date": $scope.operform.date, "type": $scope.operform.type, "updateall": false, "org_id": $stateParams.id})
                  .success(function (data) {
                       //alert(JSON.stringify(data));
                       $scope.performance = data.performance;
                       $timeout(function () {
                            $scope.opConfig.options.xAxis.categories = $scope.performance.label;
                            $scope.opConfig.series = [
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
     $scope.drawoverallperformance();
}
;
