angular
        .module('6connect')
        .controller('profileCtrl', profileCtrl)
        .controller('settingsCtrl', settingsCtrl)
        .controller('passwordCtrl', passwordCtrl)
        .controller('organisationDetailCtrl', organisationDetailCtrl)
        .controller('accountPaymentCtrl', accountPaymentCtrl)
        .controller('orgpaymentCtrl', orgpaymentCtrl)
        .controller('singlepaymentCtrl', singlepaymentCtrl);
//controller for account profile
function profileCtrl($scope, $http, $state, $templateCache, notify) {
     $scope.user = {};
     $scope.errors = {};
     var file = undefined;
     $http.post(BASE_URL + 'account/account_profile/getAccountDetails').success(function (data) {
          $scope.user = data;
          //alert(JSON.stringify(data));
          if (data.profile_picture === null) {
               $scope.user.pic_selection = "custom";
          }
     });

     $scope.save = function () {
          uploadFiles($scope.user);
     };
     $scope.profile_pic_delete = function () {
          $http.post(BASE_URL + 'account/account_profile/deleteProfilePic').success(function (data) {

               if (data.status === 1) {
                    angular.element("#user_profile_navigation_bar").attr('src', data.dp);
                    var currentPageTemplate = $state.current.templateUrl;
                    $templateCache.remove(currentPageTemplate);
                    $state.reload();
               }
          }).error(function (data) {
               alert(data);
          });
     };

     // Add events
     $('#account_picture_upload').on('change', prepareUpload);

     // Grab the files and set them to our variable
     function prepareUpload(event) {
          file = event.target.files[0];
     }

     // Catch the form submit and upload the files
     function uploadFiles(user) {

          // Create a formdata object and add the files
          var data = new FormData();
          data.append("profile_username", user.profile_username);
          data.append("settings_email", user.settings_email);
          data.append("settings_fullname", user.settings_fullname);
          data.append("settings_description", user.settings_description);
          data.append("settings_phone", user.settings_phone);
          data.append("settings_fax", user.settings_fax);
          data.append("pic_selection", user.pic_selection);
          if (file !== undefined) {
               data.append("account_picture_upload", file);
          }
          $.ajax({
               url: BASE_URL + 'account/account_profile/updateProfile',
               type: 'POST',
               data: data,
               cache: false,
               dataType: 'json',
               processData: false, // Don't process the files
               contentType: false, // Set content type to false as jQuery will tell the server its a query string request
               success: function (data)
               {
                    notify({
                         message: data.msg,
                         classes: data.class,
                         templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    if (typeof data.success !== 'undefined' && data.success === 1)
                    {
                         $scope.errors = {};
                         angular.element("#user_name_navigation_bar").text(data.username);

                         if (typeof data.upload_success !== 'undefined' && data.upload_success === 1)
                         {
                              angular.element("#user_profile_navigation_bar").attr('src', data.dp);
                              var currentPageTemplate = $state.current.templateUrl;
                              $templateCache.remove(currentPageTemplate);
                              $state.reload();
                         }
                    }
                    else
                    {
                         $scope.errors = data.errors;
                         // Handle errors here
                         console.log('ERRORS: ' + data.errors);
                    }
               }
          });
     }

}
//controller for account settings
function settingsCtrl($scope, $http, $window, notify) {
     $scope.user = {};
     $scope.errors = {};
     $scope.success = false;
     $http.post(BASE_URL + 'account/account_settings/getAccountDetails').success(function (data) {
          $scope.user = data;
     });
     $scope.save = function () {
          $http.post(BASE_URL + 'account/account_settings/updateSettings', $scope.user).success(function (data) {
               $scope.errors = data.errors;
               if (data.reload) {
                    $window.location.reload();
               }
               if ($scope.errors) {
                    $scope.success = true;
               }
          }).error(function (data) {

          });
     };

}
//controller for password settings
function passwordCtrl($scope, $http) {
     $scope.user = {};
     $scope.success = false;
     $scope.save = function () {
          $http.post(BASE_URL + 'account/account_password/updatePassword', {"password_current_password": $scope.password_current_password, "password_new_password": $scope.password_new_password, "password_retype_new_password": $scope.password_retype_new_password}).success(function (data) {
               $scope.errors = data;
               if ($scope.errors == "") {
                    $scope.password_current_password = "";
                    $scope.password_new_password = "";
                    $scope.password_retype_new_password = "";
                    $scope.success = true;
               }
          }).error(function (data) {
               alert(data);
          });
     };
}

//controller for edit organisation
function organisationDetailCtrl($scope, $http, $stateParams, $timeout, $state, $rootScope) {
     $scope.org = {};
     $scope.temorg = {};
     $scope.isEditing = false;
     var id = $stateParams.id; // Variable to store your files
     var files;

     // Add events
     $('#file_upload').on('change', prepareUpload);

     // Grab the files and set them to our variable
     function prepareUpload(event) {
          file = event.target.files[0];
          uploadFiles(event);
     }

     // Catch the form submit and upload the files
     function uploadFiles(event)
     {
          event.stopPropagation(); // Stop stuff happening
          event.preventDefault(); // Totally stop stuff happening

          // START A LOADING SPINNER HERE

          // Create a formdata object and add the files
          var data = new FormData();
          data.append("file_upload_id", angular.element("#file_upload_id").val());
          data.append("file_upload", file);
          $.ajax({
               url: BASE_URL + 'app/organisation/uploaddp/',
               type: 'POST',
               data: data,
               cache: false,
               dataType: 'json',
               processData: false, // Don't process the files
               contentType: false, // Set content type to false as jQuery will tell the server its a query string request
               success: function (data)
               {
                    if (typeof data.error === 'undefined')
                    {
                         var src = ROOT_PATH + "filebox/organisation/" + data.files;
                         angular.element("#org_profile_dp").attr("src", src);
                    }
                    else
                    {
                         // Handle errors here
                         console.log('ERRORS: ' + data.error);
                    }
               }
          });
     }

     $http.post(BASE_URL + 'app/organisation/getOrganisationDetails', {id: id}).success(function (data) {
          $scope.org = data;
          $rootScope.orgname = data.name;
     });

     $scope.success = false;
     $scope.enableEditing = function () {
          angular.copy($scope.org, $scope.temorg);
          $scope.isEditing = true;
          var avatar_div = angular.element(document.querySelector('.avatar .image_fade'));
          avatar_div.addClass('shadow_bg');
     };
     $scope.cancelEditing = function () {
          angular.copy($scope.temorg, $scope.org);
          $scope.isEditing = false;
          var avatar_div = angular.element(document.querySelector('.avatar .image_fade'));
          avatar_div.removeClass('shadow_bg');
     };
     $scope.save = function () {
          $http.post(BASE_URL + 'app/organisation/save', $scope.org).success(function (data) {
               // alert(JSON.stringify(data));
               if (data.status === 1) {
                    //$scope.success = true;
                    $scope.isEditing = false;
                    angular.element('#org_profile_dp').attr('src', data.avatar);
                    var avatar_div = angular.element(document.querySelector('.avatar .image_fade'));
                    avatar_div.removeClass('shadow_bg');

               } else if (data.status === 0) {
                    $scope.errors = data.result;
               }
          }).error(function (data) {
               alert(data);
          });
     };
}
function accountPaymentCtrl($scope, $http, notify) {
     $scope.account = {};
     $scope.delete_warning_popup = false;
     $scope.create_account_form = false;
     $scope.errors = {};
     $scope.accountlist = {};
     $scope.countrylist = {};
     $scope.isDisabled = false;
     $scope.account_id = 0;
     $scope.show_delete_warning_popup = false;
     $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     $(window).resize(function () {
          $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     });
     $scope.get_accounts = function () {
          $http.post(BASE_URL + 'credit/get_my_accounts').success(function (data) {
               if (data.accounts) {
                    $scope.accountlist = data.accounts;
               } else {
                    $scope.accountlist = {};
               }
          });
     };
     $scope.get_accounts();
     $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
          $scope.countrylist = response.data.countries;
     });
     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
          $scope.orglist = data.organisations;
     });

     $scope.payment = {};
     $scope.get_payments = function () {
          $http.post(BASE_URL + 'credit/get_payments').success(function (data) {
               if (data.payment) {
                    $scope.payment = data.payment;
               }
          });
     };
     $scope.get_payments();
     $scope.update_payment = function () {
          $http.post(BASE_URL + 'credit/save_payments', $scope.payment).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH
                            + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.get_payments();
               }
          });
     };
     $scope.create_account = function () {
          $scope.create_account_form = true;
     };

     $scope.cancel_create_account = function () {
          $scope.account = {};
          $scope.isDisabled = false;
          $scope.errors = {};
          $scope.create_account_form = false;
     };
     $scope.save_account = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'credit/add_postpaid_account', $scope.account).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_create_account();
                    $scope.get_accounts();
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };



     $scope.update_account = function (account) {
          $scope.account = angular.copy(account);
          $scope.create_account();
     };
     $scope.show_delete_warning = function (id) {
          $scope.account_id = id;
          $scope.show_delete_warning_popup = true;
     }
     $scope.cancel_delete_warning = function () {
          $scope.account_id = 0;
          $scope.show_delete_warning_popup = false;
     }
     $scope.delete_account = function () {
          $http.post(BASE_URL + 'credit/delete_postpaid_account', {id: $scope.account_id}).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_delete_warning();
                    $scope.get_accounts();
               }
          });
     }
     $scope.resetSort = function () {
          $scope.accountheaders = {
               id: {},
               name: {},
               status: {},
               balance: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.accountheaders[column].reverse === undefined) {
               $scope.accountheaders[column].reverse = false;
          } else {
               $scope.accountheaders[column].reverse = !$scope.accountheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.accountheaders[column].reverse;
     };
}

function orgpaymentCtrl($scope, $http, notify, $stateParams) {
     $scope.account = {};
     $scope.delete_warning_popup = false;
     $scope.create_account_form = false;
     $scope.errors = {};
     $scope.accountlist = {};
     $scope.countrylist = {};
     $scope.isDisabled = false;
     $scope.account_id = 0;
     $scope.show_delete_warning_popup = false;
     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
          $scope.orglist = data.organisations;
//          $scope.account.account_holder = ORGANISATIONID;
          $scope.account.country_code = 0;
     });
     $scope.ORGANISATIONID = ORGANISATIONID;

     $scope.get_accounts = function () {
          $http.post(BASE_URL + 'credit/org_payment/get_org_accounts/' + $stateParams.id).success(function (data) {
               if (data.accounts) {
                    $scope.accountlist = data.accounts;
               } else {
                    $scope.accountlist = {};
               }
          });
     };
     $scope.get_accounts();
     $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
          $scope.countrylist = response.data.countries;
     });
     
     $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     $(window).resize(function () {
          $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     });
     
     $scope.create_account = function () {
          $scope.create_account_form = true;
          $scope.account.country_code = 'sg';
          $scope.account.account_holder = ORGANISATIONID;
     };

     $scope.cancel_create_account = function () {
          $scope.account = {};
          $scope.isDisabled = false;
          $scope.errors = {};
          $scope.create_account_form = false;
     };
     $scope.save_account = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'credit/org_payment/add_postpaid_account/' + $stateParams.id, $scope.account).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_create_account();
                    $scope.get_accounts();
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };



     $scope.update_account = function (account) {
          $scope.account = angular.copy(account);
          $scope.create_account();
     };
     $scope.show_delete_warning = function (id) {
          $scope.account_id = id;
          $scope.show_delete_warning_popup = true;
     }
     $scope.cancel_delete_warning = function () {
          $scope.account_id = 0;
          $scope.show_delete_warning_popup = false;
     }
     $scope.delete_account = function () {
          $http.post(BASE_URL + 'credit/org_payment/delete_postpaid_account', {id: $scope.account_id}).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_delete_warning();
                    $scope.get_accounts();
               }
          });
     }

     $scope.payment = {};
     $scope.get_payments = function () {
          $http.post(BASE_URL + 'credit/org_payment/get_payments/' + $stateParams.id).success(function (data) {
               if (data.payment) {
                    $scope.payment = data.payment;
               }
          });
     };
     $scope.get_payments();
     $scope.update_payment = function () {
          $scope.payment.id = $stateParams.id;
          $http.post(BASE_URL + 'credit/org_payment/save_payments', $scope.payment).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH
                            + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.get_payments();
               }
          });
     };
     $scope.resetSort = function () {
          $scope.accountheaders = {
               id: {},
               name: {},
               status: {},
               balance: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.accountheaders[column].reverse === undefined) {
               $scope.accountheaders[column].reverse = false;
          } else {
               $scope.accountheaders[column].reverse = !$scope.accountheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.accountheaders[column].reverse;
     };
}

function singlepaymentCtrl($scope, $http, notify, $stateParams,$state) {
     $scope.account = {};
     $scope.errors = {};
     $scope.accountlist = {};
     $scope.countrylist = {};
     $scope.isDisabled = false;
     $scope.account_id = ACCOUNT_ID;
     $scope.ORGANISATIONID = ORGANISATIONID;
     $scope.get_single_account = function () {
          $http.post(BASE_URL + 'payment/get_payment/' + ACCOUNT_ID).success(function (data) {
               if (data.accounts) {
                    $scope.account = data.accounts;
               } else {
                    $scope.account = {};
               }
          });
     };
     $scope.get_single_account();
     $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
          $scope.countrylist = response.data.countries;
     });
     
     $scope.downloadForm=function(){
          $("#downloadTR").submit();
     }
     

     $scope.cancel_create_account = function () {
          $scope.account = {};
          $state.go('accounts.paymentmethod');
     };
     $scope.save_account = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'payment/update_payment/' + ACCOUNT_ID, $scope.account).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: "Updated Successfully",
                    classes: 'alert-success',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == true) {
                    $scope.cancel_create_account();
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };
}

