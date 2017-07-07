angular
        .module('6connect')
        .controller('feedbackController', feedbackController)
        .controller('feedbackSingleController', feedbackSingleController);
function feedbackSingleController($scope, $state) {
     $scope.goback = function () {
          $state.go('accounts.feedback');
     };
}
function feedbackController($scope, $http, $state) {
     $scope.orgperpage = [{
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
     $scope.show_table = true;
     $scope.show_feedback = false;
     $scope.padinteger = function (n, width, z) {
          z = z || '0';
          n = n + '';
          return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
     };
     $scope.singlefeedback = {};
     $scope.show_feedback_Fn = function (id) {
          $http.post(BASE_URL + 'feedback/get_feedback', {id: id})
                  .success(function (data) {
                       $scope.singlefeedback = data.feedback;
                       $scope.show_feedback = true;
                  });
     };
     $scope.getStars = function (rating) {
          // Get the value
          var val = parseFloat(rating);
          // Turn value into number/100
          var size = val / 5 * 100;
          return size + '%';
     };

     $scope.hide_feedback = function () {
          $scope.show_feedback = false;
     };

     $scope.feedbacklistdata = {perpage_value: 15, currentPage: 1, total: 0, perpage: $scope.orgperpage[2], holder_type: '', account_type: ''};

     $scope.orderByField = 'org_id';
     $scope.reverseSort = false;

     $scope.getOrganisations = function (page) {
          $scope.feedbacklistdata.currentPage = page;
          $http.post(BASE_URL + 'feedback/feedback_json', $scope.feedbacklistdata)
                  .success(function (data) {
                       $scope.total = data.total;
                       $scope.start = data.start;
                       $scope.end = data.end;
                       $scope.feedbackslist = data.feedbacks;
                       $scope.feedbacklistdata.total = data.total;
                       $scope.feedbacklistdata.currentPage = data.page;
                  });
     };
     $scope.getOrganisations($scope.feedbacklistdata.currentPage);
     $scope.perpagechange = function () {
          $scope.feedbacklistdata.perpage_value = $scope.feedbacklistdata.perpage.value;
          $scope.getOrganisations($scope.feedbacklistdata.currentPage);
     };

     $scope.resetSort = function () {
          $scope.orgheaders = {
               id: {},
               service_name: {},
               score: {},
               customer_name: {},
               timestamp: {}
          };
     };
     $scope.resetSort();
     $scope.sort = function (column) {
          if ($scope.orderByField !== column)
               $scope.resetSort();
          if ($scope.orgheaders[column].reverse === undefined) {
               $scope.orgheaders[column].reverse = false;
          } else {
               $scope.orgheaders[column].reverse = !$scope.orgheaders[column].reverse;
          }
          $scope.orderByField = column;
          $scope.reverseSort = $scope.orgheaders[column].reverse;
     };



}