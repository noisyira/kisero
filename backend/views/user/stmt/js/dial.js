var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $cookies = $injector.get('$cookies');
Notification = $injector.get('Notification');

// initial load
query = function () {
    $scope.myPromise = DialPeople.query({}, function (row) {
        $scope.listMO = row.MO;

        if(row.params.y)
        {
            $scope.filters = {"y":row.params.y};
        } else {
            /* ФИЛЬТР ДИСПАНСЕРИЗАЦИИ */
            $scope.filters = {
                "y": {
                    "_16": true,
                    "_17": true
                }
            };
        }
    }).$promise;
};
query();

/* Сохранить фильтры диспансеризации */
$scope.accept = function (data) {
    DialPeople.saveParamsMO({data: data}, function (row) {
        Notification.warning('Фильтр применен!');
        query();
    }, function (r) {
        Notification.danger('Ошибка');
    });
};