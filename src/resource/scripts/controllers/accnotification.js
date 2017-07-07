angular
        .module('6connect')
        .controller('accnotificationCtrl', accnotificationCtrl);

function accnotificationCtrl($http, $state, $scope, notify) {
     $scope.notf = {};
     $scope.get_notf_settings = function () {
          $http.post(BASE_URL + 'account/account_notifications/get_notification_settings').success(function (data) {
               if (data.notification) {
                    $scope.notf = data.notification;
               }
          }).error(function () {
               alert('something went wrong!');
          });
     };
     $scope.get_notf_settings();
     $scope.update = function () {
          $http.post(BASE_URL + 'account/account_notifications/update_notification_settings', $scope.notf).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
          }).error(function () {
               alert('something went wrong!');
          });
     };
}
;