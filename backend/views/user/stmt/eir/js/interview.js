$location = $injector.get('$location');
SharedSipPhone = $injector.get('SharedSipPhone');
Notification = $injector.get('Notification');

init = function () {
    $scope.myPromise = Eir.eirGetPeople({}, function (row) {
        if (row.data == null)
        {
            console.log("null");
            $scope.discard();
            Notification.error('Нет людей для опроса');
        }

        $scope.pacient = row.data;
        $scope.setting = row.setting;
        $scope.data = {
            question1:0,
            question2:0,
            question3:0,
            question4:0
        };

        $scope.value = {
            dpgosp: "a1",
            panul: "a1"
        };
    }, function (r) {
        Notification.warning('ошибка');
    }).$promise;
};
init();

$scope.savePeople = function (value) {

    Eir.save({'params': $scope.setting, 'value': value, 'pacient':$scope.pacient}, function (row) {
        init();
    }, function (r) {
        Notification.warning('ошибка');
    });

};

$scope.panul = [
    {'id': "a1", 'name': "Забыл"},
    {'id': "a2", 'name': "Уехал на др. территорию для получения МП"},
    {'id': "a3", 'name': "Госпитализирован в другую МО"},
    {'id': "a9", 'name': "Другое"}
];

$scope.dpgosp = [
    {'id': "a1", 'name': "Оповещен"},
    {'id': "a2", 'name': "Перенос госпитализации"},
    {'id': "a3", 'name': "Отказ от госпитализации"},
    {'id': "a9", 'name': "Другое"}
];

$scope.discard = function () {
    $location.path('/eir263');
};

$scope.sendPhone = function () {
    $scope.Phone = SharedSipPhone;
    $scope.Phone.number = $scope.pacient.TEL;
};