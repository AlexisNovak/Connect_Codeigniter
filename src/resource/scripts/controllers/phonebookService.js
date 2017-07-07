function phonebook($http, $timeout) {
     var pb = {};
     pb.contact = {};
     pb.result = {};
     pb.collect = {};
     pb.deliver = {};
     pb.include_recent = false;
     pb.set_contact = function (contact) {
          this.contact = contact;
     };

     pb.get_contact = function () {
          return this.contact;
     };
     pb.set_collect = function (contact) {
          this.collect = contact;
     };
     pb.get_collect = function () {
          return this.collect;
     };
     pb.get_deliver = function () {
          return this.deliver;
     };
     pb.set_deliver = function (contact) {
          this.deliver = contact;
     };
     pb.clear = function () {
          pb.collect = {};
          pb.deliver = {};
     };
     pb.reset_contact = function () {
          $timeout(function () {
               if (pb.collect.id != undefined && pb.deliver.id != undefined) {
                    $http.post(BASE_URL + 'contacts/save_recent', {from: pb.collect.id, to: pb.deliver.id}).then(function (response) {
                         pb.contact = {};
                         pb.collect = {};
                         pb.deliver = {};
                    });
               } else {

                    pb.contact = {};
                    pb.collect = {};
                    pb.deliver = {};
               }
          }, 1000);

     };
     pb.set_include_recent = function () {
          this.include_recent = true;
     };
     pb.unset_include_recent = function () {
          this.include_recent = false;
     };
     pb.save_collect = function () {
          var promise = $http.post(BASE_URL + 'contacts/save_contact', this.collect).then(function (response) {
               if (response.data.status == 1) {
                    pb.collect.id = response.data.id;
               }
               return response.data;
          });
          return promise;
     };
     pb.save_deliver = function () {
          var promise = $http.post(BASE_URL + 'contacts/save_contact', this.deliver).then(function (response) {
               if (response.data.status == 1) {
                    pb.deliver.id = response.data.id;
               }
               return response.data;
          });
          return promise;
     };
     pb.save_contact = function () {
          var promise = $http.post(BASE_URL + 'contacts/save_contact', this.contact).then(function (response) {

               return response.data;
          });
          return promise;
     };
     pb.get_contact_list = function (arg) {
          if (this.collect.contact_name !== "" && this.include_recent === true) {
               arg.c_id = this.collect.id;
          }
          var promise = $http.post(BASE_URL + 'contacts/contactlist_json', arg).then(function (response) {

               return response.data;
          });
          return promise;
     };
     return pb;
}
;
angular
        .module('6connect')
        .factory('phonebook', phonebook);
