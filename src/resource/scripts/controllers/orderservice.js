/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function orderService($http, $q) {
     return {
          service: {},
          is_new: false,
          is_bidding: false,
          setBidding: function () {
               this.is_bidding = true;
          },
          getBidding: function () {
               return this.is_bidding;
          },
          resetBidding: function () {
               this.is_bidding = false;
          },
          set_service: function (value1, value2) {
               this.service.org_id = value1;
               this.service.service_id = value2;
          },
          get_service: function () {
               return this.service;
          },
          reset_service: function () {
               this.service = {};
          },
          consignmenttypes: function () {
               var def = $q.defer();
               $http.get(BASE_URL + "orders/consignment_types").then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get types");
               });
               return def.promise;
          },
          assignedServices: function (org_id, collection_time, delivery_time, c_country, d_country, type, consignment_type, term,location) {
               var def = $q.defer();
               $http.post(BASE_URL + "orders/assigned_services_by_org", {'org_id': org_id, 'collection_time': collection_time, 'delivery_time': delivery_time, c_country: c_country, d_country: d_country, type: type, consignment_type: (consignment_type ? consignment_type : ""), term: term,location:location}).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get services");
               });
               return def.promise;
          },
          OrdersCount: function () {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/orderslist_count').then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get count");
               });
               return def.promise;
          },
          OrdersList: function (orderslistdata) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/orderslist_json', orderslistdata).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get orders");
               });
               return def.promise;
          },
          getOrder: function (id) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/get_order_json/' + id).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get order");
               });
               return def.promise;
          },
          getmultiOrder: function () {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/multiorders/getMultiorders/').then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to get order");
               });
               return def.promise;
          },
          confirmOrder: function (id) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/confirmOrder/' + id).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to confirm");
               });
               return def.promise;
          }, approvePrice: function (id) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/approve_price/' + id).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to approve");
               });
               return def.promise;
          }, rejectPrice: function (id) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/approve_price/' + id + '/0').then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to reject");
               });
               return def.promise;
          },
          deleteOrder: function (id) {
               var def = $q.defer();
               $http.post(BASE_URL + 'orders/deleteOrder/' + id).then(function (data) {
                    def.resolve(data);
               }, function () {
                    def.reject("Failed to delete");
               });
               return def.promise;
          },
          setNew: function () {
               this.is_new = true;
          },
          getNew: function () {
               return this.is_new;
          },
          resetNew: function () {
               this.is_new = false;
          }
     };
}
;
angular
        .module('6connect')
        .factory('orderService', orderService);