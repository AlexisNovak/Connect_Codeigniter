angular.module('6connect').controller('avail_serviceCtrl', avail_serviceCtrl).controller('viewavail_serviceCtrl', viewavail_serviceCtrl).controller('editservicesCtrl', editservicesCtrl);
function avail_serviceCtrl($scope, $http, $rootScope, $stateParams, notify, $state, orderService) {
     $scope.serviceperpage = [{
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
     $scope.servicelistdata = {perpage_value: 15, currentPage: 1, total: 0, type: 'all', 'org_id': $stateParams.id, perpage: $scope.serviceperpage[2], sort: {field: 'org', direction: 'desc'}};

     $scope.typelist = [];
     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.adminorglist = {};
     $scope.orglist = {};
     $scope.default_admin_org = '';
     $scope.default_org = '';
     $scope.use_this_popup = false;
     $scope.proceed = false;

     orderService.consignmenttypes().then(function (response) {
          $scope.typelist = response.data.types;
     });

     $scope.getServices = function (page) {
          $scope.servicelistdata.currentPage = page;
          $http.post(BASE_URL + 'app/available_services/available_service_list', $scope.servicelistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.servicelist = data.services;
                       $scope.servicelistdata.total = data.total;
                       $scope.servicelistdata.currentPage = data.page;
                  });
     };

     $scope.getServices($scope.servicelistdata.currentPage);
     $scope.perpagechange = function () {

          $scope.servicelistdata.perpage_value = $scope.servicelistdata.perpage.value;
          $scope.getServices($scope.servicelistdata.currentPage);
     };
     $scope.findservice = function () {
          $scope.getServices($scope.servicelistdata.currentPage);
     };
     $scope.show_use_this_popup = function (service_id, courier_id, org_id) {
          if ($scope.request_confirm_popup === true) {
               $scope.cancel_request();
          }
          $scope.service_id = service_id;
          $scope.courier_id = courier_id;
          $scope.org_id = org_id;
          $scope.use_this_service();
     };
     $scope.cancel_use_this_popup = function () {
          $scope.use_this_popup = false;
          $scope.no_org_popup = false;
          $scope.service_id = 0;
          $scope.courier_id = 0;
          $scope.isDisabled = false;
          $scope.errors = {};
          $scope.proceed = false;
          $scope.org_id = $scope.default_org;

     };

     $scope.resetSort = function () {
          $scope.serviceheaders = {
               service: {},
               courier: {},
               type: {},
               price: {},
               cutoff: {},
               days: {},
               description: {},
               org: {}
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
          $scope.servicelistdata.sort.field = column;
          $scope.servicelistdata.sort.direction = $scope.reverseSort ? 'desc' : 'asc';
          $scope.getServices($scope.servicelistdata.currentPage);
     };
     $http.post(BASE_URL + 'app/organisation/request_allowed_organisations').success(function (data) {
          if (data.any) {
               if (data.many) {
                    $scope.adminorglist = data.organisations;
                    $scope.default_admin_org = '';
               } else {
                    //  $scope.single_org = true;
                    $scope.adminorglist = data.organisations;
                    $scope.admin_org_id = data.org_id;
                    $scope.default_admin_org = data.org_id;
               }
          }
     });

     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
          if (data.any) {
               if (data.many) {
                    $scope.orglist = data.organisations;
                    $scope.default_admin_org = '';
               } else {
                    //  $scope.single_org = true;
                    $scope.orglist = data.organisations;
                    $scope.org_id = data.org_id;
                    $scope.default_org = data.org_id;
               }

          }
     });


     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };
     $scope.confirm_request_service = function (id, courier_id) {
          if ($scope.use_this_popup === true) {
               $scope.cancel_use_this_popup();
          }
          if ($scope.adminorglist.length > 0) {
               $scope.service_id = id;
               $scope.courier_id = courier_id;
               $scope.request_confirm_popup = true;
          } else {
               alert('Oops! You do not have permission.');
          }
     };
     $scope.cancel_request = function () {
          $scope.service_id = 0;
          $scope.courier_id = 0;
          $scope.request_confirm_popup = false;
          $scope.isDisabled = false;
          $scope.errors = {};
          $scope.admin_org_id = $scope.default_admin_org;
     };
     $scope.cancel_request();
     $scope.request_service = function () {

          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/available_services/request_service', {'org_id': $scope.admin_org_id, 'service_id': $scope.service_id, 'courier_id': $scope.courier_id}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.getServices($scope.servicelistdata.currentPage);
                    $scope.cancel_request();
               } else if (data.error) {
                    $scope.errors = data.error;
               }

          });
     };

     $scope.use_this_service = function () {
          $http.post(BASE_URL + 'app/available_services/can_use_this', {service_id: $scope.service_id, 'org_id': $scope.org_id}).success(function (data) {
               if (data.status === 1) {
                    orderService.set_service($scope.org_id, $scope.service_id);
                    $state.go('delivery_orders.new_order');
               } else if (data.status === 2) {
                    $scope.proceed = true;

               } else if (data.status === 0) {
                    if ($scope.org_id) {
                         $scope.errors = data.errors;
                    } else {
                         $scope.use_this_popup = true;
                    }
               }
          });
     };
     $scope.proceed_use_this = function () {
          orderService.set_service($scope.org_id, $scope.service_id);
          $state.go('delivery_orders.new_order');
     };
}
function viewavail_serviceCtrl($scope, $http, $rootScope, notify, $state, $stateParams) {
     $scope.goback = function () {
          $state.go('^');
     };

     $scope.parcelTypePrices = [];
     $scope.getPrices = function () {
          $http.post(BASE_URL + 'app/available_services/get_parcel_prices/' + $stateParams.as_id)
                  .success(function (data) {
                       if (data.prices)
                            $scope.parcelTypePrices = data.prices;
                  });
     };
     $scope.getPrices();
}
function editservicesCtrl($scope, $http, notify, $state, $stateParams) {
     $scope.scountrylist = [];
     $scope.dcountrylist = [];

     $scope.csclist = [
          {
               'name': "Next Business Day",
               "data": "next-day"
          },
          {
               'name': "90-min Delivery",
               "data": "90-minute"
          },
          {
               'name': "3-hours Delivery",
               "data": "3-hour"
          },
          {
               'name': "6-hours Delivery",
               "data": "6-hour"
          }
     ];

     $scope.new_service = {
          org: "", origin: "sg", destination: ["sg"], deliverytime: {'name': "90-min from now", "data": "90-minute"}
     };
     $http.post(BASE_URL + 'app/available_services/get_service/' + $stateParams.as_id).success(function (data) {
          if (data.service) {
               $scope.new_service = data.service;
               angular.forEach($scope.csclist, function (data1) {
                    if (data1.data == $scope.new_service.delivery_time) {
                         $scope.new_service.deliverytime = data1;
                    }
               });
          }
     });
     $scope.termslist = [{name: "Cash", value: "Cash"}, {name: "Credit", value: "Credit"}, {name: "Cheque", value: "Cheque"}];
     $scope.errors = {};
     $scope.orglist = {};
     $scope.getorglist = function () {
          $http.post(BASE_URL + 'app/organisation/allorganisations', {search: $scope.new_service.display_name}).success(function (data) {
               $scope.orglist = data.organisations;
          });
     };
     $scope.getorglist();
     $scope.getCountryList = function () {
          $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
               var countriers = response.data.countries;
               $scope.scountrylist = angular.copy(countriers);
               $scope.dcountrylist = angular.copy(countriers);
               $scope.dcountrylist.splice(0, 0, {code: "all", "country": "Anywhere"});

          });
     };
     $scope.getCountryList();
     $scope.cancel_service = function () {
          $state.go('^');
     };
     $scope.save = function () {
          $http.post(BASE_URL + 'app/available_services/update_service', $scope.new_service).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $state.go('^');
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };
     $scope.check_multiple = function () {
          if ($scope.new_service.destination.length > 1) {
               if ($scope.new_service.destination.indexOf('all') > -1) {
                    if ($scope.new_service.destination.indexOf('all') === ($scope.new_service.destination.length - 1)) {
                         $scope.new_service.destination = ['all'];
                    } else {
                         $scope.new_service.destination.splice($scope.new_service.destination.indexOf('all'), 1);
                    }
               }
          }
     };
}