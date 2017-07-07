angular
        .module('6connect')
        .controller('paymentController', paymentController);

function paymentController($scope, $http, $state) {
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
     $scope.show_init = false;
     $scope.show_table = false;
     $scope.init_count = function () {
          $http.post(BASE_URL + 'app/organisation/get_total_org').success(function (data) {
               if (data.total !== undefined && data.total === 0) {
                    $scope.show_init = true;
                    $scope.show_table = false;
               } else {
                    $scope.show_table = true;
                    $scope.show_init = false;
               }
          });
     };
     $scope.init_count();
     $scope.paylistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.orgperpage[2], holder_type: '', account_type: ''};

     $scope.orderByField = 'org_id';
     $scope.reverseSort = false;
     $scope.getOrganisations = function (page) {
          $scope.paylistdata.currentPage = page;
          $http.post(BASE_URL + 'payment/paymethod_json', $scope.paylistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.paymentlist = data.payment_accounts;
                       $scope.paylistdata.total = data.total;
                       $scope.paylistdata.currentPage = data.page;
                  });
     };
     $scope.getOrganisations($scope.paylistdata.currentPage);
     $scope.perpagechange = function () {

          $scope.paylistdata.perpage_value = $scope.paylistdata.perpage.value;
          $scope.getOrganisations($scope.paylistdata.currentPage);
     };
     $scope.findpayments = function () {
          $scope.getOrganisations($scope.paylistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.orgheaders = {
               account_name: {},
               parent_name: {},
               holder_typename: {},
               credit: {},
               status: {},
               account_typename:{}
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



     $scope.org = {};
     $scope.success = false;
     $scope.create_org_form = false;
     $scope.errors = {};
     $scope.save = function () {
          $http.post(BASE_URL + 'app/organisation/save', $scope.org).success(function (data) {
               //  alert(JSON.stringify(data));
               if (data.status === 1) {
                    //   $location.url('organisation/' + data.result + '/');
                    $state.go('organisation.members', {id: data.result, flag: 1});
               } else if (data.status === 0) {
                    $scope.errors = data.result;
               }
          }).error(function (data) {
               alert(data);
          });
     };
     $scope.create_org = function () {

          $scope.create_org_form = true;
     };
     $scope.cancel_create_org = function () {

          $scope.create_org_form = false;
     };

     $scope.selected_payment_id = 0;
     $scope.activate_account = function (pay) {
          $scope.selected_payment_id = pay.account_id;
          $scope.DO_accept_warning_popup = true;
     };

     $scope.acceptPaymentType = function (selected_payment_id) {
          $http.post(BASE_URL + 'payment/accept_payment', {payment_id: selected_payment_id}).success(function (data) {
               //  alert(JSON.stringify(data));
               $scope.selected_payment_id = 0;
               $scope.DO_accept_warning_popup = false;
               $scope.getOrganisations($scope.paylistdata.currentPage);
          });
     };

     $scope.cancel_accept = function () {
          $scope.selected_payment_id = 0;
          $scope.DO_accept_warning_popup = false;
     }

}