$location = $injector.get('$location');
$q = $injector.get('$q');
$timeout = $injector.get('$timeout');
Notification = $injector.get('Notification');

// model
$scope.model = {
    statement:2,
    form_statement:0,
    stage_statement:1
};

$scope.btnSave = true;

// save Item
$scope.save = function(){

    return Stmt.save({},$scope.model,function(model){
        id = model.id;
        $scope.stmtID = model.id;
        Notification.success('Обращение сохранено');
        // $location.path('/' + id);
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

// Продолжить редактирование
$scope.continue = function () {

    if($scope.stmtID)
    {
        $location.path('/' + $scope.stmtID + '/edit')
    } else {
        Stmt.save({},$scope.model,function(model){
            id = model.id;
            Notification.success('Обращение сохранено');
            $location.path('/' + id + '/edit')
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
    }
};

$scope.discard = function(){
    window.history.back();
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

/* Очистить тематику обращения */
$scope.clear = function () {
    $scope.model.theme_statement = '';
    $scope.theme = '';
};

$scope.answer = function (theme) {
    Stmt.getAnswerScript({key:theme}, function (r) {
        $scope.answerList = r;
    });
};