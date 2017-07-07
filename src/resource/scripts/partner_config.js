

function configState($stateProvider, $urlRouterProvider, $compileProvider) {

     // Optimize load start with remove binding information inside the DOM element
     $compileProvider.debugInfoEnabled(true);

     // Set default state
     $urlRouterProvider.otherwise("/dashboard");
     $stateProvider
             // Landing page
             .state('dashboard', {
                  url: "/dashboard",
                  templateUrl: BASE_URL + 'app/home',
                  data: {
                       pageTitle: 'Dashboard',
                       pageDesc: 'Here is a summary of your delivery orders and tender requests',
                       'hidebreadcrumb': true,
                       'breadcrumb': 'Get Started'
                  },
                  controller: dashboardCtrl
             })
             //main modules
             .state('my_contacts', {
                  url: "/address_book",
                  templateUrl: BASE_URL + 'contacts',
                  data: {
                       pageTitle: 'Address Book',
                       pageDesc: 'Store the delivery or collection contacts that you used regularly here to reduce the effort in keying in the detail every time'
                  },
                  controller: mycontactsCtrl
             })



             .state('organisations', {
                  url: "/organisations",
                  templateUrl: BASE_URL + 'app/organisation',
                  data: {
                       pageTitle: 'Organisations',
                       pageDesc: 'Organisations are companies or businesses that you are involved in'

                  }
             })
             .state('tender_requests', {
                  abstract: true,
                  url: "/tender_requests",
                  templateUrl: BASE_URL + 'app/tender_requests/index/?' + Date.now().toString(),
                  data: {
                       pageTitle: 'Tender Requests'

                  }
             })
             .state('tender_requests.service', {
                  url: "/service_request",
                  templateUrl: BASE_URL + 'app/service_request/index/' + Date.now().toString(),
                  data: {
                       pageTitle: 'Service Tender Requests',
                       pageDesc: 'Service Request Tenders are tender requests put up to the courier providers for a long-term delivery service to your organisation\'s need.'
                  }
             })
             .state('tender_requests.service.view_request', {
                  url: "/view_request/:request_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/service_request/view/' + $stateParams.request_id + '/?' + Date.now().toString();
                  }, data: {
                       pageTitle: 'View Service Request Details '

                  },
                  controller: viewrequestCtrl
             })
             .state('tender_requests.service.new_request', {
                  url: "/new_request",
                  templateUrl: function () {
                       return BASE_URL + 'app/service_request/new_request/?' + Date.now().toString();
                  }, data: {
                       pageTitle: 'New Service Request',
                       pageDesc: 'Request for service quotations from the courier providers for your long-term delivery jobs.'
                  }
             })
             .state('tender_requests.delivery', {
                  url: "/delivery",
                  templateUrl: BASE_URL + 'app/tender_requests/delivery/' + Date.now().toString(),
                  data: {
                       pageTitle: 'Delivery Tender Requests',
                       pageDesc: 'Shows the list of tenders that are put up by you for getting quotes from the courier providers.'

                  }
             })
             .state('tender_requests.delivery.view_order', {
                  url: "/view_order/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View order'
                  },
                  controller: vieworderCtrl
             })

             .state('delivery_orders', {
                  url: "/orders",
                  templateUrl: BASE_URL + 'orders',
                  data: {
                       pageTitle: 'Delivery Request',
                       pageDesc: 'Orders are the delivery jobs that you have created. You can check the status of your orders here'

                  }
             })
             .state('delivery_orders.new_order', {
                  url: "/new_order",
                  templateUrl: function () {
                       return BASE_URL + 'orders/newOrder/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'New Delivery Request',
                       pageDesc: 'Create and submit your delivery request here'
                  },
                  resolve: {
                       editOrder: function () {
                            return false;
                       }
                  },
                  controller: neworderCtrl
             })

             .state('delivery_orders.view_order', {
                  url: "/view_order/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View order'
                  },
                  controller: vieworderCtrl
             })
             .state('delivery_orders.view_order.view_morder', {
                  url: "/view_morder/:item_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + '/' + $stateParams.item_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View order'
                  },
                  controller: vieworderCtrl
             })
             .state('delivery_orders.edit_order', {
                  url: "/edit_order/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/edit_order/' + $stateParams.order_id + "";
                  },
                  data: {
                       pageTitle: 'Edit Delivery Request'

                  },
                  resolve: {
                       editOrder: function () {
                            return true;
                       }
                  },
                  controller: neworderCtrl
             })
             .state('delivery_orders.multiple_order', {
                  url: "/multiple_order",
                  templateUrl: function () {
                       return BASE_URL + 'orders/multiorders/multiOrder/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Multiple Orders',
                       pageDesc: 'Create your order here if you are delivering many items from one location to many locations'
                  },
                  controller: mutliorderCtrl
             })

             .state('delivery_orders.view_multiple_order', {
                  url: "/view_multiple_order/:cgroup_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/multiorders/view_orders/' + $stateParams.cgroup_id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Multiple Orders',
                       pageDesc: 'View all your orders added together'
                  }
             })
             .state('delivery_orders.view_multiple_order.view_morder', {
                  url: "/view_morder/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View Order'
                  },
                  controller: vieworderCtrl
             })
             .state('organisation', {
                  url: "/organisation/:id",
                  abstact: true,
                  params: {'flag': 0},
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/getOrganisation/' + $stateParams.id + "/" + $stateParams.flag + "/?" + Date.now().toString();
                  }, data: {
                       pageTitle: 'Organisations'
                  },
                  controller: "organisationDetailCtrl"
             })
             .state('organisation.members', {
                  url: "/",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/members/index/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Organisation Members'
                  }
             })
             .state('organisation.products', {
                  url: "/product",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/products';
                  }, data: {
                       pageTitle: 'Organisation Products/Services'

                  }
             })
             .state('organisation.api', {
                  url: "/integration",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/api/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Organisation API'

                  }
             })
             .state('organisation.tracking', {
                  url: "/public_tracking",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/public_tracking/index/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Public Tracking'

                  }
             })
             .state('organisation.services', {
                  url: "/services",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/services/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Organisation Services'

                  }
             })
             .state('available_services', {
                  url: "/available_services",
                  templateUrl: BASE_URL + 'app/available_services',
                  data: {
                       pageTitle: 'Available Services',
                       pageDesc: 'These are existing services that are put up by the couriers which member can request to add it into their organisation as a pre-approved service for their organisation to use.'
                  }
             })
             .state('available_services.view_service', {
                  url: "/view_service/:as_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/available_services/view/' + $stateParams.as_id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Available Services',
                       pageDesc: 'These are existing services that are put up by the couriers which member can request to add it into their organisation as a pre-approved service for their organisation to use.'
                  },
                  controller: viewavail_serviceCtrl
             })
             .state('available_services.edit_service', {
                  url: "/edit_service/:as_id",
                  templateUrl: function () {
                       return BASE_URL + 'app/available_services/edit';
                  },
                  data: {
                       pageTitle: 'Edit Available Service',
                       pageDesc: ''
                  },
                  controller: editservicesCtrl
             })
             .state('organisation.pre_approved_bidders', {
                  url: "/pre_approved_couriers",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/pre_approved_bidders/index/' + $stateParams.id;
                  },
                  data: {
                       pageTitle: 'Pre-approved Couriers'
                  }
             })
             .state('organisation.settings', {
                  url: "/settings",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/settings';
                  }, data: {
                       pageTitle: 'Organisation Settings '

                  }
             })
             .state('organisation.orders', {
                  url: "/orders",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/?' + Date.now().toString();
                  }, data: {
                       pageTitle: 'Organisation Orders '

                  }
             })
             .state('organisation.orders.new_order', {
                  url: "/new_order",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/newOrder/?' + Date.now().toString();
                  }, data: {
                       pageTitle: 'New Delivery Request',
                       pageDesc: 'Create and submit your delivery request here'

                  },
                  resolve: {
                       editOrder: function () {
                            return false;
                       }
                  },
                  controller: neworderCtrl
             })
             .state('organisation.orders.edit_order', {
                  url: "/edit_order/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/edit_order/' + $stateParams.order_id + "";
                  },
                  data: {
                       pageTitle: 'Edit Delivery Request'

                  },
                  resolve: {
                       editOrder: function () {
                            return true;
                       }
                  },
                  controller: neworderCtrl
             })
             .state('organisation.orders.view_order', {
                  url: "/view_order/:order_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View order'
                  },
                  controller: vieworderCtrl
             })
             .state('organisation.orders.view_order.view_morder', {
                  url: "/view_morder/:item_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'orders/view_order/' + $stateParams.order_id + '/' + $stateParams.item_id + "/?" + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View order'
                  },
                  controller: vieworderCtrl
             })
             .state('organisation.activities', {
                  url: "/activity",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/activity/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Organisation Settings '

                  }
             })
             .state('organisation.groups', {
                  url: "/teams",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'app/organisation/groups/' + $stateParams.id;
                  }, data: {
                       pageTitle: 'Organisation Teams '

                  }
             })

             .state('organisation.reports', {
                  url: "/reports",
                  abstract: true,
                  templateUrl: BASE_URL + 'reports',
                  data: {
                       pageTitle: 'Reports',
                       specialClass: 'Reports'
                  }
             })

             .state('organisation.reports.userperform', {
                  url: "/users",
                  templateUrl: BASE_URL + 'reports/user_performance',
                  data: {
                       pageTitle: 'User Performance'
                  }
             })
             .state('organisation.reports.emailschedule', {
                  url: "/emailschedule",
                  templateUrl: BASE_URL + 'reports/email_schedule',
                  data: {
                       pageTitle: 'Email Reports with Schedule'
                  }
             })
             .state('organisation.reports.overall', {
                  url: "/overall",
                  templateUrl: BASE_URL + 'reports/overall',
                  data: {
                       pageTitle: 'Overall Usage Report'
                  }
             })
             .state('organisation.reports.groupperform', {
                  url: "/teams",
                  templateUrl: BASE_URL + 'reports/group_performance',
                  data: {
                       pageTitle: 'Team Performance'
                  }
             })
             .state('organisation.reports.export', {
                  url: "/export_transactions",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'reports/export_transaction/' + $stateParams.id;
                  },
                  data: {
                       pageTitle: 'Export Transactions'
                  }
             })
             .state('new_organisation', {
                  url: "/new_organisation",
                  templateUrl: BASE_URL + 'app/organisation/create',
                  data: {
                       pageTitle: 'Create New Organisation '
                  },
                  controller: "neworganisationCtrl"
             })


             //Account info

//             .state('account_profile', {
//                  url: "/account_profile",
//                  templateUrl: BASE_URL + 'account/account_profile',
//                  data: {
//                       pageTitle: 'Account profile',
//                       pageDesc: 'The information you provide on this page is publicly viewable on your site profile.'
//                  },
//                  controller: "profileCtrl"
//             })

             .state('account_settings', {
                  url: "/account_settings",
                  templateUrl: BASE_URL + 'account/account_settings',
                  data: {
                       pageTitle: 'Account settings',
                       pageDesc: 'The information we collect on this page is private and will not be shown anywhere on this website or shared with third parties without your explicit permission. '
                  },
                  controller: "settingsCtrl"
             })

             .state('account_notifications', {
                  url: "/email_notifications",
                  templateUrl: function () {
                       return BASE_URL + 'account/account_notifications/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Notifications',
                       pageDesc: 'Notification settings'
                  }, controller: accnotificationCtrl
             })
             .state('manage_users', {
                  url: "/manage_users",
                  templateUrl: BASE_URL + 'account/manage_users',
                  data: {
                       pageTitle: 'Manage Users',
                       pageDesc: 'Manage users.'
                  }
             })
             .state('manage_users_save', {
                  url: "/manage_users/save/:id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'account/manage_users/save/' + $stateParams.id + "";
                  }, data: {
                       pageTitle: 'Manage Users',
                       pageDesc: 'Manage Users'
                  }
             })

             .state('manage_roles', {
                  url: "/manage_roles",
                  templateUrl: BASE_URL + 'account/manage_roles',
                  data: {
                       pageTitle: 'Manage Roles',
                       pageDesc: 'Manage roles'
                  }
             })
             .state('manage_roles_save', {
                  url: "/manage_roles/save/:id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'account/manage_roles/save/' + $stateParams.id + "";
                  }, data: {
                       pageTitle: 'Manage Roles',
                       pageDesc: 'Manage roles'
                  }
             })
             .state('manage_permissions', {
                  url: "/manage_permissions",
                  templateUrl: BASE_URL + 'account/manage_permissions',
                  data: {
                       pageTitle: 'Manage Permissions',
                       pageDesc: 'Manage permissions'
                  }
             })
             .state('manage_permissions_save', {
                  url: "/manage_permissions/save/:id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'account/manage_permissions/save/' + $stateParams.id + "";
                  }, data: {
                       pageTitle: 'Manage Permissions',
                       pageDesc: 'Manage permissions'
                  }
             })
}

angular
        .module('6connect')
        .config(configState)
        .run(function ($rootScope, $state, editableOptions, editableThemes, $templateCache) {
             $rootScope.$state = $state;
             editableOptions.theme = 'bs3';

             editableThemes.bs3.inputClass = 'input-sm';
             editableThemes.bs3.buttonsClass = 'btn-sm';
             $rootScope.$on('$stateChangeStart', function () {
                  $templateCache.remove($state.current.templateUrl);
             });
        });

angular.module('6connect').config(['ngClipProvider', function (ngClipProvider) {
          ngClipProvider.setPath(ROOT_PATH + "resource/bower_components/zeroclipboard/dist/ZeroClipboard.swf");
     }]);

