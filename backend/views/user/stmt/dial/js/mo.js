$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

$scope.paramId = $routeParams.id;
$scope.paramY = $routeParams.y;

$scope.init = {
    'count': 50,
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

$scope.search = function () {

    // Сохранить условия поиска
    DialPeople.saveParamsPeople({data: $scope.filterList}, function (row) {
        Notification.warning('Фильтр применен!');
        $scope.reloadCallback();
    }, function (r) {
        Notification.danger('Ошибка');
    });

};

$scope.clearSearch = function () {
    $scope.filterList = {};
    $scope.reloadCallback();
};

$scope.getResource = function (params, paramsObj) {

    return $scope.myPromise = DialPeople.getDialMO({id:$scope.paramId, y:$scope.paramY, params:paramsObj}, paramsObj).$promise.then(function(res){
        $scope.nameMo = res.mo;
        $scope.total = res.data.pagination.size;

        $scope.filterList = {
            "pd" : res.params.pd,
            "status" : res.params.status,
            "action" : res.params.action,
            "contact" : res.params.contact,
            "actionDV" : res.params.actionDV
        };

        return {
            'rows': res.data.rows,
            "header": [
                {
                    "key": "fam",
                    "name": "ФИО"
                },
                {
                    "key": "pd",
                    "name": "Период"
                },
                {
                    "key": "result",
                    "name": "Статус",
                    'sortable': false
                },
                {
                    "key": "action",
                    "name": "Действие",
                    'sortable': false
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

// Шаблон вывода ФИО заявителя
$scope.fioStmt = function (item) {
    if(item)
    {
        var fam = item.fam ? item.fam : " ";
        var im = item.im ? item.im : " ";
        var ot = item.ot ? item.ot : " ";
        if(item && (item.fam != null) && (item.im != null))
        {
            return $sce.trustAsHtml(fam +" "+ im +" "+ ot);
        }
    }
    return $sce.trustAsHtml("<code>не указанно</code>");
};

$scope.periods = [
    {"value":"1","label":"1"},
    {"value":"2","label":"2"},
    {"value":"3","label":"3"},
    {"value":"4","label":"4"}
    ];

$scope.updates = [
    // {"value":"0","label":"Новый"},
    {"value":"1","label":"Прошел"},
    {"value":"2","label":"Планирует пройти"},
    {"value":"3","label":"Отказался от прохождения"},
    {"value":"4","label":"Затрудняется ответить"},
    {"value":"5","label":"Отказался отвечать"},
    // {"value":"9","label":"В работе"},
    {"value":"10","label":"Не отвечает на звонок"},
    {"value":"11","label":"Перезвонить"},
    {"value":"12","label":"Обрабатывается"}
];

// Disp_file_action
$scope.actions = [
    {"value":"0","label":"Отказ в информировании (ошибка флк)"},
    {"value":"1","label":"Принят СМО к исполнению"},
    {"value":"2","label":"Прошел ДВ1"},
    {"value":"3","label":"Прошел ДВ2"},
    {"value":"4","label":"Прошел ОПВ"},
    {"value":"5","label":"Уведомление СМС"},
    {"value":"6","label":"Уведомление звонком"},
    {"value":"7","label":"Уведомление почтой"},
    {"value":"8","label":"Уведомление лично"},
    {"value":"9","label":"Уведомление по e-mail"}
];