angular.module('6connect').controller('servicesCtrl', servicesCtrl);

function servicesCtrl($scope, $http, $stateParams, notify, $rootScope, $state) {
	$scope.services = {};
	$scope.threshold = 0;
	$scope.total_services = false;
	$scope.new_service = {};
	$scope.add_service_form = false;
	$scope.service_id = 0;
	$scope.searchname = {};
	$scope.searchgrpname = {};
	$scope.servicesperpage = [ {
		value : 5,
		label : 5
	}, {
		value : 10,
		label : 10
	}, {
		value : 15,
		label : 15
	}, {
		value : 20,
		label : 20
	} ];
	$scope.serviceslistdata = {
		perpage_value : 15,
		currentPage : 1,
		total : 0,
		perpage : $scope.servicesperpage[2]
	};

	$scope.orderByField = '';
	$scope.reverseSort = false;
	$scope.init_count = function() {
		$http.post(BASE_URL + 'app/services/services_count/' + $stateParams.id)
				.success(function(data) {
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
	$scope.getservices = function(page) {
		$scope.serviceslistdata.currentPage = page;
		$http
				.post(
						BASE_URL + 'app/services/serviceslist_json/'
								+ $stateParams.id + "", $scope.serviceslistdata)
				.success(
						function(data) {
							$scope.total = data.total;
							$scope.start = data.start;
							$scope.end = data.end;
							$scope.serviceslist = data.service_detail;
							$scope.serviceslistdata.total = data.total;
							$scope.serviceslistdata.currentPage = data.page;
							$scope.serviceslistdata.current_service_id = data.current_service_id;
							//alert($scope.serviceslistdata.current_service_id);
						});
	};
	$scope.getservices($scope.serviceslistdata.currentPage);

	$scope.perpagechange = function() {
		$scope.serviceslistdata.perpage_value = $scope.serviceslistdata.perpage.value;
		$scope.getservices($scope.serviceslistdata.currentPage);
	};
	$scope.findservices = function() {
		$scope.getservices(1);
	};

	$scope.servicelist_content = true;
	$scope.service_detail = function(service_id) {
		$scope.service_id = service_id;
		$scope.servicelist_content = false;
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/get_detail',
			data : {
				"service_id" : service_id,
				"org_id" : $stateParams.id
			}
		}).success(function(data) {
			$scope.getMembers($scope.memberslistdata.currentPage);
			$scope.getGroups($scope.groupslistdata.currentPage);

			$scope.services_detail = true;
			// alert(JSON.stringify(data));
			$scope.service_detail_data = data.details;
			$scope.threshold = $scope.service_detail_data.threshold_price;
			$scope.limit_use = $scope.service_detail_data.limit_use;

		}).error(function(data) {
			alert(data);
		});
		$scope.parcelTypePrices = [];
		$scope.getPrices = function() {
			$http.post(BASE_URL + 'app/available_services/get_parcel_prices/' + service_id).success(function(data) {
				if (data.prices)
					$scope.parcelTypePrices = data.prices;
			});
		};
		$scope.getPrices();
	};
	$scope.savethreshold = function(id, price) {
		$http.post(BASE_URL + 'app/services/add_threshold', {
			service_id : id,
			threshold : price
		}).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status === 1) {
				$scope.threshold = price;
			} else {
				$scope.service_detail_data.threshold_price = $scope.threshold;
			}
		});
	};

	$scope.use_public = false;
	$http.get(
			BASE_URL + 'app/organisation/get_use_public_status/'
					+ $stateParams.id).success(function(data) {
		if (data.status) {
			$scope.use_public = data.status;
		}
	});
	$http.get(
			BASE_URL + 'app/organisation/get_service_count/' + $stateParams.id)
			.success(function(data) {
				if (data.total) {
					if (data.total > 0) {
						$scope.total_services = true;
					}
				}
			});
	$scope.show_use_public_confirm = function() {
		$scope.show_confirm_popup = true;
	};
	$scope.cancel_use_public_confirm = function() {
		$scope.show_confirm_popup = false;
		if ($scope.use_public) {
			$scope.use_public = false;
		} else {
			$scope.use_public = true;
		}
	};
	$scope.proceed = function() {
		$http.post(BASE_URL + 'app/organisation/enable_use_public', {
			use_public : $scope.use_public,
			org_id : $stateParams.id
		}).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status === 1) {
				$scope.show_confirm_popup = false;
			} else {
				$scope.cancel_use_public_confirm();
			}
		});
	};

	$scope.show_limit_use_confirm = function() {
		$scope.show_limit_use_popup = true;
	};
	$scope.cancel_limit_use_confirm = function() {
		$scope.show_limit_use_popup = false;
		if ($scope.limit_use) {
			$scope.limit_use = false;
		} else {
			$scope.limit_use = true;
		}
	};
	$scope.update_limit_use = function() {
		$http.post(BASE_URL + 'app/services/limit_use', {
			limit_use : $scope.limit_use,
			org_id : $stateParams.id,
			service_id : $scope.service_detail_data.id
		}).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status === 1) {
				$scope.show_limit_use_popup = false;
			} else {
				$scope.cancel_limit_use_confirm();
			}
		});
	};
	$scope.detail_back = function() {
		$scope.services_detail = false;
		$scope.servicelist_content = true;
		$scope.getservices($scope.serviceslistdata.currentPage);
	};
	$scope.add_service = function() {
		$scope.add_service_form = true;
	};

	$scope.confirm_approve = function(id) {
		$scope.approve_id = id;
		$scope.approve_popup = true;

	};
	$scope.cancel_approve = function() {
		$scope.approve_id = '';
		$scope.approve_popup = false;
	};
	$scope.confirm_reject = function(id) {
		$scope.reject_id = id;
		$scope.reject_popup = true;
	};
	$scope.cancel_reject = function() {
		$scope.reject_id = '';
		$scope.reject_remark = '';
		$scope.reject_popup = false;
	};
	$scope.service_approve = function() {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/approve_service',
			data : {
				"service_id" : $scope.approve_id,
				"org_id" : $stateParams.id
			}
		}).success(function(data) {
			$scope.approve_id = '';
			$scope.approve_popup = false;
			$scope.getservices($scope.serviceslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.service_reject = function() {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/reject_service',
			data : {
				"service_id" : $scope.reject_id,
				"remark" : $scope.reject_remark,
				"org_id" : $stateParams.id
			}
		}).success(function(data) {
			$scope.reject_id = '';
			$scope.reject_remark = '';
			$scope.reject_popup = false;
			$scope.getservices($scope.serviceslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.service_delete = function(service_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/delete_service',
			data : {
				"service_id" : service_id,
				"org_id" : $stateParams.id
			}
		}).success(function(data) {
			if (data.reload) {
				$state.transitionTo($state.current, $stateParams, {
					reload : true
				});
			}
			$scope.warning_popup = false;
			$scope.getservices($scope.serviceslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.delete_warning = function(service_id) {
		$scope.warning_popup = true;

		$scope.delete_id = service_id;

	};
	$scope.cancel_warning = function() {
		$scope.warning_popup = false;
	};

	$scope.service_suspend = function(service_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/suspend_service',
			data : {
				"service_id" : service_id,
				"org_id" : $stateParams.id,
				"status" : $scope.suspendstatus
			}
		}).success(function(data) {
			$scope.suspend_warning_popup_service = false;
			$scope.getservices($scope.serviceslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.suspend_warning_service = function(service) {
		$scope.suspend_warning_popup_service = true;
		$scope.suspend_id = service.id;
		$scope.suspendstatus = service.status;
	};
	$scope.cancel_suspend_warning_service = function() {
		$scope.suspend_warning_popup_service = false;
	};
	$scope.resetSort = function() {
		$scope.serviceheaders = {
			display_name : {},
			service_id : {},
			description : {},
			threshold_price : {},
			org_status : {}
		};
	};
	$scope.resetSort();
	$scope.sort = function(column) {
		if ($scope.orderByField !== column)
			$scope.resetSort();
		if ($scope.serviceheaders[column].reverse === undefined) {
			$scope.serviceheaders[column].reverse = false;
		} else {
			$scope.serviceheaders[column].reverse = !$scope.serviceheaders[column].reverse;
		}
		$scope.orderByField = column;
		$scope.reverseSort = $scope.serviceheaders[column].reverse;
	};
	$scope.view_courier_info = function(id) {
		$rootScope.$broadcast("show_courier_popup", {
			id : id
		});
	};

	/*
	 * 
	 * 
	 */

	$scope.members = {};
	$scope.searchmem = {};
	$scope.isInvite = false;
	$scope.isSearch = false;
	$scope.add_member_form = false;
	$scope.getmem = function() {
		$http.post(BASE_URL + 'app/members/orgmemberslist/', {
			"search" : $scope.searchname.str,
			"org_id" : $stateParams.id
		}).success(function(data) {
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
	$scope.getall = function() {
		$scope.isSearch = true;
		$scope.getmem();
	};
	$scope.setMember = function(member) {
		$scope.isSearch = false;
		$scope.searchmem = member;
		$scope.searchmem.service_id = $scope.service_id;
		$scope.searchname.str = member.Username;
	};
	$scope.saveMember = function() {
		$http.post(BASE_URL + 'app/services/addMember/' + $stateParams.id,
				$scope.searchmem).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status !== 0) {
				$scope.add_member_form = false;
				$scope.isInvite = false;
				//$scope.memberslistdata.filter = $scope.searchmem.Username;
				$scope.members = {};
				$scope.searchmem = {};
				$scope.searchname.str = "";
				$scope.getMembers($scope.memberslistdata.currentPage);

			} else {
				$scope.cancel_add_member();
			}

		});
	};

	$scope.invite = function() {
		$http.post(BASE_URL + 'app/members/inviteMember/' + $stateParams.id, {
			"email" : $scope.searchname.str
		}).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status !== 0) {
				$scope.add_member_form = false;
				$scope.isInvite = false;
				$scope.members = {};
				$scope.searchmem = {};
				$scope.searchname.str = "";
			} else {
				$scope.cancel_add_member();
			}

		});
	};
	$scope.membersperpage = [ {
		value : 5,
		label : 5
	}, {
		value : 10,
		label : 10
	}, {
		value : 15,
		label : 15
	}, {
		value : 20,
		label : 20
	} ];
	$scope.memberslistdata = {
		perpage_value : 10,
		currentPage : 1,
		total : 0,
		perpage : $scope.membersperpage[2]
	};

	$scope.orderByField_mem = '';
	$scope.reverseSort_mem = false;

	$scope.getMembers = function(page) {
		$scope.memberslistdata.currentPage = page;
		$scope.memberslistdata.service = $scope.service_id;
		$http.post(
				BASE_URL + 'app/services/memberslist_json/' + $stateParams.id
						+ "", $scope.memberslistdata).success(function(data) {
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

	$scope.perpagechange_member = function() {
		$scope.memberslistdata.perpage_value = $scope.memberslistdata.perpage.value;
		$scope.getMembers($scope.memberslistdata.currentPage);
	};
	$scope.findmembers = function() {
		$scope.getMembers(1);
	};
	$scope.add_member = function() {
		$scope.add_member_form = true;
		$scope.getall();
	};
	$scope.cancel_add_member = function() {
		$scope.isInvite = false;
		$scope.add_member_form = false;
		$scope.members = {};
		$scope.searchmem = {};
		$scope.searchname.str = "";
	};

	$scope.memberlist_content = true;

	$scope.member_delete = function(member_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/delete_member',
			data : {
				"member_id" : member_id,
				"org_id" : $stateParams.id,
				"service_id" : $scope.service_id
			}
		}).success(function(data) {
			$scope.warning_popup_mem = false;
			$scope.getMembers($scope.memberslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.delete_warning_mem = function(member_id) {
		$scope.warning_popup_mem = true;

		$scope.delete_id = member_id;

	};
	$scope.cancel_warning_mem = function() {
		$scope.warning_popup_mem = false;
	};

	$scope.member_suspend = function(member_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/suspend_member',
			data : {
				"member_id" : member_id,
				"org_id" : $stateParams.id,
				"service_id" : $scope.service_id,
				"status" : $scope.suspendstatus
			}
		}).success(function(data) {
			$scope.suspend_warning_popup_mem = false;
			$scope.getMembers($scope.memberslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.suspend_warning_mem = function(member) {
		$scope.suspend_warning_popup_mem = true;
		$scope.suspend_id = member.id;
		$scope.suspendstatus = member.Status;
	};
	$scope.cancel_suspend_warning_mem = function() {
		$scope.suspend_warning_popup_mem = false;
	};
	$scope.resetSort_mem = function() {
		$scope.memberheaders = {
			Email : {},
			FullName : {},
			Status : {}
		};
	};
	$scope.resetSort_mem();
	$scope.sort_mem = function(column) {
		if ($scope.orderByField_mem !== column)
			$scope.resetSort_mem();
		if ($scope.memberheaders[column].reverse === undefined) {
			$scope.memberheaders[column].reverse = false;
		} else {
			$scope.memberheaders[column].reverse = !$scope.memberheaders[column].reverse;
		}
		$scope.orderByField_mem = column;
		$scope.reverseSort_mem = $scope.memberheaders[column].reverse;
	};
	/*
	 * 
	 * 
	 * 
	 */
	$scope.groups = {};
	$scope.searchgrp = {};
	$scope.isInvite_grp = false;
	$scope.isSearch_grp = false;
	$scope.add_group_form = false;
	$scope.getgrp = function() {
		$http.post(BASE_URL + 'app/groups/orggroupslist/', {
			"search" : $scope.searchgrpname.str,
			"org_id" : $stateParams.id
		}).success(function(data) {
			// alert(JSON.stringify(data));
			$scope.groups = data;
			$scope.isSearch_grp = true;
			if ($scope.groups.length === 0) {
				$scope.isInvite_grp = true;
			} else {
				$scope.isInvite_grp = false;
			}
		});
	};
	$scope.getall_grp = function() {
		$scope.getgrp();
		$scope.isSearch_grp = true;
	};
	$scope.setGroup = function(group) {
		$scope.isSearch_grp = false;
		$scope.searchgrp = group;
		$scope.searchgrp.service_id = $scope.service_id;
		$scope.searchgrpname.str = group.name;
	};
	$scope.saveGroup = function() {
		$http.post(BASE_URL + 'app/services/addGroup/' + $stateParams.id,
				$scope.searchgrp).success(function(data) {
			notify({
				message : data.msg,
				classes : data.class,
				templateUrl : ROOT_PATH + "/resource/partial/notify.html"
			});
			if (data.status !== 0) {
				$scope.add_group_form = false;
				$scope.isInvite_Grp = false;
				//$scope.groupslistdata.filter = $scope.searchgrp.Username;
				$scope.groups = {};
				$scope.searchgrp = {};
				$scope.searchgrpname.str = "";
				$scope.getGroups($scope.groupslistdata.currentPage);

			} else {
				$scope.cancel_add_group();
			}

		});
	};

	$scope.groupsperpage = [ {
		value : 5,
		label : 5
	}, {
		value : 10,
		label : 10
	}, {
		value : 15,
		label : 15
	}, {
		value : 20,
		label : 20
	} ];
	$scope.groupslistdata = {
		perpage_value : 10,
		currentPage : 1,
		total : 0,
		perpage : $scope.groupsperpage[2]
	};

	$scope.orderByField_grp = '';
	$scope.reverseSort_grp = false;

	$scope.getGroups = function(page) {
		$scope.groupslistdata.currentPage = page;
		$scope.groupslistdata.service = $scope.service_id;
		$http.post(
				BASE_URL + 'app/services/groupslist_json/' + $stateParams.id
						+ "", $scope.groupslistdata).success(function(data) {
			$scope.total = data.total;
			$scope.start = data.start;
			$scope.end = data.end;
			$scope.groupslist = data.group_detail;
			$scope.groupslistdata.total = data.total;
			$scope.groupslistdata.currentPage = data.page;
			$scope.groupslistdata.current_user_id = data.current_user_id;
			//alert($scope.groupslistdata.current_user_id);
		});
	};

	$scope.perpagechange_group = function() {
		$scope.groupslistdata.perpage_value = $scope.groupslistdata.perpage.value;
		$scope.getGroups($scope.groupslistdata.currentPage);
	};
	$scope.findgroups = function() {
		$scope.getGroups(1);
	};
	$scope.add_group = function() {
		$scope.getall_grp();
		$scope.add_group_form = true;
	};
	$scope.cancel_add_group = function() {
		$scope.isInvite_grp = false;
		$scope.add_group_form = false;
		$scope.groups = {};
		$scope.searchgrp = {};
		$scope.searchgrpname.str = "";
	};

	$scope.grouplist_content = true;

	$scope.group_delete = function(group_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/delete_group',
			data : {
				"group_id" : group_id,
				"org_id" : $stateParams.id,
				"service_id" : $scope.service_id
			}
		}).success(function(data) {
			$scope.warning_popup_grp = false;
			$scope.getGroups($scope.groupslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.delete_warning_grp = function(group_id) {
		$scope.warning_popup_grp = true;

		$scope.delete_id = group_id;

	};
	$scope.cancel_warning_grp = function() {
		$scope.warning_popup_grp = false;
	};
	$scope.group_suspend = function(group_id) {
		$http({
			method : 'POST',
			url : BASE_URL + 'app/services/suspend_group',
			data : {
				"group_id" : group_id,
				"org_id" : $stateParams.id,
				"service_id" : $scope.service_id,
				"status" : $scope.suspendstatus
			}
		}).success(function(data) {
			$scope.suspend_warning_popup_grp = false;
			$scope.getGroups($scope.groupslistdata.currentPage);

		}).error(function(data) {
			alert(data);
		});
	};
	$scope.suspend_warning_grp = function(group) {
		$scope.suspend_warning_popup_grp = true;
		$scope.suspend_id = group.group_id;
		$scope.suspendstatus = group.Status;
	};
	$scope.cancel_suspend_warning_grp = function() {
		$scope.suspend_warning_popup_grp = false;
	};
	$scope.resetSort_grp = function() {
		$scope.groupheaders = {
			name : {},
			code : {},
			Status : {}
		};
	};
	$scope.resetSort_grp();
	$scope.sort_grp = function(column) {
		if ($scope.orderByField_grp !== column)
			$scope.resetSort_grp();
		if ($scope.groupheaders[column].reverse === undefined) {
			$scope.groupheaders[column].reverse = false;
		} else {
			$scope.groupheaders[column].reverse = !$scope.groupheaders[column].reverse;
		}
		$scope.orderByField_grp = column;
		$scope.reverseSort_grp = $scope.groupheaders[column].reverse;
	};

}