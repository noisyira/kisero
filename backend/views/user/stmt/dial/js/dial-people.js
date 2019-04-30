$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

$scope.paramId = $routeParams.id;
$scope.paramMo = $routeParams.mo;
$scope.paramY = $routeParams.y;

$scope.myPromise = DialPeople.getPeople({id: $scope.paramId, mo: $scope.paramMo}, function (row) {
    $scope.model = row;

    $scope.answer = {
        result: parseInt(row.result.result),
        question: row.answer,
        description: row.description
    };

    if($scope.answer.result == 5)
        $scope.answer.fail = true;

    // Список вопросов и ответов
    $scope.questionList = row.questions;
    $scope.answerList = row.answers;

}, function (r) {
    Notification.warning('Ошибка');
}).$promise;

/**
 * Вернеться назад
 */
$scope.discard = function () {
    $location.path('/dial-mo/' + $scope.paramMo);
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

// Действия по опросу
/**
 * Очистить результаты опроса
 */
$scope.clear = function () {
    $scope.answer.question = {};
};

$scope.DialFail = function () {
    $scope.answer.result = '5';
    $scope.clear();
};

/**
 * Сохранение опроса
 */
$scope.save = function () {
    DialPeople.save({id: $scope.paramId, mo: $scope.paramMo, model: $scope.answer}, function (row) {
        Notification.success('Сохранено');
        // $location.path('/dial');
        $scope.nextCall();
    });
};

/**
 * Следующий застрахованный для опроса
 */
$scope.nextCall = function () {
    $scope.myPromise = DialPeople.nextCall({mo: $scope.paramMo, y: $scope.paramY, id: $scope.paramId}, function (row) {
        $location.path('/dial-people/' + $scope.paramMo + '/' + row.id);
    }, function (r) {
        Notification.error('Нет застрахованных для опроса!');
    }).$promise;
};

/**
 * Нет ответа
 */
$scope.notAnswer = function () {
    DialPeople.notAnswer({id: $scope.paramId}, function (row) {
        Notification.warning('Изменение статуса - Нет ответа');
        $scope.nextCall();
    });
};

/**
 * Перезвонить
 */
$scope.reCall = function () {
    DialPeople.reCall({id: $scope.paramId, model: $scope.answer}, function (row) {
        Notification.warning('Изменение статуса - Перезвонить');
        $scope.nextCall();
    });
};

// Список ответов
$scope.vnimanie = [
    {'id': "a1", 'name': "С вниманием"},
    {'id': "a2", 'name': "Не очень внимательно"},
    {'id': "a3", 'name': "С безразличием"},
    {'id': "a9", 'name': "Затрудняюсь ответить"}
];

$scope.conduct = [
    {'id': "a1", 'name': "Да"},
    {'id': "a2", 'name': "Нет"},
    {'id': "a3", 'name': "Больше да, чем нет"},
    {'id': "a4", 'name': "Не полностью"},
    {'id': "a9", 'name': "Затрудняюсь ответить"}
];

$scope.prichina = [
    {'id': "a1", 'name': "Невозможно уйти с работы"},
    {'id': "a2", 'name': "Отказ поликлиники в прохождении диспансеризации"},
    {'id': "a3", 'name': "Считаю не нужным прохождение диспансеризации"},
    {'id': "a9", 'name': "Затрудняюсь ответить"}
];

$scope.sendMO = [
    {'id': "a1", 'name': "Для консультации у специалиста"},
    {'id': "a2", 'name': "Для проведения обследования"},
    {'id': "a3", 'name': "Не направляли"}
];

$scope.resultList = [
    {'id': "a1", 'name': "Полностью удовлетворен(а)"},
    {'id': "a2", 'name': "Больше удовлетворен(а) чем неудовлетворен(а)"},
    {'id': "a3", 'name': "Не полностью удовлетворен(а)"},
    {'id': "a4", 'name': "Не удовлетворен"}
];

$scope.yearRange = [
    {'id': "a1", 'name': "I квартал"},
    {'id': "a2", 'name': "II квартал"},
    {'id': "a3", 'name': "III квартал"},
    {'id': "a4", 'name': "IV квартал"}
];