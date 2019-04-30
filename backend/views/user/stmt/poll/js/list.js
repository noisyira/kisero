$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');

$scope.paramId = $routeParams.id;

$scope.resource = {
    "header": [
        {
            "key": "fam",
            "name": "ФИО",
            "style": {},
            "class": []
        },
        {
            "key": "user_o",
            "name": "Оператор",
            "style": {},
            "class": []
        },
        {
            "key": "company",
            "name": "Организация",
            "style": {},
            "class": []
        },
        {
            "key": "description",
            "name": "Описание",
            "style": {},
            "class": []
        },
        {
            "key": "status",
            "name": "Статус",
            "style": {},
            "class": []
        }

    ],
    "rows": [],
    "pagination": {
        "page": 1
    }
};

$scope.discard = function () {
    window.history.back();
};

$scope.myPromise = PollList.list({id: $scope.paramId}, function (row) {
    $scope.model = row;
    $scope.resource.rows = row;
}, function (r) {
    Notification.warning('Обращение не найдено');
    //  $location.path('/dial');
}).$promise;

// Шаблон вывода ФИО заявителя
$scope.fioStmt = function (item) {
    if (item) {
        var fam = item.fam ? item.fam : " ";
        var im = item.im ? item.im : " ";
        var ot = item.ot ? item.ot : " ";
        if (item && (fam != null) && (im != null)) {
            return sce.trustAsHtml(fam + " " + im + " " + ot);
        }
    }
    return sce.trustAsHtml("<code>( не указано)</code>");
};

// Шаблон вывода ФИО оператора
$scope.fioOpearator = function (item) {
    if (item) {
        var fam = item.fam ? item.fam : " ";
        var im = item.im ? item.im : " ";
        var ot = item.ot ? item.ot : " ";
        var num = item.user.sip_private_identity;
        if (item && (fam != null) && (im != null)) {
            return sce.trustAsHtml(fam + " " + im + " " + ot + "<br><small>" + "Вн. номер: " + num + "</small>");
        }
    }
};