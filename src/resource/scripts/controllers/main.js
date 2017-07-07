/**
 *
 * appCtrl
 *
 */

angular
        .module('6connect')
        .controller('appCtrl', appCtrl)
        .controller('enotificationCtrl', enotificationCtrl);

function appCtrl($http, $scope, $timeout, $state) {
     $scope.new_msg_count = 0;
     $scope.o_new_msg_count = 0;
     $scope.s_new_msg_count = 0;
     $scope.msgs = [];
     $scope.get_latest_msg_info = function () {
          $http.get(BASE_URL + 'app/messages/get_latest_msg_info').success(function (data) {
               $scope.new_msg_count = data.tmcount;
               $scope.o_new_msg_count = data.omcount;
               $scope.s_new_msg_count = data.smcount;
               $scope.msgs = data.msgs;
          });
     };
     $scope.update_time = function () {
          if ($scope.new_msg_count != 0) {
               $http.get(BASE_URL + 'app/messages/update_last_msg_time');
          }
          $timeout(function () {
               $scope.new_msg_count = 0;
               $scope.o_new_msg_count = 0;
               $scope.s_new_msg_count = 0;
          }, 2000);
     };
     $scope.get_latest_msg_info();
     $timeout(function () {
          $scope.get_latest_msg_info();
     }, 60000);
     // For iCheck purpose only
     $scope.checkOne = true;
     $scope.dashboard = false;
     $scope.get_start = false;


     $scope.$on("refreshDashboard", function (event) {
          $http.post(BASE_URL + 'app/home/get_user_status').success(function (data) {
               //if new user
               if (data.status === 1) {
                    $scope.open_get_start();
               } else {
                    $scope.close_get_start();
               }
          });
     });
     $scope.close_get_start = function () {
          $state.current.data.pageTitle = "Dashboard";
          $state.current.data.pageDesc = "Here is a summary of your delivery orders and tender requests";
          $state.current.data.breadcrumb = "Get Started";
          $scope.get_start = false;
          $scope.dashboard = true;
     };
     $scope.open_get_start = function () {
          $state.current.data.pageTitle = "Get started";
          $state.current.data.pageDesc = "";
          $state.current.data.breadcrumb = "";
          $scope.get_start = true;
          $scope.dashboard = false;
     };
}
function enotificationCtrl($http, $scope, $timeout, $state) {
     $scope.get_notifications = function () {
          $http.post(BASE_URL + 'app/messages/get_notifications', {page: $scope.page, limit: $scope.limit}).success(function (data) {
               $scope.loading = false;
               $scope.last_notified = data.last_notified;
               $scope.loadmore = data.loadmore;
               $scope.page++;
               $scope.msgs.push.apply($scope.msgs, data.msgs);
          });
     };
     $scope.init_msgs = function () {
          $scope.msgs = [];
          $scope.last_notified = '';
          $scope.page = 1;
          $scope.limit = 10;
          $scope.get_notifications();
     };
     $scope.init_msgs();
     $scope.load_more_messages = function () {
          $scope.loading = true;
          $scope.get_notifications();
     };
}
