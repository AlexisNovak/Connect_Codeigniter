var componentForm = {
     street_number: 'short_name',
     route: 'long_name',
     locality: 'long_name',
     administrative_area_level_2: 'long_name',
     administrative_area_level_1: 'long_name',
     country: 'long_name',
     postal_code: 'short_name',
     sublocality_level_2: 'long_name',
     sublocality_level_1: 'long_name'
};
angular.module('6connect')
        .controller('mycontactsCtrl', mycontactsCtrl)
        .directive('googleplace', function () {
             return {
                  require: 'ngModel',
                  link: function (scope, element, attrs, model) {
                       var options = {
                            types: [],
                            componentRestrictions: {}
                       };
                       scope.gPlace = new google.maps.places.Autocomplete(element[0], options);

                       google.maps.event.addListener(scope.gPlace, 'place_changed', function () {
                            scope.$apply(function () {
                                 var place = scope.gPlace.getPlace();
                                 var sublocality_level_2 = "";
                                 var sublocality_level_1 = "";
                                 var locality = "";
                                 var route = "";
                                 for (var i = 0; i < place.address_components.length; i++) {
                                      var addressType = place.address_components[i].types[0];
                                      if (componentForm[addressType]) {

                                           var val = place.address_components[i][componentForm[addressType]];

                                           if (addressType == 'route') {
                                                route = val;
                                           }

                                           if (addressType == 'sublocality_level_2') {
                                                sublocality_level_2 = val;
                                           }
                                           if (addressType == 'sublocality_level_1') {
                                                sublocality_level_1 = val;
                                           }
                                           if (addressType == 'locality') {
                                                locality = val;
                                           }
                                           if (addressType == 'administrative_area_level_2') {
//                                    scope.contact.address_line2 = val;
                                           }
                                           if (addressType == 'postal_code') {
                                                scope.contact.postal_code = val;
                                           }
                                           if (addressType == 'country') {
                                                angular.forEach(scope.countrylist, function (country) {
                                                     if (country.country == val) {
                                                          scope.contact.country_code = country.code;
                                                     }
                                                })
                                           }
                                      }
                                 }
                                 scope.contact.address_line2 = ((route != "") ? route + ", " : "") + ((sublocality_level_2 != "") ? sublocality_level_2 + ", " : "") + sublocality_level_1;
                                 //                                 model.$setViewValue(element.val());
                            });
                       });
                  }
             };
        });
function mycontactsCtrl($scope, $http, phonebook, notify, $state) {
     $scope.countrylist = {};
     $scope.orglist = [];
     $scope.contact = {
          country_code: 'sg'
     };
     $scope.contact_id = 0;
     $scope.delete_warning_popup = false;
     $scope.success = false;
     $scope.create_contact_form = false;
     $scope.show_org_list = false;
     $scope.errors = {};
     $scope.recentlist = {};

     $scope.gPlace;

     $scope.contactperpage = [{
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
     $scope.contactlistdata = {
          perpage_value: 15,
          currentPage: 1,
          total: 0,
          perpage: $scope.contactperpage[2]
     };
     $scope.orderByField = 'contact_name';
     $scope.reverseSort = false;
     $scope.show_init = false;
     $scope.show_table = false;
     $scope.init_count = function () {
          $http.post(BASE_URL + 'contacts/get_total_contacts').success(function (data) {
               if (data.total !== undefined && data.total === 0) {
                    $scope.show_init = true;
                    $scope.show_table = false;
               } else {
                    $scope.show_init = false;
                    $scope.show_table = true;
               }
          });
     };
     $scope.init_count();

     $http.get(BASE_URL + 'orders/countrylist').then(function (response) {
          $scope.countrylist = response.data.countries;
     });
     $http.post(BASE_URL + 'app/organisation/myorganisation_list').success(function (data) {
          $scope.orglist = data.organisations;
     });
     $scope.getContacts = function (page) {
          $scope.contactlistdata.currentPage = page;
          phonebook.get_contact_list($scope.contactlistdata)
                  .then(function (data) {
                       // console.log(data);
                       if (data.recent) {
                            $scope.recentlist = data.recent;
                       }
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.contactlist = data.contacts;
                       $scope.contactlistdata.total = data.total;
                       $scope.contactlistdata.currentPage = data.page;
                  });
     };
     $scope.getContacts($scope.contactlistdata.currentPage);
     $scope.$on("refreshList", function (event) {
          $scope.contactlistdata.filter = "";
          $scope.getContacts(1);
     });
     $scope.perpagechange = function () {
          $scope.contactlistdata.perpage_value = $scope.contactlistdata.perpage.value;
          $scope.getContacts($scope.contactlistdata.currentPage);
     };
     $scope.findcontact = function () {
          $scope.getContacts($scope.contactlistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.contactheaders = {
               contact_name: {},
               phone_number: {},
               address_line1: {},
               company_name: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.contactheaders[column].reverse === undefined) {
               $scope.contactheaders[column].reverse = false;
          } else {
               $scope.contactheaders[column].reverse = !$scope.contactheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.contactheaders[column].reverse;
     };
     $scope.save = function () {
          phonebook.set_contact($scope.contact);
          $scope.result = phonebook.save_contact().then(function (data) {
               if (data.status === 1) {
                    notify({
                         message: data.msg,
                         classes: data.class,
                         templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    if ($scope.show_init) {
                         $scope.init_count();
                    }
                    $scope.cancel_create_contact();
                    $scope.getContacts();
               } else {
                    $scope.errors = data.errors;
               }
          });
     };
     $scope.create_contact = function () {
          $scope.create_contact_form = true;
     };
     $scope.cancel_create_contact = function () {
          $scope.contact = {};
          $scope.errors = {};
          $scope.show_org_list = false;
          $scope.create_contact_form = false;
     };
     $scope.update = function (contact) {
          $scope.contact = angular.copy(contact);
          $scope.show_orgs();
          $scope.create_contact();
     };

     $scope.show_delete_warning = function (id) {
          $scope.contact_id = id;
          $scope.delete_warning_popup = true;
     };
     $scope.cancel_delete_warning = function () {
          $scope.contact_id = 0;
          $scope.delete_warning_popup = false;
     };
     $scope.delete = function () {
          $http.post(BASE_URL + 'contacts/delete_contact/', {
               'contact_id': $scope.contact_id
          })
                  .success(function (data) {
                       notify({
                            message: data.msg,
                            classes: data.class,
                            templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                       });
                       if (data.status === 1) {
                            $scope.getContacts($scope.contactlistdata.currentPage);
                            if ($scope.show_table) {
                                 $scope.init_count();
                            }
                       }
                       $scope.cancel_delete_warning();
                  });
     };
     $scope.show_orgs = function () {
          if ($scope.contact.share_contact) {
               if (!$scope.contact.orgs) {
                    $scope.contact.orgs = [];
               }
               $scope.show_org_list = true;
          } else {
               $scope.contact.orgs = [];
               $scope.show_org_list = false;
          }
     };
     $scope.sent_to_this = function (contact) {
          phonebook.clear();
          phonebook.set_deliver(contact);
          $state.go('delivery_orders.new_order');
     };
     $scope.collect_from_this = function (contact) {
          phonebook.clear();
          phonebook.set_collect(contact);
          $state.go('delivery_orders.new_order');
     };

}

