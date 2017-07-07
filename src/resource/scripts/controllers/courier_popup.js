

angular.module('6connect')
        .controller('courier_popupCtrl', courier_popupCtrl);
function courier_popupCtrl($scope, $http) {
     $scope.showmore = true;
     $scope.u_courier = {};
     $scope.orglist = {};
     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
          $scope.orglist = data.organisations;
     });
     $scope.show_popup = false;
     $scope.show_more = function () {
          if ($scope.showmore == true) {
               $scope.showmore = false;
          } else {
               $scope.showmore = true;
          }
     };

     $scope.cancel_popup = function () {
          $scope.u_courier = {};
          $scope.show_popup = false;
     };
     $scope.view_popup = function () {
          $scope.show_popup = true;
     };
     $scope.$on("show_courier_popup", function (event, arg) {
          $http.post(BASE_URL + 'app/services/get_courier_profile', {'courier_id': arg.id}).success(function (data) {
               if (data.status === 1) {
                    $scope.u_courier = data.courier;
                    $scope.u_courier.reviews = data.reviews;
                    $scope.u_courier.rating=parseInt(data.courier.rating);
                    console.log(data.courier.rating);
                    $scope.view_popup();
               }
          });
     });

     $scope.show_accept = function (order_id, flag, price) {
          $scope.reset_popups();
          $scope.show_accept_popup = true;
          $scope.accept.order_id = order_id;
          if (flag == 1) {
               $scope.show_price_field = false;
          } else {
               $scope.show_price_field = true;
               if (price != 'false') {
                    $scope.accept.price = price;
               }
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



}
;