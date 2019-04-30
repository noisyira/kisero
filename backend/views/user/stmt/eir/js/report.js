$location = $injector.get('$location');
Notification = $injector.get('Notification');

$scope.discard = function () {
    $location.path('/eir263');
};

init = function (data) {
    console.log(data);
    $scope.myPromise = Eir.eirReport({data:data}, function (row) {
        $scope.users = row;
    }, function (r) {
        Notification.warning('ошибка');
    }).$promise;
};
init();

$scope.reLoadReport = function (data) {
    init(data);
};