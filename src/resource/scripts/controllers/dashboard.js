angular
        .module('6connect')
        .controller('dashboardCtrl', dashboardCtrl);
function dashboardCtrl($scope, $http, phonebook,$rootScope) {

     $scope.show_tip = true;
     $scope.tip_of_the_day = "";
     phonebook.save_contact();
     $scope.orders = {};
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
                         showInLegend: false
                    }
               },
               tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span> : {point.y}<br/>',
                    style: {
                         padding: 10,
                         fontWeight: 'normal'
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
               text: 'Todays Deliveries'
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

     $http.post(BASE_URL + 'app/generalreport/statusbd').success(function (data) {
          $scope.piechartConfig.series = [{
                    name: "Deliveries",
                    colorByPoint: true,
                    data: data.statusbreakdown
               }];
     });
     $rootScope.$broadcast("refreshDashboard");
     $http.post(BASE_URL + 'orders/orderslist_json', {perpage_value: 5}).success(function (data) {
          $scope.orders = data.order_detail;
     });
     $http.post(BASE_URL + 'app/tip_of_the_day/get_tip_of_the_day').success(function (data) {
          $scope.tip_of_the_day = data.tip;
     });
     $scope.close_tip = function () {
          $scope.show_tip = false;
     };

}
;