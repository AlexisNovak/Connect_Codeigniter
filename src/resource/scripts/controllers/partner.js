angular.module('6connect').controller('partnerCtrl', partnerCtrl)
        .controller('viewpartnerCtrl', viewpartnerCtrl);
function viewpartnerCtrl($http, $scope, notify, $timeout, $stateParams, $state) {
     $scope.partner_exist = false;
     $scope.errors = {};
     $scope.remove_css_popup = false;

     $scope.getTextToCopy = function () {
          return angular.element('#embedded .pre').text();
     }
     $scope.getTextToCopy2 = function () {
          return angular.element('#embedded2 .pre').text();
     }
     $scope.notify_selection = function () {
          notify({
               message: 'Embedded code copied to clipboard',
               classes: 'alert-success',
               templateUrl: ROOT_PATH + "/resource/partial/notify.html"
          });
     };
     $scope.goback = function () {
          $state.go('partner');
     };
     $scope.generate = function (code) {
          angular.element('#embedded .pre').text('<iframe width="900" height="450"  frameborder="0" style="border:0"  src="' + code + '"></iframe>');
     };
     $scope.generate2 = function (code) {
          angular.element('#embedded2 .pre').text('<iframe width="900" height="450"  frameborder="0" style="border:0"  src="' + code + '"></iframe>');
     };
     $scope.get_partner = function () {
          $http.post(BASE_URL + 'partner_management/get_partner_json/' + $stateParams.partner_id, $scope.partner).success(function (data) {
               if (data !== "") {
                    $scope.partner = data;
                    $scope.partner_exist = true;
                    $timeout(function () {
                         $scope.generate($scope.partner.src);
                         if ($scope.partner.sec == true) {
                              $scope.generate2($scope.partner.src2);
                         }
                    }, 500);
               }

          });
     };
     $scope.get_partner();
     $scope.save = function () {
          $http.post(BASE_URL + 'partner_management/update_partner/' + $stateParams.partner_id, $scope.partner).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.errors = {};
                    $scope.get_partner();
                    $state.go('partner');
               }
               else if (data.status == 0) {
                    $scope.errors = data.errors;
               }
          });
     };
     $scope.show_remove_warning = function () {
          $scope.remove_css_popup = true;
     };
     $scope.cancel_remove_warning = function () {
          $scope.remove_css_popup = false;
     };
     $scope.remove_css = function () {
          $http.post(BASE_URL + 'partner_management/remove_css', {'partner': $scope.partner.partner_id}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status) {
                    $scope.cancel_remove_warning();
                    $scope.get_partner();
               }
          });
     };
}

function partnerCtrl($scope, $http, notify, $timeout, $stateParams, $state) {
     $scope.partnerperpage = [{
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
     $scope.partnerlistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.partnerperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.getPartners = function (page) {
          $scope.partnerlistdata.currentPage = page;
          $http.post(BASE_URL + 'partner_management/partnerlist_json', $scope.partnerlistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.partnerlist = data.partners;
                       $scope.partnerlistdata.total = data.total;
                       $scope.partnerlistdata.currentPage = data.page;
                  });
     };

     $scope.partner = {"p_domain": ""};
     $scope.success = false;
     $scope.create_partner_form = false;
     $scope.errors = {};
     $scope.create_partner = function () {

          $scope.create_partner_form = true;
     };
     $scope.cancel_create_partner = function () {

          $scope.create_partner_form = false;
     };
     $scope.add_partner = function () {
          $http.post(BASE_URL + 'partner_management/update_partner/', $scope.partner).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.errors = {};
                    $scope.partner = {p_domain: ""};

                    $scope.cancel_create_partner();
                    $scope.getPartners($scope.partnerlistdata.currentPage);
               }
               else if (data.status == 0) {
                    $scope.errors = data.errors;
               }
          });
     };
     $scope.getPartners($scope.partnerlistdata.currentPage);
     $scope.perpagechange = function () {

          $scope.partnerlistdata.perpage_value = $scope.partnerlistdata.perpage.value;
          $scope.getPartners($scope.partnerlistdata.currentPage);
     };
     $scope.findpartner = function () {
          $scope.getPartners($scope.partnerlistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.partnerheaders = {
               partner_id: {},
               partner_name: {},
               shortname: {},
               domain: {},
               partner_url: {},
               username:{}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.partnerheaders[column].reverse === undefined) {
               $scope.partnerheaders[column].reverse = false;
          } else {
               $scope.partnerheaders[column].reverse = !$scope.partnerheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.partnerheaders[column].reverse;
     };

     $scope.members = {};
     $scope.selected_member = {};
     $scope.isSearch = false;
     $scope.assign_member_form = false;
     $scope.searchname = "";
     $scope.validemail = false;
     $scope.isDisabled = false;
     $scope.getall = function () {
          $scope.selected_member.email = $scope.searchname;
          if ($scope.searchname.length > 2 && $scope.selected_member.userid === undefined) {

               $scope.isSearch = true;
               $http.post(BASE_URL + 'app/members/allmemberslist/', {"search": $scope.searchname})
                       .success(function (data) {
                            $scope.members = data.members;
                            $scope.isSearch = true;
                            if ($scope.members.length === 0) {
                                 $scope.isInvite = true;
                                 $scope.isDisabled = true;
                            } else {
                                 $scope.isInvite = false;
                                 $scope.isDisabled = false;
                            }
                       });
          }
     };

     $scope.setMember = function (member) {
          $scope.isSearch = false;
          $scope.selected_member.userid = member.Userid;
          $scope.searchname = member.Username + " " + member.Email + " ";
     };
     $scope.assign_user = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'partner_management/assign_user', $scope.selected_member)
                  .success(function (data) {
                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status != 0) {
                            $scope.assign_member_form = false;
                            $scope.isInvite = false;
                            $scope.members = {};
                            $scope.selected_member = {};
                            $scope.searchname = "";
                           $scope.getPartners($scope.partnerlistdata.currentPage);

                       } else {
                            $scope.cancel_assign_member();
                       }
                  });
     };
     $scope.show_assign_user = function (partner_id) {
          $scope.selected_member.partner_id = partner_id;
          $scope.getall();
          $scope.assign_member_form = true;
     };
     $scope.cancel_assign_member = function () {
          $scope.isInvite = false;
          $scope.assign_member_form = false;
          $scope.members = {};
          $scope.selected_member = {};
          $scope.searchname = "";
          $scope.isDisabled = false;
     };

}