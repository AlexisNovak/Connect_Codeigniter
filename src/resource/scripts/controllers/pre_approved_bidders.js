angular
        .module('6connect')
        .controller('prebiddersCtrl', prebiddersCtrl);

function prebiddersCtrl($scope, $http, $rootScope, orderService, $stateParams, $state, notify) {
     $scope.bidcount = {};
     $scope.bidperpage = [{
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
     $scope.bidlistdata = {org_id: $stateParams.id, perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.bidperpage[2]};
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };

     $scope.allow_check_box = false;
     $scope.init_count = function () {
          $http.post(BASE_URL + 'app/pre_approved_bidders/couriers_count/' + $stateParams.id).success(function (data) {
               if (data.total !== undefined && data.total === 0) {
                    $scope.show_init = true;
                    $scope.show_table = false;
                    $scope.allow_check_box = false;
               } else {
                    if (data.total < 2) {
                         $scope.allow_check_box = false;
                    } else {
                         $scope.allow_check_box = true;
                    }
                    $scope.show_init = false;
                    $scope.show_table = true;
               }
          });
     };
     $scope.init_count();
     $scope.orderByField_bid = '';
     $scope.reverseSort_bid = false;
     $scope.couriers = [];
     $scope.searchmem = {};
     $scope.searchname = "";
     $scope.get_status = function () {
          $http.post(BASE_URL + 'app/pre_approved_bidders/get_open_bid_status/' + $stateParams.id).success(function (data) {
               if (data.status === 1) {
                    $scope.closed_bidding = true;
               } else {
                    $scope.closed_bidding = false;
               }
          });
     };
     $scope.get_status();
     $scope.getBidders = function (page) {
          $scope.bidlistdata.currentPage = page;
          $http.post(BASE_URL + 'app/pre_approved_bidders/bidderlist_json', $scope.bidlistdata)
                  .success(function (data) {
                       $scope.bidcount.total = data.total;
                       $scope.bidcount.start = data.start;
                       $scope.bidcount.end = data.end;
                       $scope.bidlist = data.bidders;
                       //alert(JSON.stringify($scope.bidlist));
                       $scope.bidlistdata.total = data.total;
                       $scope.bidlistdata.currentPage = data.page;
                  });
     };
     $scope.getBidders($scope.bidlistdata.currentPage);
     $scope.bidperpagechange = function () {
          $scope.bidlistdata.perpage_value = $scope.bidlistdata.perpage.value;
          $scope.getBidders($scope.bidlistdata.currentPage);
     };
     $scope.findBidders = function () {
          $scope.getBidders($scope.bidlistdata.currentPage);
     };
     $scope.resetBidSort = function () {
          $scope.bidheaders = {
               id: {},
               courier: {},
               email: {},
               url: {},
               description: {},
               status: {}
          };
     };
     $scope.resetBidSort();
     $scope.bidsort = function (column) {
          if ($scope.orderByField_bid !== column)
               $scope.resetBidSort();
          if ($scope.bidheaders[column].reverse === undefined) {
               $scope.bidheaders[column].reverse = false;
          } else {
               $scope.bidheaders[column].reverse = !$scope.bidheaders[column].reverse;
          }
          $scope.orderByField_bid = column;
          $scope.reverseSort_bid = $scope.bidheaders[column].reverse;
     };

     $scope.open_bid_confirm = function () {
          $scope.show_confirm_popup = true;
     };
     $scope.close_bid_confirm = function () {
          $scope.show_confirm_popup = false;
          if ($scope.closed_bidding) {
               $scope.closed_bidding = false;
          } else {
               $scope.closed_bidding = true;
          }
     };
     $scope.proceed = function () {
          $http.post(BASE_URL + 'app/pre_approved_bidders/update_open_bidding', {open_bid: $scope.closed_bidding, org_id: $stateParams.id})
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.show_confirm_popup = false;
                       } else {
                            $scope.close_bid_confirm();
                       }
                  });
     };

     $scope.add_courier = function () {
          $scope.getall_couriers();
          $scope.add_courier_form = true;
     };
     $scope.cancel_add_courier = function () {
          $scope.add_courier_form = false;
          $scope.searchmem = {};
          $scope.searchname = "";
          $scope.couriers = [];
          $scope.unknown = false;
          $scope.isDisabled = false;
     };
     $scope.getall_couriers = function () {
          $scope.unknown = false;
          $scope.isSearch = true;
          $http.post(BASE_URL + 'app/pre_approved_bidders/get_all_couriers', {org_id: $stateParams.id, search: $scope.searchname}).success(function (data) {
               if (data.couriers) {
                    $scope.couriers = data.couriers;
               }
          });
     };
     $scope.setCourier = function (courier) {
          $scope.searchmem = courier;
          $scope.searchname = courier.company_name;
          $scope.isSearch = false;
     };

     $scope.invite = function () {
          $scope.isDisabled = true;
          if ($scope.searchmem.courier_id) {
               var sdata = {courier_id: $scope.searchmem.courier_id, org_id: $stateParams.id};
          } else {
               var sdata = {courier_mail: $scope.searchname, org_id: $stateParams.id};
          }
          $http.post(BASE_URL + 'app/pre_approved_bidders/invite', sdata)
                  .success(function (data) {
                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.cancel_add_courier();
                            $scope.init_count();
                            $scope.getBidders($scope.bidlistdata.currentPage);
                       } else {
                            $scope.unknown = true;
                       }
                  });

     };
     $scope.show_remove_warning = function (id) {
          $scope.remove_warning_popup = true;
          $scope.courier_id = id;
     };
     $scope.cancel_remove_warning = function () {
          $scope.remove_warning_popup = false;
          $scope.courier_id = 0;
     };
     $scope.remove_preapproved = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/pre_approved_bidders/remove_pre_approved_courier', {'org_id': $stateParams.id, 'courier_id': $scope.courier_id})
                  .success(function (data) {
                       $scope.isDisabled = false;
                       if (data.reload) {
                            $state.transitionTo($state.current, $stateParams, {
                                 reload: true
                            });
                       }
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.cancel_remove_warning();
                            $scope.init_count();
                            $scope.getBidders($scope.bidlistdata.currentPage);
                       }
                  });
     };
}
;

