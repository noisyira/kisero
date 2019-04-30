Notification = $injector.get('Notification');

$scope.kodMO = {selected:null};
//$scope.kodMOs = {selected:null};
$scope.ready = false;

init = function () {
    $scope.myPromise = Eir.eirGet({}, function (row) {

        $scope.kodMO = {selected:row.code_mo};
        $scope.selectedParam = row.params;
        $scope.selectedRange = row.range;
        $scope.total = row.total;
        $scope.phone = row.phone;
        $scope.call = row.call; // <----
        $scope.ready = row.ready;
        $scope.eir = row.eir;
        $scope.dates = {
            start: new Date(row.eir.from_date),
            end: new Date(row.eir.to_date)
        };

    }, function (r) {
        Notification.warning('ошибка');
    }).$promise;
};
init();

$scope.getAsyncMo = function (data) {
    Eir.getMO({data: data}, function (row) {
        $scope.MO = row;
    }, function (r) {
        Notification.danger('Ошибка запроса');
    })
};

$scope.SettingEir = function () {
    if($scope.kodMO.selected.length == 0)
    {
        alert("Выберите МО");
        return;
    }

    if(!$scope.selectedParam)
    {
        alert("Выберите Фильтр");
        return;
    }

    if(!$scope.selectedRange)
    {
        alert("Выберите Диапазон");
        return;
    }

    var kodMO = $scope.kodMO.selected;
    var MO = kodMO.map(function(a) {return a.KODMO;});

    Eir.eirSettingSave({mo:MO, param:$scope.selectedParam, range:$scope.selectedRange}, function (row) {
        console.log(row);
        init();
        Notification.success('Сохранение');

    }, function (r) {
        Console.g("Error save EirSetting");
    })
};

/* Фильтр */
$scope.selectedParam = '';
$scope.params = [
    {value: 'DPGOSP', label: 'Начало госпитализации'},
    {value: 'DANUL', label: 'Неявка пациента на госпитализацию'},
    {value: 'DPOGOSP', label: 'Результат госпитализации'}
];

/* Диапазон */
$scope.selectedRange = '';
$scope.range = [
    {value: '1', label: 'Сегодня'},
    {value: '3', label: '3 дня'},
    {value: '5', label: '5 дней'},
    {value: '7', label: '7 дней'}
];