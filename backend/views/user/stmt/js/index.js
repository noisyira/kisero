var $location = $injector.get('$location');
var $pageInfo = $injector.get('$pageInfo');
var sce = $injector.get('$sce');
var $http = $injector.get('$http');

$scope.init = {
    'count': 15,
    'page': 1,
    'sortBy': 'id',
    'sortOrder': 'DESC',
    'filterBase': 1 // set false to disable
};

$scope.filterBy = {};
$scope.filterList = {};

$scope.reloadCallback = function () {
    $scope.filterBy = {
       'id':  $scope.filterList.id,
        'fio': $scope.filterList.fio,
        'tip_statement': $scope.filterList.tip_statement,
        'user_o': $scope.filterList.user_o,
        'form_statement': $scope.filterList.form_statement,
        'theme_statement': $scope.filterList.theme_statement,
        'mo': $scope.filterList.mo
    };
};

$scope.search = function () {
    $scope.reloadCallback();
};

$scope.clearSearch = function () {
    $scope.filterList = {};
    $scope.reloadCallback();
};

$scope.getResource = function (params, paramsObj) {
    return $scope.myPromise = Stmt.pagi({}, paramsObj).$promise.then(function(res){
        return {
            'rows': res.data.rows,
            "header": [
                {
                    "key": "stmt.id",
                    "name": "Номер"
                },
                {
                    "key": "stmt_deffered.fam",
                    "name": "ФИО"
                },
                {
                    "key": "stmt.statement_date",
                    "name": "Дата"
                },
                {
                    "key": "stmt.tip_statement",
                    "name": "Тип"
                },
                {
                    "key": "stmt.status",
                    "name": "Статус"
                }
            ],
            'pagination': res.data.pagination,
            'sortBy': [],
            'sortOrder': []
        }

    }, function (error) {
        console.log('error');
    });
};

$scope.initForm = function(data) {
    $scope.filterList.form_statement = data;
};

// Шаблон вывода ФИО заявителя
$scope.fioStmt = function (item) {
    if(item)
    {
        var fam = item.fam ? item.fam : " ";
        var im = item.im ? item.im : " ";
        var ot = item.ot ? item.ot : " ";
        if(item && (item.fam != null) && (item.im != null))
        {
            return sce.trustAsHtml(fam +" "+ im +" "+ ot);
        }
    }
    return sce.trustAsHtml("<code>анонимно</code>");
};

/* Список тем обращений */
$scope.listStmt = function () {
    Stmt.getList({}, function (r) {
        $scope.stmtlists = r;
    });
};

/* Список пользователей для фильтров */
$scope.listUser = function () {
    Stmt.getUsers({}, function (r) {
        $scope.userlists = r;
    });
};

/* список типов обращений */
$scope.listTipStmt = [
    {id: "", title: "Все обращения"},
    {id: '1', title: 'Жалоба'},
    {id: '2', title: 'Консультация'},
    {id: '3', title: 'Заявление'},
    {id: '4', title: 'Предложение'}
];

// delete Item
$scope.deleteModel = function(model){
    if(confirm('Are you sure you want to delete')){
        id = model.id;
        Stmt.remove({id:id},{},function(){
            query();
        });
    }
};