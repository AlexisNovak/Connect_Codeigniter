angular
        .module('6connect')
        .controller('courierorgCtrl', courierorgCtrl);

function courierorgCtrl($scope, $http) {
    $scope.orgperpage = [{
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
  $scope.orglistdata = {perpage_value: 15, currentPage: 1, total: 0,perpage:$scope.orgperpage[2]};
    
     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.getOrganisations = function (page) {
          $scope.orglistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/associates/associate_org_detail_list', $scope.orglistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.orglist = data.organisations;
                       $scope.orglistdata.total = data.total;
                       $scope.orglistdata.currentPage = data.page;
                  });
     };
     $scope.getOrganisations($scope.orglistdata.currentPage);
     $scope.perpagechange = function () {

          $scope.orglistdata.perpage_value = $scope.orglistdata.perpage.value;
          $scope.getOrganisations($scope.orglistdata.currentPage);
     };
     $scope.findorg = function () {
          $scope.getOrganisations($scope.orglistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.orgheaders = {
               org_name: {},
               org_shortname: {},
               Description: {},
               Website: {},
               preservices: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.orgheaders[column].reverse === undefined) {
               $scope.orgheaders[column].reverse = false;
          } else {
               $scope.orgheaders[column].reverse = !$scope.orgheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.orgheaders[column].reverse;
     };

}

