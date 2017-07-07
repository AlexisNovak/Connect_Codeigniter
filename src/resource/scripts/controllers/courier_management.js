angular.module('6connect').controller('courierMgmtCtrl', courierMgmtCtrl)
        .controller('viewcourierCtrl', viewcourierCtrl)
        .controller('cservicesCtrl', cservicesCtrl)
        .controller('cordersCtrl', cordersCtrl);
function viewcourierCtrl($http, $scope, notify, $timeout, $stateParams, $state) {
     $scope.courier = {};
     $scope.errors = {};
     $scope.ratings = {};
     $scope.get_ratings = function () {
          $http.post(BASE_URL + 'courier_management/get_compliance_ratings', $scope.courierlistdata)
                  .success(function (data) {
                       if (data.ratings) {
                            $scope.ratings = data.ratings;
                       }
                  });
     };
     $scope.get_ratings();
     $scope.get_courier = function () {
          $http.post(BASE_URL + 'courier_management/get_courier_json/' + $stateParams.courier_id).success(function (data) {
               if (data !== "") {
                    $scope.courier = data;
               }
          });
     };
     $scope.get_courier();

     $scope.goback = function () {
          $state.go('couriers');
     };

     $scope.save = function () {
          $http.post(BASE_URL + 'courier_management/updateCourier/' + $stateParams.courier_id, $scope.courier).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.errors = {};
                    $state.go('^');
               } else if (data.errors) {
                    $scope.errors = data.errors;
               }
          }).error(function (data) {

          });
     };
     $scope.setbilling = function () {
          if ($scope.courier.same_addr) {
               $scope.courier.settings_billing_address = $scope.courier.settings_address;
          } else {
               $scope.courier.settings_billing_address = "";
          }
     };
}
;
function courierMgmtCtrl($scope, $http, notify, $timeout, $stateParams, $state) {
     $scope.approve_confirm_popup = false;
     $scope.courier_id = 0;
     $scope.courierperpage = [{
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
     $scope.courierlistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.courierperpage[2]};
     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.getCouriers = function (page) {
          $scope.courierlistdata.currentPage = page;
          $http.post(BASE_URL + 'courier_management/courierlist_json', $scope.courierlistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.courierlist = data.couriers;
                       $scope.courierlistdata.total = data.total;
                       $scope.courierlistdata.currentPage = data.page;
                  });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'couriers')
               $scope.getCouriers($scope.courierlistdata.currentPage);
     });
     $scope.courier = {"p_domain": ""};
     $scope.success = false;
     $scope.create_courier_form = false;
     $scope.errors = {};
     $scope.perpagechange = function () {

          $scope.courierlistdata.perpage_value = $scope.courierlistdata.perpage.value;
          $scope.getCouriers($scope.courierlistdata.currentPage);
     };
     $scope.findcourier = function () {
          $scope.getCouriers($scope.courierlistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.courierheaders = {
               courier_id: {},
               email: {},
               company_name: {},
               access_key: {},
               url: {},
               verified: {},
               approved: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.courierheaders[column].reverse === undefined) {
               $scope.courierheaders[column].reverse = false;
          } else {
               $scope.courierheaders[column].reverse = !$scope.courierheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.courierheaders[column].reverse;
     };
     $scope.show_approve_confirm = function (id) {
          $scope.courier_id = id;
          $scope.approve_confirm_popup = true;
     };
     $scope.cancel_approve = function (id) {
          $scope.approve_confirm_popup = false;
          $scope.courier_id = 0;
     };
     $scope.approve = function () {
          $http.post(BASE_URL + 'courier_management/approve', {courier_id: $scope.courier_id}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.cancel_approve();
                    $scope.getCouriers($scope.courierlistdata.currentPage);
               }
          });
     };
}
;
function cservicesCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.services = {};
     $scope.searchname = {};
     $scope.searchgrpname = {};
     $scope.servicesperpage = [{
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
     $scope.serviceslistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.servicesperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.getservices = function (page) {
          $scope.serviceslistdata.currentPage = page;
          $http.post(BASE_URL + 'courier_management/serviceslist_json/' + $stateParams.courier_id, $scope.serviceslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.serviceslist = data.service_detail;
                       $scope.serviceslistdata.total = data.total;
                       $scope.serviceslistdata.currentPage = data.page;
                       $scope.serviceslistdata.current_service_id = data.current_service_id;
                       //alert($scope.serviceslistdata.current_service_id);
                  });
     };
     $scope.getservices($scope.serviceslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.serviceslistdata.perpage_value = $scope.serviceslistdata.perpage.value;
          $scope.getservices($scope.serviceslistdata.currentPage);
     };
     $scope.findservices = function () {
          $scope.getservices(1);
     };
     $scope.servicelist_content = true;
     $scope.resetSort = function () {
          $scope.serviceheaders = {
               display_name: {},
               service_id: {},
               org_name: {},
               description: {},
               is_public: {},
               auto_approve: {},
               org_status: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.serviceheaders[column].reverse === undefined) {
               $scope.serviceheaders[column].reverse = false;
          } else {
               $scope.serviceheaders[column].reverse = !$scope.serviceheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.serviceheaders[column].reverse;
     }
}
function cordersCtrl($scope, $http, $stateParams, $state, notify) {
     $scope.$state = $state;

     $scope.orderslist = {};
     $scope.orglist = {};
     $scope.filter_servicelist = {};
     $scope.filter_statuslist = {};
     $scope.ordersperpage = [{
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

     $scope.orderslistdata = {perpage_value: 15, currentPage: 1, total: 0, organisation: "", "service": "all", "status": "all", perpage: $scope.ordersperpage[2]};

     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
          $scope.org_dropdown = false;
          $scope.orderslistdata.organisation = $stateParams.id;
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'courier_management/associate_orglist/' + $stateParams.courier_id).success(function (data) {
               $scope.orglist = data.organisations;
          });
     }
     $http.post(BASE_URL + 'courier_management/assigned_services/' + $stateParams.courier_id).success(function (data) {
          $scope.filter_servicelist = data.services;
     });
     $http.post(BASE_URL + 'orders/statusList').success(function (data) {
          $scope.filter_statuslist = data.status;
     });
     $scope.getOrders = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          $http.post(BASE_URL + 'courier_management/get_deliveries_json/' + $stateParams.courier_id, $scope.orderslistdata).success(function (data) {
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.orderslist = data.order_detail;
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               $scope.orderslistdata.total = data.total;
               $scope.orderslistdata.currentPage = data.page;
               $scope.orderslistdata.current_user_id = data.current_user_id;
               //alert($scope.orderslistdata.current_user_id);
          });
     };
     $scope.getOrders($scope.orderslistdata.currentPage);
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };
     $scope.findorders = function () {
          $scope.getOrders(1);
     };
     $scope.resetSort = function () {
          $scope.orderheaders = {
               public_id: {},
               private_id: {},
               username: {},
               service: {},
               collection_address: {},
               delivery_address: {},
               org_name: {},
               status: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.orderheaders[column].reverse === undefined) {
               $scope.orderheaders[column].reverse = false;
          } else {
               $scope.orderheaders[column].reverse = !$scope.orderheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.orderheaders[column].reverse;
     };




}
