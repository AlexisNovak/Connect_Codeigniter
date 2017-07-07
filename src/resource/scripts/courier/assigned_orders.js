angular
        .module('6connect')
        .controller('assigned_orderCtrl', assigned_orderCtrl)
        .controller('view_orderCtrl', view_orderCtrl);
function assigned_orderCtrl($scope, $http, $stateParams, $state, notify) {
     $scope.$state = $state;
     $scope.orderslist = {};
     $scope.orglist = {};
     $scope.accept = {};
     $scope.update = {"status": ""};
     $scope.change_price = {};
     $scope.allowcancel = {};
     $scope.rejectorder = {};
     $scope.update_statuslist = {};
     $scope.filter_servicelist = {};
     $scope.filter_statuslist = {};
     $scope.ordersperpage = [{
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

     $scope.orderslistdata = {perpage_value: 15, perpage: $scope.ordersperpage[2], currentPage: 1, total: 0, organisation: "", "service": "all", "status": "9999"};


     $scope.orderByField = 'consignment_id';
     $scope.reverseSort = true;
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
          $scope.org_dropdown = false;
          $scope.orderslistdata.organisation = $stateParams.id;
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'couriers/associate_orglist').success(function (data) {
               $scope.orglist = data.organisations;
          });
     }
     $http.post(BASE_URL + 'couriers/assigned_orders/assigned_services').success(function (data) {
          $scope.filter_servicelist = data.services;
     });
     $http.post(BASE_URL + 'couriers/assigned_orders/allstatusList').success(function (data) {
          $scope.filter_statuslist = data.status;
          $scope.filter_statuslist[$scope.filter_statuslist.length] = {
               display_name: "Pending",
               status_id: "9999"
          };
     });
     $http.post(BASE_URL + 'couriers/assigned_orders/statusList').success(function (data) {
          $scope.update_statuslist = data.status;
     });
     $scope.getOrders = function (page) {
          var tmp = {};
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          angular.copy($scope.orderslistdata, tmp);
          if ($scope.orderslistdata.status == 9999) {
               tmp.status = 'all';
          }
          $http.post(BASE_URL + 'couriers/assigned_orders/get_assignedorders_json', tmp).success(function (data) {
               $scope.total = data.total;
               $scope.start = data.start;
               $scope.end = data.end;
               $scope.orderslist = [];
               if ($scope.orderslistdata.status == 9999) {
                    angular.forEach(data.order_detail, function (order, key) {
                         if (order.consignment_status_id != 401 && order.consignment_status_id != 402 && order.consignment_status_id != 501) {
                              $scope.orderslist.push(order);
                         }
                         ;
                    });
               } else {
                    $scope.orderslist = data.order_detail;
               }
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               $scope.orderslistdata.total = data.total;
               // $scope.orderslistdata.currentPage = data.page;
               $scope.orderslistdata.current_user_id = data.current_user_id;
               //alert($scope.orderslistdata.current_user_id);
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'assigned_orders')
               $scope.getOrders($scope.orderslistdata.currentPage);
     });
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };
     $scope.findorders = function () {
          $scope.getOrders(1);
     };
     $scope.resetSort = function () {
          $scope.orderheaders = {
               public_id: {},
               private_id: {},
               username: {},
               service: {},
               collection_address: {},
               delivery_address: {},
               org_name: {},
               status: {},
               cdate: {}
          };
     };
     $scope.reset_popups = function () {
          if ($scope.show_accept_popup) {
               $scope.cancel_accept();
          }
          if ($scope.show_change_price_popup) {
               $scope.cancel_change_price();
          }
          if ($scope.show_allow_cancel_popup) {
               $scope.cancel_allow_cancel();
          }
          if ($scope.show_update_status_popup) {
               $scope.cancel_update_status();
          }
     };
     $scope.show_update_status = function (order_id) {
          $scope.reset_popups();
          $scope.show_update_status_popup = true;
          $scope.update.order_id = order_id;
     };
     $scope.cancel_update_status = function () {
          $scope.show_update_status_popup = false;
          $scope.update = {"status": ""};
          $scope.isDisabled = false;
     };
     $scope.update_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/assigned_orders/update_status', $scope.update).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_update_status();
                    $scope.getOrders($scope.orderslistdata.currentPage);
                    $scope.getOrders();
               } else {
                    if (data.errors) {
                         $scope.update.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_change_price = function (order_id) {
          $scope.reset_popups();
          $scope.show_change_price_popup = true;
          $scope.change_price.order_id = order_id;
     };
     $scope.cancel_change_price = function () {
          $scope.show_change_price_popup = false;
          $scope.change_price = {};
          $scope.isDisabled = false;
     };
     $scope.add_change_price = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/assigned_orders/change_price', $scope.change_price).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_change_price();
                    $scope.getOrders();
               } else {
                    if (data.errors) {
                         $scope.change_price.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_accept = function (order_id, flag, price) {
          $scope.reset_popups();
          $scope.show_accept_popup = true;
          $scope.accept.order_id = order_id;
          if (flag == 1) {
               $scope.show_price_field = false;
          } else {
               $scope.show_price_field = true;
               $scope.accept.price = price;
          }
     };
     $scope.cancel_accept = function () {
          $scope.isDisabled = false;
          $scope.show_accept_popup = false;
          $scope.accept = {};
     };
     $scope.accept_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/assigned_orders/accept', $scope.accept).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    angular.forEach($scope.orderslist, function (order, key) {
                         if (order.consignment_id == $scope.accept.order_id) {
                              $scope.orderslist[key].private_id = $scope.accept.private_id;
                              $scope.orderslist[key].price = $scope.accept.price;
                              console.log($scope.orderslist[key]);
                         }
                         ;
                    });
                    $scope.cancel_accept();
                    $scope.getOrders($scope.orderslistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.accept.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_allow_cancel = function (order_id) {
          $scope.reset_popups();
          $scope.show_allow_cancel_popup = true;
          $scope.allowcancel.order_id = order_id;
     };
     $scope.cancel_allow_cancel = function () {
          $scope.show_allow_cancel_popup = false;
          $scope.allowcancel = {};
          $scope.isDisabled = false;
     };
     $scope.allow_cancel = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/assigned_orders/allow_cancel', $scope.allowcancel).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_allow_cancel();
                    $scope.getOrders($scope.orderslistdata.currentPage);
               } else {
                    if (data.errors) {
                         $scope.allowcancel.errors = data.errors;
                    }
               }
          });
     };
     $scope.show_reject_order = function (order_id) {
          $scope.reset_popups();
          $scope.show_reject_order_popup = true;
          $scope.rejectorder.order_id = order_id;
     };
     $scope.cancel_reject_order = function () {
          $scope.show_reject_order_popup = false;
          $scope.rejectorder = {reason: ''};
          $scope.isDisabled = false;
     };
     $scope.reject_order = function () {
          if ($scope.rejectorder.reason) {
               $scope.isDisabled = true;
               $scope.rejectorder.reasonerror = "";
               $http.post(BASE_URL + 'couriers/assigned_orders/reject_order', $scope.rejectorder).success(function (data) {
                    notify({
                         message: data.msg,
                         classes: data.class,
                         templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    $scope.isDisabled = false;
                    if (data.status === 1) {
                         $scope.cancel_reject_order();
                         $scope.getOrders($scope.orderslistdata.currentPage);
                    } else {
                         if (data.errors) {
                              $scope.rejectorder.errors = data.errors;
                         }
                    }
               });
          } else {
               $scope.rejectorder.reasonerror = "Please Give a reason.";
          }
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
function view_orderCtrl($scope, $http, $stateParams, $state, notify, Lightbox) {
     $scope.images = [];
     $scope.activetab = {};
     if ($stateParams.activetab !== undefined) {
          var tab = $stateParams.activetab;
          if (tab == "message") {
               $scope.activetab.msg = true;
          }
     }
     $scope.comment = {};
     $scope.pod = {};
     $scope.pods = {};
     $scope.signature = {};
     $scope.show_pod_popup = false;
     $scope.openLightboxModal = function (index) {
          $scope.images = [index];
          Lightbox.openModal($scope.images, 0);
     };
     $scope.goback = function () {
          $state.go('^');
     };
     $scope.get_pods = function () {
          $http.post(BASE_URL + 'couriers/assigned_orders/get_pods', {order_id: $stateParams.aorder_id})
                  .success(function (data) {
                       if (data.pods) {
                            $scope.pods = data.pods;
                       }
                       if (data.signature) {
                            $scope.signature = data.signature;

                       }
                  });
     };
     $scope.get_pods();
     $scope.addcomment = function (id) {
          $scope.isDisabled = true;
          if ($scope.comment.content) {
               $http.post(BASE_URL + 'couriers/assigned_orders/add_comment', {comment: $scope.comment.content, order_id: id})
                       .success(function (data) {
                            $scope.isDisabled = false;
                            $scope.comment = {};
                            if (data.last) {
                                 angular.element("#messages").prepend(data.last);
                            }
                       });
          }
     };
     $scope.save_pod = function () {
          $scope.pod.order_id = $stateParams.aorder_id;
          $http.post(BASE_URL + 'couriers/assigned_orders/add_new_pod', $scope.pod)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.cancel_pod();
                            $scope.get_pods();
                       } else {
                            if (data.errors) {
                                 $scope.pod.errors = data.errors;
                            }
                       }
                  });
     };
     $scope.show_pod = function () {
          $scope.show_pod_popup = true;
     };
     $scope.cancel_pod = function () {
          clean_dropzone();
          $scope.show_pod_popup = false;
          $scope.pod = {};
     };
     $scope.logcount = {};
     $scope.attach = {};
     $scope.logperpage = [{
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
     $scope.loglistdata = {order_id: $stateParams.aorder_id, perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.logperpage[2]};

     $scope.orderByField_log = '';
     $scope.reverseSort_log = false;
     $scope.getLog = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'couriers/assigned_orders/loglist_json', $scope.loglistdata)
                  .success(function (data) {
                       $scope.logcount.total = data.total;
                       $scope.logcount.start = data.start;
                       $scope.logcount.end = data.end;
                       $scope.loglist = data.loglist;
                       //alert(JSON.stringify($scope.loglist));
                       $scope.loglistdata.total = data.total;
                       $scope.loglistdata.currentPage = data.page;
                  });
     };
     $scope.getLog($scope.loglistdata.currentPage);
     var iz = 0;
     $scope.addattachment = function (order_id) {
          $('#attachmentfile').click();
          if (iz == 0) {
               $('#attachmentfile').change(function () {
                    if ($("#attachmentfile").val() != '') {
                         $scope.$apply(function () {
                              $scope.processingattach = true;
                         });
                         var formElement = document.querySelector("#attachmentsform");
                         var data = new FormData(formElement);
                         data.append('order_id', order_id);
                         $.ajax({
                              url: BASE_URL + 'orders/uploadattachments',
                              type: 'POST',
                              data: data,
                              cache: false,
                              dataType: 'json',
                              processData: false, // Don't process the files
                              contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                              success: function (data)
                              {
                                   $scope.getAttachments(1);
                                   $scope.processingattach = false;
                                   $("#attachmentfile").val("");
                              }
                         });
                    }
               });
          }
          iz++;
     };



     $scope.getAttachments = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/attachlist_json', $scope.loglistdata)
                  .success(function (data) {
                       $scope.attachmentslist = data.attachmentslist;
                       $scope.attach.total = data.total;
                       $scope.attach.currentPage = data.page;
                       $scope.attach.start = data.start;
                       $scope.attach.end = data.end;
                  });
     };

     $scope.getAttachments(1);

     $scope.deleteAttachments = function (id) {
          $scope.processingattach = true;
          var form = new FormData();
          form.append('id', id);
          $.ajax({
               url: BASE_URL + 'orders/deleteAttach',
               type: 'POST',
               data: form,
               cache: false,
               dataType: 'json',
               processData: false, // Don't process the files
               contentType: false, // Set content type to false as jQuery will tell the server its a query string request
               success: function (data)
               {
                    $scope.getAttachments(1);
                    $scope.processingattach = false;
               }
          });
     };



     $scope.logperpagechange = function () {
          $scope.loglistdata.perpage_value = $scope.loglistdata.perpage.value;
          $scope.getLog($scope.loglistdata.currentPage);
     };
     $scope.show_accept = function (order_id, flag, price) {
          $scope.show_accept_popup = true;
          $scope.accept.order_id = order_id;
          if (flag == 1) {
               $scope.show_price_field = false;
          } else {
               $scope.show_price_field = true;
               $scope.accept.price = price;
          }
     };
     $scope.cancel_accept = function () {
          $scope.isDisabled = false;
          $scope.show_accept_popup = false;
          $scope.accept = {};
     };
     $scope.accept_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'couriers/assigned_orders/accept', $scope.accept).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $state.transitionTo($state.current, $stateParams, {reload: true});
               } else {
                    if (data.errors) {
                         $scope.accept.errors = data.errors;
                    }
               }
          });
     };
}
;