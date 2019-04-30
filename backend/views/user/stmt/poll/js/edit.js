$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');

$scope.paramId = $routeParams.id;
// model

$scope.myPromise = PollList.getPeople({id: $scope.paramId}, function (row) {
    $scope.model = row;

    $scope.answer = {
        question: row.answerlist
    };

    $rootScope.var = row;
}, function (r) {
    Notification.warning('Обращение не найдено');
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
    $location.path('/poll/');
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
            'fail': $scope.answer.fail
        };
    } else {
        $scope.data = $scope.answer;
    }

    PollList.save({id: $scope.paramId, model: $scope.data}, function (row) {
        Notification.success('Сохранено');
        $location.path('/poll');
        $scope.nextCall();
    });
};

$scope.btnSc = true;

$scope.chgAnswer = function (ind) {

    $scope.data = {};

    angular.forEach($scope.answer.question, function (value, key) {

        if(key <= ind) { $scope.data[key] = value; }

        if(value == 1)
        {
            $scope.btnSc = true;
        } else {
            $scope.btnSc = false;
        }
    });

    if(Object.keys($scope.answer.question).length == $scope.model.questions.length) { $scope.btnSc = false; }

    $scope.answer.question = $scope.data;
};

/**
 * Следующий застрахованный для опроса
 */
$scope.nextCall = function () {
    $scope.myPromise = PollList.nextCall({id: $scope.paramId}, function (row) {
       //console.log(row);
        $location.path('/poll/edit/'+row.id);
    }).$promise;
};

/**
 * Нет ответа
 */
$scope.notAnswer = function () {
    PollList.notAnswer({id: $scope.paramId}, function (row) {
        Notification.warning('Изменение статуса - Нет ответа');
        $scope.nextCall();
       // $location.path('/poll');
    });
};

/**
 * Перезвонить
 */
$scope.reCall = function () {
    PollList.reCall({id: $scope.paramId, model: $scope.answer}, function (row) {
        Notification.warning('Изменение статуса - Перезвонить');
        $scope.nextCall();
       // $location.path('/poll');
    });
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
