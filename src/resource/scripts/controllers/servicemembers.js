

angular
        .module('6connect')
        .controller('serviceMembersCtrl', serviceMembersCtrl);


function serviceMembersCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.members = {};
     $scope.searchmem = {};
     $scope.isInvite = false;
     $scope.isSearch = false;
     $scope.add_member_form = false;
     $scope.getmem = function () {
          $http.post(BASE_URL + 'app/members/orgmemberslist/', {"search": $scope.searchname, "org_id": $stateParams.id})
                  .success(function (data) {
                       // alert(JSON.stringify(data));
                       $scope.members = data;
                       $scope.isSearch = true;
                       if ($scope.members.length === 0) {
                            $scope.isInvite = true;
                       } else {
                            $scope.isInvite = false;
                       }
                  });
     };
     $scope.getall = function () {
          alert();
          $scope.isSearch = true;
          $scope.getmem();
     };
     $scope.setMember = function (member) {
          $scope.isSearch = false;
          $scope.searchmem = member;
          $scope.searchname = member.Username;
     };
     $scope.save = function () {
          $http.post(BASE_URL + 'app/members/addMember/' + $stateParams.id, $scope.searchmem)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status !== 0) {
                            $scope.add_member_form = false;
                            $scope.isInvite = false;
                            //$scope.memberslistdata.filter = $scope.searchmem.Username;
                            $scope.members = {};
                            $scope.searchmem = {};
                            $scope.searchname = "";
                            $scope.getMembers($scope.memberslistdata.currentPage);

                       } else {
                            $scope.cancel_add_member();
                       }

                  });
     };

     $scope.invite = function () {
          $http.post(BASE_URL + 'app/members/inviteMember/' + $stateParams.id, {"email": $scope.searchname})
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status !== 0) {
                            $scope.add_member_form = false;
                            $scope.isInvite = false;
                            $scope.members = {};
                            $scope.searchmem = {};
                            $scope.searchname = "";
                       } else {
                            $scope.cancel_add_member();
                       }

                  });
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

     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getMembers = function (page) {
          $scope.memberslistdata.currentPage = page;
          $http.post(BASE_URL + 'app/service/memberslist_json/' + $stateParams.id + "", $scope.memberslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.memberslist = data.member_detail;
                       $scope.memberslistdata.total = data.total;
                       $scope.memberslistdata.currentPage = data.page;
                       $scope.memberslistdata.current_user_id = data.current_user_id;
                       //alert($scope.memberslistdata.current_user_id);
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
     $scope.add_member = function () {
          $scope.add_member_form = true;
     };
     $scope.cancel_add_member = function () {
          $scope.isInvite = false;
          $scope.add_member_form = false;
          $scope.members = {};
          $scope.searchmem = {};
          $scope.searchname = "";
     };


     $scope.memberlist_content = true;

     $scope.member_delete = function (member_id) {
          $http({method: 'POST', url: BASE_URL + 'app/members/delete_member', data: {"member_id": member_id,
                    "org_id": $stateParams.id
               }})
                  .success(function (data) {
                       $scope.warning_popup = false;
                       $scope.getMembers($scope.memberslistdata.currentPage);

                  }).error(function (data) {
               alert(data);
          });
     };
     $scope.delete_warning = function (member_id) {
          $scope.warning_popup = true;

          $scope.delete_id = member_id;

     };
     $scope.cancel_warning = function () {
          $scope.warning_popup = false;
     };
     $scope.resetSort = function () {
          $scope.memberheaders = {
               Email: {},
               FullName: {},
               Status: {}
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
     };
}