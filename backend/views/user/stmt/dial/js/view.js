$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

$scope.paramId = $routeParams.id;
// model

$scope.myPromise = DialPeople.get({id: $scope.paramId}, function (row) {
    $scope.model = row;

    if (row.people) {
        $scope.answer = {
            type: parseInt(row.people.result),
            question: row.answerlist,
            description: row.people.description
        };
    }

    $rootScope.var = row;
}, function (r) {
    Notification.warning('Обращение не найдено');
    $location.path('/dial');
}).$promise;

$scope.bindHtml = function (value) {
    if (value)
        return $sce.trustAsHtml(value);

    return $sce.trustAsHtml("<code>не указанно</code>");
};

$scope.bindDate = function (value) {
    if (value)
        return new Date(value);

    return $sce.trustAsHtml("<code>не указанно</code>");
};

$scope.discard = function () {
    $location.path('/dial');
};

$scope.clear = function () {
    $scope.answer.question = {};
};

/**
 * Сохранение опроса
 */
$scope.save = function () {

    if ($scope.answer.fail == true) {
        $scope.data = {
            'description': $scope.answer.description,
            'fail': $scope.answer.fail,
            'type': '5'
        };
    } else {
        $scope.data = $scope.answer;
    }

    DialPeople.save({id: $scope.paramId, model: $scope.data}, function (row) {
        Notification.success('Сохранено');
       // $location.path('/dial');

        $scope.nextCall();
    });
};

/**
 * Нет ответа
 */
$scope.notAnswer = function () {
    DialPeople.notAnswer({id: $scope.paramId, model: $scope.answer}, function (row) {
        Notification.warning('Изменение статуса - Нет ответа');
      //  $location.path('/dial');
        $scope.nextCall();
    });
};

/**
 * Перезвонить
 */
$scope.reCall = function () {
    DialPeople.reCall({id: $scope.paramId, model: $scope.answer}, function (row) {
        Notification.warning('Изменение статуса - Перезвонить');
       // $location.path('/dial');
        $scope.nextCall();
    });
};

/**
 * Следующий застрахованный для опроса
 */
$scope.nextCall = function () {
    $scope.myPromise = DialPeople.nextCall({}, function (row) {
        $location.path('/dial/'+row.id);
    }).$promise;
};

/**
 * @param data
 * @returns {*}
 */
$scope.checkNull = function (data) {

    if (data == null)
        return null;

    return Number(data);
};

$scope.DialFail = function () {
    $scope.answer.type = '5';
};

$scope.vnimanie = [
    {'id': "1", 'name': "С вниманием"},
    {'id': "2", 'name': "Не очень внимательно"},
    {'id': "3", 'name': "С безразличием"},
    {'id': "9", 'name': "Затрудняюсь ответить"}
];

$scope.conduct = [
    {'id': "1", 'name': "Да"},
    {'id': "2", 'name': "Нет"},
    {'id': "3", 'name': "Больше да, чем нет"},
    {'id': "4", 'name': "Не полностью"},
    {'id': "9", 'name': "Затрудняюсь ответить"}
];

$scope.prichina = [
    {'id': "1", 'name': "Невозможно уйти с работы"},
    {'id': "2", 'name': "Отказ поликлиники в прохождении диспансеризации"},
    {'id': "3", 'name': "Считаю не нужным прохождение диспансеризации"},
    {'id': "9", 'name': "Затрудняюсь ответить"}
];

$scope.sendMO = [
    {'id': "1", 'name': "Для консультации у специалиста"},
    {'id': "2", 'name': "Для проведения обследования"},
    {'id': "3", 'name': "Не направляли"}
];

$scope.resultList = [
    {'id': "1", 'name': "Полностью удовлетворен(а)"},
    {'id': "2", 'name': "Больше удовлетворен(а) чем неудовлетворен(а)"},
    {'id': "3", 'name': "Не полностью удовлетворен(а)"},
    {'id': "4", 'name': "Не удовлетворен"}
];

$scope.yearRange = [
    {'id': "1", 'name': "I квартал"},
    {'id': "2", 'name': "II квартал"},
    {'id': "3", 'name': "III квартал"},
    {'id': "4", 'name': "IV квартал"}
];
