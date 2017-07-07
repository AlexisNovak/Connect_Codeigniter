angular
        .module('6connect')
        .controller('mutliorderCtrl', mutliorderCtrl)
        .controller('confirmorderCtrl', confirmorderCtrl);
function mutliorderCtrl($scope, $http, $rootScope, $stateParams, $state, orderService, $rootScope, notify, phonebook) {

     $scope.neworder = {};
     $scope.selected_contact = {
          'save_collect_contact': true,
          'save_delivery_contact': true,
          'collect_from_contact': false,
          'delivery_from_contact': false
     };
     //for toggle display of different popup window
     $scope.view_type_list = false;
     $scope.view_collect_timezone_list = false;
     $scope.view_delivery_timezone_list = false;
     $scope.view_assigned_couriers_list = false;

     //initial lists
     $scope.typelist = {};
     $scope.countrylist = {};
     $scope.servicelist = {};
     $scope.timezones = {};
     $scope.ctimezones = {};
     $scope.errors = {};
     $scope.orglist = {};
     $scope.scop = {};
     $scope.assign_text = "Please select the organisation right at the top of this page first";


     orderService.getOrder('').then(function (response) {
          $scope.neworder = response.data.order;
     });

     if ($stateParams.id !== undefined) {
          $scope.org_dropdown = false;
          $scope.org_id = $stateParams.id;
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
               $scope.orglist = data.organisations;
          });
     }
     $scope.getTypeList = function () {
          orderService.consignmenttypes().then(function (response) {
               $scope.typelist = response.data.types;
          });
     };
     $scope.getCountryList = function () {
          $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
               $scope.countrylist = response.data.countries;
          });
     };
     $scope.getTimezones = function () {
          $http.post(BASE_URL + 'orders/timezones').then(function (response) {
               $scope.timezones = response.data.timezones;
          });
     };
     $scope.getCTimezones = function () {
          $http.post(BASE_URL + 'orders/timezones', {country: $scope.neworder.collect_country}).then(function (response) {
               $scope.ctimezones = response.data.timezones;
               $scope.neworder.collect_timezone = $scope.ctimezones[0];
               $scope.setCTime();
          });
     };
     $scope.setCTime = function () {
          if ($scope.neworder.collect_date) {
               var cdate = $scope.neworder.collect_date;
               var czone = $scope.neworder.collect_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {time: cdate, 'timezone': czone}).then(function (response) {
                    if (response.data.date)
                         $scope.neworder.cdate_convert = response.data.date;
               });
          }
     };
     $scope.getServiceList = function () {
          var org_id = null;
          if ($scope.org_id !== undefined) {
               org_id = $scope.org_id;
          } else if ($scope.neworder.org_id !== undefined) {
               org_id = $scope.neworder.org_id;
          }
          if (org_id !== null) {
               $scope.assign_text = "Select the Service to assign the order to";
          }
          orderService.assignedServices(org_id).then(function (response) {
               $scope.servicelist = response.data.services;
              $scope.scop.open_bid = response.data.open_bid;
                if ($scope.servicelist.length === 0) {
                    $scope.assign_text = "You have not been assigned any service. Please contact your Organisation Admin.";
               }
               if (org_id === null) {
                    $scope.assign_text = "Please select the organisation right at the top of this page first";
               }
          });
     };
     //initialize lists
     $scope.getTypeList();
     $scope.getCountryList();
     $scope.getTimezones();
     $scope.test = function () {
          $state.transitionTo('organisation.orders', {id: 1}, {reload: true});
     };

     $scope.saveOrder = function () {
          $scope.processing = true;
          if ($stateParams.id !== undefined) {
               $scope.neworder.org_id = $scope.org_id;
          }
          $http.post(BASE_URL + 'orders/multiorders/processOrder', $scope.neworder).success(function (data) {
               $scope.processing = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $state.go('delivery_orders.confirm_multiple_order');
               } else {
                    $scope.errors = data.errors.collect;
                    console.log($scope.errors);
               }
          });
     };



     $scope.resetServices = function () {
          $scope.neworder.assigned_service = undefined;
          $scope.scop.service_selected = undefined; 
          $scope.scop.open_bid = undefined;
          $scope.neworder.open_bid = undefined;
          $scope.getServiceList();
     };
     $scope.set_service = function () {
          $scope.neworder.assigned_service = $scope.scop.service_selected;
          $scope.view_assigned_couriers_list = false;
     };
     $scope.display_typelist = function () {
          $scope.view_type_list = true;
     };
     $scope.hide_typelist = function () {

     };
     $scope.settype = function (type) {
          $scope.neworder.type = type;
          $scope.neworder.typename = type.display_name;
          $scope.view_type_list = false;
     };
     $scope.show_collection_timezone = function () {
          $scope.view_collect_timezone_list = true;
     };
     $scope.show_delivery_timezone = function () {
          $scope.view_delivery_timezone_list = true;
     };
     $scope.show_service_providers = function () {
          $scope.getServiceList();
          $scope.view_assigned_couriers_list = true;
     };
     $scope.cancel_service_providers = function () {
          $scope.view_assigned_couriers_list = false;
     };
     $scope.cancel_collection_timezone = function () {
          $scope.view_collect_timezone_list = false;
          $scope.setCTime();
     };
     $scope.cancel_delivery_timezone = function () {
          $scope.view_delivery_timezone_list = false;
     };

     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };
     $scope.show_contact_popup = function (item) {
          $scope.$broadcast("refreshList");
          $scope.selected_contact.item = item;
          if (item === 'delivery') {
               $scope.selected_contact.popup_for_d = true;
               phonebook.set_include_recent();
          } else {
               $scope.selected_contact.popup_for_d = false;
               phonebook.unset_include_recent();
          }
          $scope.my_contacts_popup = true;
     };
     $scope.set_contact_temp = function (contact) {
          if ($scope.selected_contact.item === 'collect') {
               phonebook.set_collect(contact);
               $scope.selected_contact.collect_from_contact = true;
               $scope.selected_contact.save_collect_contact = false;
               $scope.selected_contact.collect_from_contact_id = contact.id;
               $scope.set_collect_data(contact);
          }
          $scope.cancel_contact_popup();
     };
     $scope.set_collect_data = function (contact) {
          $scope.neworder.collect_from_l1 = contact.address_line1;
          $scope.neworder.collect_from_l2 = contact.address_line2;
          $scope.neworder.collection_zipcode = contact.postal_code;
          $scope.neworder.collect_country = contact.country_code;
          $scope.neworder.collect_contactname = contact.contact_name;
          $scope.neworder.collect_phone = contact.phone_number;
          $scope.neworder.collect_email = contact.email;
          $scope.getCTimezones(true);
     };
     $scope.cancel_contact_popup = function () {
          $scope.my_contacts_popup = false;
     };
}
function confirmorderCtrl($scope, $http, $rootScope, $stateParams, $state, orderService, $rootScope, notify) {

     $scope.neworder = {};

     //for toggle display of different popup window
     $scope.view_type_list = false;
     $scope.view_collect_timezone_list = false;
     $scope.view_delivery_timezone_list = false;
     $scope.view_assigned_couriers_list = false;

     //initial lists
     $scope.typelist = {};
     $scope.countrylist = {};
     $scope.servicelist = {};
     $scope.timezones = {};
     $scope.dtimezones = {};
     $scope.cerrors = {};
     $scope.orglist = {};
     $scope.scop = {};
     $scope.deliveries = {};
     $scope.assign_text = "Please select the organisation right at the top of this page first";

     orderService.getmultiOrder('').then(function (response) {
          $scope.neworder = response.data.form_values;
          $scope.deliveries = response.data.delivery_infos;
          $scope.scop.service_selected = $scope.neworder.assigned_service;
         $scope.scop.open_bid = $scope.neworder.open_bid;
         $scope.getCTimezones(false);
     });

     if ($stateParams.id !== undefined) {
          $scope.org_dropdown = false;
          $scope.org_id = $stateParams.id;
          $scope.getServiceList();
     }
     else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
               $scope.orglist = data.organisations;
               $scope.getServiceList();
          });
     }
     $scope.getTypeList = function () {
          orderService.consignmenttypes().then(function (response) {
               $scope.typelist = response.data.types;
          });
     };
     $scope.getCountryList = function () {
          $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
               $scope.countrylist = response.data.countries;
          });
     };
     $scope.getDTimezones = function () {
          $http.post(BASE_URL + 'orders/timezones').then(function (response) {
               $scope.dtimezones = response.data.timezones;
          });
     };
     $scope.getCTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones', {country: $scope.neworder.collect_country}).then(function (response) {
               $scope.ctimezones = response.data.timezones;
               if (update)
                    $scope.neworder.collect_timezone = $scope.ctimezones[0];
          });
     };

     $scope.getServiceList = function () {
          var org_id = null;
          if ($scope.org_id !== undefined) {
               org_id = $scope.org_id;
          } else if ($scope.neworder.org_id !== undefined) {
               org_id = $scope.neworder.org_id;
          }
          if (org_id !== null) {
               $scope.assign_text = "Select the Service to assign the order to";
          }
          orderService.assignedServices(org_id).then(function (response) {
               $scope.servicelist = response.data.services;
                 $scope.scop.open_bid = response.data.open_bid;
             if ($scope.servicelist.length === 0) {
                    $scope.assign_text = "You have not been assigned any service. Please contact your Organisation Admin.";
               }
               if (org_id === null) {
                    $scope.assign_text = "Please select the organisation right at the top of this page first";
               }
          });
     };
     //initialize lists
     $scope.getTypeList();
     $scope.getCountryList();
     $scope.getDTimezones();

     $scope.delete = function (index) {
          //alert(JSON.stringify($scope.deliveries[index]));
          if (confirm("Delete this row?")) {
               $scope.deliveries.splice(index, 1);
          }
     };
     $scope.temp = {};
     $scope.edit_row = function (delivery) {
          $scope.temp = delivery;
          delivery.edit = true;
     };
     $scope.edit_row_cancel = function (delivery) {
          $scope.temp = delivery;
          delivery.edit = true;
     };
     $scope.edit_cell = function ($event) {
          console.log($event.target);
     };
     $scope.saveOrder = function () {

          var error = false;
          angular.forEach($scope.deliveries, function (de, index) {
               if (de.error) {
                    error = true;
               }
          });
          if (error) {
               notify({
                    message: "Please clear errors",
                    classes: 'alert-danger',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
          } else {
               $scope.processing = true;
               $http.post(BASE_URL + "orders/multiorders/save_multiple_orders", {"collection_info": $scope.neworder, "delivery_info": $scope.deliveries}).success(function (data) {
                    $scope.processing = false;
                    notify({
                         message: data.msg,
                         classes: data.class,
                         templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    if (data.status === 1) {
                         $state.go("delivery_orders");
                    } else {
                         if (data.errors.collection_info !== undefined) {
                              $scope.collection_info_error = data.errors.collection_info;
                              $scope.cerrors = data.errors.collection_errors;
                         } else {
                              $scope.collection_info_error = undefined;
                              $scope.cerrors = {};
                         }
                         if (data.errors.delivery_info !== undefined) {
                              $scope.delivery_info_error = data.errors.delivery_info;
                              $scope.derrors = data.errors.delivery_errors;
                         } else {
                              $scope.delivery_info_error = undefined;
                              $scope.derrors = {};
                         }
                    }
               });
          }

     };
     $scope.submit_edit = function (delivery) {
          $http.post(BASE_URL + "orders/multiorders/validate_delivery", {info: delivery}).success(function (data) {
               if (data.status === 1) {
                    $scope.deliveries[$scope.deliveries.indexOf(delivery)] = data.result;
               }
          });
     };


     $scope.resetServices = function () {
          $scope.neworder.assigned_service = undefined;
          $scope.scop.service_selected = undefined;
          $scope.scop.open_bid = undefined;
          $scope.neworder.open_bid = undefined;
          $scope.getServiceList();
     };
     $scope.set_service = function () {
          $scope.neworder.assigned_service = $scope.scop.service_selected;
          $scope.view_assigned_couriers_list = false;
     };
     $scope.display_typelist = function () {
          $scope.view_type_list = true;
     };
     $scope.hide_typelist = function () {

     };
     $scope.settype = function (type) {
          $scope.neworder.type = type;
          $scope.neworder.typename = type.display_name;
          $scope.view_type_list = false;
     };
     $scope.show_collection_timezone = function () {
          $scope.view_collect_timezone_list = true;
     };
     $scope.show_delivery_timezone = function () {
          $scope.view_delivery_timezone_list = true;
     };
     $scope.show_service_providers = function () {
          $scope.getServiceList();
          $scope.view_assigned_couriers_list = true;
     };
     $scope.cancel_service_providers = function () {
          $scope.view_assigned_couriers_list = false;
     };
     $scope.cancel_collection_timezone = function () {
          $scope.view_collect_timezone_list = false;
     };
     $scope.cancel_delivery_timezone = function () {
          $scope.view_delivery_timezone_list = false;
     };
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
     };
}