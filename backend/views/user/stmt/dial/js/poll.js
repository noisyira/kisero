$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');

$scope.resource = {
    "header": [
        {
            "key": "poll_key",
            "name": "Название",
            "style": {},
            "class": []
        },
        {
            "key": "poll_start",
            "name": "Период",
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


$scope.myPromise = PollList.get({}, function (row) {
    $scope.model = row;
    $scope.resource.rows = row;
}, function (r) {
    Notification.warning('Обращение не найдено');
    //  $location.path('/dial');
}).$promise;

// Шаблон вывода Названия опроса
$scope.pollName = function (item) {

    var name = item.name.name;
    var desc = item.description;

    return sce.trustAsHtml("<h4>" + name + "</h4><p>" + desc + "</p>");
};

// Шаблон вывода Период опроса
$scope.pollRange = function (item) {
    if (item.poll_start && item.poll_end) {
        return sce.trustAsHtml(item.poll_start + " — " + item.poll_end);
    } else if (item.poll_end) {
        return sce.trustAsHtml(item.poll_end);
    } else {
        return sce.trustAsHtml("<code>(не указано)</code>");
    }
};

// Шаблон вывода Статус опроса
$scope.pollStatus = function (item) {
    var status = '';
    switch (item.status) {
        case "0":
            status = 'Новый';
            break;
        case "1":
            status = 'Идет опрос';
            break;
        case "2":
            status = 'Завершен';
            break;
        default:
            status = 'Идет опрос';
    }

    return sce.trustAsHtml("<span class='label label-default'>"+ status +"</span>");
};