angular
        .module('6connect')
        .controller('tipCtrl', tipCtrl)
        .controller('holidayCtrl', holidayCtrl)
        .controller('custParcelCtrl', custParcelCtrl);


function holidayCtrl($scope, $http, $stateParams, notify, $rootScope) {
     $scope.holiday_list = {};
     $scope.new_holiday = {};
     $scope.errors = {};
     $scope.edit = false;
     $scope.new_holiday_form = false;
     $scope.holiday = 0;
     $scope.delete_warning_popup = false;
     $scope.get_list = function () {
          $http.post(BASE_URL + 'app/holidays/get_list').success(function (data) {
               if (data.holidays) {
                    $scope.holiday_list = data.holidays;
               }
          });
     };
     $scope.get_list();
     $scope.show_new_holiday_form = function () {
          $scope.new_holiday_form = true;
     };
     $scope.cancel_new_holiday_form = function () {
          $scope.new_holiday_form = false;
          $scope.new_holiday = {};
          $scope.errors = {};
          $scope.edit = false;
     };
     $("#datepickerHoliday").daterangepicker({
                         timePicker: false,
                         singleDatePicker: true,
                         format: 'YYYY-MM-DD',
                         autoUpdateInput: true,
                         minDate: new Date()
                    });
                    
     $scope.save_new_holiday = function () {
          $http.post(BASE_URL + 'app/holidays/save', $scope.new_holiday).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_new_holiday_form();
                    $scope.get_list();

               } else if (data.errors) {
                    $scope.errors = data.errors;
               }
          });
     };
     $scope.edit_holiday = function (tip) {
          $scope.edit = true;
          $scope.new_tip = angular.copy(tip);
          $scope.show_new_tip_form();
     };
     $scope.show_delete_warning = function (id) {
          $scope.holiday = id;
          $scope.delete_warning_popup = true;
     };
     $scope.cancel_delete_warning = function () {
          $scope.delete_warning_popup = false;
          $scope.holiday = 0;
     };
     $scope.delete_holiday = function () {
          $http.post(BASE_URL + 'app/holidays/delete_holiday', {id: $scope.holiday}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.cancel_delete_warning();
               $scope.get_list();

          });
     };
}

function tipCtrl($scope, $http, $stateParams, notify, $rootScope) {
     $scope.tiplist = {};
     $scope.new_tip = {};
     $scope.errors = {};
     $scope.edit = false;
     $scope.new_tip_form = false;
     $scope.tip = 0;
     $scope.delete_warning_popup = false;
     $scope.get_list = function () {
          $http.post(BASE_URL + 'app/tip_of_the_day/get_tip_list').success(function (data) {
               if (data.tips) {
                    $scope.tiplist = data.tips;
               }
          });
     };
     $scope.get_list();
     $scope.show_new_tip_form = function () {
          $scope.new_tip_form = true;
     };
     $scope.cancel_new_tip_form = function () {
          $scope.new_tip_form = false;
          $scope.new_tip = {};
          $scope.errors = {};
          $scope.edit = false;
     };
     $scope.save_new_tip = function () {
          $http.post(BASE_URL + 'app/tip_of_the_day/save', $scope.new_tip).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_new_tip_form();
                    $scope.get_list();

               } else if (data.errors) {
                    $scope.errors = data.errors;
               }
          });
     };
     $scope.edit_tip = function (tip) {
          $scope.edit = true;
          $scope.new_tip = angular.copy(tip);
          $scope.show_new_tip_form();
     };
     $scope.show_delete_warning = function (id) {
          $scope.tip = id;
          $scope.delete_warning_popup = true;
     };
     $scope.cancel_delete_warning = function () {
          $scope.delete_warning_popup = false;
          $scope.tip = 0;
     };
     $scope.delete_tip = function () {
          $http.post(BASE_URL + 'app/tip_of_the_day/delete_tip', {id: $scope.tip}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.cancel_delete_warning();
               $scope.get_list();

          });
     };
}
function custParcelCtrl($scope, $http, $stateParams, notify, $rootScope, $state) {
     $scope.paymentTypelist = {};
     $scope.paytype = {};
     $scope.org_list = {};
     $scope.errors = {};
     $scope.edit = false;
     $scope.new_tip_form = false;
     $scope.tip = 0;
     $scope.delete_warning_popup = false;
     $scope.get_list = function () {
          $http.post(BASE_URL + 'app/cust_parcel_type/get_list').success(function (data) {
               if (data.types) {
                    $scope.paymentTypelist = data.types;
               }

               $scope.org_list = data.org_list;
               $('select').chosen();
          });
     };
     $scope.get_list();
     $scope.show_new_tip_form = function () {
          $scope.new_tip_form = true;
     };
     $scope.cancel_new_form = function () {
          $scope.new_tip_form = false;
          $scope.paytype = {};
          $scope.errors = {};
          $scope.edit = false;
          $state.go('cust_parcel_type')
     };
     $scope.save_new_type = function () {
          $http.post(BASE_URL + 'app/cust_parcel_type/save', $scope.paytype).success(function (data) {
               notify({
                    message: "Added successfully",
                    classes: 'alert-success',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.cancel_new_form();

               } else if (data.errors) {
                    $scope.errors = data.errors;
               }
          });
     };

     if (ID != 0) {
          $http.post(BASE_URL + 'app/cust_parcel_type/getsingle/' + ID).success(function (data) {
               $scope.paytype = data.ptype;
          });
     }

     $scope.edit_type = function (tip) {
          $scope.edit = true;
          $scope.new_tip = angular.copy(tip);
          $scope.show_new_tip_form();
     };
     $scope.show_delete_warning = function (id) {
          $scope.ptid = id;
          $scope.delete_warning_popup = true;
     };
     $scope.cancel_delete_warning = function () {
          $scope.delete_warning_popup = false;
          $scope.ptid = 0;
     };
     $scope.delete_type = function () {
          $http.post(BASE_URL + 'app/cust_parcel_type/delete_type', {id: $scope.ptid}).success(function (data) {
               notify({
                    message: 'Deleted Successfully',
                    classes: 'alert-success',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.get_list();

               $scope.delete_warning_popup = false;
          });
     };
}
  