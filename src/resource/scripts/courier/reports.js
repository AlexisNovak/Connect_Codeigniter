angular
        .module('6connect')
        .controller('invoiceCtrl', invoiceCtrl);
function invoiceCtrl($http, $scope) {

}
;
function viewinvoiceCtrl($http, $scope, notify) {
     $scope.invoiceslist = {};
     $scope.invoicesperpage = [{
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
     $scope.invoiceslistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.invoicesperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getinvoices = function (page) {
          $scope.invoiceslistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/reports/invoiceslist_json/', $scope.invoiceslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.invoiceslist = data.invoices;
                       $scope.invoiceslistdata.total = data.total;
                       $scope.invoiceslistdata.currentPage = data.page;
                  });
     };
     $scope.getinvoices($scope.invoiceslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.invoiceslistdata.perpage_value = $scope.invoiceslistdata.perpage.value;
          $scope.getinvoices($scope.invoiceslistdata.currentPage);
     };
     $scope.findinvoices = function () {
          $scope.getinvoices(1);
     };
     $scope.resetSort = function () {
          $scope.invoiceheaders = {
               invoice_date: {},
               invoice_id: {},
               customer: {},
               period: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.invoiceheaders[column].reverse === undefined) {
               $scope.invoiceheaders[column].reverse = false;
          } else {
               $scope.invoiceheaders[column].reverse = !$scope.invoiceheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.invoiceheaders[column].reverse;
     }
     $scope.show_warning_popup = function (id) {
          $scope.delete_id = id;
          $scope.warning_popup = true;
     }
     $scope.cancel_warning = function () {
          $scope.delete_id = 0;
          $scope.warning_popup = false;
     }
     $scope.cancel_warning();
     $scope.invoice_delete = function () {
          $http.post(BASE_URL + 'couriers/reports/delete_invoice', {id: $scope.delete_id}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.cancel_warning();
                    $scope.getinvoices($scope.invoiceslistdata.currentPage);
               }
          });
     };
}
;