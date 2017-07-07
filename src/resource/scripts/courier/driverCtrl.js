angular
.module('6connect')
.controller('driverCtrl', driverCtrl)
.controller('addDriver', addDriver)
.controller('viewDriver', viewDriver)
.controller('updateDriver', updateDriver);

function addDriver($scope, $http,$state,notify) {
  $scope.countrycodelist=[{value:"+65"},{value:"+86"},{value:"+91"}];
  $scope.new_driver={};
  $scope.new_driver.country_code="+65";
  $("#collectdate2").daterangepicker({
    timePicker: false,
    singleDatePicker: true,
    format: 'MM/DD/YYYY',
    autoUpdateInput: true,
    startDate: new Date('1950-01-01'),
    endDate: new Date(),
    showDropdowns: true,
    maxDate: new Date(),
    minDate: new Date('1950-01-01')
  });
  $scope.error={};
  $scope.save = function(){
    $http.post(BASE_URL + 'drivers/savedriver', $scope.new_driver).success(function (data) {
      if (data.status==true) {
        $state.go('view_driver',{'id':data.insert_id});
      }else{
        $scope.error=data.error;
      }
    });
  }

  $scope.calculateAge=function(){
    var dob=$scope.new_driver.dob;
    $scope.new_driver.age = moment().diff(dob, 'years');
  }
}

function viewDriver($scope, $http,$stateParams,$state,notify){
  $scope.driverdata={};
  $scope.get_driver_details_by_id =  function(){
    $http.post(BASE_URL + 'drivers/get_driver_details_by_id/'+$stateParams.id)
    .success(function (data) {
      $scope.driverdata=data.driverdata;
    });
  }
  $scope.get_driver_details_by_id();

  $scope.deleteDriver = function(driver_id){
    $http.post(BASE_URL + 'drivers/delete_driver/'+driver_id)
    .success(function (data){
      $state.go('adddriver');
    });
  }

  $scope.saveDriver = function(driver_id){
   notify({
     message: "Driver added successfully",
     classes: 'alert-success',
     templateUrl: ROOT_PATH + "/resource/partial/notify.html"
   });
   $state.go('drivers');
 }

}

function driverCtrl($scope, $http) {
  $scope.drvperpage = [{
   value: 5,
   label: 5
 }, {
   value: 10,
   label: 10
 }, {
   value: 15,
   label: 15}, {
     value: 20,
     label: 20}
     ];
     $scope.drvlistdata = {perpage_value: 15,filter:'',status:'1', currentPage: 1, total: 0,perpage:$scope.drvperpage[2]};
     $scope.orderByField = '';
     $scope.reverseSort = false;
     $scope.driverslist={};
     $scope.getdrivers = function (page) {
      $scope.drvlistdata.currentPage = page;
      $http.post(BASE_URL + 'drivers/get_all_list', $scope.drvlistdata)
      .success(function (data) {
        $scope.drvlistdata.total=data.total;
        $scope.driverslist=data.drivers;
      });
    };
    $scope.getdrivers($scope.drvlistdata.currentPage);

    $scope.perpagechange = function () {
      $scope.drvlistdata.perpage_value = $scope.drvlistdata.perpage.value;
      $scope.getdrivers($scope.drvlistdata.currentPage);
    };

    $scope.suspendDrivers = function(driver_id){
      $http.post(BASE_URL + 'drivers/suspend_driver/'+driver_id)
      .success(function (data){
        $scope.getdrivers(1)
      });
    }

    $scope.activateDrivers = function(driver_id){
      $http.post(BASE_URL + 'drivers/activate_driver/'+driver_id)
      .success(function (data){
        $scope.getdrivers(1)
      });
    }

    $scope.deleteDrivers = function(driver_id){
      $http.post(BASE_URL + 'drivers/delete_driver/'+driver_id)
      .success(function (data){
        $scope.getdrivers(1)
      });
    }

  }

  function updateDriver($scope, $http, $state,$stateParams, notify){
    $scope.new_driver = {};
    $scope.countrycodelist=[{value:"+65"},{value:"+86"},{value:"+91"}];
    $("#collectdate2").daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      format: 'MM/DD/YYYY',
      autoUpdateInput: true,
      startDate: new Date('1950-01-01'),
      endDate: new Date(),
      showDropdowns: true,
      maxDate: new Date(),
      minDate: new Date('1950-01-01')
    });
    $scope.error={};
    $scope.save = function(){
      console.log($scope.new_driver);
      $http.post(BASE_URL + 'drivers/update_driver_json/'+$stateParams.id, $scope.new_driver).success(function (data) {
        if (data.status==true) {
          notify({
            message: "Driver updated successfully",
            classes: 'alert-success',
            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
          });
          $state.go('view_driver',{'id':$stateParams.id});
        }else{
          $scope.error=data.error;
        }
      });
    }
    $scope.get_driver_details_by_id =  function(){
      $http.post(BASE_URL + 'drivers/get_driver_details_by_id/'+$stateParams.id)
      .success(function (data) {
        $scope.new_driver=data.driverdata;
      });
    }
    $scope.get_driver_details_by_id();
  }
