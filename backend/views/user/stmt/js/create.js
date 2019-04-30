$location = $injector.get('$location');
$q = $injector.get('$q');
$timeout = $injector.get('$timeout');
FileUploader = $injector.get('FileUploader');
Notification = $injector.get('Notification');

// model
$scope.model = {
    form_statement: 0
};

$scope.model.file = {
    date: new Date()
};

$scope.btnSave = true;

// save Item
$scope.save = function () {
    if ($scope.model.form_statement == 1) {
        if (!$scope.model.file.num) {
            Notification.error(' Заполните - Номер документа ');
            return false;
        }
        if (uploader.queue.length == 0) {
            Notification.warning(' Выберите файлы ');
            return false;
        }
    }

    Stmt.save({}, $scope.model, function (model) {
        id = model.id;

        if (uploader.queue.length > 0) {
            uploader.onBeforeUploadItem = onBeforeUploadItem;

            function onBeforeUploadItem(item) {
                item.formData.push(
                    {
                        id: model.id,
                        num: $scope.model.file.num,
                        dt: $scope.model.file.date
                    }
                );
            }

            uploader.uploadAll();
            uploader.onCompleteAll = function () {
                Notification.success('Обращение сохранено');
                $location.path('/');
            };
        }
        Notification.success('Обращение сохранено');
       $location.path('/');
    }, function (r) {
        $scope.errors = {};
        $scope.errorStatus = r.status;
        $scope.errorText = r.statusTest;
        Notification.error('Ошибка при сохранении');
        if (r.status == 422) {
            angular.forEach(r.data, function (err) {
                $scope.errors[err.field] = err.message;
            });
        }
    });
};

$scope.discard = function () {
    window.history.back();
};

/* Запрос на поиск по реестру застрахованных */
$scope.submitForm = function () {
    $scope.loading = true;
    $scope.erzData = '';

    Stmt.erz({data: $scope.erz}, function (r) {
        $scope.erzData = r;
        $scope.loading = false;
    });
};

/* Преодразование из строки в дату */
$scope.formatDate = function (date) {
    return new Date(date);
};

/* Очистить форму поиска ЕРЗ */
$scope.clearERZ = function () {
    $scope.erz = {};
    $scope.erzData = '';
};

/* Прикрепить обратившегося к обращению */
$scope.setERZ = function (data) {
    $scope.model.deffered = {
        fam: data.sName,
        im: data.Name,
        ot: data.pName,
        dt: moment(data.dateMan).add(3, 'hours'),
        id: data.id,
        //   phone: $scope.sRemoteNumber,
        id_erz: data.ENP,
        req_okato: data.okato_reg,
        name_okato: data.pbMan,
        okato_erz: true
    };
};

//Все виды обращений
$scope.refreshModeStmt = function () {
    Stmt.getModeStmt({}, function (r) {
        $scope.listMode = r;
    });
};

//Все МО
$scope.moList = function(){
    Stmt.getMoList({}, function (r) {
        $scope.listMo = r;
    });
};

/* список типов обращений */
$scope.listTipStmt = [
    {id: '1', title: 'Жалоба'},
    {id: '2', title: 'Консультация'},
    {id: '3', title: 'Заявление'},
    {id: '4', title: 'Предложение'}
];

/* Список тем обращений */
$scope.refreshStmt = function (tip) {
    Stmt.getThemeStmt({data: tip}, function (r) {
        $scope.themeLists = r;
    });
};

/* Список пользователей для переадресации обращения */
$scope.transferList = function (id) {
    Stmt.getUsersList({}, function (r) {
        $scope.userTransferLists = r.users;
        $scope.user_o = $.grep(r.users, function (e) {
            return e.user_id == r.user_o;
        });
    });
};

/* Очистить тематику обращения */
$scope.clear = function () {
    $scope.model.theme_statement = '';
    $scope.theme = '';
    $scope.model.mo = '';
};

var uploader = $scope.uploader = new FileUploader({
    url: '/user/stmt/file'
});

// FILTERS
uploader.filters.push({
    name: 'customFilter',
    fn: function (item /*{File|FileLikeObject}*/, options) {
        return this.queue.length < 10;
    }
});