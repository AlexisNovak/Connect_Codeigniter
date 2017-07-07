/**
 * HOMER - Responsive Admin Theme
 * Copyright 2015 Webapplayers.com
 *
 */
(function () {
     angular.module('6connect', [])
})();
angular.module('6connect').controller('trackCtrl', trackCtrl);
angular.module('6connect').controller('ratingCtrl', ratingCtrl);

function trackCtrl($scope, $http) {
     $scope.show_list = false;
     $scope.total = 0;
     $scope.orderslist = {};
     $scope.track = function () {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $http.post(BASE_URL + 'tracking/orderslist', {ids: $scope.tracking}).success(function (data) {
               $scope.show_list = true;
               angular.element("#orderslist_body").show();
               angular.element("#orders_loading").hide();
               if (data.orders) {
                    $scope.orderslist = data.orders;
                    $scope.total = data.total;
               }
          });
     };
}
function ratingCtrl($scope, $http) {
     $scope.rated = false;
     $scope.showsplash = false;
     $scope.rating = {reason: '', value: parseInt(rate), email: email, public_id: public_id};
     $scope.setRate = function (val) {
          $scope.rating.value = val;
     }
     $scope.submitRating = function () {
          $scope.showsplash = true;
          $http.post(BASE_URL + 'rating/submitrate', $scope.rating).success(function (data) {
               if (data.status == true) {
                    $scope.rated = true;
                    $scope.showsplash = false;
               }
          });
     };
}