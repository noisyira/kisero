var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $http = $injector.get('$http');
$routeParams = $injector.get('$routeParams');

$scope.paramMo = $routeParams.mo;

$scope.init = {
    'count': 25,
    'page': 1,
    'sortBy': 'fam',
    'sortOrder': 'asc',
    'filterBase': 1 // set false to disable
};

$scope.filterBy = {};

$scope.reloadCallback = function () {
    $scope.filterBy = {
        'pd':$scope.filterList.pd,
        'status':$scope.filterList.status,
        'action':$scope.filterList.action,
        'contact':$scope.filterList.contact,
        'actionDV':$scope.filterList.actionDV
    };
};

$scope.clearSearch = function () {
    $scope.filterList = {};
    $scope.reloadCallback();
};

$scope.getResource = function (params, paramsObj) {

    return $scope.myPromise = DspPeople.list({id:$scope.paramMo, params:paramsObj}, paramsObj).$promise.then(function(res){

        $scope.MO = res.mo;
        $scope.total = res.count;

        return {
            'rows': res.data.rows,
            "header": [
                {
                    "key": "fam",
                    "name": "ФИО"
                },
                {
                    "key": "group",
                    "name": "Группа"
                },
                {
                    "key": "ds",
                    "name": "Диагноз по МКБ-10"
                },
                {
                    "key": "id",
                    "name": "Действие"
                }
            ],
            'pagination': res.data.pagination,
            'sortBy': [],
            'sortOrder': []
        }

    }, function (error) {
        console.log('error');
    });
};