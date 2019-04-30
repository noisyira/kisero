var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $http = $injector.get('$http');


$scope.myPromise = DialPeople.monitoring({}, function (row) {
    $scope.model = row.data;
}, function (r) {
    console.log('Ошибка');
    Notification.warning('Ошибка');
}).$promise;