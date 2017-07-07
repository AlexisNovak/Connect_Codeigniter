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
     $scope.pending_orders = 0;
     $scope.temp = {organisation: "", "service": "all", "status": "8"};
     $scope.get_latest_msg_info = function () {
          $http.post(BASE_URL + 'couriers/assigned_orders/get_assignedorders_json', $scope.temp).success(function (data) {
               $scope.pending_orders = data.total;
          });
     };
     $scope.new_msg_count = 0;
     $scope.o_new_msg_count = 0;
     $scope.s_new_msg_count = 0;
     $scope.msgs = [];
     $scope.get_latest_msg_info = function () {
          $http.get(BASE_URL + 'app/messages/get_latest_msg_info_for_courier').success(function (data) {
               $scope.new_msg_count = data.tmcount;
               $scope.o_new_msg_count = data.omcount;
               $scope.s_new_msg_count = data.smcount;
               $scope.msgs = data.msgs;
          });
     };
     $scope.update_time = function () {
          if ($scope.new_msg_count != 0) {
               $http.get(BASE_URL + 'app/messages/update_last_msg_time_of_courier');
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
}
function enotificationCtrl($http, $scope, $timeout, $state) {
     $scope.get_notifications = function () {
          $http.post(BASE_URL + 'app/messages/get_notifications_courier', {page: $scope.page, limit: $scope.limit}).success(function (data) {
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
