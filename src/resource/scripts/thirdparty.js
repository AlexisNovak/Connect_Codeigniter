

(function () {
     angular.module('6connect', [
          'ui.bootstrap', 'cgNotify'])
})();

angular.module('6connect').controller('thirdpartyCtrl', thirdpartyCtrl);
function thirdpartyCtrl($scope, $http, notify) {
     $scope.accept = {};
     $scope.update = {"status": ""};
     $scope.update_statuslist = {};
     $scope.show_update_status_popup = false;
     $scope.show_accept_popup = false;
     $http.post(BASE_URL + 'thirdparty/orders/statusList').success(function (data) {
          $scope.update_statuslist = data.status;
     });

     $scope.show_update_status = function (order_id) {
          if ($scope.show_accept_popup) {
               $scope.cancel_accept();
          }
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
          $http.post(BASE_URL + 'thirdparty/orders/update_status', $scope.update).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_update_status();
                    window.location.reload(false);
               } else {
                    if (data.errors) {
                         $scope.update.errors = data.errors;
                    }
               }
          });
     };

     $scope.show_accept = function (order_id, flag) {
          if ($scope.show_update_status_popup) {
               $scope.cancel_update_status();
          }
          $scope.show_accept_popup = true;
          $scope.accept.order_id = order_id;
          if (flag == 1) {
               $scope.show_price_field = false;
          } else {
               $scope.show_price_field = true;
          }
     };
     $scope.cancel_accept = function () {
          $scope.isDisabled = false;
          $scope.show_accept_popup = false;
          $scope.accept = {};
     };
     $scope.accept_order = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'thirdparty/orders/accept', $scope.accept).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               $scope.isDisabled = false;
               if (data.status === 1) {
                    $scope.cancel_accept();
                    window.location.reload(false);
               } else {
                    if (data.errors) {
                         $scope.accept.errors = data.errors;
                    }
               }
          });
     };

     $scope.comment = {};
     $scope.pod = {};
     $scope.pods = {};
     $scope.signature = {};
     $scope.show_pod_popup = false;
     $scope.get_pods = function () {
          $http.post(BASE_URL + 'thirdparty/orders/get_pods', {order_id: order_id})
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
               $http.post(BASE_URL + 'thirdparty/orders/add_comment', {comment: $scope.comment.content, order_id: id})
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
          $scope.pod.order_id = order_id;
          $http.post(BASE_URL + 'thirdparty/orders/add_new_pod', $scope.pod)
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
     $scope.loglistdata = {order_id: order_id, perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.logperpage[2]};

     $scope.orderByField_log = '';
     $scope.reverseSort_log = false;
     $scope.getLog = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'thirdparty/orders/loglist_json', $scope.loglistdata)
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
     $scope.logperpagechange = function () {
          $scope.loglistdata.perpage_value = $scope.loglistdata.perpage.value;
          $scope.getLog($scope.loglistdata.currentPage);
     };
}