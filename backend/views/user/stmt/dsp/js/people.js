var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $http = $injector.get('$http');
$routeParams = $injector.get('$routeParams');

$scope.paramMo = $routeParams.mo;
$scope.paramPid = $routeParams.pid;

// initial load
query = function () {
    $scope.myPromise = DspPeople.people({mo:$scope.paramMo, pid:$scope.paramPid}, function (row) {

        $scope.people = row.data;

    }).$promise;
};
query();

$scope.discard = function () {
    $location.path('/dsp/' + $scope.paramMo );
};

$scope.bindHtml = function (value) {
    if (value)
        return sce.trustAsHtml(value);
    return sce.trustAsHtml("<code>не указанно</code>");
};

$scope.bindDate = function (value) {
    if (value)
        return new Date(value);
    return sce.trustAsHtml("<code>не указанно</code>");
};
