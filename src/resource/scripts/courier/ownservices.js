angular.module('6connect').controller('ownservicesCtrl', ownservicesCtrl)
        .controller('newownservicesCtrl', newownservicesCtrl).controller(
        'viewservicesCtrl', viewservicesCtrl).controller(
        'editownservicesCtrl', editownservicesCtrl);

function ownservicesCtrl($scope, $http, $rootScope, notify) {
     $scope.services = {};
     $scope.searchname = {};
     $scope.servicesperpage = [{
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
     $scope.serviceslistdata = {
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          'category': 1,
          perpage: $scope.servicesperpage[2]
     };

     $scope.orderByField = 'id';
     $scope.reverseSort = true;
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {
               id: id
          });
     };
     $scope.getservices = function (page) {
          $scope.serviceslistdata.currentPage = page;
          $http
                  .post(BASE_URL + 'couriers/ownservices/serviceslist_json/',
                          $scope.serviceslistdata)
                  .success(
                          function (data) {
                               $scope.total = data.total;
                               $scope.start = data.start;
                               $scope.end = data.end;
                               $scope.serviceslist = data.service_detail;
                               $scope.serviceslistdata.total = data.total;
                               $scope.serviceslistdata.currentPage = data.page;
                               $scope.serviceslistdata.current_service_id = data.current_service_id;
                               // alert($scope.serviceslistdata.current_service_id);
                          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'ownservices')
               $scope.getservices($scope.serviceslistdata.currentPage);
     });

     $scope.perpagechange = function () {
          $scope.serviceslistdata.perpage_value = $scope.serviceslistdata.perpage.value;
          $scope.getservices($scope.serviceslistdata.currentPage);
     };
     $scope.findservices = function () {
          $scope.getservices(1);
     };

     $scope.service_suspend = function (service_id) {
          $http({
               method: 'POST',
               url: BASE_URL + 'couriers/ownservices/suspend_service',
               data: {
                    "service_id": service_id,
                    "status": $scope.suspendstatus
               }
          }).success(function (data) {
               $scope.suspend_warning_popup_service = false;
               $scope.getservices($scope.serviceslistdata.currentPage);

          }).error(function (data) {
               alert(data);
          });
     };
     $scope.suspend_warning_service = function (service) {
          $scope.suspend_warning_popup_service = true;
          $scope.suspend_id = service.id;
          $scope.suspendstatus = service.status;
     };
     $scope.cancel_suspend_warning_service = function () {
          $scope.suspend_warning_popup_service = false;
     };
     $scope.resetSort = function () {
          $scope.serviceheaders = {
               service: {},
               destination: {},
               type: {},
               cutoff: {},
               days: {},
               org_name: {},
               description: {},
               status: {}
          };
     };

     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.serviceheaders[column].reverse === undefined) {
               $scope.serviceheaders[column].reverse = false;
          } else {
               $scope.serviceheaders[column].reverse = !$scope.serviceheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.serviceheaders[column].reverse;
     }
}
function newownservicesCtrl($scope, $http, notify, $state) {
     $scope.scountrylist = [];
     $scope.dcountrylist = [];

     $scope.csclist = [
          {
               'name': "Next Business Day",
               "data": "next-day"
          },
          {
               'name': "90-min Delivery",
               "data": "90-minute"
          },
          {
               'name': "3-hours Delivery",
               "data": "3-hour"
          },
          {
               'name': "6-hours Delivery",
               "data": "6-hour"
          }
     ];

     $scope.new_service = {
          org: "",
          origin: "sg",
          destination: ["sg"],
          deliverytime: $scope.csclist[0]
     };
     $scope.termslist = [{
               name: "Cash",
               value: "Cash"
          }, {
               name: "Credit",
               value: "Credit"
          }, {
               name: "Cheque",
               value: "Cheque"
          }];
     $scope.errors = {};
     $scope.orglist = {};
     $scope.getorglist = function () {
          $http.post(BASE_URL + 'app/organisation/allorganisations', {
               search: $scope.new_service.display_name
          }).success(function (data) {
               $scope.orglist = data.organisations;
          });
     };
     $scope.getorglist();
     $scope.getCountryList = function () {
          $http.get(BASE_URL + 'couriers/ownservices/countrylist').then(
                  function (response) {
                       var countriers = response.data.countries;
                       $scope.scountrylist = angular.copy(countriers);
                       $scope.dcountrylist = angular.copy(countriers);
                       $scope.dcountrylist.splice(0, 0, {
                            code: "all",
                            "country": "Anywhere"
                       });

                  });
     };
     $scope.getCountryList();
     $scope.cancel_service = function () {
          $state.go('^');
     };
     $scope.save = function () {
          $http.post(BASE_URL + 'couriers/ownservices/create_service',
                  $scope.new_service).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $state.go('^');
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };
     $scope.check_multiple = function () {
          if ($scope.new_service.destination.length > 1) {
               if ($scope.new_service.destination.indexOf('all') > -1) {
                    if ($scope.new_service.destination.indexOf('all') === ($scope.new_service.destination.length - 1)) {
                         $scope.new_service.destination = ['all'];
                    } else {
                         $scope.new_service.destination.splice(
                                 $scope.new_service.destination.indexOf('all'), 1);
                    }
               }
          }
     };
}
function viewservicesCtrl($scope, $http, $stateParams, $state, notify, orderService) {
     $scope.goback = function () {
          $state.go('^');
     };

     $scope.selectedParcelType = {
          selected: {}
     };
     $scope.insertedPrice = {
          type: '',
          price: '',
          maxVolume: '',
          cubicCost: '',
          maxWeight: '',
          weightCost: ''
     };
     $scope.typelist = [];
     $scope.selectParcel = function () {
          for (o in $scope.insertedPrice)
               $scope.insertedPrice[o] = '';

          var typeId = $scope.selectedParcelType.selected.consignment_type_id;
          $scope.insertedPrice.type = typeId;
          $.each($scope.parcelTypePrices, function (i, p) {
               if (p.type == typeId)
               {
                    $scope.insertedPrice.cubicCost = p.volume_cost;
                    $scope.insertedPrice.maxVolume = p.max_volume;
                    $scope.insertedPrice.maxWeight = p.max_weight;
                    $scope.insertedPrice.weightCost = p.weight_cost;
                    $scope.insertedPrice.price = p.price;
               }
          });
     };
     $scope.getTypeList = function () {
          orderService.consignmenttypes().then(function (response) {
               $scope.typelist = response.data.types;
          });
     };
     $scope.getTypeList();

     $scope.parcelTypePrices = [];
     $scope.getPrices = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_parcel_prices/' + $stateParams.os_id)
                  .success(function (data) {
                       if (data.prices)
                            $scope.parcelTypePrices = data.prices;
                  });
     };
     $scope.getPrices();

     $scope.setPrice = function () {
          angular.extend($scope.insertedPrice, {
               "service_id": $stateParams.os_id
          });
          $http.post(BASE_URL + 'couriers/ownservices/add_parcel_price', $scope.insertedPrice)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH
                                    + "/resource/partial/notify.html"
                       });
                       $scope.errors = data.errors;
                       $scope.getPrices();
                  });
     };

     $scope.removePrice = function (item, index) {
          if (item.id) {
               $http.post(BASE_URL + 'couriers/ownservices/delete_parcel_price', item).success(function (data) {
                    if (data.status === 0) {
                         $scope.parcelTypePrices.splice(index, 1);
                    }
               });
          } else {
               $scope.parcelTypePrices.splice(index, 1);
          }
     };

     //surcharge item
     $scope.surchargeitems = [];
     $scope.get_items = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_items/' + $stateParams.os_id).success(function (data) {
               if (data.items) {
                    $scope.surchargeitems = data.items;
               }
          });
     };
     $scope.get_items();

     $scope.saveItem = function (data, id) {
          angular.extend(data, {
               service_id: $stateParams.os_id,
               'id': id
          });
          $http.post(BASE_URL + 'couriers/ownservices/add_surcharge_item', data)
                  .success(
                          function (data) {
                               notify({
                                    message: data.msg,
                                    classes: data.class,
                                    templateUrl: ROOT_PATH
                                            + "/resource/partial/notify.html"
                               });
                               $scope.get_items();
                          });
     };

     // remove user
     $scope.removeItem = function (item, index) {
          if (item.id) {
               $http.post(BASE_URL + 'couriers/ownservices/delete_surcharge_item',
                       item).success(function (data) {
                    if (data.status === 1) {
                         $scope.surchargeitems.splice(index, 1);
                    }
               });
          } else {
               $scope.surchargeitems.splice(index, 1);
          }
     };
     $scope.locations = [{value: 0, text: 'NA'},
          {value: 8, text: 'Tuas'},
          {value: 4, text: 'CBD'},
          {value: 2, text: 'Sentosa'},
          {value: 1, text: 'Other Restricted Area'},
          {value: 16, text: "Collect and deliver on the same day"},
          {value: 17, text: "Collect the day before delivery"},
          {value: 18, text: "Collect back on next business day"},
          {value: 19, text: "Collect back on same day as delivery"},
          {value: 20, text: "Collect back within a week"},
          {value: 25, text: "Appointed Service"}
     ];
     // add user
     $scope.addItem = function () {
          $scope.inserted = {
               name: '',
               price: '',
               remarks: '',
               location: 0
          };
          $scope.surchargeitems.push($scope.inserted);
     };
     $scope.cancel_item = function (rowform, index) {
          if ($scope.surchargeitems[index].id === undefined) {
               $scope.surchargeitems.splice(index, 1);
          }
          rowform.$cancel();
     }
     $scope.payment = {};
     $scope.get_payments = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_payments/' + $stateParams.os_id).success(function (data) {
               if (data.payment) {
                    $scope.payment = data.payment;
               }
          });
     };
     $scope.get_payments();
     $scope.update_payment = function () {
          $scope.payment.id = $stateParams.os_id;
          $http.post(BASE_URL + 'couriers/ownservices/save_payments', $scope.payment).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH
                            + "/resource/partial/notify.html"
               });
               $("#new_c_service_form").submit();
          });
     };
}
;
function editownservicesCtrl($scope, $http, notify, $state, $stateParams, $scope, orderService) {
     $scope.scountrylist = [];
     $scope.dcountrylist = [];
     $scope.csclist = [
          {
               'name': "Next Business Day",
               "data": "next-day"
          },
          {
               'name': "90-min Delivery",
               "data": "90-minute"
          },
          {
               'name': "3-hours Delivery",
               "data": "3-hour"
          },
          {
               'name': "6-hours Delivery",
               "data": "6-hour"
          }
     ];

     $scope.new_service = {
          org: "",
          origin: "sg",
          destination: ["sg"],
          deliverytime: $scope.csclist[0]
     };
     $http
             .post(
                     BASE_URL + 'couriers/ownservices/get_service/'
                     + $stateParams.os_id).success(function (data) {
          if (data.service) {
               $scope.new_service = data.service;
               angular.forEach($scope.csclist, function (data1) {
                    if (data1.data == $scope.new_service.delivery_time) {
                         $scope.new_service.deliverytime = data1;
                    }
               });
          }
     });
     $scope.termslist = [{
               name: "Cash",
               value: "Cash"
          }, {
               name: "Credit",
               value: "Credit"
          }, {
               name: "Cheque",
               value: "Cheque"
          }];
     $scope.errors = {};
     $scope.orglist = {};
     $scope.getorglist = function () {
          $http.post(BASE_URL + 'app/organisation/allorganisations', {
               search: $scope.new_service.display_name
          }).success(function (data) {
               $scope.orglist = data.organisations;
          });
     };
     $scope.getorglist();
     $scope.getCountryList = function () {
          $http.get(BASE_URL + 'couriers/ownservices/countrylist').then(
                  function (response) {
                       var countriers = response.data.countries;
                       $scope.scountrylist = angular.copy(countriers);
                       $scope.dcountrylist = angular.copy(countriers);
                       $scope.dcountrylist.splice(0, 0, {
                            code: "all",
                            "country": "Anywhere"
                       });

                  });
     };
     $scope.getCountryList();
     $scope.cancel_service = function () {
          $state.go('^');
     };
     $scope.save = function () {
          $http.post(BASE_URL + 'couriers/ownservices/update_service',
                  $scope.new_service).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $state.go('^');
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };
     $scope.check_multiple = function () {
          if ($scope.new_service.destination.length > 1) {
               if ($scope.new_service.destination.indexOf('all') > -1) {
                    if ($scope.new_service.destination.indexOf('all') === ($scope.new_service.destination.length - 1)) {
                         $scope.new_service.destination = ['all'];
                    } else {
                         $scope.new_service.destination.splice(
                                 $scope.new_service.destination.indexOf('all'), 1);
                    }
               }
          }
     };



     $scope.selectedParcelType = {
          selected: {}
     };
     $scope.insertedPrice = {
          type: '',
          price: '',
          maxVolume: '',
          cubicCost: '',
          maxWeight: '',
          weightCost: ''
     };
     $scope.typelist = [];
     $scope.selectParcel = function () {
          for (o in $scope.insertedPrice)
               $scope.insertedPrice[o] = '';

          var typeId = $scope.selectedParcelType.selected.consignment_type_id;
          $scope.insertedPrice.type = typeId;
          $.each($scope.parcelTypePrices, function (i, p) {
               if (p.type == typeId)
               {
                    $scope.insertedPrice.cubicCost = p.volume_cost;
                    $scope.insertedPrice.maxVolume = p.max_volume;
                    $scope.insertedPrice.maxWeight = p.max_weight;
                    $scope.insertedPrice.weightCost = p.weight_cost;
                    $scope.insertedPrice.price = p.price;
               }
          });
     };
     $scope.getTypeList = function () {
          orderService.consignmenttypes().then(function (response) {
               $scope.typelist = response.data.types;
          });
     };
     $scope.getTypeList();

     $scope.parcelTypePrices = [];
     $scope.getPrices = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_parcel_prices/' + $stateParams.os_id)
                  .success(function (data) {
                       if (data.prices)
                            $scope.parcelTypePrices = data.prices;
                  });
     };
     $scope.getPrices();

     $scope.setPrice = function () {
          angular.extend($scope.insertedPrice, {
               "service_id": $stateParams.os_id
          });
          $http.post(BASE_URL + 'couriers/ownservices/add_parcel_price', $scope.insertedPrice)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH
                                    + "/resource/partial/notify.html"
                       });
                       $scope.errors = data.errors;
                       $scope.getPrices();
                  });
     };

     $scope.removePrice = function (item, index) {
          if (item.id) {
               $http.post(BASE_URL + 'couriers/ownservices/delete_parcel_price', item).success(function (data) {
                    if (data.status === 0) {
                         $scope.parcelTypePrices.splice(index, 1);
                    }
               });
          } else {
               $scope.parcelTypePrices.splice(index, 1);
          }
     };

     //surcharge item
     $scope.surchargeitems = [];
     $scope.get_items = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_items/' + $stateParams.os_id).success(function (data) {
               if (data.items) {
                    $scope.surchargeitems = data.items;
               }
          });
     };
     $scope.get_items();

     $scope.saveItem = function (data, id) {
          angular.extend(data, {
               service_id: $stateParams.os_id,
               'id': id
          });
          $http.post(BASE_URL + 'couriers/ownservices/add_surcharge_item', data)
                  .success(
                          function (data) {
                               notify({
                                    message: data.msg,
                                    classes: data.class,
                                    templateUrl: ROOT_PATH
                                            + "/resource/partial/notify.html"
                               });
                               $scope.get_items();
                          });
     };

     // remove user
     $scope.removeItem = function (item, index) {
          if (item.id) {
               $http.post(BASE_URL + 'couriers/ownservices/delete_surcharge_item',
                       item).success(function (data) {
                    if (data.status === 1) {
                         $scope.surchargeitems.splice(index, 1);
                    }
               });
          } else {
               $scope.surchargeitems.splice(index, 1);
          }
     };
     $scope.locations = [{value: 0, text: 'NA'},
          {value: 8, text: 'Tuas'},
          {value: 4, text: 'CBD'},
          {value: 2, text: 'Sentosa'},
          {value: 1, text: 'Other Restricted Area'},
          {value: 16, text: "Collect and deliver on the same day"},
          {value: 17, text: "Collect the day before delivery"},
          {value: 18, text: "Collect back on next business day"},
          {value: 19, text: "Collect back on same day as delivery"},
          {value: 20, text: "Collect back within a week"},
          {value: 25, text: "Appointed Service"}
     ];
     // add user
     $scope.addItem = function () {
          $scope.inserted = {
               name: '',
               price: '',
               remarks: '',
               location: 0
          };
          $scope.surchargeitems.push($scope.inserted);
     };
     $scope.cancel_item = function (rowform, index) {
          if ($scope.surchargeitems[index].id === undefined) {
               $scope.surchargeitems.splice(index, 1);
          }
          rowform.$cancel();
     }
     $scope.payment = {};
     $scope.get_payments = function () {
          $http.post(BASE_URL + 'couriers/ownservices/get_payments/' + $stateParams.os_id).success(function (data) {
               if (data.payment) {
                    $scope.payment = data.payment;
               }
          });
     };
     $scope.get_payments();
     $scope.update_payment = function () {
          $scope.payment.id = $stateParams.os_id;
          $http.post(BASE_URL + 'couriers/ownservices/save_payments', $scope.payment).success(function (data) {
               $scope.save();
          });
     };

}