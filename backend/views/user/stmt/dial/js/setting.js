$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

$scope.selection = {};

/*  */
$scope.myPromise = DialPeople.getListMO({}, function (row) {
    $scope.model = row.data;

    if (Object.keys(row.selected).length > 0)
    {
        $scope.selection = row.selected;
    }

}, function (r) {
    Notification.warning('ошибка');
}).$promise;

$scope.save = function (data) {
    DialPeople.settingMO({data: data}, function (row) {
    $location.path('/dial');
    Notification.success('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> настройки сохранены!');
    }, function (r) {
        Notification.warning('ошибка при сохранении');
    });
};

$scope.checkVal = function (item) {
    if($scope.selection[item] == false)
    {
        delete $scope.selection[item];
    }
    $scope.selectionCount = Object.keys($scope.selection).length;
};