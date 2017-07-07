angular
        .module('6connect')
        .controller('tenderCtrl', tenderCtrl)
        .controller('view_tenderCtrl', view_tenderCtrl)
        .controller('srequestcourierCtrl', srequestcourierCtrl)
        .controller('view_srequestcourierCtrl', view_srequestcourierCtrl)
        .controller('asrequestcourierCtrl', asrequestcourierCtrl)
        .controller('view_asrequestcourierCtrl', view_asrequestcourierCtrl);

function tenderCtrl($scope, $http, $rootScope, notify, $stateParams, $state) {
     $scope.$state = $state;
     $scope.orderslist = {};
     $scope.orglist = {};
     $scope.servicelist = {};
     $scope.bid = {"service": ""};
     $scope.show_bid_popup = false;
     $scope.show_withdraw_popup = false;
     $scope.withdraw = {};

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
     $scope.orderslistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.ordersperpage[2]};


     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;
     $http.post(BASE_URL + 'couriers/ownservices/bidding_service_list').success(function (data) {
          if (data.services) {
               $scope.servicelist = data.services;
          }
     });
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
          $scope.org_dropdown = false;
          $scope.orderslistdata.organisation = $stateParams.id;
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'couriers/associate_orglist').success(function (data) {
               $scope.orglist = data.organisations;
          });
     }

     $scope.getOrders = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/tenders/get_tenders_json', $scope.orderslistdata).success(function (data) {
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
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'tenders')
               $scope.getOrders($scope.orderslistdata.currentPage);
     });
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };
     $scope.findorders = function () {
          $scope.getOrders(1);
     };

     $scope.show_bid = function (order_id) {
          if ($scope.show_withdraw_popup) {
               $scope.cancel_withdraw();
          }
          $scope.show_bid_popup = true;
          $scope.bid.order_id = order_id;
     };
     $scope.cancel_bid = function () {
          $scope.show_bid_popup = false;
          $scope.bid = {"service": ""};
          $scope.isDisabled = false;
     };
     $scope.bid_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/tenders/bid_order', $scope.bid).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_bid();
                    $scope.getOrders($scope.orderslistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.bid.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_withdraw = function (bid_id) {
          if ($scope.show_bid_popup) {
               $scope.cancel_bid();
          }
          $scope.show_withdraw_popup = true;
          $scope.withdraw.bid_id = bid_id;
     };
     $scope.cancel_withdraw = function () {
          $scope.show_withdraw_popup = false;
          $scope.withdraw = {};
          $scope.isDisabled = false;
     };
     $scope.withdraw_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/tenders/withdraw_bid', $scope.withdraw).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_withdraw();
                    $scope.getOrders($scope.orderslistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.withdraw.errors = data.errors;
                    }
               }
          });
     };

     $scope.resetSort = function () {
          $scope.orderheaders = {
               created_date: {},
               public_id: {},
               username: {},
               service: {},
               collection_address: {},
               delivery_address: {},
               org_name: {},
               status: {},
               status_name: {},
               bid: {}
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
;
function view_tenderCtrl($scope, $http, $location, $stateParams, $state, notify, Lightbox) {
     $scope.images = [];
     $scope.comment = {};
     $scope.openLightboxModal = function (index) {
          $scope.images = [index];
          Lightbox.openModal($scope.images, 0);
     };
     $scope.goback = function () {
          $state.go('^');
     };
     $scope.addcomment = function (id) {
          if ($scope.comment.content) {
               $http.post(BASE_URL + 'couriers/assigned_orders/add_comment', {comment: $scope.comment.content, order_id: id})
                       .success(function (data) {
                            $scope.comment = {};
                            if (data.last) {
                                 angular.element("#messages").prepend(data.last);
                            }
                       });
          }
     };
}
;
function srequestcourierCtrl($scope, $http, $rootScope, notify, $stateParams, $state) {
     $scope.$state = $state;
     $scope.requestlist = {};
     $scope.orglist = {};
     $scope.servicelist = {};
     $scope.bid = {"service": ""};
     $scope.show_bid_popup = false;
     $scope.show_withdraw_popup = false;
     $scope.withdraw = {};

     $scope.requestperpage = [{
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
     $scope.requestlistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.requestperpage[2], category: "open", sort: 'added_on', sort_direction: 'desc'};


     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;
     $http.post(BASE_URL + 'couriers/ownservices/bidding_service_list').success(function (data) {
          if (data.services) {
               $scope.servicelist = data.services;
          }
     });
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
          $scope.org_dropdown = false;
          $scope.orderslistdata.organisation = $stateParams.id;
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'couriers/associate_orglist').success(function (data) {
               $scope.orglist = data.organisations;
          });
     }

     $scope.getRequests = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.requestlistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/service_tenders/get_service_tenders_json', $scope.requestlistdata).success(function (data) {
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.requestlist = data.requests;
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               $scope.requestlistdata.total = data.total;
               $scope.requestlistdata.currentPage = data.page;
               $scope.requestlistdata.current_user_id = data.current_user_id;
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'service_requests')
               $scope.getRequests($scope.requestlistdata.currentPage);
     });
     $scope.perpagechange = function () {
          $scope.requestlistdata.perpage_value = $scope.requestlistdata.perpage.value;
          $scope.getRequests($scope.requestlistdata.currentPage);
     };
     $scope.findrequests = function () {
          $scope.getRequests(1);
     };

     $scope.show_bid = function (req_id) {
          if ($scope.show_withdraw_popup) {
               $scope.cancel_withdraw();
          }
          $scope.show_bid_popup = true;
          $scope.bid.request_id = req_id;
     };
     $scope.cancel_bid = function () {
          $scope.show_bid_popup = false;
          $scope.bid = {"service": ""};
          $scope.isDisabled = false;
     };
     $scope.bid_request = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/service_tenders/bid_request', $scope.bid).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_bid();
                    $scope.getRequests($scope.requestlistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.bid.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_withdraw = function (bid_id) {
          if ($scope.show_bid_popup) {
               $scope.cancel_bid();
          }
          $scope.show_withdraw_popup = true;
          $scope.withdraw.bid_id = bid_id;
     };
     $scope.cancel_withdraw = function () {
          $scope.show_withdraw_popup = false;
          $scope.withdraw = {};
          $scope.isDisabled = false;
     };
     $scope.withdraw_bid = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/service_tenders/withdraw_bid', $scope.withdraw).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_withdraw();
                    $scope.getRequests($scope.requestlistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.withdraw.errors = data.errors;
                    }
               }
          });
     };

     $scope.resetSort = function () {
          $scope.serviceheaders = {
               added_on: {},
               name: {},
               remarks: {},
               delivery_p_m: {},
               service_duration: {},
               status: {},
               request_stat: {},
               bid: {}
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
          $scope.requestlistdata.sort = column;
          $scope.requestlistdata.sort_direction = $scope.reverseSort ? "desc" : "asc";
          $scope.getRequests($scope.requestlistdata.currentPage);
     };

}
;
function view_srequestcourierCtrl($scope, $http, $location, $stateParams, $state, notify, Lightbox) {
     $scope.images = [];
     $scope.comment = {};
     $scope.openLightboxModal = function (index) {
          $scope.images = [index];
          Lightbox.openModal($scope.images, 0);
     };
     $scope.goback = function () {
          $state.go('^');
     };
     $scope.addcomment = function (id) {
          if ($scope.comment.content) {
               $http.post(BASE_URL + 'couriers/service_tenders/add_comment', {comment: $scope.comment.content, req_id: $stateParams.req_id})
                       .success(function (data) {
                            $scope.comment = {};
                            if (data.last) {
                                 angular.element("#messages").prepend(data.last);
                            }
                       });
          }
     };
}
;
function asrequestcourierCtrl($scope, $http) {
     $scope.services = {};
     $scope.searchname = {};
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

     $scope.orderByField = 'id';
     $scope.reverseSort = true;
     $scope.getservices = function (page) {
          $scope.serviceslistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/available_service_requests/serviceslist_json/', $scope.serviceslistdata)
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
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'available_service_requests')
               $scope.getservices($scope.serviceslistdata.currentPage);
     });


     $scope.perpagechange = function () {
          $scope.serviceslistdata.perpage_value = $scope.serviceslistdata.perpage.value;
          $scope.getservices($scope.serviceslistdata.currentPage);
     };
     $scope.findservices = function () {
          $scope.getservices(1);
     };

     $scope.resetSort = function () {
          $scope.serviceheaders = {
               service: {},
               count: {}
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
;
function view_asrequestcourierCtrl($scope, $http, $location, $stateParams, $state, notify, Lightbox) {
     $scope.isDisabled = false;
     $scope.accept = {};
     $scope.reject = {};
     $scope.goback = function () {
          $state.go('^');
     };
     $scope.getRequests = function () {
          $http.post(BASE_URL + 'couriers/available_service_requests/get_requestlist_json/' + $stateParams.asreq_id).success(function (data) {
               $scope.requestlist = data.requests;
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'available_service_requests.view_request')
               $scope.getRequests();
     });

     $scope.show_accept = function (request_id) {
          if ($scope.show_reject_popup) {
               $scope.cancel_reject();
          }
          $scope.show_accept_popup = true;
          $scope.accept.request_id = request_id;
     };
     $scope.cancel_accept = function () {
          $scope.isDisabled = false;
          $scope.show_accept_popup = false;
          $scope.accept = {};
     };
     $scope.accept_request = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/available_service_requests/accept_request', $scope.accept).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.getRequests();
               }
               $scope.cancel_accept();
          });
     };
     $scope.show_reject = function (request_id) {
          if ($scope.show_accept_popup) {
               $scope.cancel_accept();
          }
          $scope.show_reject_popup = true;
          $scope.reject.request_id = request_id;
     };
     $scope.cancel_reject = function () {
          $scope.isDisabled = false;
          $scope.show_reject_popup = false;
          $scope.reject = {};
     };
     $scope.reject_request = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/available_service_requests/reject_request', $scope.reject).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.getRequests();
                    $scope.cancel_reject();
               } else {
                    $scope.reject.errors = data.errors;
               }

          });
     };

     $scope.resetRSort = function () {
          $scope.requestheaders = {
               organisation: {},
               status: {}
          };
     };

     $scope.resetRSort();
     $scope.rsort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetRSort();
          if ($scope.requestheaders[column].reverse === undefined) {
               $scope.requestheaders[column].reverse = false;
          } else {
               $scope.requestheaders[column].reverse = !$scope.requestheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.requestheaders[column].reverse;
     }

}
;