angular
        .module('6connect')
        .controller('publicTrackingCtrl', publicTrackingCtrl);
function publicTrackingCtrl($scope, $http, $stateParams, notify) {
    $scope.enable_tracking = false;
    $scope.tracking_url = "";
    $scope.tracking = {};
    
    $http.get(BASE_URL + 'app/public_tracking/get_tracking_status/' + $stateParams.id).success(function (data) {
        if (data.status) {
            $scope.enable_tracking = data.status;
            $scope.tracking_url = data.tracking;
            $scope.tracking=data.tracking_info;
        }
    });
    $scope.show_enable_confirm = function () {
        $scope.show_confirm_popup = true;
    };
    $scope.cancel_enable_confirm = function () {
        $scope.show_confirm_popup = false;
        if ($scope.enable_tracking) {
            $scope.tracking_url = "";
            $scope.enable_tracking = false;
        } else {
            $scope.enable_tracking = true;
        }
    };
    $scope.proceed = function () {
        $http.post(BASE_URL + 'app/public_tracking/enable_tracking', {enable_tracking: $scope.enable_tracking, org_id: $stateParams.id})
                .success(function (data) {
                    notify({
                        message: data.msg,
                        classes: data.class,
                        templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                    if (data.status === 1) {
                        $scope.show_confirm_popup = false;
                        $scope.tracking_url = data.tracking;

                    } else {
                        $scope.cancel_enable_confirm();
                    }
                });
    };
    
    $scope.add_tracking_info = function () {
        $scope.tracking.org_id = $stateParams.id;
        $http.post(BASE_URL + 'app/public_tracking/add_tracking_info', $scope.tracking)
                .success(function (data) {
                    notify({
                        message: data.msg,
                        classes: data.class,
                        templateUrl: ROOT_PATH + "/resource/partial/notify.html"
                    });
                });
    };

}
;