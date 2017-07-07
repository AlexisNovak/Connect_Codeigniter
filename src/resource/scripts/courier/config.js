

function configState($stateProvider, $urlRouterProvider, $compileProvider) {

     // Optimize load start with remove binding information inside the DOM element
     $compileProvider.debugInfoEnabled(true);

     // Set default state
     $urlRouterProvider.otherwise("/");
     $stateProvider

             // Landing page
             .state('dashboard', {
                  "abstract": true,
                  "templateUrl": BASE_URL + "couriers/couriers_info",
                  data: {
                       pageTitle: 'Dashboard'
                  },
                  controller: "CourierDetailCtrl"
             })
             .state('dashboard.overview', {
                  url: "/",
                  "templateUrl": BASE_URL + 'couriers/overview/?' + Date.now().toString(),
                  data: {
                       pageTitle: 'Overview'
                  },
                  controller: "courierservicesCtrl"
             })
             
             .state('drivers', {
                  url: "/drivers",
                  templateUrl: BASE_URL + 'drivers',
                  data: {
                       pageTitle: 'Driver List'
                  }
             })

             .state('adddriver', {
                  url: "/adddriver",
                  templateUrl: BASE_URL + 'drivers/adddriver',
                  data: {
                       pageTitle: 'New Driver'
                  }
             })

             .state('view_driver', {
                  url: "/view_driver/:id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'drivers/view_driver/' + $stateParams.id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Driver Details'
                  }
             })

             .state('update_driver', {
                  url: "/update_driver/:id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'drivers/updateDriver/' + $stateParams.id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Driver Details'
                  }
             })

             .state('dashboard.services', {
                  url: "/services",
                  "templateUrl": BASE_URL + 'couriers/services/?' + Date.now().toString(),
                  data: {
                       pageTitle: 'Services',
                       specialClass: 'Services'
                  },
                  controller: "courierservicesCtrl"
             })
             .state('account_profile', {
                  url: "/account_profile",
                  templateUrl: BASE_URL + 'couriers/courierAccount/account_profile',
                  data: {
                       pageTitle: 'Account profile',
                       pageDesc: 'The information you provide on this page is publicly viewable on your site profile.'
                  },
                  controller: "profileCtrl"
             })

             .state('account_settings', {
                  url: "/account_settings",
                  templateUrl: BASE_URL + 'couriers/courierAccount/account_settings',
                  data: {
                       pageTitle: 'Account settings',
                       pageDesc: 'The information we collect on this page is private and will not be shown anywhere on this website or shared with third parties without your explicit permission. '
                  },
                  controller: "settingsCtrl"
             })
             .state('account_password', {
                  url: "/account_password",
                  templateUrl: BASE_URL + 'couriers/courierAccount/account_password',
                  data: {
                       pageTitle: 'Change Password',
                       pageDesc: 'To safe guard your account, we strongly recommend that you pick a complex password.'
                  }, controller: "passwordCtrl"
             })
             .state('notifications', {
                  url: "/notifications",
                  templateUrl: BASE_URL + 'app/messages/notifications',
                  data: {
                       pageTitle: 'Notifications',
                       pageDesc: 'All notifications'
                  },
                  controller: enotificationCtrl
             })
             .state('assigned_orders', {
                  url: "/assigned_orders",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/assigned_orders/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Orders',
                       pageDesc: 'List of all orders awarded to you'
                  }, controller: assigned_orderCtrl
             })

             .state('assigned_orders.view_order', {
                  url: "/view_order/:aorder_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/assigned_orders/view_order/' + $stateParams.aorder_id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Orders',
                       pageDesc: 'List of all orders awarded to you'
                  }, controller: view_orderCtrl
             })
             .state('assigned_orders.view_order_msg', {
                  url: "/view_order/:aorder_id/:activetab",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/assigned_orders/view_order/' + $stateParams.aorder_id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Orders',
                       pageDesc: 'List of all orders awarded to you'
                  }, controller: view_orderCtrl
             })
             .state('tenders', {
                  url: "/delivery_requests",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/tenders/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Delivery Requests',
                       pageDesc: 'List of all delivery requests'
                  }, controller: tenderCtrl
             })
             .state('tenders.view_tender', {
                  url: "/view_request/:tender_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/tenders/view/' + $stateParams.tender_id + '/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View Request',
                       pageDesc: 'Delivery request details'
                  }, controller: view_tenderCtrl
             })
             .state('service_requests', {
                  url: "/service_tenders",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/service_tenders/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Service Tenders',
                       pageDesc: 'List of all service requests available for tender'
                  }, controller: srequestcourierCtrl
             })
             .state('service_requests.view_request', {
                  url: "/view_tender/:req_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/service_tenders/view/' + $stateParams.req_id + '?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View Service Tender',
                       pageDesc: 'Service tender details'
                  }, controller: view_srequestcourierCtrl
             })
             .state('available_service_requests', {
                  url: "/available_service_requests",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/available_service_requests/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Service Tenders',
                       pageDesc: 'List of all available service requests from organisations'
                  }, controller: asrequestcourierCtrl
             })
             .state('available_service_requests.view_request', {
                  url: "/view_request/:asreq_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/available_service_requests/view/' + $stateParams.asreq_id + '?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'View Service Request',
                       pageDesc: 'Available service request details'
                  }, controller: view_asrequestcourierCtrl
             })
             .state('ownservices', {
                  url: "/ownservices",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/ownservices/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Services',
                       pageDesc: 'List of all services that you had in this system'
                  },
                  controller: ownservicesCtrl
             })
             .state('ownservices.newservice', {
                  url: "/newservice",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/ownservices/newservice/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Add New Service',
                       pageDesc: 'Add new service into this system'
                  },
                  controller: newownservicesCtrl
             })
             .state('ownservices.edit_service', {
                  url: "/edit_service/:os_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/ownservices/edit/' + $stateParams.os_id + '?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Edit Available Service',
                       pageDesc: 'These are existing services that are put up by the couriers which member can request to add it into their organisation as a pre-approved service for their organisation to use.'
                  },
                  controller: editownservicesCtrl
             })
             .state('ownservices.view_service', {
                  url: "/view_service/:os_id",
                  templateUrl: function ($stateParams) {
                       return BASE_URL + 'couriers/ownservices/view/' + $stateParams.os_id + '?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Services',
                       pageDesc: 'List of all services that you had in this system'
                  },
                  controller: viewservicesCtrl
             })
             .state('organisations', {
                  url: "/organisations",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/associates/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Organisations',
                       pageDesc: 'list of organisations that are using your pre-approved services'
                  },
                  controller: courierorgCtrl
             })
             .state('c_notifications', {
                  url: "/notification_settings",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/notifications/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Notification settings',
                       pageDesc: 'Notification settings'
                  }, controller: notificationCtrl
             })
             .state('reports', {
                  url: "/reports",
                  abstract: true,
                  templateUrl: function () {
                       return BASE_URL + 'couriers/reports/?' + Date.now().toString();
                  }, data: {
                       pageTitle: 'Reports',
                       pageDesc: ''
                  }
             })
             .state('reports.invoice', {
                  url: "/invoice",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/reports/invoice/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Generate Invoice',
                       pageDesc: ''
                  }, controller: invoiceCtrl
             })
             .state('reports.downloads', {
                  url: "/downloads",
                  templateUrl: function () {
                       return BASE_URL + 'couriers/reports/invoicelist/?' + Date.now().toString();
                  },
                  data: {
                       pageTitle: 'Invoices',
                       pageDesc: 'View generated invoices'
                  }, controller: viewinvoiceCtrl
             })
}

angular
        .module('6connect')
        .config(configState)
        .run(function ($rootScope, $state, editableOptions, $http, $location) {
             $rootScope.$state = $state;
             editableOptions.theme = 'bs3';
             $rootScope.$on('$stateChangeStart', function (e, toState, toParams, fromState, fromParams) {
                  $http.post(BASE_URL + "couriers/check").success(function (response) {
                       if (!response.result) {
                            window.location.href = BASE_URL + "couriers/login";
                       }
                  });
             });
        });