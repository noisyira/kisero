$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');

$scope.resource = {
    "header": [
        {
            "key": "fam",
            "name": "ФИО",
            "style": {},
            "class": []
        },
        {
            "key": "total",
            "name": "Общее количество",
            "style": {},
            "class": []
        },
        {
            "key": "success",
            "name": "Завершенные",
            "style": {},
            "class": []
        },
        {
            "key": "recall",
            "name": "Перезвонить",
            "style": {},
            "class": []
        },
        {
            "key": "notcall",
            "name": "Нет ответа",
            "style": {},
            "class": []
        },
        {
            "key": "process",
            "name": "В работе",
            "style": {},
            "class": []
        }
    ],
    "rows": [],
    "sortBy": "total",
    "sortOrder": "desc",
    "pagination": {
        "page": 1
    }
};

$scope.myPromise = DialPeople.reportUser({}, function (row) {
    $scope.resource.rows = row;
}, function (r) {

}).$promise;

// Шаблон вывода ФИО оператора
$scope.fioStmt = function (item) {
    if (item) {
        var fam = item.fam ? item.fam : " ";
        var im = item.im ? item.im : " ";
        var ot = item.ot ? item.ot : " ";
        if (item && (fam != null) && (im != null)) {
            return $sce.trustAsHtml(fam + " " + im + " " + ot);
        }
    }
    return $sce.trustAsHtml("<code>( не указано)</code>");
};