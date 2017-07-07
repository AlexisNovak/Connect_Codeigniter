angular.module('6connect').controller('pusersCtrl', pusersCtrl)
        .controller('porderCtrl', porderCtrl)
        .controller('pvieworderCtrl', pvieworderCtrl);
function pusersCtrl($scope, $http) {
     $scope.userslist = {};
     $scope.membersperpage = [{
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

     $scope.memberslistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.membersperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getMembers = function (page) {
          angular.element("#orgmembers_body").hide();
          angular.element("#orgmembers_loading").show();

          $scope.memberslistdata.currentPage = page;
          $http.post(BASE_URL + 'partner_user/userslist_json', $scope.memberslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.userslist = data.member_detail;
                       angular.element("#orgmembers_body").show();
                       angular.element("#orgmembers_loading").hide();

                       $scope.memberslistdata.total = data.total;
                       $scope.memberslistdata.currentPage = data.page;
                  });
     };
     $scope.getMembers($scope.memberslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.memberslistdata.perpage_value = $scope.memberslistdata.perpage.value;
          $scope.getMembers($scope.memberslistdata.currentPage);
     };
     $scope.findmembers = function () {
          $scope.getMembers(1);
     };

     $scope.resetSort = function () {
          $scope.memberheaders = {
               email: {},
               fullname: {},
               description: {},
               phone: {},
               fax: {},
               country: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.memberheaders[column].reverse === undefined) {
               $scope.memberheaders[column].reverse = false;
          } else {
               $scope.memberheaders[column].reverse = !$scope.memberheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.memberheaders[column].reverse;
     }
}
function porderCtrl($scope, $http, $rootScope, orderService, $stateParams, $state) {
     $scope.$state = $state;
     $scope.orderslist = {};
     $scope.filter_statuslist = [];
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

     $scope.orderslistdata = {perpage_value: 15, currentPage: 1, total: 0, "status": "all", perpage: $scope.ordersperpage[2]};

     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;

     $http.post(BASE_URL + 'orders/statusList').success(function (data) {
          $scope.filter_statuslist = data.status;
     });

     $scope.getOrders = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          $http.post(BASE_URL + 'partner_user/orderslist_json', $scope.orderslistdata).then(function (response) {
               var data = response.data;
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.orderslist = data.order_detail;
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               $scope.orderslistdata.total = data.total;
               $scope.orderslistdata.currentPage = data.page;
               $scope.orderslistdata.current_user_id = data.current_user_id;
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'partner_user.orders') {
               $scope.getOrders($scope.orderslistdata.currentPage);
          }
     });
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };
     $scope.findteams = function () {
          $scope.get_teams();
          $scope.getOrders(1);
     };
     $scope.findorders = function () {
          $scope.getOrders(1);
     };
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };
     $scope.resetSort = function () {
          $scope.orderheaders = {
               consignment_id: {},
               public_id: {},
               private_id: {},
               service: {},
               collection_address: {},
               delivery_address: {},
               username: {},
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
function pvieworderCtrl($scope, $http, $rootScope, $stateParams, orderService, $state, notify, Lightbox) {

     $scope.images = [];
     $scope.openLightboxModal = function (index) {
          $scope.images = [index];
          Lightbox.openModal($scope.images, 0);
     };



     $scope.goback = function () {
          $state.go('^');
     };
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };
     $scope.show_service_info = false;
     $scope.sdetail = {};
     $scope.view_service_info = function (id) {
          $scope.show_service_info = true;
          $http.post(BASE_URL + 'app/services/get_service_info/' + id).success(function (data) {
               $scope.sdetail = data.service;
          });
     };
     $scope.cancel_service_info = function () {
          $scope.show_service_info = false;
          $scope.sdetail = {};
     };
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
     $scope.bidlistdata = {order_id: $stateParams.order_id, perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.bidperpage[2]};

     $scope.orderByField_bid = '';
     $scope.reverseSort_bid = false;
     $scope.getBidders = function (page) {
          $scope.bidlistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/bidderlist_json', $scope.bidlistdata)
                  .success(function (data) {
                       $scope.bidcount.notify = data.notify;
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
               service: {},
               price: {},
               remarks: {}
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
     /*
      * Messages tab
      * 
      */
     $scope.comment = {};
     $scope.msgcount = {};
     $scope.messages = {};
     $http.post(BASE_URL + 'orders/messageslist_json', {order_id: $stateParams.order_id})
             .success(function (data) {
                  $scope.msgcount.total = data.total;
                  $scope.msgcount.reply = data.reply;
                  $scope.messages = data.messages;
             });


     $scope.logcount = {};
     $scope.logperpage = [{
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
     $scope.loglistdata = {order_id: $stateParams.order_id, perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.logperpage[2]};

     $scope.orderByField_log = '';
     $scope.reverseSort_log = false;
     $scope.getLog = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/loglist_json', $scope.loglistdata)
                  .success(function (data) {
                       $scope.logcount.total = data.total;
                       $scope.logcount.start = data.start;
                       $scope.logcount.end = data.end;
                       $scope.loglist = data.loglist;
                       //alert(JSON.stringify($scope.loglist));
                       $scope.loglistdata.total = data.total;
                       $scope.loglistdata.currentPage = data.page;
                  });
     };
     $scope.getLog($scope.loglistdata.currentPage);
     
     
     $scope.getAttachments = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/attachlist_json', $scope.loglistdata)
                  .success(function (data) {
                       $scope.attachments = data.loglist;
                       $scope.loglistdata.total = data.total;
                       $scope.loglistdata.currentPage = data.page;
                  });
     };
     $scope.getAttachments(1);
     
     
     $scope.logperpagechange = function () {
          $scope.loglistdata.perpage_value = $scope.loglistdata.perpage.value;
          $scope.getLog($scope.loglistdata.currentPage);
     };
}