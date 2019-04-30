$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$timeout = $injector.get('$timeout');
FileUploader = $injector.get('FileUploader');
Notification = $injector.get('Notification');
$sce = $injector.get('$sce');

$scope.paramId = $routeParams.id;

// model
$scope.model = {
    form_statement:0
};


$scope.chForm = function(f_stmt)
{
  $scope.model.form_statement = f_stmt;
};

$scope.btnUpdate = true;

$timeout(function () {
$scope.myPromise = Stmt.get({id:$scope.paramId},function(row){
    $scope.model = row.data;
    $scope.model.form_statement = parseInt(row.form_statement);
    $scope.model.file = { date: new Date() };

    if($scope.model.attachment.length > 0)
    {
        $scope.model.file = { num: $scope.model.attachment[0]['n_attach'], date: new Date() };
        $scope.fileNumDisable = true;
    }

    if($scope.model['call'] !== undefined && $scope.model['call'] !== null)
    {
        var call = $scope.model['call'];
        if(call.callUID.recordingfile)
        {
            $scope.path = '/record/'+$scope.model.company +'/';
            $scope.recordName = call.callUID.recordingfile + '.wav';
            $scope.config = {
                sources: [
                    {src: $sce.trustAsResourceUrl($scope.path + $scope.recordName), type: "audio/wav"},
                ],
                theme: {
                    url: "http://www.videogular.com/styles/themes/default/latest/videogular.css"
                }
            };
        }
    }

    var tip  = $scope.model.tip_statement;

    Stmt.getThemeStmt({data: tip}, function (r) {
        $scope.themeLists = r;
        $scope.theme = $.grep(r, function(e){ return e.key_statement == row.theme_statement; });
    });
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
    if($scope.model.form_statement == 1 && $scope.model.statement != 9 )
    {
        if(!$scope.model.file.num)
        {
            Notification.error(' Заполните - Номер документа ');
            return false;
        }

        if(uploader.queue.length == 0 && $scope.model.attachment.length == 0)
        {
            Notification.warning(' Выберите файлы ');
            return false;
        }
    }

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
             //   return $location.path('/');
            };
        }
        Notification.success('Обращение сохранено');
       // $location.path('/');
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


//transfer
$scope.transfer = function () {
    /* Перевести обращение */
    
    /* Если установленно соединение, то идет переадресация */
    if(oSipSessionCall)
    {
            $timeout(sipTransfers($scope.transferUser.sip_private_identity), 3000);
            $location.path("/");
        /* Переадресация и новая запсь в StmtCall */
        // $http.post(ctrl+'/setdelayedtransfer', task)
        //     .then(function(response){
        //         if(response.data != false)
        //         {
        //             $timeout(sipTransfers(response.data.transfer.sip_private_identity), 3000);
        //             $location.path("/");
        //         }
        //     }, function(error) {
        //         console.log('Error :: setdelayedresponse');
        //     });

    }
    /* Если нет соединения, то «Отсроченный ответ» */
    else{
        $scope.update();
        /* выбираем «Исполнителя»  и изменяю статус на «Отсроченный ответ» */
        Stmt.transfer({id:$scope.paramId, data: $scope.transferUser}, function (r) {
            Notification.warning('Обращение перенаправленно');
           //  $location.path('/');
        },function(r){
            Notification.error('Ошибка');
        });
    }
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
    return new moment(date).format('DD-MM-YYYY');
};

/* Очистить форму поиска ЕРЗ */
$scope.clearERZ = function () {
    $scope.erz = {};
    $scope.erzData = '';
};

/* Прикрепить обратившегося к обращению */
$scope.setERZ = function (data) {
    console.log($scope.formatDate(data.dateMan));
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

//Все МО
$scope.moList = function(){
    Stmt.getMoList({}, function (r) {
        $scope.listMo = r;
    });
};

/* Список пользователей для переадресации обращения */
// $scope.transferList = function(id) {
//     Stmt.getUsersList({}, function (r) {
//         $scope.userTransferLists = r;
//
//         $scope.userTransferLists.$promise.then(function(data) {
//
//             var result = $.grep(data, function(e){ return e.user_id == id; });
//             $scope.user_o = result;
//             $scope.model.user_o = id;
//         });
//     });
// };

/* Список пользователей для переадресации обращения */
$scope.transferList = function(id) {
    Stmt.getUsersList({id:$scope.paramId}, function (r) {
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
    fn: function(item /*{File|FileLikeObject}*/, options) {
        return this.queue.length < 10;
    }
});
