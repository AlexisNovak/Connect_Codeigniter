angular
        .module('6connect')
        .controller('membersCtrl', membersCtrl)
        .controller('parcelsCtrl', parcelsCtrl);
function membersCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.members = {};
     $scope.searchmem = {};
     $scope.isInvite = false;
     $scope.isSearch = false;
     $scope.add_member_form = false;
     $scope.searchname = "";
     $scope.validemail = false;
     $scope.isDisabled = false;
     $scope.getmem = function () {
          if ($scope.searchname.length > 2 && $scope.searchmem.Userid === undefined) {

               $scope.isSearch = true;
               $http.post(BASE_URL + 'app/members/allmemberslist/', {"search": $scope.searchname})
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
     $scope.setMember = function (member) {
          $scope.isSearch = false;
          $scope.searchmem = member;
          $scope.searchname = member.Username + " " + member.Email + " ";
     };
     $scope.save = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/members/addMember/' + $stateParams.id, $scope.searchmem)
                  .success(function (data) {

                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status != 0) {
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

          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/members/inviteMember/' + $stateParams.id, {"email": $scope.searchname})
                  .success(function (data) {

                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status != 0) {
                            $scope.add_member_form = false;
                            $scope.isInvite = false;
                            $scope.members = {};
                            $scope.searchmem = {};
                            $scope.searchname = "";
                            $scope.getMembers($scope.memberslistdata.currentPage);
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

     $scope.roles = {
          1: "Admin",
          2: "Reviewer",
          3: "Referral"
     };
     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getMembers = function (page) {
          angular.element("#orgmembers_body").hide();
          angular.element("#orgmembers_loading").show();

          $scope.memberslistdata.currentPage = page;
          $http.post(BASE_URL + 'app/members/memberslist_json/' + $stateParams.id + "", $scope.memberslistdata)
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
     $scope.getMembers($scope.memberslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.memberslistdata.perpage_value = $scope.memberslistdata.perpage.value;
          $scope.getMembers($scope.memberslistdata.currentPage);
     };
     $scope.findmembers = function () {
          $scope.getMembers(1);
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


     $scope.memberlist_content = true;
     $scope.member_detail = function (member_id) {
          $scope.memberlist_content = false;
          $http({method: 'POST', url: BASE_URL + 'app/members/get_detail', data: {"member_id": member_id, "org_id": $stateParams.id}}).success(function (data) {

               $scope.members_detail = true;
               $scope.member_detail_data = data[0];
               if ($scope.member_detail_data.role_id == 1) {
                    $scope.member_detail_data.role_name = "Admin";
               } else {
                    $scope.member_detail_data.role_name = "Member";
               }

          }).error(function (data) {
               alert(data);
          });
     }
     $scope.detail_back = function () {
          $scope.members_detail = false;
          $scope.memberlist_content = true;
     }

     $scope.member_edit = function (member_id) {
          $scope.memberlist_content = false;
          $scope.editmember = {};
          $http({method: 'POST', url: BASE_URL + 'app/members/get_detail', async: false, data: {"member_id": member_id, "org_id": $stateParams.id}}).success(function (data) {

               $scope.members_edit = true;
               $scope.member_id = data[0].user_id;
               $scope.editmember = data[0];
          }).error(function (data) {
               alert(data);
          });

          $http({method: 'POST', url: BASE_URL + 'app/groups/get_all_groups_org/' + $stateParams.id}).success(function (data) {
               $scope.grouplist = data;
          }).error(function (data) {
               alert(data);
          });
     }
     $scope.cancel_edit_member = function () {
          $scope.members_edit = false;
          $scope.memberlist_content = true;
     }

     $scope.update = function (member_id) {
          $http({
               method: 'POST',
               url: BASE_URL + 'app/members/update_member',
               data: $scope.editmember})
                  .success(function (data) {
                       if (data.reload == 1) {
                            $window.location.reload();
                       } else {
                            $scope.getMembers($scope.memberslistdata.currentPage);
                            $scope.members_edit = false;
                            $scope.memberlist_content = true;
                       }
                  }).error(function (data) {
               alert(data);
          });
     }

     $scope.member_delete = function (member_id) {
          $http({method: 'POST', url: BASE_URL + 'app/members/delete_member', data: {"member_id": member_id,
                    "org_id": $stateParams.id,
               }})
                  .success(function (data) {
                       $scope.warning_popup = false;
                       $scope.getMembers($scope.memberslistdata.currentPage);

                  }).error(function (data) {
               alert(data);
          });
     }
     $scope.delete_warning = function (member_id) {
          $scope.warning_popup = true;

          $scope.delete_id = member_id;

     }
     $scope.cancel_warning = function () {
          $scope.warning_popup = false;
     }
     $scope.resetSort = function () {
          $scope.memberheaders = {
               Email: {},
               FullName: {},
               Note: {},
               groupname: {},
               role: {},
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
     }
}
function parcelsCtrl($scope, $http, $stateParams, notify, $window) {
     $scope.members = {};
     $scope.searchmem = {};
     $scope.isInvite = false;
     $scope.isSearch = false;
     $scope.add_member_form = false;
     $scope.searchname = "";
     $scope.validemail = false;
     $scope.isDisabled = false;
     $scope.getmem = function () {
          if ($scope.searchname.length > 2 && $scope.searchmem.Userid === undefined) {

               $scope.isSearch = true;
               $http.post(BASE_URL + 'app/parcels/allparcelsslist/', {"search": $scope.searchname})
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
     $scope.setMember = function (member) {
          $scope.isSearch = false;
          $scope.searchmem = member;
          $scope.searchname = member.Username + " " + member.Email + " ";
     };
     $scope.save = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/parcels/addparcels/' + $stateParams.id, $scope.newtype)
                  .success(function (data) {

                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status != 0) {
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

          $scope.isDisabled = true;
          $http.post(BASE_URL + 'app/parcels/inviteparcels/' + $stateParams.id, {"email": $scope.searchname})
                  .success(function (data) {

                       $scope.isDisabled = false;
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status != 0) {
                            $scope.add_member_form = false;
                            $scope.isInvite = false;
                            $scope.members = {};
                            $scope.searchmem = {};
                            $scope.searchname = "";
                            $scope.getMembers($scope.memberslistdata.currentPage);
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

     $scope.roles = {
          1: "Admin",
          2: "Reviewer",
          3: "Referral"
     };
     $scope.orderByField = '';
     $scope.reverseSort = false;

     $scope.getMembers = function (page) {
          angular.element("#orgmembers_body").hide();
          angular.element("#orgmembers_loading").show();

          $scope.memberslistdata.currentPage = page;
          $http.post(BASE_URL + 'app/parcels/parcelslist_json/' + $stateParams.id + "", $scope.memberslistdata)
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
     $scope.getMembers($scope.memberslistdata.currentPage);

     $scope.perpagechange = function () {
          $scope.memberslistdata.perpage_value = $scope.memberslistdata.perpage.value;
          $scope.getMembers($scope.memberslistdata.currentPage);
     };
     $scope.findmembers = function () {
          $scope.getMembers(1);
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


     $scope.memberlist_content = true;
     $scope.member_detail = function (member_id) {
          $scope.memberlist_content = false;
          $http({method: 'POST', url: BASE_URL + 'app/parcels/get_detail', data: {"member_id": member_id, "org_id": $stateParams.id}}).success(function (data) {

               $scope.members_detail = true;
               $scope.member_detail_data = data[0];
               if ($scope.member_detail_data.role_id == 1) {
                    $scope.member_detail_data.role_name = "Admin";
               } else {
                    $scope.member_detail_data.role_name = "Member";
               }

          }).error(function (data) {
               alert(data);
          });
     }
     $scope.detail_back = function () {
          $scope.members_detail = false;
          $scope.memberlist_content = true;
     }

     $scope.member_edit = function (member_id) {
          $scope.memberlist_content = false;
          $scope.editmember = {};
          $http({method: 'POST', url: BASE_URL + 'app/parcels/get_detail', async: false, data: {"member_id": member_id, "org_id": $stateParams.id}}).success(function (data) {

               $scope.members_edit = true;
               $scope.member_id = data[0].user_id;
               $scope.editmember = data[0];
          }).error(function (data) {
               alert(data);
          });

          $http({method: 'POST', url: BASE_URL + 'app/groups/get_all_groups_org/' + $stateParams.id}).success(function (data) {
               $scope.grouplist = data;
          }).error(function (data) {
               alert(data);
          });
     }
     $scope.cancel_edit_member = function () {
          $scope.members_edit = false;
          $scope.memberlist_content = true;
     }

     $scope.update = function (member_id) {
          $http({
               method: 'POST',
               url: BASE_URL + 'app/parcels/update_parcels',
               data: $scope.editmember})
                  .success(function (data) {
                       if (data.reload == 1) {
                            $window.location.reload();
                       } else {
                            $scope.getMembers($scope.memberslistdata.currentPage);
                            $scope.members_edit = false;
                            $scope.memberlist_content = true;
                       }
                  }).error(function (data) {
               alert(data);
          });
     }

     $scope.member_delete = function (member_id) {
          $http({method: 'POST', url: BASE_URL + 'app/parcels/delete_parcels', data: {"ptype_id": member_id,
                    "org_id": $stateParams.id
               }})
                  .success(function (data) {
                       $scope.warning_popup = false;
                       $scope.getMembers($scope.memberslistdata.currentPage);

                  }).error(function (data) {
               alert(data);
          });
     }
     $scope.delete_warning = function (member_id) {
          $scope.warning_popup = true;

          $scope.delete_id = member_id;

     }
     $scope.cancel_warning = function () {
          $scope.warning_popup = false;
     }
     $scope.resetSort = function () {
          $scope.memberheaders = {
               Email: {},
               FullName: {},
               Note: {},
               groupname: {},
               role: {},
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
     }
}