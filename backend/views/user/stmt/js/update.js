$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$timeout = $injector.get('$timeout');
FileUploader = $injector.get('FileUploader');
Notification = $injector.get('Notification');

$scope.paramId = $routeParams.id;
// model

$scope.model = [];

$scope.btnUpdate = true;

$timeout(function () {
    $scope.myPromise = Stmt.get({id:$scope.paramId},function(row){
        $scope.model = row.data;
        $scope.model.form_statement = parseInt(row.data.form_statement);
        var tip  = $scope.model.tip_statement;

        Stmt.getThemeStmt({data: tip}, function (r) {
            $scope.themeLists = r;
            $scope.theme = $.grep(r, function(e){ return e.key_statement == row.data.theme_statement; });
        });
    },function(r){
        Notification.warning('Обращение не найдено');
        $location.path('/');
    }).$promise;
}, 500);

$scope.deleteAtt = function(data, inx){
    if(confirm('Вы уверены, что хотите удалить?')){
        Stmt.deleteAttachment({data:data}, function (r) {});

        $scope.model.attachment.splice(inx, 1);
    }
};

// update Items
$scope.update = function(){

    Stmt.update({id:$scope.paramId},$scope.model,function(model){
        id = model.id;
        if(uploader.queue.length > 0)
        {
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

            uploader.onCompleteAll = function() {
                 Notification.success('Обращение сохранено');
                 $location.path('/');
            };
        }

         Notification.success('Обращение сохранено');
        $location.path('/');
    },function(r){
        $scope.errors = {};
        $scope.errorStatus = r.status;
        $scope.errorText = r.statusTest;
        Notification.error('Ошибка при сохранении');
        if (r.status == 422) {
            angular.forEach(r.data,function(err) {
                $scope.errors[err.field] = err.message;
            });
        }
    });
};

$scope.discard = function(){
    window.history.back();
};

/* Запрос на поиск по реестру застрахованных */
$scope.submitForm = function() {
    $scope.loading = true;
    $scope.erzData = '';

    Stmt.erz({data: $scope.erz}, function (r) {
        $scope.erzData = r;
        $scope.loading = false;
    });
};

/* Преодразование из строки в дату */
$scope.formatDate = function (date) {
    return new Date(date).toISOString();
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
        dt: $scope.formatDate(data.dateMan),
        id: data.id,
        //   phone: $scope.sRemoteNumber,
        id_erz: data.ENP,
        req_okato: data.okato_reg,
        name_okato: data.pbMan,
        okato_erz: true
    };
};

//Все виды обращений
$scope.refreshModeStmt = function(){
    Stmt.getModeStmt({}, function (r) {
        $scope.listMode = r;
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
$scope.refreshStmt = function(tip) {
    Stmt.getThemeStmt({data: tip}, function (r) {
        $scope.themeLists = r;
    });
};

/* Список пользователей для переадресации обращения */
$scope.transferList = function(id) {
    Stmt.getUsersList({id:$scope.paramId}, function (r) {
        $scope.userTransferLists = r.users;
        $scope.user_o = $.grep(r.users, function (e) {
            return e.user_id == r.user_o;
        });
    });
};

//Все МО
$scope.moList = function(){
    Stmt.getMoList({}, function (r) {
        $scope.listMo = r;
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
    fn: function(item /*{File|FileLikeObject}*/, options) {
        return this.queue.length < 10;
    }
});
