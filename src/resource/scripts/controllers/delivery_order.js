
angular
        .module('6connect')
        .controller('orderCtrl', orderCtrl)
        .controller('neworderCtrl', neworderCtrl)
        .controller('vieworderCtrl', vieworderCtrl)
        .controller('changeorderCtrl', changeorderCtrl)
        .controller('apiCtrl', apiCtrl);
function orderCtrl($scope, $http, $rootScope, orderService, $stateParams, $state, notify) {
     $scope.DO_delete_warning_popup = false;
     $scope.$state = $state;
     $scope.orderslist = {};
     $scope.orglist = {};
     $scope.filter_servicelist = [];
     $scope.filter_statuslist = [];
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
     $scope.show_init = false;
     $scope.show_table = false;
     $scope.orderslistdata = {
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          organisation: "",
          "service": "all",
          "status": "9999",
          "team": "all",
          perpage: $scope.ordersperpage[2]
     };
     $scope.orderByField = 'consignment_id';
     $scope.init_count = function () {
          orderService.OrdersCount().then(function (response) {
               if (response.data.count !== undefined && response.data.count === 0) {
                    $scope.show_init = true;
                    $scope.show_table = false;
               } else {
                    $scope.show_init = false;
                    $scope.show_table = true;
               }

          });
     };
     $scope.init_count();
     $scope.reverseSort = true;
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
          $scope.org_dropdown = false;
          $scope.orderslistdata.organisation = $stateParams.id;
     } else {
          $scope.org_dropdown = true;
          $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
               $scope.orglist = data.organisations;
          });
     }
     $http.post(BASE_URL + 'orders/services_list').success(function (data) {
          $scope.filter_servicelist = data.services;
     });
     $http.post(BASE_URL + 'orders/statusList').success(function (data) {
          $scope.filter_statuslist = data.status;
          $scope.filter_statuslist[$scope.filter_statuslist.length] = {
               display_name: "Pending",
               status_id: "9999"
          };
     });
     $scope.get_teams = function () {
          $http.post(BASE_URL + 'orders/teamList', {
               'organisation': $scope.orderslistdata.organisation
          }).success(function (data) {
               $scope.filter_teamlist = data.teams;
               $scope.orderslistdata.team = "all";
          });
     };
     $scope.getOrders = function (page) {
          var tmp = {};
          angular.element("#orderslist_body").hide();
          angular.element("#orders_loading").show();
          $scope.orderslistdata.currentPage = page;
          angular.copy($scope.orderslistdata, tmp);
          if ($scope.orderslistdata.status == 9999) {
               tmp.status = 'all';
          }
          orderService.OrdersList(tmp).then(function (response) {
               var data = response.data;
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
               // $scope.orderslistdata.currentPage = page;
               $scope.orderslistdata.current_user_id = data.current_user_id;
               //alert($scope.orderslistdata.current_user_id);
          });
     };
     $scope.$watch('$state.current.name', function (newValue) {
          if (newValue === 'delivery_orders' || newValue === 'organisation.orders') {
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
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {
               id: id
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
     $scope.show_delete_warning = function (order_id) {
          $scope.delete_id = order_id;
          $scope.DO_delete_warning_popup = true;
     };
     $scope.deleteOrder = function () {
          orderService.deleteOrder($scope.delete_id).then(function (response) {
               notify({
                    message: response.data.msg,
                    classes: response.data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (response.data.status === 1) {
                    $scope.getOrders($scope.orderslistdata.currentPage);
               }
               $scope.cancel_warning();
          });
     };
     $scope.cancel_warning = function () {
          $scope.DO_delete_warning_popup = false;
          $scope.delete_id = 0;
     };
     $scope.show_cancel_warning = function (order_id) {
          $scope.cancel_id = order_id;
          $scope.cancel_warning_popup = true;
     };
     $scope.cancelOrder = function () {
          $http.post(BASE_URL + "orders/cancel_order", {
               'consignment_id': $scope.cancel_id
          }).success(function (data) {
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.getOrders($scope.orderslistdata.currentPage);
               }
               $scope.hide_cancel_warning();
          });
     };
     $scope.hide_cancel_warning = function () {
          $scope.cancel_warning_popup = false;
          $scope.cancel_id = 0;
     };
}
function neworderCtrl($scope, $http, $rootScope, $stateParams, $state, editOrder, orderService, notify, $filter, phonebook, $timeout, $window) {
     $scope.neworder = {};
     $scope.show_part = {
          one: true,
          two: false,
          three: false
     };
     $scope.show_part_one = function () {
          $scope.show_part = {
               one: true,
               two: false,
               three: false
          };
     };
     $scope.backup_list = {};
     var backlist = [
          {'id': 18, 'name': "Collect back on next business day"},
          {'id': 19, 'name': "Collect back on same day as delivery"},
          {'id': 20, 'name': "Collect back within a week"}
     ];
     var picklist = [
          {'id': 16, 'name': "Collect and deliver on the same day"},
          {'id': 17, 'name': "Collect the day before delivery"}
     ];
     $scope.neworder.pickup = 0;
     $scope.$watch('neworder.pickup', function () {
          if ($scope.neworder.pickup != 1 && $scope.neworder.pickup != 2) {
               $scope.neworder.collect_back_item = null;
          }
          if ($scope.neworder.pickup == 1) {
               $scope.backup_list = picklist;
               $scope.neworder.collect_back_item = picklist[0].id;
          } else if ($scope.neworder.pickup == 2) {
               $scope.backup_list = backlist;
               $scope.neworder.collect_back_item = backlist[0].id;
          } else {
               $scope.backup_list = {};
          }
     });
     $scope.collect_back_fn = function () {
          if ($scope.collect_back_fn) {
               $scope.neworder.pickup = 2;
          } else {
               $scope.neworder.pickup = 0;
          }
     };
     $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     $(window).resize(function () {
          $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     });
     $scope.newordertypesel = function () {
          $timeout(function () {
               angular.element('form .ng-invalid').addClass('formerror');
               var error_fields = angular.element('.order_form').find('.ng-invalid.formerror');
               var erf = angular.element('.order_form').find('.ng-invalid#newordertype');
               if (erf.length > 0) {
                    erf[0].focus();
                    // get the ui select controller
                    var uiSelect = angular.element('.ng-invalid#newordertype').controller('uiSelect');
                    // focus the focusser, putting focus onto select but without opening the dropdown
                    uiSelect.focusser[0].focus();
                    // Open the select without focusing the search box.  I use this on mobile to
                    // prevent keyboard from popping up automatically when clicking into the box
                    //                uiSelect.open = true;

                    // Open the select and focus:
                    uiSelect.activate();
               } else if (error_fields.length > 0) {
                    error_fields[0].focus();
               }
          }, 500);
     }

     $scope.show_part_two = function () {
          if ($scope.show_part.one === true && $scope.new_order_p1.$invalid) {
               $timeout(function () {
                    angular.element('form .ng-invalid').addClass('formerror');
                    var error_fields = angular.element('.order_form').find('.ng-invalid.formerror');
                    var erf = angular.element('.order_form').find('.ng-invalid#newordertype');
                    console.log(error_fields);
                    if (erf.length > 0) {
                         erf[0].focus();
                    } else if (error_fields.length > 0) {
                         error_fields[0].focus();
                    }
               }, 500);
          } else {
               $scope.show_part = {
                    one: false,
                    two: true,
                    three: false
               };
               $scope.getServiceList(true);
          }
     };
     $scope.editeddraft = function () {
          if ($rootScope.draftConfirm) {
               $rootScope.draftConfirm = false;
               setTimeout(function () {
                    $scope.show_part_two();
               }, 500);
          }
     };
     //     

     $scope.validateemail = function (email) {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return re.test(email);
     }
     $scope.show_part_three = function () {
          var set = false;
          angular.forEach($scope.payment_modes, function (data) {
               if (!set) {
                    if (data.id != 0) {
                         set = true;
                         $scope.neworder.payment_mode = data;
                    }
               }
          });
          if ($scope.neworder.assigned_service != undefined) {
               $scope.calcServicePrice();
          }

          if ($scope.neworder.delivery_is_assign == 1) {
               if ($scope.neworder.assigned_service != undefined) {
                    $scope.show_part = {
                         one: false,
                         two: false,
                         three: true
                    };
               }
          } else {
               delete $scope.neworder.assigned_service;
          }

          if ($scope.neworder.delivery_is_assign == 2) {
               angular.forEach($scope.payments, function (value, index) {
                    if (value.name != "All") {
                         $scope.payment_modes.push(value);
                    }
               });
               $scope.neworder.payment_mode = $scope.payment_modes[0];
               if ($scope.neworder.deadline != undefined) {
                    $scope.show_part = {
                         one: false,
                         two: false,
                         three: true
                    };
               }
          }
          if ($scope.neworder.delivery_is_assign == 3) {
               if ($scope.neworder.third_party_email != undefined) {
                    if ($scope.validateemail($scope.neworder.third_party_email)) {
                         $scope.show_part = {
                              one: false,
                              two: false,
                              three: true
                         };
                    } else {
                         $scope.neworder.third_party_emailerror = 'has-error';
                    }
               } else {
                    $scope.neworder.third_party_emailerror = 'has-error';
               }
          }

     };
     $scope.service_filter_field = 'priority';
     $scope.set_filter = function (filter) {
          $scope.service_filter_field = filter;
     };
     $scope.my_contacts_popup = false;
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
     $scope.typelist = [];
     $scope.countrylist = {};
     $scope.servicelist = {};
     $scope.ctimezones = {};
     $scope.timezones = {};
     $scope.dtimezones = {};
     $scope.errors = {};
     $scope.orglist = {};
     $scope.scop = {};
     $scope.tags = [];
     $scope.refs = [];
     $scope.collect_shortcuts = "";
     $scope.delivery_shortcuts = "";
     $scope.mapc = {
          result1: '',
          option1: null,
          details1: ''
     };
     $scope.mapd = {
          result2: '',
          option2: null,
          details2: ''
     };
     $scope.set_dpin = function () {
          $timeout(function () {
               if ($scope.mapd.details2.address_components !== undefined) {
                    var components = $scope.mapd.details2.address_components;
                    $scope.neworder.delivery_address_l2 = $scope.mapd.result2;
                    angular.forEach(components, function (component, key) {
                         if (component.types.indexOf('postal_code') !== -1) {
                              $scope.neworder.delivery_zipcode = component.long_name;
                         }
                         if (component.types.indexOf('country') !== -1) {
                              $scope.neworder.delivery_country = component.short_name.toLowerCase();
                              $scope.getDTimezones(true);
                         }
                         ;
                    });
               }
          }, 200);
     };
     $scope.set_cpin = function () {
          $timeout(function () {
               if ($scope.mapc.details1.address_components !== undefined) {
                    var components = $scope.mapc.details1.address_components;
                    console.info(components);
                    $scope.neworder.collect_from_l2 = $scope.mapc.result1;
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
     $scope.calcPrice = function (service) {
          var neworder = $scope.neworder;
          var p = (neworder.is_bulk ? Math.max(service.volume_cost * neworder.volume, service.weight_cost * neworder.weight) : neworder.quantity * service.price);
          //          console.log(p);


          if (service.surcharge != undefined && $scope.neworder.is_d_restricted_area != undefined) {
               var checkstau = false;
               //               console.log($scope.neworder.is_d_restricted_area.collect_back);
               if ($scope.neworder.is_d_restricted_area.collect_back) {
                    angular.forEach(service.surcharge, function (component, key) {
                         if (component.location == 16) {
                              p += $scope.neworder.quantity * component.price;
                         }
                    });
               }
               if ($scope.neworder.is_d_restricted_area.loc != undefined) {

                    if ($scope.neworder.is_d_restricted_area.loc == 1) {
                         angular.forEach(service.surcharge, function (component, key) {
                              if (component.location == 8) {
                                   p += $scope.neworder.quantity * component.price;
                              }
                         });
                    }
                    if ($scope.neworder.is_d_restricted_area.loc == 2) {
                         angular.forEach(service.surcharge, function (component, key) {
                              if (component.location == 4) {
                                   p += $scope.neworder.quantity * component.price;
                              }
                         });
                    }
                    if ($scope.neworder.is_d_restricted_area.loc == 3) {
                         angular.forEach(service.surcharge, function (component, key) {
                              if (component.location == 2) {
                                   p += $scope.neworder.quantity * component.price;
                              }
                         });
                    }
                    if ($scope.neworder.is_d_restricted_area.loc == 4) {
                         angular.forEach(service.surcharge, function (component, key) {
                              if (component.location == 1) {
                                   p += $scope.neworder.quantity * component.price;
                              }
                         });
                    }
               } else {
                    checkstau = true;
               }
               if ($scope.neworder.is_d_restricted_area.clocation != undefined) {
                    if (checkstau || ($scope.neworder.is_d_restricted_area.clocation != $scope.neworder.is_d_restricted_area.loc)) {
                         if ($scope.neworder.is_d_restricted_area.clocation == 1) {
                              angular.forEach(service.surcharge, function (component, key) {
                                   if (component.location == 8) {
                                        p += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.clocation == 2) {
                              angular.forEach(service.surcharge, function (component, key) {
                                   if (component.location == 4) {
                                        p += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.clocation == 3) {
                              angular.forEach(service.surcharge, function (component, key) {
                                   if (component.location == 2) {
                                        p += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.clocation == 4) {
                              angular.forEach(service.surcharge, function (component, key) {
                                   if (component.location == 1) {
                                        p += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                    }
               }
          }
          return p + " SGD";
     };
     $scope.price = 0;
     $scope.calcServicePrice = function () {
          if ($scope.neworder.assigned_service != undefined) {
               var neworder = $scope.neworder;
               var p = (neworder.is_bulk ? Math.max($scope.neworder.assigned_service.volume_cost * neworder.volume, $scope.neworder.assigned_service.weight_cost * neworder.weight) : neworder.quantity * $scope.neworder.assigned_service.price);
               $scope.price = p;
               if ($scope.neworder.assigned_service.surcharge != undefined && $scope.collect_back != undefined) {
                    if ($scope.collect_back) {
                         angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                              if (component.location == 16) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                              if (component.location == 17) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                              if (component.location == 18) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                              if (component.location == 19) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                              if (component.location == 20) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                         });
                    }
               }
               if ($scope.neworder.assigned_service.surcharge != undefined && $scope.neworder.is_d_restricted_area != undefined) {
                    var checkstau = false;
                    var appointment = 0;
                    if ($scope.neworder.is_d_restricted_area.appointment != undefined && $scope.neworder.is_d_restricted_area.appointment != null) {
                         appointment = 25;
                    }
                    if ($scope.neworder.is_d_restricted_area.appointment1 != undefined && $scope.neworder.is_d_restricted_area.appointment1 != null) {
                         appointment = 25;
                    }
                    if (appointment == 25) {
                         angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                              if (component.location == 25) {
                                   $scope.price += $scope.neworder.quantity * component.price;
                              }
                         });
                    }

                    if ($scope.neworder.is_d_restricted_area.loc != undefined) {
                         if ($scope.neworder.is_d_restricted_area.loc == 1) {
                              angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                   if (component.location == 8) {
                                        $scope.price += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.loc == 2) {
                              angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                   if (component.location == 4) {
                                        $scope.price += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.loc == 3) {
                              angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                   if (component.location == 2) {
                                        $scope.price += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                         if ($scope.neworder.is_d_restricted_area.loc == 4) {
                              angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                   if (component.location == 1) {
                                        $scope.price += $scope.neworder.quantity * component.price;
                                   }
                              });
                         }
                    } else {
                         checkstau = true;
                    }

                    if ($scope.neworder.is_d_restricted_area.clocation != undefined) {
                         if (checkstau || ($scope.neworder.is_d_restricted_area.clocation != $scope.neworder.is_d_restricted_area.loc)) {
                              if ($scope.neworder.is_d_restricted_area.clocation == 1) {
                                   angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                        if (component.location == 8) {
                                             $scope.price += $scope.neworder.quantity * component.price;
                                        }
                                   });
                              }
                              if ($scope.neworder.is_d_restricted_area.clocation == 2) {
                                   angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                        if (component.location == 4) {
                                             $scope.price += $scope.neworder.quantity * component.price;
                                        }
                                   });
                              }
                              if ($scope.neworder.is_d_restricted_area.clocation == 3) {
                                   angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                        if (component.location == 2) {
                                             $scope.price += $scope.neworder.quantity * component.price;
                                        }
                                   });
                              }
                              if ($scope.neworder.is_d_restricted_area.clocation == 4) {
                                   angular.forEach($scope.neworder.assigned_service.surcharge, function (component, key) {
                                        if (component.location == 1) {
                                             $scope.price += $scope.neworder.quantity * component.price;
                                        }
                                   });
                              }
                         }
                    }
               }
          }
          $scope.neworder.threshold = $scope.price;
     };
     $scope.view_restricted_info = function () {
          $scope.show_restrict_info = $scope.show_restrict_info ? false : true;
     };
     $scope.view_tuas_info = function () {
          $scope.show_tuas_info = $scope.show_tuas_info ? false : true;
     };
     $scope.filtertypelist = [
          {
               value: 1,
               name: "All Services"
          },
          {
               value: 0,
               name: "Pre-approved Services"
          }
     ];
     $scope.payments = [{
               value: '0000',
               name: 'All'
          }];
     $scope.payment_modes = [];
     $scope.servicelistdata = {
          type: $scope.filtertypelist[0]['value'],
          payment: $scope.payments[0]['value']
     };
     $scope.third_parties = [];
     $scope.show_third_party_list = false;
     $scope.suggest_third_party = function () {
          $http.post(BASE_URL + 'orders/get_third_partys', {
               "search": $scope.neworder.third_party_email
          }).success(function (data) {
               if (data.couriers) {
                    $scope.show_third_party_list = true;
                    $scope.third_parties = data.couriers;
               } else {
                    $scope.third_parties = [];
                    $scope.show_third_party_list = false;
               }
          });
     };
     $scope.setParty = function (party) {
          $scope.neworder.third_party_email = party.email;
          $scope.show_third_party_list = false;
     }
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

          var days = 1;

          $http.post(BASE_URL + 'orders/getholidays', {days: days, date: moment().add(days, 'days').format('YYYY-MM-DD')}).then(function (data) {
               days = data.data.days;
               $scope.csclist = [
                    {
                         'name': "Today",
                         "from": moment().hour(10).minute(0),
                         "to": moment().hour(18).minute(0)
                    },
                    {
                         'name': "Next business Day",
                         "from": moment().add(days, 'days').hour(10).minute(0),
                         "to": moment().add(days, 'days').hour(18).minute(0)
                    },
                    {
                         'name': "Other Collection Timing Date",
                         "from": null,
                         "to": null
                    }
               ];
               $scope.collect_shortcuts = $scope.csclist[0];
          });


     };
     $scope.setClist();
     $scope.setCollectionTime = function () {
          $scope.setCRange();
     };
     $('.d-datepicker').daterangepicker({
          timePicker: false,
          singleDatePicker: true,
          format: 'MM/DD/YYYY',
          autoUpdateInput: true,
          minDate: new Date()
     });
     $scope.dsclist = [
          {
               'name': "Next business Day",
               "from": moment().add(1, 'days').hour(10).minute(0),
               "to": moment().add(1, 'days').hour(18).minute(0)
          },
          {
               'name': "6-hours from now",
               "from": moment(),
               "to": moment().add(6, 'hour')
          },
          {
               'name': "3-hours from now",
               "from": moment(),
               "to": moment().add(3, 'hour')
          },
          {
               'name': "90-min from now",
               "from": moment(),
               "to": moment().add(90, 'minute')
          }/*,
           {
           'name': "3 business days",
           "from": moment().hour(10).minute(0),
           "to": $scope.addBusinessDays()
           },
           {
           'name': "Weekend Delivery (10am - 10pm)",
           "from": moment().endOf('week').hour(10).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           },
           {
           'name': "Weekend Delivery (Night 6-10pm)",
           "from": moment().endOf('week').subtract(1, 'days').hour(18).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           },
           {
           'name': "Weekday Delivery (Night 6-10pm)",
           "from": $scope.startweekdays().hour(18).minute(0),
           "to": $scope.endweekdays().hour(22).minute(0)
           }*/
     ];
     $scope.locSurcharges = [
          {
               name: 'Tuas',
               value: 1
          },
          {
               name: 'CBD',
               value: 2
          },
          {
               name: 'Sentosa',
               value: 3
          },
          {
               name: 'Other Restricted Areas',
               value: 4
          }
     ];
     $scope.setDlist = function (to) {
          $scope.delivery_shortcuts = "";
          $scope.dsclist = [];
          if (to == null) {
               to = moment();
          }
          var todatetime = to.format('MM/DD/YYYY h:mm A');
          var todate = moment(to.format('MM/DD/YYYY'));
          var name = "";
          var days = 1;


          $http.post(BASE_URL + 'orders/getholidays', {days: days, date: moment(todatetime).add(days, 'days').format('YYYY-MM-DD')}).then(function (data) {
               days = data.data.days;

               $scope.dsclist = [];
               if (todate < moment()) {
                    name = "Today";
               } else {
                    name = "Same Day";
               }

               $scope.dsclist.push({
                    'name': name,
                    "from": moment(todate),
                    "to": moment(todate).hour(18).minute(0)
               });
               $scope.dsclist.push({
                    'name': "Next Business Day",
                    "from": moment(todatetime).add(days, 'day').hour(10).minute(0),
                    "to": moment(todatetime).add(days, 'day').hour(18).minute(0)
               });
               $scope.dsclist.push({
                    'name': "Other Timing",
                    "from": null,
                    "to": null
               });
               $scope.delivery_shortcuts = $scope.dsclist[0];
               $scope.setDRange();

               setTimeout(function () {
                    $scope.changedeliveryshortcuts();
               }, 200);
          });


          /*
           if (to < $scope.addBusinessDays()) {
           $scope.dsclist.push({
           'name': "3 business days",
           "from": moment().hour(10).minute(0),
           "to": $scope.addBusinessDays()
           });
           }
           if (to < moment().add(1, 'week').startOf('week').hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekend Delivery (10am - 10pm)",
           "from": moment().endOf('week').hour(10).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           });
           }
           if (to < moment().add(1, 'week').startOf('week').hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekend Delivery (Night 6-10pm)",
           "from": moment().endOf('week').subtract(1, 'days').hour(18).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           });
           }
           if (to < $scope.endweekdays().hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekday Delivery (Night 6-10pm)",
           "from": $scope.startweekdays().hour(18).minute(0),
           "to": $scope.endweekdays().hour(22).minute(0)
           });
           }
           */

     };
     $scope.collectlist = [{
               'name': "Morning(10am - 1pm)",
               "from": {'hour': '10'},
               "to": {'hour': '13'}
          }, {
               'name': "Afternoon(1pm - 6pm)",
               "from": {'hour': '13'},
               "to": {'hour': '18'}
          }, {
               'name': "By Appointment",
               "from": {'hour': null},
               "to": {'hour': null}
          }

     ];
     $scope.collect_spl_list = [
          {
               'name': "6am-6:30am",
               "from": {'hour': 6, minute: 0},
               "to": {'hour': 6, minute: 30}
          }, {
               'name': "6:30am-7am",
               "from": {'hour': 6, minute: 30},
               "to": {'hour': 7, minute: 0}
          }, {
               'name': "7am-7:30am",
               "from": {'hour': 7, minute: 0},
               "to": {'hour': 7, minute: 30}
          }, {
               'name': "7:30am-8am",
               "from": {'hour': 7, minute: 30},
               "to": {'hour': 8, minute: 0}
          }, {
               'name': "8am-8:30am",
               "from": {'hour': 8, minute: 0},
               "to": {'hour': 8, minute: 30}
          }, {
               'name': "8:30am-9am",
               "from": {'hour': 8, minute: 30},
               "to": {'hour': 9, minute: 0}
          }, {
               'name': "9am-9:30am",
               "from": {'hour': 9, minute: 0},
               "to": {'hour': 9, minute: 30}
          }, {
               'name': "9:30am-10am",
               "from": {'hour': 9, minute: 30},
               "to": {'hour': 10, minute: 0}
          },
          {
               'name': "10am-10:30am",
               "from": {'hour': 10, minute: 0},
               "to": {'hour': 10, minute: 30}
          }, {
               'name': "10:30am-11am",
               "from": {'hour': 10, minute: 30},
               "to": {'hour': 11, minute: 0}
          }, {
               'name': "11am-11:30am",
               "from": {'hour': 11, minute: 0},
               "to": {'hour': 11, minute: 30}
          }, {
               'name': "11:30am-12pm",
               "from": {'hour': 11, minute: 30},
               "to": {'hour': 12, minute: 0}
          }, {
               'name': "12pm-12:30pm",
               "from": {'hour': 12, minute: 0},
               "to": {'hour': 12, minute: 30}
          }, {
               'name': "12:30pm-1pm",
               "from": {'hour': 12, minute: 30},
               "to": {'hour': 13, minute: 0}
          }, {
               'name': "1pm-1:30pm",
               "from": {'hour': 13, minute: 0},
               "to": {'hour': 13, minute: 30}
          }, {
               'name': "1:30pm-2pm",
               "from": {'hour': 13, minute: 30},
               "to": {'hour': 14, minute: 0}
          }, {
               'name': "2pm-2:30pm",
               "from": {'hour': 14, minute: 0},
               "to": {'hour': 14, minute: 30}
          }, {
               'name': "2:30pm-3pm",
               "from": {'hour': 14, minute: 30},
               "to": {'hour': 15, minute: 0}
          }, {
               'name': "3pm-3:30pm",
               "from": {'hour': 15, minute: 0},
               "to": {'hour': 15, minute: 30}
          }, {
               'name': "3:30pm-4pm",
               "from": {'hour': 15, minute: 30},
               "to": {'hour': 16, minute: 0}
          }, {
               'name': "4pm-4:30pm",
               "from": {'hour': 16, minute: 0},
               "to": {'hour': 16, minute: 30}
          }, {
               'name': "4:30pm-5pm",
               "from": {'hour': 16, minute: 30},
               "to": {'hour': 17, minute: 0}
          }, {
               'name': "5pm-5:30pm",
               "from": {'hour': 17, minute: 0},
               "to": {'hour': 17, minute: 30}
          }, {
               'name': "5:30pm-6pm",
               "from": {'hour': 17, minute: 30},
               "to": {'hour': 18, minute: 0}
          }, {
               'name': "6pm-6:30pm",
               "from": {'hour': 18, minute: 0},
               "to": {'hour': 18, minute: 30}
          }, {
               'name': "6:30pm-7pm",
               "from": {'hour': 18, minute: 30},
               "to": {'hour': 19, minute: 0}
          }, {
               'name': "7pm-7:30pm",
               "from": {'hour': 19, minute: 0},
               "to": {'hour': 19, minute: 30}
          }, {
               'name': "7:30pm-8pm",
               "from": {'hour': 19, minute: 30},
               "to": {'hour': 20, minute: 0}
          }, {
               'name': "8pm-8:30pm",
               "from": {'hour': 20, minute: 0},
               "to": {'hour': 20, minute: 30}
          }, {
               'name': "8:30pm-9pm",
               "from": {'hour': 20, minute: 30},
               "to": {'hour': 21, minute: 0}
          }, {
               'name': "9pm-9:30pm",
               "from": {'hour': 21, minute: 0},
               "to": {'hour': 21, minute: 30}
          }, {
               'name': "9:30pm-10pm",
               "from": {'hour': 21, minute: 30},
               "to": {'hour': 22, minute: 0}
          }

     ];
     $scope.collect_timing = $scope.collectlist[0];
     $scope.changecollectshortcuts = function () {
          $scope.setcollect_timing();
          var from = $scope.collect_shortcuts.from;
          if (from == null) {
               $scope.collect_shortcut_date = moment().format('MM/DD/YYYY');
          }
     };

     $scope.setdelivery_shortcut_date = function () {
          if ($scope.delivery_shortcut_date != '') {

          }
     }

     $scope.changedeliveryshortcuts = function () {
          $scope.dellist = {};
          $scope.del_shortcut_timing = $scope.collect_spl_list[0];
          if ($scope.delivery_shortcuts.from != null) {
               if ($scope.delivery_shortcuts.from.format("MM/DD/YYYY") == moment($scope.neworder.collect_date1).format('MM/DD/YYYY')) {

                    var t = $scope.neworder.collect_date1;
                    if (moment() > moment(t)) {
                         t = moment().format('MM/DD/YYYY HH:mm');
                    }
                    $scope.dellist = [{
                              'name': "3 hours",
                              "from": moment(t),
                              "to": moment(t).add('3', 'hour')
                         }, {
                              'name': "6 hours",
                              "from": moment(t),
                              "to": moment(t).add('6', 'hour')
                         }

                    ];
               } else {
                    var t = $scope.delivery_shortcuts.from.format("MM/DD/YYYY");
                    $scope.dellist = [{
                              'name': "Office Hours",
                              "from": moment(t).hour('10'),
                              "to": moment(t).hour('18')
                         }, {
                              'name': "9am - 12noon (Morning)",
                              "from": moment(t).hour('9'),
                              "to": moment(t).hour('12')
                         }, {
                              'name': "12noon - 3pm (Noon)",
                              "from": moment(t).hour('12'),
                              "to": moment(t).hour('15')
                         }, {
                              'name': "3pm - 6pm (Afternoon)",
                              "from": moment(t).hour('15'),
                              "to": moment(t).hour('18')
                         }, {
                              'name': "7pm -10pm (Night Delivery)",
                              "from": moment(t).hour('19'),
                              "to": moment(t).hour('22')
                         }, {
                              'name': "By Appointment",
                              "from": null,
                              "to": null
                         }

                    ];
               }
               $scope.delivery_shortcut_date = $scope.delivery_shortcuts.from.format("MM/DD/YYYY");
          } else {
               if ($scope.delivery_shortcut_date == '') {
                    $scope.delivery_shortcut_date = moment($scope.neworder.collect_date1).format("MM/DD/YYYY");
               }
               if ($scope.collect_shortcut_date != '') {
                    $("#datepickerSingle2").data('daterangepicker').remove();
                    $("#datepickerSingle2").daterangepicker({
                         timePicker: false,
                         singleDatePicker: true,
                         format: 'MM/DD/YYYY',
                         autoUpdateInput: true,
                         minDate: new Date($scope.neworder.collect_date1)
                    });

                    $("#datepickerSingle2").data('daterangepicker').setStartDate($scope.neworder.collect_date1);
//               $("#datepickerSingle2").data('daterangepicker').setminDate(moment($scope.collect_shortcut_date));
               }
               if ($scope.delivery_shortcut_date == '') {
                    var collect_shor_d = moment().format("MM/DD/YYYY");
               } else {
                    var collect_shor_d = $scope.delivery_shortcut_date.toString();
               }

               $scope.dellist = [{
                         'name': "Office Hours",
                         "from": moment(collect_shor_d, "MM/DD/YYYY").hour('10'),
                         "to": moment(collect_shor_d, "MM/DD/YYYY").hour('18')
                    }, {
                         'name': "9am - 12noon (Morning)",
                         "from": moment(collect_shor_d, "MM/DD/YYYY").hour('9'),
                         "to": moment(collect_shor_d, "MM/DD/YYYY").hour('12')
                    }, {
                         'name': "12noon - 3pm (Noon)",
                         "from": moment(collect_shor_d, "MM/DD/YYYY").hour('12'),
                         "to": moment(collect_shor_d, "MM/DD/YYYY").hour('15')
                    }, {
                         'name': "3pm - 6pm (Afternoon)",
                         "from": moment(collect_shor_d, "MM/DD/YYYY").hour('15'),
                         "to": moment(collect_shor_d, "MM/DD/YYYY").hour('18')
                    }, {
                         'name': "7pm -10pm (Night Delivery)",
                         "from": moment(collect_shor_d, "MM/DD/YYYY").hour('19'),
                         "to": moment(collect_shor_d, "MM/DD/YYYY").hour('22')
                    }, {
                         'name': "By Appointment",
                         "from": null,
                         "to": null
                    }

               ];
          }

          if ($scope.dellist[0]) {
               $scope.del_timing = $scope.dellist[0];
          }
          $scope.setDRange();
     };
     $scope.setcshortcut_timing = function () {
          var from = $scope.collect_shortcuts.from;
          if (from > moment()) {
               $scope.collect_shortcut_timing = $scope.collect_spl_list[0];
          }

          angular.forEach($scope.collect_spl_list, function (value, key) {
               var from = moment($scope.neworder.collect_date1).hour(value.from.hour).minute(value.from.minute);
               var to = moment($scope.neworder.collect_date2).hour(value.to.hour).minute(value.to.minute);
               var now = moment();
               if (now > from && now < to) {
                    $scope.collect_shortcut_timing = value;
               }
          });

     };
     $scope.setcshortcut_timing();
     $scope.setcollect_shortcut_date = function () {
          $scope.setCRange();
          $scope.setcshortcut_timing();
          $scope.setCRange();
     };
     $scope.setcollect_timing = function () {
          $scope.setCRange();
          $scope.setcshortcut_timing();
          $scope.setCRange();
     }

     $scope.setCRange = function () {
          if ($scope.collect_shortcuts != null) {
               var from = $scope.collect_shortcuts.from;
               var to = $scope.collect_shortcuts.to;
               if ($scope.neworder.is_d_restricted_area == undefined) {
                    $scope.neworder.is_d_restricted_area = {};
               }
               if ($scope.collect_timing.from.hour == null || $scope.collect_timing.to.hour == null) {

                    $scope.neworder.is_d_restricted_area.appointment1 = 25;
                    if (from == null || to == null) {
                         var collect_shortcut_date = moment($scope.collect_shortcut_date)
                         if ($scope.collect_shortcut_date == null) {
                              collect_shortcut_date = moment();
                         }
                         $scope.neworder.collect_date1 = collect_shortcut_date.hour($scope.collect_shortcut_timing.from.hour).minute($scope.collect_shortcut_timing.from.minute).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.collect_date2 = collect_shortcut_date.hour($scope.collect_shortcut_timing.to.hour).minute($scope.collect_shortcut_timing.to.minute).format('MM/DD/YYYY h:mm A');
                    } else {
                         $scope.neworder.collect_date1 = from.hour($scope.collect_shortcut_timing.from.hour).minute($scope.collect_shortcut_timing.from.minute).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.collect_date2 = to.hour($scope.collect_shortcut_timing.to.hour).minute($scope.collect_shortcut_timing.to.minute).format('MM/DD/YYYY h:mm A');

                    }

               } else {
                    $scope.neworder.is_d_restricted_area.appointment1 = null;
                    if (from == null || to == null) {
                         var collect_shortcut_date = moment($scope.collect_shortcut_date)
                         if ($scope.collect_shortcut_date == null) {
                              collect_shortcut_date = moment();
                         }
                         $scope.neworder.collect_date1 = collect_shortcut_date.hour($scope.collect_timing.from.hour).minute(0).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.collect_date2 = collect_shortcut_date.hour($scope.collect_timing.to.hour).minute(0).format('MM/DD/YYYY h:mm A');

                    } else {
                         $scope.collect_shortcut_date = null;
                         $scope.neworder.collect_date1 = from.hour($scope.collect_timing.from.hour).minute(0).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.collect_date2 = to.hour($scope.collect_timing.to.hour).minute(0).format('MM/DD/YYYY h:mm A');
                    }

//                    $scope.collect_shortcut_timing.from = moment($scope.collect_shortcut_date).hour($scope.collect_timing.from.hour).minute(0);
//                    $scope.collect_shortcut_timing.to = moment($scope.collect_shortcut_date).hour($scope.collect_timing.to.hour).minute(0);
               }
               $scope.setCTime();
               $scope.setDlist(from);
          } else {
               $scope.collect_timing = $scope.collectlist[0];
          }

     };
     setTimeout(function () {
          $scope.setCRange();
     }, 500);
     $scope.setDRange = function () {
          if ($scope.neworder.is_d_restricted_area == undefined) {
               $scope.neworder.is_d_restricted_area = {};
          }
          if ($scope.del_timing != null) {
               if ($scope.del_timing.from != null && $scope.del_timing.to != null) {
                    $scope.neworder.deliver_date1 = $scope.del_timing.from.format('MM/DD/YYYY h:mm A');
                    $scope.neworder.deliver_date2 = $scope.del_timing.to.format('MM/DD/YYYY h:mm A');
                    $scope.setDTime();
                    $scope.neworder.is_d_restricted_area.appointment = null;
               } else {
                    $scope.neworder.is_d_restricted_area.appointment = 25;
                    var tming = moment().format('MM/DD/YYYY');
                    if ($scope.delivery_shortcuts.from != null) {
                         tming = $scope.delivery_shortcuts.from.format('MM/DD/YYYY');
                    } else if ($scope.delivery_shortcut_date != '') {
                         tming = $scope.delivery_shortcut_date;
                    }
                    if ($scope.del_shortcut_timing.from.hour != null && $scope.del_shortcut_timing.to.hour != null) {

                         $scope.neworder.deliver_date1 = moment(tming, 'MM/DD/YYYY').hour($scope.del_shortcut_timing.from.hour).minute($scope.del_shortcut_timing.from.minute).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.deliver_date2 = moment(tming, 'MM/DD/YYYY').hour($scope.del_shortcut_timing.to.hour).minute($scope.del_shortcut_timing.to.minute).format('MM/DD/YYYY h:mm A');

                    }
               }
          }

     };
     $scope.scop.service_selected = {};
     $scope.org_dropdown = true;
     $scope.single_org = false;
     $scope.getTypeList = function () {
          orderService.consignmenttypes().then(function (response) {
               $scope.typelist = response.data.types;
          });
     };
     $scope.getTypeList();
     $scope.view_courier_info = function (id, $event) {
          $event.stopPropagation();
          $rootScope.$broadcast("show_courier_popup", {
               id: id
          });
     };
     $scope.getCountryList = function () {

          $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
               $scope.countrylist = response.data.countries;
          });
     };
     $scope.cscountrylist = [{
               "code": "sg",
               "numeric": "702",
               "country": "Singapore"
          }];
     $scope.getTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones').then(function (response) {
               $scope.timezones = response.data.timezones;
          });
     };
     $scope.getCTimezones = function (update) {
          $scope.neworder.collect_country = 'sg';
          $http.post(BASE_URL + 'orders/timezones', {
               country: $scope.neworder.collect_country
          }).then(function (response) {
               $scope.ctimezones = response.data.timezones;
               if (update)
                    $scope.neworder.collect_timezone = $scope.ctimezones[0];
               $scope.setCTime();
               console.log($scope.neworder.collect_country);
          });
     };
     $scope.getDTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones', {
               country: $scope.neworder.delivery_country
          }).then(function (response) {
               $scope.dtimezones = response.data.timezones;
               if (update)
                    $scope.neworder.delivery_timezone = $scope.dtimezones[0];
               $scope.setDTime();
          });
     };
     $scope.get_tag_array = function () {
          if ($scope.neworder.tags) {
               $scope.tags = $scope.neworder.tags.split(",");
          }
     }
     $scope.get_ref_array = function () {
          if ($scope.neworder.ref) {
               $scope.refs = $scope.neworder.ref.split(",");
          }
     }
     $scope.set_bulk = function () {
          if ($scope.neworder.type.consignment_type_id == 0) {
               $scope.neworder.is_bulk = true;
          } else {
               $scope.neworder.is_bulk = false;
          }
     };
     $scope.getServiceList = function (flag) {
          var org_id = null;
          if ($scope.neworder.org_id !== undefined) {
               org_id = $scope.neworder.org_id;
          }
          var collection_time = $scope.neworder.collect_date1 + '-' + $scope.neworder.collect_date2;
          var delivery_time = $scope.neworder.deliver_date1 + '-' + $scope.neworder.deliver_date2;
          var c_country = $scope.neworder.collect_country;
          var d_country = $scope.neworder.delivery_country;
          var location = {};
          if ($scope.neworder.is_d_restricted_area) {
               location = $scope.neworder.is_d_restricted_area;
          }
          if ($scope.collect_back) {
               location.collect_back = $scope.neworder.collect_back_item;
          }
          var consignment_type = $scope.neworder.type ? $scope.neworder.type.consignment_type_id : "-1";
          var type = 1;
          var term = '0000';
          if (!flag) {
               type = $scope.servicelistdata.type;
               term = $scope.servicelistdata.payment;
          }
          orderService.assignedServices(org_id, collection_time, delivery_time, c_country, d_country, type, consignment_type, term, location).then(function (response) {

               $scope.servicelist = response.data.services;
               angular.forEach($scope.servicelist, function (value, key) {
                    value.index = $scope.servicelist.length - (key + 1);
               });
               $scope.scop.open_bid = response.data.open_bid;
               if (response.data.open_bid == true) {
                    $scope.neworder.open_bid = true;
               } else {
                    $scope.neworder.open_bid = false;
               }
               if (flag) {
                    if (response.data.payments) {
                         $scope.payments = response.data.payments;
                    }

                    if (response.data.use_public == true) {
                         $scope.filtertypelist = [
                              {
                                   value: 1,
                                   name: "All Services"
                              }, {
                                   value: 0,
                                   name: "Pre-approved Services"
                              }
                         ];
                    } else {
                         $scope.filtertypelist = [
                              {
                                   value: 0,
                                   name: "Pre-approved Services"
                              }
                         ];
                    }
                    $scope.servicelistdata = {
                         type: $scope.filtertypelist[0]['value'],
                         payment: $scope.payments[0]['value']
                    };
               }


               $scope.scop.c_count = response.data.c_count;
               if ($scope.neworder.assigned_service != undefined) {
                    var test = $filter('filter')($scope.servicelist, {
                         'service_id': $scope.neworder.assigned_service.servie_id
                    })[0];
                    if (test) {
                    } else {
                         $scope.neworder.assigned_service = undefined;
                    }
               }
               $scope.temp_service = orderService.get_service();
               if ($scope.temp_service.service_id) {
                    if ($scope.temp_service.org_id) {
                         $scope.defaultorganisation_id = $scope.temp_service.org_id;
                    }
                    $filter_list = [];
                    angular.forEach($scope.servicelist, function (data) {
                         if (data.service_id == $scope.temp_service.service_id) {
                              $filter_list.push(data);
                         }
                    });
                    if ($filter_list.length > 0) {
                         $scope.not_available = false;
                         $filter_list[0].priority = $scope.servicelist.length;
                         $scope.scop.service_selected = $filter_list[0];
                         $timeout(function () {
                              $scope.set_service($scope.scop.service_selected, false);
                         }, 1000);
                    } else {
                         $scope.not_available = true;
                    }


               }
          });
     };
     $scope.get_org_list = function () {
          if ($stateParams.id !== undefined) {
               $scope.org_dropdown = false;
               $scope.org_id = $stateParams.id;
          } else {
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
                         }

                    }

               });
          }
          $scope.temp_service = orderService.get_service();
          if ($scope.temp_service.org_id) {
               $scope.neworder.org_id = $scope.temp_service.org_id;
          }
          $scope.getServiceList(true);
     };
     if (editOrder) {
          $scope.get_org_list();
          orderService.getOrder($stateParams.order_id).then(function (response) {
               if (response.data.count === 0)
                    $scope.is_empty = true;
               $scope.neworder = response.data.order;
               $scope.getServiceList(true);
               $scope.getCTimezones(false);
               $scope.getDTimezones(false);
               $scope.get_tag_array();
               $scope.get_ref_array();
               $timeout(function () {
                    $scope.scop.service_selected = $scope.neworder.assigned_service;
                    $scope.set_service($scope.neworder.assigned_service, false);
                    $scope.editeddraft();
               }, 3000);
               $scope.scop.open_bid = $scope.neworder.open_bid;
          });
          $scope.selected_contact = {
               'save_collect_contact': false,
               'save_delivery_contact': false,
               'collect_from_contact': false,
               'delivery_from_contact': false
          };
     } else {
          orderService.getOrder('').then(function (response) {
               $scope.neworder = response.data.order;
               $scope.get_org_list();
               $scope.getCTimezones(true);
               $scope.getDTimezones(false);
               $scope.get_collect_data = phonebook.get_collect();
               if ($scope.get_collect_data.contact_name) {
                    $scope.set_collect_data($scope.get_collect_data);
                    $scope.selected_contact.save_collect_contact = false;
               }
               $scope.get_deliver_data = phonebook.get_deliver();
               if ($scope.get_deliver_data.contact_name) {
                    $scope.set_deliver_data($scope.get_deliver_data);
                    $scope.selected_contact.save_delivery_contact = false;
               }
               phonebook.clear();
               $scope.bidding_service = orderService.getBidding();
               if ($scope.bidding_service) {
                    $scope.neworder.delivery_is_assign = 2;
               }
               orderService.resetBidding();
               $scope.neworder.deliver_date1 = moment().add(2, 'days').hour(10).minute(0).format('MM/DD/YYYY h:mm A')
               $scope.neworder.deliver_date2 = moment().add(2, 'days').hour(17).minute(0).format('MM/DD/YYYY h:mm A')
               $scope.setDTime();
               $scope.setCollectionTime();
          });
     }


     //initialize lists
     $scope.getTypeList();
     $scope.getCountryList();
     $scope.getTimezones();
     $scope.saveOrder = function (flag) {
          scroll_to_loading();
          $scope.neworder.draft = flag;
          $scope.processing = true;
          if ($stateParams.id !== undefined || $scope.single_org) {
               $scope.neworder.org_id = $scope.org_id;
          }
          $scope.neworder.price = $scope.price;
          $http.post(BASE_URL + 'orders/saveOrder', $scope.neworder).success(function (data) {
               $scope.processing = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    ga('send', {
                         hitType: 'event',
                         eventCategory: 'Deliver',
                         eventAction: 'add',
                         eventLabel: $scope.neworder.deadline && $scope.neworder.deadline != '' ? 'New Delivery Tender Request' : 'New Delivery Order'
                    });
                    if ($scope.selected_contact.save_collect_contact == true) {
                         $scope.save_c_contact = {
                              address_line1: $scope.neworder.collect_from_l1,
                              address_line2: $scope.neworder.collect_from_l2,
                              postal_code: $scope.neworder.collection_zipcode,
                              country_code: $scope.neworder.collect_country,
                              contact_name: $scope.neworder.collect_contactname,
                              phone_number: $scope.neworder.collect_phone,
                              'company_name': $scope.neworder.collect_company,
                              email: $scope.neworder.collect_email
                         };
                         phonebook.set_collect($scope.save_c_contact);
                         phonebook.save_collect();
                    }
                    if ($scope.selected_contact.save_delivery_contact == true) {
                         $scope.save_d_contact = {
                              address_line1: $scope.neworder.delivery_address_l1,
                              address_line2: $scope.neworder.delivery_address_l2,
                              postal_code: $scope.neworder.delivery_zipcode,
                              country_code: $scope.neworder.delivery_country,
                              contact_name: $scope.neworder.delivery_contactname,
                              phone_number: $scope.neworder.delivery_phone,
                              'company_name': $scope.neworder.delivery_company,
                              email: $scope.neworder.delivery_email
                         };
                         phonebook.set_deliver($scope.save_d_contact);
                         phonebook.save_deliver();
                    }
                    phonebook.reset_contact();
                    orderService.reset_service();
                    if (data.public_id) {
                         if (data.new) {
                              orderService.setNew();
                         }
                         $state.go('delivery_orders.view_order', {
                              order_id: data.public_id
                         });
                    } else {
                         $state.go('^');
                    }
               } else {
                    $scope.errors = data.errors;
                    if (data.part === 1) {
                         $scope.show_part_one();
                    } else if (data.part === 2) {
                         $scope.show_part_two();
                    } else if (data.part === 3) {
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
     $scope.checkvolume = function () {
          if ($scope.neworder.height !== undefined && $scope.neworder.breadth !== undefined && $scope.neworder.length !== undefined) {
               $scope.calc_volume();
          } else {
               $scope.reset_volume();
          }
     };
     $scope.calc_volume = function () {
          var volume = parseFloat($scope.neworder.height) * parseFloat($scope.neworder.breadth) * parseFloat($scope.neworder.length);
          if (volume > 0)
               $scope.neworder.volume = $filter("number")(volume, 3);
          else {
               $scope.reset_volume();
          }
     };
     $scope.reset_volume = function () {
          $scope.neworder.volume = undefined;
     };
     $scope.resetServices = function () {
          angular.element(".srv_list li").removeClass('active');
          $scope.neworder.assigned_service = undefined;
          $scope.scop.service_selected = {};
          $scope.scop.open_bid = undefined;
          $scope.scop.c_count = undefined;
          $scope.neworder.open_bid = undefined;
          $scope.getServiceList(true);
     };
     $scope.set_bid_request = function (flag) {
          $scope.price = 0;
          if (flag === 1) {
               $scope.neworder.delivery_is_assign = 1;
          } else if (flag === 2) {
               angular.element(".srv_list li").removeClass('active');
               $scope.neworder.delivery_is_assign = 2;
          } else if (flag === 3) {
               angular.element(".srv_list li").removeClass('active');
               $scope.neworder.delivery_is_assign = 3;
          }
     };
     $scope.reset_bid_request = function () {
          $scope.neworder.delivery_is_assign = 1;
     };
     $scope.getIntersectionOfArray = function (array1, array2) {
          $scope.payment_modes = [];
          angular.forEach(array1, function (value, index) {
               angular.forEach(array2, function (object, index1) {
                    if (value.value == object.value) {
                         $scope.payment_modes.push(object);
                    }
               });
          });
     };
     $scope.set_service = function (service, flag) {
          if (service.service_id) {
               $scope.reset_bid_request();
               angular.element(".srv_list li").removeClass('active');
               angular.element(".srv_list li#service" + service.service_id).addClass('active');
               $scope.neworder.assigned_service = service;
               $scope.getIntersectionOfArray($scope.payments, service.payments);
               $scope.neworder.payment_mode = $scope.payment_modes[0];
               if (flag) {
                    if (service.is_public == 0) {
                         $scope.neworder.threshold = service.threshold_price;
                    } else {
                         if ($scope.price != undefined) {
                              $scope.neworder.threshold = $scope.price;
                         } else {
                              $scope.neworder.threshold = "";
                         }
                    }
                    $scope.view_assigned_couriers_list = false;
               }
          }
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
          if ($scope.neworder.collect_country) {
               $scope.view_collect_timezone_list = true;
          } else {
               alert('Select country first');
          }
     };
     $scope.show_delivery_timezone = function () {
          if ($scope.neworder.delivery_country) {
               $scope.view_delivery_timezone_list = true;
          } else {
               alert('Select country first');
          }
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
     $scope.setCTime = function () {
          if ($scope.neworder.collect_date1 && $scope.neworder.collect_date2) {
               var cdate = $scope.neworder.collect_date1 + '-' + $scope.neworder.collect_date2;
               if ($scope.neworder.collect_timezone == undefined) {
                    $scope.neworder.collect_timezone = {"zoneinfo": "Asia\/Singapore", "offset": "UTC+8", "summer": null, "country": "sg", "cicode": "UP8", "cicodesummer": null};
               }
               var czone = $scope.neworder.collect_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {
                    time: cdate,
                    'timezone': czone
               }).then(function (response) {
                    if (response.data.date)
                         $scope.neworder.cdate_convert = response.data.date;
                    $scope.neworder.cdate_convert1 = response.data.date.split("-")[0];
                    $scope.neworder.cdate_convert2 = response.data.date.split("-")[1];
                    $scope.neworder.cdate_convert1_1 = response.data.start_date;
                    $scope.neworder.cdate_convert1_2 = response.data.start_time;
                    $scope.neworder.cdate_convert2_1 = response.data.end_date;
                    $scope.neworder.cdate_convert2_2 = response.data.end_time;
                    var date = new Date(response.data.date.split("-")[0]);
                    var formatted = moment(date);
                    $scope.setDlist(formatted);
               });
          }
     };
     $scope.setDTime = function () {
          if ($scope.neworder.deliver_date1 && $scope.neworder.deliver_date2) {
               var ddate = $scope.neworder.deliver_date1 + '-' + $scope.neworder.deliver_date2;
               var dzone = $scope.neworder.delivery_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {
                    time: ddate,
                    'timezone': dzone
               }).then(function (response) {
                    if (response.data.date)
                         $scope.neworder.ddate_convert = response.data.date;
                    $scope.neworder.ddate_convert1 = response.data.date.split("-")[0];
                    $scope.neworder.ddate_convert2 = response.data.date.split("-")[1];
                    $scope.neworder.ddate_convert1_1 = response.data.start_date;
                    $scope.neworder.ddate_convert1_2 = response.data.start_time;
                    $scope.neworder.ddate_convert2_1 = response.data.end_date;
                    $scope.neworder.ddate_convert2_2 = response.data.end_time;
               });
          }
     };
     $scope.cancel_delivery_timezone = function () {
          $scope.setDTime();
          $scope.view_delivery_timezone_list = false;
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
     $scope.cancel_contact_popup = function () {
          $scope.my_contacts_popup = false;
     };
     $scope.set_contact_temp = function (contact) {
          if ($scope.selected_contact.item === 'collect') {
               // phonebook.set_collect(contact);                $scope.selected_contact.collect_from_contact = true;
               $scope.selected_contact.save_collect_contact = false;
               $scope.selected_contact.collect_from_contact_id = contact.id;
               $scope.set_collect_data(contact);
          } else if ($scope.selected_contact.item === 'delivery') {
               $scope.selected_contact.delivery_from_contact = true;
               $scope.selected_contact.save_delivery_contact = false;
               $scope.set_deliver_data(contact);
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
     $scope.set_deliver_data = function (contact) {
          $scope.neworder.delivery_address_l1 = contact.address_line1;
          $scope.neworder.delivery_address_l2 = contact.address_line2;
          $scope.neworder.delivery_zipcode = contact.postal_code;
          $scope.neworder.delivery_country = contact.country_code;
          $scope.neworder.delivery_contactname = contact.contact_name;
          $scope.neworder.delivery_phone = contact.phone_number;
          $scope.neworder.delivery_email = contact.email;
          $scope.neworder.delivery_company = contact.company_name;
          $scope.getDTimezones(true);
     };
     $scope.create_account = function () {
          $scope.account = {};
          $scope.account.country_code = 'sg';
          $scope.account.account_holder = '';
          if ($scope.neworder.org_id) {
               $scope.account.account_holder = $scope.neworder.org_id;
          }
          $scope.create_account_form = true;
     };
     $scope.cancel_create_account = function () {
          $scope.account = {};
          $scope.isDisabled = false;
          $scope.errors = {};
          $scope.create_account_form = false;
     };
     $scope.save_account = function () {
          $scope.isDisabled = true;
          $http.post(BASE_URL + 'credit/add_postpaid_account', $scope.account).success(function (data) {
               $scope.isDisabled = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {
                    $scope.cancel_create_account();
                    $scope.get_accounts();
               } else {
                    if (data.errors) {
                         $scope.errors = data.errors;
                    }
               }
          });
     };
}
function vieworderCtrl($scope, $http, $rootScope, $stateParams, orderService, $state, notify, Lightbox) {
     $scope.cancel_view_receipt = function () {
          $scope.receipt_view = false;
     };
     $scope.show_receipt = function () {
          $scope.receipt_view = true;
     }
     $scope.cancel_view_receipt();
     $scope.activetab = {};
     if ($stateParams.activetab !== undefined) {
          var tab = $stateParams.activetab;
          if (tab == "message") {
               $scope.activetab.msg = true;
          }
     }
     if (orderService.getNew()) {
          $scope.print_info = true;
     }
     orderService.resetNew();
     $scope.images = [];
     $scope.openLightboxModal = function (index) {
          $scope.images = [index];
          Lightbox.openModal($scope.images, 0);
     };
     if ($stateParams.id !== undefined) {
          $scope.org_id = $stateParams.id;
     }
     ;

     $scope.imageCheck = function (imgname) {
          var extension = imgname.substring(imgname.lastIndexOf('.'));
          var validFileType = ".jpg , .png , .bmp";
          if (validFileType.toLowerCase().indexOf(extension) < 0) {
               return false;
          } else {
               return true;
          }
     }

     $scope.notifydata = function (data) {
          data = JSON.parse(data);
          if (data.error != "") {
               notify({
                    message: data.error,
                    classes: 'alert-danger',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
          }
     };
     $scope.notify_selection = function () {
          if ($scope.show_permalink) {
               notify({
                    message: 'Link copied to clipboard',
                    classes: 'alert-success',
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
          }
     };
     $scope.multipleorders = {};
     $scope.getTextToCopy = function () {
          if (!$scope.show_permalink) {
               $scope.show_permalink = true;
               angular.element('#permalink input').focus();
               return angular.element('#permalink input').val();
          } else {
               $scope.show_permalink = false;
          }
     }

     $scope.confirmOrder = function () {
          if (SERVICE_ASSIGNED) {
               orderService.confirmOrder($stateParams.order_id).then(function (response) {
                    notify({
                         message: response.data.msg,
                         classes: response.data.class,
                         templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    if (response.data.status === 1) {
                         $scope.hide_pending = true;
                         angular.element('.order_status').text(response.data.consignment_status);
                    }

               });
          } else {
               $rootScope.draftConfirm = true;
               $state.go("delivery_orders.edit_order", {
                    order_id: $stateParams.order_id
               }, {
                    reload: true
               });
          }
     };
     $scope.approvePrice = function () {
          orderService.approvePrice($stateParams.order_id).then(function (response) {
               notify({
                    message: response.data.msg,
                    classes: response.data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (response.data.status === 1) {
                    $state.transitionTo($state.current, $stateParams, {
                         reload: true
                    });
               }

          });
     };
     $scope.rejectPrice = function () {
          orderService.rejectPrice($stateParams.order_id).then(function (response) {
               notify({
                    message: response.data.msg,
                    classes: response.data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (response.data.status === 1) {
                    $state.transitionTo($state.current, $stateParams, {
                         reload: true
                    });
               }

          });
     };
     $scope.goback = function () {
          $state.go('^');
     };
     $scope.view_courier_info = function (id) {
          $rootScope.$broadcast("show_courier_popup", {
               id: id
          });
     };
     $scope.show_service_info = false;
     $scope.sdetail = {};
     $scope.view_service_info = function (id) {
          $scope.show_service_info = true;
          $http.post(BASE_URL + 'app/services/get_service_info/' + id).success(function (data) {
               $scope.sdetail = data.service;
          });
     };
     $scope.cancel_service_info = function () {
          $scope.show_service_info = false;
          $scope.sdetail = {};
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
          order_id: $stateParams.order_id,
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.bidperpage[2]
     };
     $scope.orderByField_bid = '';
     $scope.reverseSort_bid = false;
     $scope.getBidders = function (page) {
          $scope.bidlistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/bidderlist_json', $scope.bidlistdata)
                  .success(function (data) {
                       $scope.bidcount.notify = data.notify;
                       $scope.bidcount.total = data.total;
                       $scope.bidcount.start = data.start;
                       $scope.bidcount.end = data.end;
                       $scope.bidlist = data.bidders;
                       //alert(JSON.stringify($scope.bidlist));
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
     $scope.accept = function (bid_id) {
          $http.post(BASE_URL + 'orders/accept_bid', {
               bid_id: bid_id,
               order_id: $stateParams.order_id
          })
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            orderService.setNew();
                            $state.transitionTo($state.current, $stateParams, {
                                 reload: true
                            });
                            $scope.getBidders($scope.bidlistdata.currentPage);
                       }
                  });
     }
     $scope.resetBidSort = function () {
          $scope.bidheaders = {
               id: {},
               courier: {},
               service: {},
               price: {},
               remarks: {}
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
      */      $scope.comment = {};
     $scope.msgcount = {};
     $scope.messages = {};
     $http.post(BASE_URL + 'orders/messageslist_json', {
          order_id: $stateParams.order_id
     })
             .success(function (data) {
                  $scope.msgcount.total = data.total;
                  $scope.msgcount.reply = data.reply;
                  $scope.messages = data.messages;
             });
     $scope.savereply = function (msg) {
          if (msg.reply !== null) {
               $http.post(BASE_URL + 'orders/add_reply', {
                    msg_id: msg.msg_id,
                    reply: msg.reply
               })
                       .success(function (data) {
                            $scope.msgcount.reply = $scope.msgcount.reply + 1;
                            msg.replytime = moment().format('MMM D YYYY hh:mm a');
                       });
          }
     };
     $scope.maketimeformat = function (msgtime) {
          return moment(msgtime).format('D MMM YYYY hh:mm A');
     };
     $scope.addcomment = function (msg) {
          if ($scope.comment.content) {
               $http.post(BASE_URL + 'orders/add_comment', {
                    comment: $scope.comment.content,
                    order_id: $stateParams.order_id
               })
                       .success(function (data) {
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
          order_id: $stateParams.order_id,
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.logperpage[2]
     };
     $scope.sortActivity = function () {
          $scope.reverseSort_log = !$scope.reverseSort_log;
     }
     $scope.orderByField_log = 'time';
     $scope.reverseSort_log = true;
     $scope.getLog = function (page) {
          $scope.loglistdata.currentPage = page;
          $http.post(BASE_URL + 'orders/loglist_json', $scope.loglistdata)
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
     var iz = 0;
     $scope.addattachment = function (order_id) {
          $('#attachmentfile').click();
          if (iz == 0) {
               $('#attachmentfile').change(function (e) {
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
                              success: function (data) {
                                   $scope.getAttachments(1);
                                   $scope.processingattach = false;
                                   $("#attachmentfile").val("");
                                   if (data.status == true) {
                                        notify({
                                             message: "Uploaded Successfully",
                                             classes: 'alert-success',
                                             templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                                        });
                                   } else {
                                        notify({
                                             message: "Failed to Upload",
                                             classes: 'alert-danger',
                                             templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                                        });
                                   }
                              }
                         });
                    }
               });
          }
          iz++;
     };
     $scope.attach = {};
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
     $scope.deleteAttachments = function (id, name) {
          $scope.processingattach = true;
          var form = new FormData();
          form.append('id', id);
          form.append('name', name);
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
     $scope.getLog($scope.loglistdata.currentPage);
     $scope.logperpagechange = function () {
          $scope.loglistdata.perpage_value = $scope.loglistdata.perpage.value;
          $scope.getLog($scope.loglistdata.currentPage);
     };
}
function apiCtrl($scope, $http, $stateParams, notify) {
     $scope.allow_api = false;
     $scope.accesskey = "";
     $http.get(BASE_URL + 'app/organisation/get_api_status/' + $stateParams.id).success(function (data) {
          if (data.status) {
               $scope.allow_api = data.status;
          }
     });
     $http.get(BASE_URL + 'app/organisation/get_access_key/' + $stateParams.id).success(function (data) {
          if (data.accesskey) {
               $scope.accesskey = data.accesskey;
          }
     });
     $scope.show_allow_api_confirm = function () {
          $scope.show_confirm_popup = true;
     };
     $scope.cancel_allow_api_confirm = function () {
          $scope.show_confirm_popup = false;
          if ($scope.allow_api) {
               $scope.allow_api = false;
          } else {
               $scope.allow_api = true;
          }
     };
     $scope.proceed = function () {
          $http.post(BASE_URL + 'app/organisation/enable_api', {
               allow_api: $scope.allow_api,
               org_id: $stateParams.id
          })
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.show_confirm_popup = false;
                       } else {
                            $scope.cancel_allow_api_confirm();
                       }
                  });
     };
     $scope.show_reset_confirm = function () {
          $scope.reset_confirm_popup = true;
     };
     $scope.cancel_reset_confirm = function () {
          $scope.reset_confirm_popup = false;
     };
     $scope.reset_access_key = function () {
          $http.post(BASE_URL + 'app/organisation/reset_hashcode/' + $stateParams.id)
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.accesskey = data.accesskey;
                            $scope.cancel_reset_confirm();
                       }
                  });
     };
}
;
function changeorderCtrl($scope, $http, $stateParams, $state, orderService, notify, $filter, phonebook, $timeout) {
     $scope.neworder = {};
     $scope.my_contacts_popup = false;
     $scope.selected_contact = {
          'save_collect_contact': true,
          'save_delivery_contact': true,
          'collect_from_contact': false,
          'delivery_from_contact': false
     };
     $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     $(window).resize(function () {
          $('.inputpaymentClass').css('max-height', $(window).height() - 80);
     });
     //for toggle display of different popup window
     $scope.view_type_list = false;
     $scope.view_collect_timezone_list = false;
     $scope.view_delivery_timezone_list = false;
     $scope.view_assigned_couriers_list = false;
     //initial lists
     $scope.typelist = [];
     $scope.countrylist = {};
     $scope.ctimezones = {};
     $scope.timezones = {};
     $scope.dtimezones = {};
     $scope.errors = {};
     $scope.collect_shortcuts = "";
     $scope.delivery_shortcuts = "";
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
          $scope.csclist = [
               {
                    'name': "Next business Day",
                    "from": moment().add(1, 'days').hour(10).minute(0),
                    "to": moment().add(1, 'days').hour(18).minute(0)
               },
               {
                    'name': "Tomorrow morning (10am - 1pm)",
                    "from": moment().add(1, 'days').hour(10).minute(0),
                    "to": moment().add(1, 'days').hour(13).minute(0)
               },
               {
                    'name': "Tomorrow office hour (10am - 6pm)",
                    "from": moment().add(1, 'days').hour(10).minute(0),
                    "to": moment().add(1, 'days').hour(18).minute(0)
               }, {
                    'name': "Tomorrow onward, anytime this week",
                    "from": moment().add(1, 'days').hour(10).minute(0),
                    "to": moment().add(1, 'days').endOf('week').endOf('day').minute(0)
               }

          ];
          if (moment() < moment().hour(13).minute(0)) {
               $scope.csclist.push({
                    'name': "Today morning (10am - 1pm)",
                    "from": moment().hour(10).minute(0),
                    "to": moment().hour(13).minute(0)
               });
          }
          if (moment() < moment().hour(18).minute(0)) {
               $scope.csclist.push({
                    'name': "Today afternoon (1pm - 6pm)",
                    "from": moment().hour(13).minute(0),
                    "to": moment().hour(18).minute(0)
               });
          }
          $scope.collect_shortcuts = $scope.csclist[0];
     };
     $scope.setClist();
     $scope.setCollectionTime = function () {
          if (moment() < moment().hour(11).minute(0)) {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {
                    'name': "Today morning (10am - 1pm)"
               })[0];
          } else if (moment() < moment().hour(16).minute(0)) {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {
                    'name': "Today afternoon (1pm - 6pm)"
               })[0];
          } else {
               $scope.collect_shortcuts = $filter('filter')($scope.csclist, {
                    'name': "Tomorrow morning (10am - 1pm)"
               })[0];
          }
          $scope.setCRange();
     };
     $scope.dsclist = [
          {
               'name': "Next business Day",
               "from": moment().add(1, 'days').hour(10).minute(0),
               "to": moment().add(1, 'days').hour(18).minute(0)
          }
          /*{
           'name': "6-hours from now",
           "from": moment(),
           "to": moment().add(6, 'hour')
           },
           {
           'name': "3-hours from now",
           "from": moment(),
           "to": moment().add(3, 'hour')
           },
           {
           'name': "90-min from now",
           "from": moment(),
           "to": moment().add(90, 'minute')
           }
           ,
           {
           'name': "3 business days",
           "from": moment().hour(10).minute(0),
           "to": $scope.addBusinessDays()
           },
           {
           'name': "Weekend Delivery (10am - 10pm)",
           "from": moment().endOf('week').hour(10).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           },
           {
           'name': "Weekend Delivery (Night 6-10pm)",
           "from": moment().endOf('week').subtract(1, 'days').hour(18).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           },
           {
           'name': "Weekday Delivery (Night 6-10pm)",
           "from": $scope.startweekdays().hour(18).minute(0),
           "to": $scope.endweekdays().hour(22).minute(0)
           }
           */
     ];
     $scope.setDlist = function (to) {
          $scope.delivery_shortcuts = "";
          $scope.dsclist = [];
          if (to < moment().add(1, 'days').hour(18).minute(0)) {
               $scope.dsclist.push({
                    'name': "Next business Day",
                    "from": moment().add(1, 'days').hour(10).minute(0),
                    "to": moment().add(1, 'days').hour(18).minute(0)
               }
               );
          }
          /*
           if (to < moment().add(6, 'hour')) {
           $scope.dsclist.push({
           'name': "6-hours from now",
           "from": moment(),
           "to": moment().add(6, 'hour')
           });
           }
           if (to < moment().add(3, 'hour')) {
           $scope.dsclist.push({
           'name': "3-hours from now",
           "from": moment(),
           "to": moment().add(3, 'hour')
           });
           }
           if (to < moment().add(90, 'minute')) {
           $scope.dsclist.push({
           'name': "90-min from now",
           "from": moment(),
           "to": moment().add(90, 'minute')
           });
           }
           
           if (to < $scope.addBusinessDays()) {
           $scope.dsclist.push({
           'name': "3 business days",
           "from": moment().hour(10).minute(0),
           "to": $scope.addBusinessDays()
           });
           }
           if (to < moment().add(1, 'week').startOf('week').hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekend Delivery (10am - 10pm)",
           "from": moment().endOf('week').hour(10).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           });
           }
           if (to < moment().add(1, 'week').startOf('week').hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekend Delivery (Night 6-10pm)",
           "from": moment().endOf('week').subtract(1, 'days').hour(18).minute(0),
           "to": moment().add(1, 'week').startOf('week').hour(22).minute(0)
           });
           }
           if (to < $scope.endweekdays().hour(22).minute(0)) {
           $scope.dsclist.push({
           'name': "Weekday Delivery (Night 6-10pm)",
           "from": $scope.startweekdays().hour(18).minute(0),
           "to": $scope.endweekdays().hour(22).minute(0)
           });
           }
           */

     };
     $scope.setCRange = function () {
          if ($scope.collect_shortcuts != null) {
               var from = $scope.collect_shortcuts.from;
               var to = $scope.collect_shortcuts.to;
               $scope.neworder.collect_date1 = from.format('MM/DD/YYYY h:mm A');
               $scope.neworder.collect_date2 = to.format('MM/DD/YYYY h:mm A');
               $scope.setCTime();
               $scope.setDlist(from);
          }
     };
     $scope.setDRange = function () {
          if ($scope.neworder.is_d_restricted_area == undefined) {
               $scope.neworder.is_d_restricted_area = {};
          }
          if ($scope.del_timing != null) {
               if ($scope.del_timing.from != null && $scope.del_timing.to != null) {
                    $scope.neworder.deliver_date1 = $scope.del_timing.from.format('MM/DD/YYYY h:mm A');
                    $scope.neworder.deliver_date2 = $scope.del_timing.to.format('MM/DD/YYYY h:mm A');
                    $scope.neworder.is_d_restricted_area.appointment = null;
                    $scope.setDTime();
               } else {
                    $scope.neworder.is_d_restricted_area.appointment = 25;
                    var tming = moment().format('MM/DD/YYYY');
                    if ($scope.delivery_shortcuts.from != null) {
                         tming = $scope.delivery_shortcuts.from.format('MM/DD/YYYY');
                    } else if ($scope.delivery_shortcut_date != '') {
                         tming = $scope.delivery_shortcut_date;
                    }
                    if ($scope.del_shortcut_timing.from.hour != null && $scope.del_shortcut_timing.to.hour != null) {

                         $scope.neworder.deliver_date1 = moment(tming, 'MM/DD/YYYY').hour($scope.del_shortcut_timing.from.hour).minute($scope.del_shortcut_timing.from.minute).format('MM/DD/YYYY h:mm A');
                         $scope.neworder.deliver_date2 = moment(tming, 'MM/DD/YYYY').hour($scope.del_shortcut_timing.to.hour).minute($scope.del_shortcut_timing.to.minute).format('MM/DD/YYYY h:mm A');

                    }
               }
          }
     };
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
     $scope.getTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones').then(function (response) {
               $scope.timezones = response.data.timezones;
          });
     };
     $scope.getCTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones', {
               country: $scope.neworder.collect_country
          }).then(function (response) {
               $scope.ctimezones = response.data.timezones;
               if (update) {
                    $scope.neworder.collect_timezone = $scope.ctimezones[0];
                    console.log($scope.neworder.collect_timezone);
               }
               $scope.setCTime();
          });
     };
     $scope.getDTimezones = function (update) {
          $http.post(BASE_URL + 'orders/timezones', {
               country: $scope.neworder.delivery_country
          }).then(function (response) {
               $scope.dtimezones = response.data.timezones;
               if (update)
                    $scope.neworder.delivery_timezone = $scope.dtimezones[0];
               $scope.setDTime();
          });
     };
     $scope.set_bulk = function () {
          if ($scope.neworder.type.consignment_type_id == 9) {
               $scope.neworder.is_bulk = true;
          } else {
               $scope.neworder.is_bulk = false;
          }
     };
     $scope.get_info = function () {
          if ($stateParams.corder_id !== undefined) {
               $http.post(BASE_URL + 'orders/get_change_info/' + $stateParams.corder_id).success(function (data) {
                    if (data.count === 0)
                         $scope.is_empty = true;
                    $scope.neworder = data.order;
                    $scope.getCTimezones(false);
                    $scope.getDTimezones(false);
               });
          }
     }
     $scope.get_info();
     //initialize lists
     $scope.getTypeList();
     $scope.getCountryList();
     $scope.getTimezones();
     $scope.saveOrder = function () {
          //          $state.go('delivery_orders');

          scroll_to_loading();
          $scope.processing = true;
          $http.post(BASE_URL + 'orders/add_change_request', $scope.neworder).success(function (data) {
               $scope.processing = false;
               notify({
                    message: data.msg,
                    classes: data.class,
                    templateUrl: ROOT_PATH + "/resource/partial/notify.html"
               });
               if (data.status === 1) {

                    $state.go('tender_requests.delivery.view_order', {
                         order_id: data.public_id
                    });
               } else {
                    $scope.errors = data.errors;
                    $timeout(function () {
                         angular.element('.form-horizontal').find('.error')[0].focus();
                    }, 500);
               }
          });
     };
     $scope.checkvolume = function () {
          if ($scope.neworder.height !== undefined && $scope.neworder.breadth !== undefined && $scope.neworder.length !== undefined) {
               $scope.calc_volume();
          } else {
               $scope.reset_volume();
          }
     };
     $scope.calc_volume = function () {
          var volume = parseFloat($scope.neworder.height) * parseFloat($scope.neworder.breadth) * parseFloat($scope.neworder.length);
          if (volume > 0)
               $scope.neworder.volume = $filter("number")(volume, 3);
          else {
               $scope.reset_volume();
          }
     };
     $scope.reset_volume = function () {
          $scope.neworder.volume = undefined;
     };
     $scope.show_collection_timezone = function () {
          if ($scope.neworder.collect_country) {
               $scope.view_collect_timezone_list = true;
          } else {
               alert('Select country first');
          }
     };
     $scope.show_delivery_timezone = function () {
          if ($scope.neworder.delivery_country) {
               $scope.view_delivery_timezone_list = true;
          } else {
               alert('Select country first');
          }
     };
     $scope.cancel_collection_timezone = function () {
          $scope.view_collect_timezone_list = false;
          $scope.setCTime();
     };
     $scope.setCTime = function () {
          if ($scope.neworder.collect_date1 && $scope.neworder.collect_date2) {
               var cdate = $scope.neworder.collect_date1 + '-' + $scope.neworder.collect_date2;
               if ($scope.neworder.collect_timezone == undefined) {
                    $scope.neworder.collect_timezone = {"zoneinfo": "Asia\/Singapore", "offset": "UTC+8", "summer": null, "country": "sg", "cicode": "UP8", "cicodesummer": null};
               }
               var czone = $scope.neworder.collect_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {
                    time: cdate,
                    'timezone': czone
               }).then(function (response) {
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
     $scope.setDTime = function () {
          if ($scope.neworder.deliver_date1 && $scope.neworder.deliver_date2) {
               var ddate = $scope.neworder.deliver_date1 + '-' + $scope.neworder.deliver_date2;
               var dzone = $scope.neworder.delivery_timezone.zoneinfo;
               $http.post(BASE_URL + 'orders/get_converted_time', {
                    time: ddate,
                    'timezone': dzone
               }).then(function (response) {
                    if (response.data.date)
                         $scope.neworder.ddate_convert = response.data.date;
                    $scope.neworder.ddate_convert1 = response.data.date.split("-")[0];
                    $scope.neworder.ddate_convert2 = response.data.date.split("-")[1];
                    $scope.neworder.ddate_convert1_1 = response.data.start_date;
                    $scope.neworder.ddate_convert1_2 = response.data.start_time;
                    $scope.neworder.ddate_convert2_1 = response.data.end_date;
                    $scope.neworder.ddate_convert2_2 = response.data.end_time;
               });
          }
     };
     $scope.cancel_delivery_timezone = function () {
          $scope.setDTime();
          $scope.view_delivery_timezone_list = false;
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
     $scope.cancel_contact_popup = function () {
          $scope.my_contacts_popup = false;
     };
     $scope.set_contact_temp = function (contact) {
          if ($scope.selected_contact.item === 'collect') {
               phonebook.set_collect(contact);
               $scope.selected_contact.collect_from_contact = true;
               $scope.selected_contact.save_collect_contact = false;
               $scope.selected_contact.collect_from_contact_id = contact.id;
               $scope.set_collect_data(contact);
          } else if ($scope.selected_contact.item === 'delivery') {
               phonebook.set_deliver(contact);
               $scope.selected_contact.delivery_from_contact = true;
               $scope.selected_contact.save_delivery_contact = false;
               $scope.set_deliver_data(contact);
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
     $scope.set_deliver_data = function (contact) {
          $scope.neworder.delivery_address_l1 = contact.address_line1;
          $scope.neworder.delivery_address_l2 = contact.address_line2;
          $scope.neworder.delivery_zipcode = contact.postal_code;
          $scope.neworder.delivery_country = contact.country_code;
          $scope.neworder.delivery_contactname = contact.contact_name;
          $scope.neworder.delivery_phone = contact.phone_number;
          $scope.neworder.delivery_email = contact.email;
          $scope.getDTimezones(true);
     };
}