angular
        .module('6connect')
        .controller('mutliorderCtrl', mutliorderCtrl)
        .controller('viewmutliorderCtrl', viewmutliorderCtrl);
function mutliorderCtrl($scope, $http, $rootScope, $stateParams, $state, $timeout, orderService, $rootScope, notify, $filter, phonebook) {
     $scope.neworder = {};
     $scope.deliveries = {};
     $scope.show_part = {one: true, two: false, three: false, four: false};
     $scope.show_part_one = function () {
          $scope.show_part = {one: true, two: false, three: false, four: false};
     };
     $scope.show_part_two = function () {
          $scope.show_part = {one: false, two: true, three: false, four: false};
     };
     $scope.show_part_three = function () {
          $scope.show_part = {one: false, two: false, three: true, four: false};
          $scope.getServiceList(true);
     };
     $scope.show_part_four = function () {
          $scope.show_part = {one: false, two: false, three: false, four: true};
     };
     $scope.service_filter_field = 'priority';
     $scope.set_filter = function (filter) {
          $scope.service_filter_field = filter;
     };
     $scope.calcPrice = function (service) {
          var neworder = $scope.neworder;
          console.log(neworder.is_bulk);
          return (neworder.is_bulk ? Math.max(service.volume_cost * neworder.volume, service.weight_cost * neworder.weight) : service.price) + " SGD";
     };
     $scope.filtertypelist = [
          {value: 1, name: "All Services"},
          {value: 0, name: "Pre-approved Services"}
     ];
     $scope.payments = [{value: '0000', name: 'All'}];
     $scope.payment_modes = [{value: '0001', name: "Cash on Collection"}];
     $scope.servicelistdata = {
          type: $scope.filtertypelist[0]['value'],
          payment: $scope.payments[0]['value']
     };
     $scope.mapc = {result1: '', option1: null, details1: ''};
     $scope.set_cpin = function () {
          $timeout(function () {
               if ($scope.mapc.details1.address_components !== undefined) {
                    var components = $scope.mapc.details1.address_components;
                    angular.forEach(components, function (component, key) {
                         if (component.types.indexOf('postal_code') !== -1) {
                              $scope.neworder.collection_zipcode = component.long_name;
                         }
                         if (component.types.indexOf('country') !== -1) {
                              $scope.neworder.collect_country = component.short_name.toLowerCase();
                              $scope.getCTimezones(true);
                         }
                         ;
                    });
               }
          }, 200);

     };
     $scope.collect_shortcuts = "";
 $scope.show_more_srcharge_items = function (service, $event) {
          $event.stopPropagation();
          service.limit = service.surcharge.length;
     }
     $scope.show_less_srcharge_items = function (service, $event) {
          $event.stopPropagation();
          service.limit = 2;
     }
     $scope.addBusinessDays = function () {
          var cnt = 0;
          var tmpDate = moment();
          while (cnt < 2) {
               tmpDate = tmpDate.add('days', 1);
               if (tmpDate.weekday() !== moment().day("Sunday").weekday() && tmpDate.weekday() !== moment().day("Saturday").weekday()) {
                    cnt = cnt + 1;
               }
          }

          return tmpDate.hour(18).minute(0);
     };
     $scope.endweekdays = function () {
          var tmpDate = moment();
          if (tmpDate.weekday() === moment().day("Saturday").weekday()) {
               tmpDate.add(1, 'week');
          }
          return tmpDate.endOf('week').subtract(1, 'days');
     };
     $scope.startweekdays = function () {
          var tmpDate = moment();
          if (tmpDate.weekday() === moment().day("Saturday").weekday()) {
               tmpDate.add(1, 'week');
          }
          return tmpDate.startOf('week').add(1, 'days');
     };
     $scope.csclist = [];
     $scope.setClist = function () {
          $scope.csclist = [];
          if (moment() < moment().hour(13).minute(0)) {
               $scope.csclist.push({'name': "Today morning (10am - 1pm)", "from": moment().hour(10).minute(0), "to": moment().hour(13).minute(0)});
          }
          if (moment() < moment().hour(18).minute(0)) {
               $scope.csclist.push({'name': "Today afternoon (1pm - 6pm)", "from": moment().hour(13).minute(0), "to": moment().hour(18).minute(0)});
          }
          $scope.csclist.push({'name': "Tomorrow morning (10am - 1pm)", "from": moment().add(1, 'days').hour(10).minute(0), "to": moment().add(1, 'days').hour(13).minute(0)});
          $scope.csclist.push({'name': "Tomorrow office hour (10am - 6pm)", "from": moment().add(1, 'days').hour(10).minute(0), "to": moment().add(1, 'days').hour(18).minute(0)});
          $scope.csclist.push({'name': "Tomorrow onward, anytime this week", "from": moment().add(1, 'days').hour(10).minute(0), "to": moment().add(1, 'days').endOf('week').endOf('day').minute(0)});

     };
     $scope.setClist();
     $scope.setCollectionTime = function () {
          if (moment() < moment().hour(11).minute(0)) {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {'name': "Today morning (10am - 1pm)"})[0];
          }
          else if (moment() < moment().hour(16).minute(0)) {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {'name': "Today afternoon (1pm - 6pm)"})[0];
          }
          else {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {'name': "Tomorrow morning (10am - 1pm)"})[0];
          }
          $scope.setCRange();
     };


     $scope.setCRange = function () {
          if ($scope.collect_shortcuts != null) {
               var from = $scope.collect_shortcuts.from;
               var to = $scope.collect_shortcuts.to;
               $scope.neworder.collect_date1 = from.format('MM/DD/YYYY h:mm A');
               $scope.neworder.collect_date2 = to.format('MM/DD/YYYY h:mm A');
               $scope.setCTime();
          }
     };
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
     $scope.org_dropdown = true;


     orderService.getOrder('').then(function (response) {
          $scope.neworder = response.data.order;
          $scope.getCTimezones(false);
          $scope.setCollectionTime();
          if ($stateParams.id !== undefined) {
               $scope.org_dropdown = false;
               $scope.org_id = $stateParams.id;
          }
          else {
               $scope.org_dropdown = true;
               $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
                    if (data.any) {
                         if (data.many) {
                              $scope.orglist = data.organisations;
                         } else {
                              //  $scope.single_org = true;
                              $scope.orglist = data.organisations;
                              $scope.org_id = data.org_id;
                              $scope.neworder.org_id = data.org_id;
                              $scope.getServiceList(true);
                         }

                    }

               });
          }
     });


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
          if ($scope.neworder.collect_date1 && $scope.neworder.collect_date2) {
               var cdate = $scope.neworder.collect_date1 + '-' + $scope.neworder.collect_date2;
               var czone = $scope.neworder.collect_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {time: cdate, 'timezone': czone}).then(function (response) {
                    if (response.data.date)
                         $scope.neworder.cdate_convert = response.data.date;
                    $scope.neworder.cdate_convert1 = response.data.date.split("-")[0];
                    $scope.neworder.cdate_convert2 = response.data.date.split("-")[1];
                    $scope.neworder.cdate_convert1_1 = response.data.start_date;
                    $scope.neworder.cdate_convert1_2 = response.data.start_time;
                    $scope.neworder.cdate_convert2_1 = response.data.end_date;
                    $scope.neworder.cdate_convert2_2 = response.data.end_time;
               });
          }
     };
     $scope.getServiceList = function (flag) {
          var org_id = null;
          if ($scope.neworder.org_id !== undefined) {
               org_id = $scope.neworder.org_id;
          }
          var collection_time = $scope.neworder.collect_date;
          var delivery_time = null;
          var c_country = $scope.neworder.collect_country;
          var d_country = $scope.neworder.delivery_country;
          var consignment_type = $scope.neworder.type ? $scope.neworder.type.consignment_type_id : "-1";
          var type = 1;
          var term = '0000';
          if (!flag) {
               type = $scope.servicelistdata.type;
               term = $scope.servicelistdata.payment;
          }
          orderService.assignedServices(org_id, collection_time, delivery_time, c_country, d_country, type, consignment_type, term).then(function (response) {
               $scope.servicelist = response.data.services;
               angular.forEach($scope.servicelist, function (value, key) {
                    value.index = $scope.servicelist.length - (key + 1);
               });
               $scope.scop.open_bid = response.data.open_bid;
               if (flag) {
                    if (response.data.payments) {
                         $scope.payments = response.data.payments;
                    }
                    if (response.data.use_public == true) {
                         $scope.filtertypelist = [
                              {value: 1, name: "All Services"},
                              {value: 0, name: "Pre-approved Services"}
                         ];
                    } else {
                         $scope.filtertypelist = [
                              {value: 0, name: "Pre-approved Services"}
                         ];
                    }
                    $scope.servicelistdata = {
                         type: $scope.filtertypelist[0]['value'],
                         payment: $scope.payments[0]['value']
                    };
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
          scroll_to_loading();
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
                    if ($scope.selected_contact.save_collect_contact == true) {
                         $scope.save_c_contact = {
                              address_line1: $scope.neworder.collect_from_l1,
                              address_line2: $scope.neworder.collect_from_l2,
                              postal_code: $scope.neworder.collection_zipcode,
                              country_code: $scope.neworder.collect_country,
                              contact_name: $scope.neworder.collect_contactname,
                              phone_number: $scope.neworder.collect_phone,
                              email: $scope.neworder.collect_email
                         };
                         phonebook.set_collect($scope.save_c_contact);
                         phonebook.save_collect();
                    }
                    if (data.group_id) {
                         if (data.new) {
                              orderService.setNew();
                         }
                         $state.go('delivery_orders.view_multiple_order', {cgroup_id: data.group_id});
                    }
                    else {
                         //$state.go('delivery_orders');
                    }

               } else {
                    $scope.errors = data.errors.collect;
                    if (data.errors.part === 1) {
                         $scope.show_part_one();
                    } else if (data.errors.part === 3) {
                         $scope.show_part_three();
                    }
                    $timeout(function () {
                         var error_fields = angular.element('.order_form').find('.error');
                         if (error_fields.length > 0) {
                              error_fields[0].focus();
                         }
                    }, 500);
               }
          });
     };

     $scope.resetServices = function () {
          $scope.neworder.assigned_service = undefined;
          $scope.scop.service_selected = {};
          $scope.scop.open_bid = undefined;
          $scope.neworder.open_bid = undefined;
          $scope.getServiceList(true);
     };
     $scope.set_bid_request = function (flag) {
          $scope.neworder.delivery_is_assign = flag;
     };
     $scope.reset_bid_request = function () {
          $scope.neworder.delivery_is_assign = 1;
     };
     $scope.getIntersectionOfArray = function (array1, array2) {
          $scope.payment_modes = [];
          angular.forEach(array1, function (value, index) {
               angular.forEach(array2, function (object, index1) {
                    if (value.value == object.value) {
                         $scope.payment_modes.push(object)
                    }
               })
          })
     }
     $scope.set_service = function (service) {
          if (service.service_id) {
               $scope.reset_bid_request();
               angular.element(".srv_list li").removeClass('active');
               angular.element(".srv_list li#service" + service.service_id).addClass('active');
               $scope.neworder.assigned_service = service;
               $scope.view_assigned_couriers_list = false;
               $scope.getIntersectionOfArray($scope.payments, service.payments);
               $scope.neworder.payment_mode = $scope.payment_modes[0];
          }
     };
     $scope.display_typelist = function () {
          $scope.view_type_list = true;
     };
     $scope.hide_typelist = function () {

     };

//     $scope.resetServices = function () {
//          $scope.neworder.assigned_service = undefined;
//          $scope.scop.service_selected = undefined;
//          $scope.scop.open_bid = undefined;
//          $scope.neworder.open_bid = undefined;
//          $scope.getServiceList();
//     };
//     $scope.set_service = function () {
//          $scope.neworder.assigned_service = $scope.scop.service_selected;
//          $scope.view_assigned_couriers_list = false;
//     };
//     $scope.display_typelist = function () {
//          $scope.view_type_list = true;
//     };
//     $scope.hide_typelist = function () {
//
//     };
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
          $scope.getServiceList(true);
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
          $scope.neworder.collect_company = contact.company_name;
          $scope.getCTimezones(true);
     };
     $scope.cancel_contact_popup = function () {
          $scope.my_contacts_popup = false;
     };
}

function viewmutliorderCtrl($scope, $http, $rootScope, orderService, $stateParams, $state, notify) {
     if (orderService.getNew()) {
          $scope.print_info = true;
     }
     orderService.resetNew();
     $scope.$state = $state;
     $scope.total_orders = 1;
     $scope.orderslist = {};
     $scope.filter_statuslist = [];
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
     $scope.orderslistdata = {perpage_value: 15, currentPage: 1, total: 0, "status": "all", "cgroup_id": $stateParams.cgroup_id, perpage: $scope.ordersperpage[2]};

     $scope.orderByField = 'consignment_id';
     orderService.OrdersCount().then(function (response) {
          $scope.total_orders = response.data.count;
     });
     $scope.reverseSort = true;


     $http.post(BASE_URL + 'orders/statusList').success(function (data) {
          $scope.filter_statuslist = data.status;
     });

     $scope.getOrders = function (page) {
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          orderService.OrdersList($scope.orderslistdata).then(function (response) {
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
               //alert($scope.orderslistdata.current_user_id);
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'delivery_orders.view_multiple_order')
               $scope.getOrders($scope.orderslistdata.currentPage);
     });
     $scope.perpagechange = function () {
          $scope.orderslistdata.perpage_value = $scope.orderslistdata.perpage.value;
          $scope.getOrders($scope.orderslistdata.currentPage);
     };

     $scope.findorders = function () {
          $scope.getOrders(1);
     };
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {id: id});
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
               status: {}
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