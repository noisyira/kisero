var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $http = $injector.get('$http');

// initial load
query = function () {
    $scope.myPromise = DspPeople.index({}, function (row) {

        $scope.listMO = row;

    }).$promise;
};
query();