
angular
        .module('6connect')
        .controller('orderCtrl', orderCtrl);


function orderCtrl($scope) {
     
     $scope.service_list=function(){
          $scope.service_list_popup=true; 
     };
      $scope.cancel_service_list=function(){
          $scope.service_list_popup=false;
     };
     
}