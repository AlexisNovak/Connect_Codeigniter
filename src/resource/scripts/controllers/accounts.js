angular
        .module('6connect')
        .controller('accorganisationCtrl', accorganisationCtrl)
        .controller('accmembersCtrl', accmembersCtrl)
        .controller('editOrgCtrl', editOrgCtrl)
        .controller('editMemberCtrl', editMemberCtrl);

function accorganisationCtrl($scope, $http, $state) {
     $scope.orgperpage = [{
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
     $scope.orglistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.orgperpage[2]};

     $scope.orderByField = 'org_id';
     $scope.reverseSort = false;
     $scope.getOrganisations = function (page) {
          $scope.orglistdata.currentPage = page;
          $http.post(BASE_URL + 'credit/organisationlist_json', $scope.orglistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.orglist = data.organisations;
                       $scope.orglistdata.total = data.total;
                       $scope.orglistdata.currentPage = data.page;
                  });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'accounts.organisations') {
               $scope.getOrganisations($scope.orglistdata.currentPage);
          }
     });
     $scope.perpagechange = function () {

          $scope.orglistdata.perpage_value = $scope.orglistdata.perpage.value;
          $scope.getOrganisations($scope.orglistdata.currentPage);
     };
     $scope.findorg = function () {
          $scope.getOrganisations($scope.orglistdata.currentPage);
     };


     $scope.resetSort = function () {
          $scope.orgheaders = {
               name: {},
               shortname: {},
               description: {},
               website: {},
               adminusers: {}
          };
     };

     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.orgheaders[column].reverse === undefined) {
               $scope.orgheaders[column].reverse = false;
          } else {
               $scope.orgheaders[column].reverse = !$scope.orgheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.orgheaders[column].reverse;
     };
}
function accmembersCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.members = {};
     $scope.searchmem = {};
     $scope.getmem = function () {
          if ($scope.searchname.length > 2 && $scope.searchmem.Userid === undefined) {

               $scope.isSearch = true;
               $http.post(BASE_URL + 'credit/allmemberslist/', {"search": $scope.searchname})
                       .success(function (data) {
                            // alert(JSON.stringify(data));
                            $scope.members = data.members;
                            $scope.isSearch = true;
                            if ($scope.members.length === 0) {
                                 $scope.isInvite = true;
                                 if (data.valid) {
                                      $scope.validemail = true;
                                 } else {
                                      $scope.validemail = false;
                                 }
                            } else {
                                 $scope.isInvite = false;
                            }
                       });
          }
     };
     $scope.getall = function () {
          $scope.getmem();
     };
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

     $scope.roles = {
          1: "Admin",
          2: "Reviewer",
          3: "Referral"
     };
     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.loginWithId = function (id) {
          $http.post(BASE_URL + 'account/sign_in/loginWithUserid', {'user_id': id})
                  .success(function (data) {
                       if (data.status == 'true') {
                            $window.location.href = data.message;
                       }
                  });
     }
     $scope.getMembers = function (page) {
          angular.element("#orgmembers_body").hide();
          angular.element("#orgmembers_loading").show();

          $scope.memberslistdata.currentPage = page;
          $http.post(BASE_URL + 'credit/memberslist_json/', $scope.memberslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.memberslist = data.member_detail;
                       angular.element("#orgmembers_body").show();
                       angular.element("#orgmembers_loading").hide();

                       $scope.memberslistdata.total = data.total;
                       $scope.memberslistdata.currentPage = data.page;
                       $scope.memberslistdata.current_user_id = data.current_user_id;
                       //alert($scope.memberslistdata.current_user_id);
                  });
     };

     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'accounts.members') {
               $scope.getMembers($scope.memberslistdata.currentPage);
          }
     });
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
               organization:{},
               country: {},
               phone_no: {},
               fax_no: {},
               description: {},
               createdtime:{}
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
function editOrgCtrl($scope, $http, $stateParams, $timeout, $state, $rootScope) {
     $scope.org = {};
     $scope.temorg = {};
     $scope.isEditing = false;
     var id = $stateParams.acc_id;
     $http.post(BASE_URL + 'app/organisation/getOrganisationDetails', {id: id}).success(function (data) {
          $scope.org = data;
          $rootScope.orgname = data.name;
     });

     $scope.success = false;
     $scope.cancelEditing = function () {
          $state.go('^');
     }
     $scope.save = function () {
          $http.post(BASE_URL + 'app/organisation/save', $scope.org).success(function (data) {
               if (data.status === 1) {
                    $state.go('^');

               } else if (data.status === 0) {
                    $scope.errors = data.result;
               }
          }).error(function (data) {

          });
     };
}
function editMemberCtrl($scope, $http, $stateParams, notify, $state) {
     $scope.user = {};
     $scope.errors = {};
     $scope.cancelEditing = function () {
          $state.go('^');
     }
     $scope.get_details = function () {
          $http.post(BASE_URL + 'credit/get_member_info', {id: $stateParams.mem_id}).success(function (data) {
               $scope.user = data.user;
          });
     };
     $scope.get_details();
     $scope.save = function () {
          $http.post(BASE_URL + 'credit/update_member', $scope.user).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.cancelEditing();
               } else {
                    $scope.errors = data.errors;
               }
          });
     }

}