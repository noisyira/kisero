$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
FileUploader = $injector.get('FileUploader');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

$scope.selectedDateStart =  moment().startOf('day').format('DD-MM-YYYY');
$scope.selectedDateEnd = moment().endOf('day').format('DD-MM-YYYY');

$scope.Submit = function () {
    console.log($scope.selectedDateStart);
    console.log($scope.selectedDateEnd);
    $scope.myPromise = DialPeople.getReport({start:$scope.selectedDateStart, end:$scope.selectedDateEnd}, function (row) {
        console.log(row);
        $scope.model = row.data;
        $scope.calls = row.calls;
    }, function (r) {
        Notification.warning('Ошибка');
    }).$promise;
};