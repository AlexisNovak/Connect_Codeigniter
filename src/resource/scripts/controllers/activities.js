angular
        .module('6connect')
        .controller('ActivityCtrl', activityCtrl);


function activityCtrl($scope, $http, $stateParams) {
     $scope.activity = {};
     $scope.activityperpage = [{
               value: 5,
               label: 5
          }, {
               value: 10,
               label: 10
          }, {
               value: 15,
               label: 15}, {
               value: 20,
               label: 20}];
     $scope.activitylistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.activityperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getActivities = function (page) {
          $scope.activitylistdata.currentPage = page;
          $http.post(BASE_URL + 'app/activity/activitylist_json/' + $stateParams.id + "", $scope.activitylistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.activitylist = data.activities;
                       $scope.activitylistdata.total = data.total;
                       $scope.activitylistdata.currentPage = data.page;
                  });
     };
     $scope.getActivities($scope.activitylistdata.currentPage);


     $scope.perpagechange = function () {

          $scope.activitylistdata.perpage_value = $scope.activitylistdata.perpage.value;
          $scope.getActivities($scope.activitylistdata.currentPage);
     };
     $scope.findactivity = function () {
          $scope.getActivities($scope.activitylistdata.currentPage);
     };
     $scope.activitylist_content = true;
     $scope.activity_detail = function (activity_id) {
          $http({method: 'POST', url: BASE_URL + 'app/activity/get_detail', data: {"activity_id": activity_id, "org_id": $stateParams.id}}).success(function (data) {
               $scope.activitylist_content = false;
               $scope.activity_detail_page = true;
               $scope.activity = data.activity;
               // alert(JSON.stringify($scope.activity));

          }).error(function (data) {
               alert(data);
          });
     };
     $scope.detail_back = function () {
          $scope.activity_detail_page = false;
          $scope.activitylist_content = true;
     };

     $scope.resetSort = function () {
          $scope.activityheaders = {
               id: {},
               name: {},
               remark: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.activityheaders[column].reverse === undefined) {
               $scope.activityheaders[column].reverse = false;
          } else {
               $scope.activityheaders[column].reverse = !$scope.activityheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.activityheaders[column].reverse;
     };

}