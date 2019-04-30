$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');


$scope.record = null;
$scope.paramId = $routeParams.id;
// model
$scope.myPromise = Stmt.get({id:$scope.paramId},function(row){
    $scope.model = row.data;
    $scope.record = row.record;
    $scope.files = row.data.attachment;

}).$promise;

// delete Item
$scope.deleteModel = function(){
    if(confirm('Are you sure you want to delete')){
        Stmt.remove({id:$scope.paramId},{},function(){
            $location.path('/');
        });
    }
};

// вернуть на доработку
$scope.rework = function () {
    Stmt.rework({id:$scope.paramId},{},function(){
        Notification.warning('Обращение обновлено');
        $location.path('/');
    });
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

$scope.bindLink = function(path, name) {

    var from = path.search('/uploads');
    var link = path.substring(26, path.length) + name;


    return $sce.trustAsHtml(href=link);
};

$scope.discard = function(){
    window.history.back();
};