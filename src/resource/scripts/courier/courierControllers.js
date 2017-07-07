angular
        .module('6connect')
        .controller('profileCtrl', profileCtrl)
        .controller('settingsCtrl', settingsCtrl)
        .controller('passwordCtrl', passwordCtrl)
        .controller('CourierDetailCtrl', CourierDetailCtrl);
//controller for account profile
function profileCtrl($scope, $http, $state, $templateCache, notify) {
     $scope.courier = {};
     $scope.errors = {};
     $http.post(BASE_URL + 'couriers/courierAccount/getAccountDetails').success(function (data) {
          $scope.courier = data;
     });

     $scope.save = function () {
          $http.post(BASE_URL + 'couriers/courierAccount/updateProfile', $scope.courier).success(function (data)
          {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (typeof data.success !== 'undefined' && data.success == 1)
               {
                    angular.element("#courier_name_navigation_bar").text(data.couriername);

                    angular.element("#courier_logo_navigation_bar").attr('src', data.dp);
                    var currentPageTemplate = $state.current.templateUrl;
                    $templateCache.remove(currentPageTemplate);
                    $state.reload();
                    $scope.errors = data.errors;
               }
               else
               {
                    $scope.errors = data.errors;

               }
          });

     };
}
//controller for account settings
function settingsCtrl($scope, $http, $window, notify) {
     $scope.courier = {};
     $scope.errors = {};
     $scope.success = false;
     $http.post(BASE_URL + 'couriers/courierAccount/getDetails').success(function (data) {
          $scope.courier = data;
     });
     $scope.save = function () {
          $http.post(BASE_URL + 'couriers/courierAccount/updateSettings', $scope.courier).success(function (data) {
               $scope.errors = data.errors;
               if (data.success) {
                    $scope.success = true;
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
     $scope.reset_settings = function () {
          $http.post(BASE_URL + 'couriers/courierAccount/getDetails').success(function (data) {
               $scope.courier = data;
          });
     };

}
//controller for password settings
function passwordCtrl($scope, $http) {
     $scope.courier = {};
     $scope.success = false;
     $scope.save = function () {
          $http.post(BASE_URL + 'couriers/courierAccount/updatePassword', {"password_current_password": $scope.password_current_password,"password_new_password": $scope.password_new_password, "password_retype_new_password": $scope.password_retype_new_password}).success(function (data) {
               $scope.errors = data.errors;
               if ($scope.errors == "") {
                    $scope.password_current_password = "";
                    $scope.password_new_password = "";
                    $scope.password_retype_new_password = "";
                    $scope.success = true;
               }
          }).error(function (data) {
               alert(data);
          });
     }
}

function CourierDetailCtrl($scope, $http, $stateParams, $timeout) {
     $scope.courier = {};
     var id = $stateParams.id; // Variable to store your files

     $scope.success = false;
     $http.post(BASE_URL + 'couriers/courierAccount/getInfo').success(function (data) {
          $scope.courier = data;
     });
}

