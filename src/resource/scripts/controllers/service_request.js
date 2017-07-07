angular.module('6connect').controller('srequestCtrl', srequestCtrl)
        .controller('newsrequestCtrl', newsrequestCtrl)
        .controller('viewrequestCtrl', viewrequestCtrl)
        .controller('tenderorderCtrl', tenderorderCtrl);
function srequestCtrl($scope, $http, $state, notify) {
     $scope.adminorglist = {};
     $http.post(BASE_URL + 'app/organisation/admin_organisations').success(
             function (data) {
                  $scope.adminorglist = data.organisations;
             });
     $scope.reqperpage = [{
               value: 5,
               label: 5
          }, {
               value: 10,
               label: 10
          }, {
               value: 15,
               label: 15
          }, {
               value: 20,
               label: 20
          }];
     $scope.reqlistdata = {
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.reqperpage[2],
          org_id: "",
          "category": "all"
     };

     $scope.orderByField = 'req_id';
     $scope.reverseSort = true;
     $scope.show_init = false;
     $scope.show_table = false;
     $scope.req_id = 0;
     $scope.cancel_warning_popup = false;
     $scope.save_request_popup = false;
     $scope.init_count = function () {
          $http.post(BASE_URL + 'app/service_request/get_srequest_count')
                  .success(function (data) {
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
     $scope.getSrequests = function (page) {
          $scope.reqlistdata.currentPage = page;
          $http.post(BASE_URL + 'app/service_request/srequestlist_json',
                  $scope.reqlistdata).success(function (data) {
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.reqlist = data.srequests;
               $scope.reqlistdata.total = data.total;
               $scope.reqlistdata.currentPage = data.page;
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'tender_requests.service') {
               $scope.init_count();
               $scope.getSrequests($scope.reqlistdata.currentPage);
          }
     });
     $scope.perpagechange = function () {

          $scope.reqlistdata.perpage_value = $scope.reqlistdata.perpage.value;
          $scope.getSrequests($scope.reqlistdata.currentPage);
     };
     $scope.findreq = function () {
          $scope.getSrequests($scope.reqlistdata.currentPage);
     };
     $scope.show_cancel_warning = function (id) {
          if ($scope.save_request_popup) {
               $scope.cancel_request_change();
          }
          $scope.req_id = id;
          $scope.cancel_warning_popup = true;
     };
     $scope.hide_cancel_warning = function () {
          $scope.req_id = 0;
          $scope.cancel_warning_popup = false;
     };
     $scope.cancel_serive_request = function () {
          $http.post(BASE_URL + "app/service_request/cancel_request", {id: $scope.req_id}).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH
                            + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.getSrequests($scope.reqlistdata.currentPage);
               }
               $scope.hide_cancel_warning();
          });
     };
     $scope.change_request = function (id) {
          if ($scope.cancel_warning_popup) {
               $scope.hide_cancel_warning();
          }
          $scope.req_id = id;
          $scope.save_request_popup = true;
     };
     $scope.edit_request_details = function () {
          $scope.save_request_popup = false;
          $state.go('tender_requests.service.new_request', {
               request_id: $scope.req_id
          });
     };
     $scope.cancel_request_change = function () {
          $scope.save_request_popup = false;
     }
     $scope.resetSort = function () {
          $scope.reqheaders = {
               title: {},
               type: {},
               description: {},
               duration: {},
               payment: {},
               org_name: {},
               status: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.reqheaders[column].reverse === undefined) {
               $scope.reqheaders[column].reverse = false;
          } else {
               $scope.reqheaders[column].reverse = !$scope.reqheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.reqheaders[column].reverse;
     };
}
function newsrequestCtrl($scope, $http, $stateParams, notify, $state, $timeout) {
     $scope.dropezone_config = {
          url: BASE_URL + "app/service_request/upload",
          dictDefaultMessage: "Add upto 5 files by drag-n-drop into here or click here to upload.",
          paramName: "uploadfile",
          maxThumbnailFilesize: 20480,
          acceptedFiles: ".doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf",
          parallelUploads: 1,
          thumbnailWidth: 120,
          autoProcessQueue: true,
          addRemoveLinks: true, maxFiles: 5
     };
     $scope.remove_url = BASE_URL + "app/service_request/remove_upload";
     $scope.file = {};
     $scope.uploads = [];
     $scope.stypelist = [];
     $scope.req = {
          'duration': 12,
          'type': 1
     };
     $scope.adminorglist = {};
     $http.post(BASE_URL + 'app/organisation/admin_organisations').success(
             function (data) {
                  $scope.adminorglist = data.organisations;
                  if ($scope.adminorglist[0]) {
                       $scope.req.org_id = $scope.adminorglist[0].org_id
                  }
             });
     $scope.success = false;
     $scope.create_req_form = false;
     $scope.errors = {};
     $scope.edit_request_id = $stateParams.request_id;

     $scope.get_request_details = function (id) {
          if (id) {
               $http.post(BASE_URL + 'app/service_request/request_details/' + id)
                       .success(function (data) {
                            if (!data.is_admin || data.request.status == '0')
                                 $state.go('tender_requests.service.view_request', {
                                      request_id: id
                                 });
                            else {
                                 var req = data.request;
                                 $scope.req = {
                                      "req_id": req.req_id,
                                      "title": req.title,
                                      "org_id": parseInt(data.org_id),
                                      "description": req.description,
                                      "type": parseInt(req.type_id),
                                      "duration": req.duration,
                                      "delpermonth": req.delivery_p_m,
                                      "payment": req.payment,
                                      "compensation": req.other_conditions,
                                      'uploads': req.uploads,
                                      'open_bid': req.open_bid
                                 };
                                 $scope.uploads = req.uploads;
                                 $scope.mock();
                                 $scope.check_org_status();
                            }
                       });
          }
     };
     $scope.get_request_details($scope.edit_request_id);
     $scope.save = function () {
          $scope.req.uploads = $scope.uploads;
          $http.post(BASE_URL + 'app/service_request/save', $scope.req).success(
                  function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH
                                    + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            ga('send', {
                                 hitType: 'event',
                                 eventCategory: 'Service',
                                 eventAction: 'add',
                                 eventLabel: 'New Service Tender Request'
                            });
                            $state.go('^');
                       } else if (data.errors) {
                            $scope.errors = data.errors;
                            $timeout(function () {
                                 var error_fields = angular.element('.new_req').find('.error');
                                 if (error_fields.length > 0) {
                                      error_fields[0].focus();
                                 }
                            }, 500);
                       }
                  }).error(function (data) {
          });
     };
     $scope.cancel_create_req = function () {
          $scope.resetDropzone();
          $state.go('^');
     };
     $scope.org_info = {};
     $scope.reset_bidding = function () {
          $scope.org_info = {};
          $scope.req.open_bid = true;

     };
     $scope.change_bidding = function () {
          $scope.reset_bidding();
          $scope.check_org_status();

     };
     $scope.check_org_status = function () {
          $http.post(BASE_URL + 'app/service_request/get_org_status', {org_id: $scope.req.org_id}).success(function (data) {
               if (data.open_bid == true) {
                    $scope.org_info.open_bid = true;
               } else {
                    $scope.org_info.open_bid = false;
                    $scope.req.open_bid = false;
               }
               $scope.org_info.c_count = data.c_count;
          });
     };

}
function viewrequestCtrl($scope, $http, $stateParams, $state, notify,
        $rootScope) {
     $scope.close_intro = function () {
          $state.go('^');
     };

     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
     }
     ;

     $scope.goback = function () {
          $state.go('^');
     };
     $scope.bidcount = {};
     $scope.bidperpage = [{
               value: 5,
               label: 5
          }, {
               value: 10,
               label: 10
          }, {
               value: 15,
               label: 15
          }, {
               value: 20,
               label: 20
          }];
     $scope.bidlistdata = {
          request_id: $stateParams.request_id,
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.bidperpage[2]
     };

     $scope.orderByField_bid = '';
     $scope.reverseSort_bid = false;
     $scope.show_service_info = false;
     $scope.save_request_popup = false;
     $scope.sdetail = {};

     $scope.change_request = function () {
          $scope.save_request_popup = true;
     };
     $scope.edit_request_details = function (id) {
          $state.go('tender_requests.service.new_request', {
               request_id: id
          });
     };
     $scope.cancel_request_change = function () {
          $scope.save_request_popup = false;
     }
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {
               id: id
          });
     };
     $scope.view_service_info = function (id) {
          $scope.show_service_info = true;
          $http.post(BASE_URL + 'app/services/get_service_info/' + id).success(
                  function (data) {
                       $scope.sdetail = data.service;
                  });
     };
     $scope.cancel_service_info = function () {
          $scope.show_service_info = false;
          $scope.sdetail = {};
     };
     $scope.getBidders = function (page) {
          $scope.bidlistdata.currentPage = page;
          $http.post(BASE_URL + 'app/service_request/bidderlist_json',
                  $scope.bidlistdata).success(function (data) {
               $scope.bidcount.total = data.total;
               $scope.bidcount.start = data.start;
               $scope.bidcount.end = data.end;
               $scope.bidlist = data.bidders;
               // alert(JSON.stringify($scope.bidlist));
               $scope.bidlistdata.total = data.total;
               $scope.bidlistdata.currentPage = data.page;
          });
     };
     $scope.getBidders($scope.bidlistdata.currentPage);
     $scope.bidperpagechange = function () {
          $scope.bidlistdata.perpage_value = $scope.bidlistdata.perpage.value;
          $scope.getBidders($scope.bidlistdata.currentPage);
     };
     $scope.findBidders = function () {
          $scope.getBidders($scope.bidlistdata.currentPage);
     };
     $scope.accept = function (bid_id, courier) {

          $http.post(BASE_URL + 'app/service_request/accept_bid', {
               bid_id: bid_id,
               request_id: $stateParams.request_id
          }).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.intro = true;
                    $scope.org_id = data.org_id;
                    $scope.courier_name = courier;
               }
          });
     }
     $scope.resetBidSort = function () {
          $scope.bidheaders = {
               id: {},
               courier: {},
               service: {},
               price: {},
               start_time: {},
               description: {}
          };
     };
     $scope.resetBidSort();
     $scope.bidsort = function (column) {
          if ($scope.orderByField_bid !== column)
               $scope.resetBidSort();
          if ($scope.bidheaders[column].reverse === undefined) {
               $scope.bidheaders[column].reverse = false;
          } else {
               $scope.bidheaders[column].reverse = !$scope.bidheaders[column].reverse;
          }
          $scope.orderByField_bid = column;
          $scope.reverseSort_bid = $scope.bidheaders[column].reverse;
     };

     /*
      * Messages tab
      * 
      */
     $scope.comment = {};
     $scope.msgcount = {};
     $scope.messages = {};
     $http.post(BASE_URL + 'app/service_request/messageslist_json', {
          request_id: $stateParams.request_id
     }).success(function (data) {
          $scope.msgcount.total = data.total;
          $scope.msgcount.reply = data.reply;
          $scope.messages = data.messages;
     });

     $scope.savereply = function (msg) {
          if (msg.reply !== null) {
               $http.post(BASE_URL + 'app/service_request/add_reply', {
                    msg_id: msg.message_id,
                    reply: msg.reply
               }).success(function (data) {
                    $scope.msgcount.reply = $scope.msgcount.reply + 1;
                    msg.replytime = moment.format('MMM D YYYY hh:mm a');
               });
          }
     };
     $scope.addcomment = function (msg) {
          if ($scope.comment.content) {
               $http.post(BASE_URL + 'app/service_request/add_comment', {
                    comment: $scope.comment.content,
                    request_id: $stateParams.request_id
               }).success(function (data) {
                    $scope.messages.push(data.last);
                    $scope.comment = {};
               });
          }
     };

     /*
      * 
      * log tab
      */
     $scope.logcount = {};
     $scope.logperpage = [{
               value: 5,
               label: 5
          }, {
               value: 10,
               label: 10
          }, {
               value: 15,
               label: 15
          }, {
               value: 20,
               label: 20
          }];
     $scope.loglistdata = {
          request_id: $stateParams.request_id,
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.logperpage[2]
     };

     $scope.orderByField_log = '';
     $scope.reverseSort_log = false;
     $scope.getLog = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'app/service_request/loglist_json',
                  $scope.loglistdata).success(function (data) {
               $scope.logcount.total = data.total;
               $scope.logcount.start = data.start;
               $scope.logcount.end = data.end;
               $scope.loglist = data.loglist;
               // alert(JSON.stringify($scope.loglist));
               $scope.loglistdata.total = data.total;
               $scope.loglistdata.currentPage = data.page;
          });
     };
     $scope.getLog($scope.loglistdata.currentPage);


     $scope.getAttachments = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/attachlist_json', $scope.loglistdata)
                  .success(function (data) {
                       $scope.attachments = data.loglist;
                       $scope.loglistdata.total = data.total;
                       $scope.loglistdata.currentPage = data.page;
                  });
     };
     $scope.getAttachments(1);

     $scope.logperpagechange = function () {
          $scope.loglistdata.perpage_value = $scope.loglistdata.perpage.value;
          $scope.getLog($scope.loglistdata.currentPage);
     };
}

function tenderorderCtrl($scope, $http, orderService, $state) {

     $scope.$state = $state;
     $scope.orderslist = {};
     $scope.orglist = {};
     $scope.filter_servicelist = [];
     $scope.filter_teamlist = [];
     $scope.ordersperpage = [{
               value: 5,
               label: 5
          }, {
               value: 10,
               label: 10
          }, {
               value: 15,
               label: 15
          }, {
               value: 20,
               label: 20
          }];
     $scope.orderslistdata = {
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          organisation: "",
          "category": "open",
          "service": "all",
          "team": "all",
          "status": "9999",
          perpage: $scope.ordersperpage[2]
     };

     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;
     $scope.init_count = function () {
          $http.post(BASE_URL + 'orders/bidding_orderslist_count').success(
                  function (data) {
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
     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(
             function (data) {
                  $scope.orglist = data.organisations;
             });
     $http.post(BASE_URL + 'orders/services_list').success(function (data) {
          $scope.filter_servicelist = data.services;
     });

     $scope.get_teams = function () {
          $http.post(BASE_URL + 'orders/teamList', {
               'organisation': $scope.orderslistdata.organisation
          }).success(function (data) {
               $scope.filter_teamlist = data.teams;
               $scope.orderslistdata.team = "all";
          });
     };
     $scope.new_delivery_request = function () {
          orderService.setBidding();
          $state.go('delivery_orders.new_order');
     }
     $scope.getOrders = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/bidding_orderslist_json',
                  $scope.orderslistdata).then(function (response) {
               var data = response.data;
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.orderslist = data.order_detail;
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               $scope.orderslistdata.total = data.total;
               $scope.orderslistdata.currentPage = data.page;
               $scope.orderslistdata.current_user_id = data.current_user_id;
               // alert($scope.orderslistdata.current_user_id);
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'tender_requests.delivery') {
               $scope.init_count();
               $scope.getOrders($scope.orderslistdata.currentPage);
          }
     });
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };
     $scope.findteams = function () {
          $scope.get_teams();
          $scope.getOrders(1);
     };
     $scope.findorders = function () {
          $scope.getOrders(1);
     };
     $scope.change_request = function (id) {
          $scope.req_id = id;
          $scope.save_request_popup = true;
     };
     $scope.edit_request_details = function () {
          $scope.save_request_popup = false;
          $state.go('tender_requests.change_request', {
               corder_id: $scope.req_id
          });
     };
     $scope.resetSort = function () {
          $scope.orderheaders = {
               consignment_id: {},
               public_id: {},
               private_id: {},
               service: {},
               collection_address: {},
               delivery_address: {},
               org_name: {},
               status: {},
               cdate: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.orderheaders[column].reverse === undefined) {
               $scope.orderheaders[column].reverse = false;
          } else {
               $scope.orderheaders[column].reverse = !$scope.orderheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.orderheaders[column].reverse;
     };
}
