$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');

$scope.paramId = $routeParams.id;
$scope.enableClose = true;

// model
$scope.myPromise = Stmt.get({id:$scope.paramId},function(row){
    $scope.model = row.data;
}).$promise;

Stmt.getCloseList({}, function (r) {
    $scope.closeList = r;
});

// Stmt.getUsersList({}, function (r) {
//     $scope.userTransferLists = r;
// });

/* Список пользователей для переадресации обращения */
$scope.transferList = function(id) {
    Stmt.getUsersList({id:$scope.paramId}, function (r) {
        $scope.userTransferLists = r.users;
        $scope.user_o = $.grep(r.users, function (e) {
            return e.user_id == r.user_o;
        });
    });
};

$scope.res = {plaint: ''};

$scope.setPlaint = function(pl){
    $scope.res.plaint = pl;
    console.log($scope.res);
};

/* Закрытие обращение */
$scope.closeStmt = function () {
    Stmt.close({id:$scope.paramId}, $scope.res,function(model){
        Notification.success('Обращение закрыто');
        $location.path('/');
    },function(r){
        $scope.errors = {};
        $scope.errorStatus = r.status;
        $scope.errorText = r.statusTest;

        if(r.data.message == '10')
        {
            Notification.warning('Неуказан «Тип Жалобы»');
        } else
        {
            Notification.error('Ошибка при сохранении');
        }

        if (r.status == 422) {
            angular.forEach(r.data,function(err) {
                $scope.errors[err.field] = err.message;
            });
        }
    });
};

// delete Item
$scope.deleteModel = function(){
    if(confirm('Are you sure you want to delete')){
        Stmt.remove({id:$scope.paramId},{},function(){
            $location.path('/');
        });
    }
};

$scope.bindHtml = function (value) {
    if(value)
        return $sce.trustAsHtml(value);

    return $sce.trustAsHtml("<code>не указанно</code>");
};
$scope.bindDate = function (value) {
    if(value)
        return new Date(value);

    return $sce.trustAsHtml("<code>не указанно</code>");
};

$scope.bindFIO = function (item) {
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

$scope.discard = function(){
    window.history.back();
};