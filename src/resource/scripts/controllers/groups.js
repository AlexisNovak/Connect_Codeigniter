

angular
        .module('6connect')
        .controller('groupsCtrl', groupsCtrl);


function groupsCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.groups = {};
     $scope.new_group = {};
     $scope.isInvite = false;
     $scope.isSearch = false;
     $scope.add_group_form = false;
     $scope.aslist = {};
     $scope.amlist = {};
     $scope.errors = {};
     $scope.group = {};
     $scope.init_count = function () {
          $http.post(BASE_URL + 'app/groups/team_count/' + $stateParams.id).success(function (data) {
               if (data.total !== undefined && data.total === 0) {
                    $scope.show_init = true;
                    $scope.show_table = false;
               } else {
                    $scope.show_init = false;
                    $scope.show_table = true;
               }
          });
     };
     $scope.init_count();
     $scope.save = function () {
          $scope.new_group.org_id = $stateParams.id;
          $http.post(BASE_URL + 'app/groups/save/', $scope.new_group)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status !== 0) {
                            $scope.add_group_form = false;
                            $scope.errors = {};
                            $scope.new_group = {};
                            $scope.init_count();
                            $scope.getgroups($scope.groupslistdata.currentPage);

                       } else {
                            $scope.errors = data.errors;
                       }

                  });
     };


     $scope.groupsperpage = [{
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

     $scope.groupslistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.groupsperpage[2]};

     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getgroups = function (page) {
          $scope.groupslistdata.currentPage = page;
          $http.post(BASE_URL + 'app/groups/groupslist_json/' + $stateParams.id + "", $scope.groupslistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.groupslist = data.group_detail;
                       $scope.groupslistdata.total = data.total;
                       $scope.groupslistdata.currentPage = data.page;
                       $scope.groupslistdata.current_group_id = data.current_group_id;
                       //alert($scope.groupslistdata.current_group_id);
                  });
     };
     $scope.getgroups($scope.groupslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.groupslistdata.perpage_value = $scope.groupslistdata.perpage.value;
          $scope.getgroups($scope.groupslistdata.currentPage);
     };
     $scope.findgroups = function () {
          $scope.getgroups(1);
     };
     $scope.add_group = function () {
          $scope.add_group_form = true;
     };
     $scope.cancel_add_group = function () {
          $scope.add_group_form = false;
          $scope.errors = {};
          $scope.new_group = {};
     };
     $scope.add_member = function () {
          $scope.getall();
          $scope.add_member_form = true;
     };
     $scope.cancel_add_member = function () {
          $scope.isInvite = false;
          $scope.add_member_form = false;
          $scope.validemail = false;
          $scope.members = {};
          $scope.searchmem = {};
          $scope.searchname = "";
          $scope.isDisabled = false;
     };
     $scope.cancel_add_member();
     $scope.getall = function () {
          if ($scope.searchname.length > 2 && $scope.searchmem.Userid === undefined) {

               $scope.isSearch = true;
               $http.post(BASE_URL + 'app/groups/get_members/', {"search": $scope.searchname, "org_id": $stateParams.id})
                       .success(function (data) {
                            // alert(JSON.stringify(data));
                            $scope.members = data.members;
                            $scope.isSearch = true;
                            if ($scope.members.length === 0) {
                                 $scope.isInvite = true;
                            } else {
                                 $scope.isInvite = false;
                            }
                       });
          }
     };
     $scope.setMember = function (member) {
          $scope.isSearch = false;
          $scope.searchmem = member;
          $scope.searchname = member.Username + " " + member.Email + " ";
     };
     $scope.save_member = function () {
          $http.post(BASE_URL + 'app/groups/add_member_to_group',
                  {group: $scope.group.id, org_id: $stateParams.id, user_id: $scope.searchmem.Userid}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status == 1) {
                    $scope.get_assigned_members();
               }
               $scope.cancel_add_member();
          }).error(function (data) {
               alert(data);
          });
     }
     $scope.grouplist_content = true;
     $scope.get_assigned_members = function () {
          $http.post(BASE_URL + 'app/groups/get_assigned_members', {"group_id": $scope.group.id, "org_id": $stateParams.id}).success(function (data) {

               $scope.amlist = data.members;
          });
     };
     $scope.group_detail = function (group_id) {
          $scope.group.id = group_id;
          $scope.grouplist_content = false;
          $http({method: 'POST', url: BASE_URL + 'app/groups/get_detail', data: {"group_id": group_id, "org_id": $stateParams.id}}).success(function (data) {

               $scope.groups_detail = true;
               // alert(JSON.stringify(data));
               $scope.group_detail_data = data.details;
               $scope.aslist = data.services;
               $scope.get_assigned_members();

          }).error(function (data) {
               alert(data);
          });
     };
     $scope.detail_back = function () {
          $scope.groups_detail = false;
          $scope.grouplist_content = true;
          $scope.group = {};
     };

     $scope.group_edit = function (group_id) {
          $scope.grouplist_content = false;
          $scope.editgroup = {};
          $http({method: 'POST', url: BASE_URL + 'app/groups/get_detail', async: false, data: {"group_id": group_id, "org_id": $stateParams.id}}).success(function (data) {
               $scope.editgroup = data.details;
               $scope.groups_edit = true;
          }).error(function (data) {
               alert(data);
          });
     };
     $scope.cancel_edit_group = function () {
          $scope.groups_edit = false;
          $scope.grouplist_content = true;
     };

     $scope.update = function () {
          $http({
               method: 'POST',
               url: BASE_URL + 'app/groups/save/',
               data: $scope.editgroup})
                  .success(function (data) {
                       if (data.reload === 1) {
                            $window.location.reload();
                       } else {
                            notify({
                                 message: data.msg,
                                 classes: data.class,
                                 templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                            });
                            $scope.getgroups($scope.groupslistdata.currentPage);
                            $scope.groups_edit = false;
                            $scope.grouplist_content = true;
                       }
                  }).error(function (data) {
               alert(data);
          });
     };

     $scope.group_delete = function (group_id) {
          $http({method: 'POST', url: BASE_URL + 'app/groups/delete_group', data: {"group_id": group_id,
                    "org_id": $stateParams.id
               }})
                  .success(function (data) {
                       $scope.warning_popup = false;
                       $scope.init_count();
                       $scope.getgroups($scope.groupslistdata.currentPage);

                  }).error(function (data) {
               alert(data);
          });
     };
     $scope.delete_warning = function (group_id) {
          $scope.warning_popup = true;
          $scope.delete_id = group_id;
     };
     $scope.cancel_warning = function () {
          $scope.warning_popup = false;
     };
     $scope.resetSort = function () {
          $scope.groupheaders = {
               name: {},
               code: {},
               description: {},
               status: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.groupheaders[column].reverse === undefined) {
               $scope.groupheaders[column].reverse = false;
          } else {
               $scope.groupheaders[column].reverse = !$scope.groupheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.groupheaders[column].reverse;
     };
     $scope.resetSsort = function () {
          $scope.assignedservices = {
               display_name: {},
               service_id: {},
               description: {}
          };
     };
     $scope.resetSsort();
     $scope.ssort = function (column) {
          if ($scope.orderBySfield !== column)
               $scope.resetSsort();
          if ($scope.assignedservices[column].reverse === undefined) {
               $scope.assignedservices[column].reverse = false;
          } else {
               $scope.assignedservices[column].reverse = !$scope.assignedservices[column].reverse;
          }
          $scope.orderBySfield = column;
          $scope.reverseSsort = $scope.assignedservices[column].reverse;
     };

     $scope.resetMsort = function () {
          $scope.assignedmembers = {
               Email: {},
               FullName: {},
               role: {},
               Status: {}
          };
     };
     $scope.resetMsort();
     $scope.Msort = function (column) {
          if ($scope.orderByMfield !== column)
               $scope.resetMsort();
          if ($scope.assignedmembers[column].reverse === undefined) {
               $scope.assignedmembers[column].reverse = false;
          } else {
               $scope.assignedmembers[column].reverse = !$scope.assignedmembers[column].reverse;
          }
          $scope.orderByMfield = column;
          $scope.reverseMsort = $scope.assignedmembers[column].reverse;
     };

     $scope.group_suspend = function (group_id) {
          $http({method: 'POST', url: BASE_URL + 'app/groups/suspend_group', data: {"group_id": group_id,
                    "org_id": $stateParams.id, "status": $scope.suspendstatus
               }})
                  .success(function (data) {
                       $scope.suspend_warning_popup_grp = false;
                       $scope.getgroups($scope.groupslistdata.currentPage);

                  }).error(function (data) {
               alert(data);
          });
     };
     $scope.suspend_warning_grp = function (group) {
          $scope.suspend_warning_popup_grp = true;
          $scope.suspend_id = group.id;
          $scope.suspendstatus = group.status;
     };
     $scope.cancel_suspend_warning_grp = function () {
          $scope.suspend_warning_popup_grp = false;
     };
}