$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
$sce = $injector.get('$sce');
Notification = $injector.get('Notification');
FileUploader = $injector.get('FileUploader');
$rootScope = $injector.get('$rootScope');
$timeout = $injector.get('$timeout');

var uploader = $scope.uploader = new FileUploader({
    url: '/user/stmt/interaction'
});

// FILTERS

// a sync filter
uploader.filters.push({
    name: 'syncFilter',
    fn: function(item /*{File|FileLikeObject}*/, options) {
        console.log('syncFilter');
        return this.queue.length < 5;
    }
});

// an async filter
uploader.filters.push({
    name: 'asyncFilter',
    fn: function(item /*{File|FileLikeObject}*/, options, deferred) {
        console.log('asyncFilter');
        setTimeout(deferred.resolve, 1e3);
    }
});

// CALLBACKS

uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
    // console.info('onWhenAddingFileFailed', item, filter, options);
};
uploader.onAfterAddingFile = function(fileItem) {
    // console.info('onAfterAddingFile', fileItem);
};
uploader.onAfterAddingAll = function(addedFileItems) {
    // console.info('onAfterAddingAll', addedFileItems);
};
uploader.onBeforeUploadItem = function(item) {
    // console.info('onBeforeUploadItem', item);
};
uploader.onProgressItem = function(fileItem, progress) {
    // console.info('onProgressItem', fileItem, progress);
};
uploader.onProgressAll = function(progress) {
    // console.info('onProgressAll', progress);
};
uploader.onSuccessItem = function(fileItem, response, status, headers) {
    // console.info('onSuccessItem', fileItem, response, status, headers);
};
uploader.onErrorItem = function(fileItem, response, status, headers) {

    switch(status) {
        case 420:
            Notification.warning('Файл с таким именем уже загружен');
            break;
    }

};
uploader.onCancelItem = function(fileItem, response, status, headers) {
    // console.info('onCancelItem', fileItem, response, status, headers);
};
uploader.onCompleteItem = function(fileItem, response, status, headers) {
    // console.info('onCompleteItem', fileItem, response, status, headers);
};
uploader.onCompleteAll = function() {
    // console.info('onCompleteAll');
};

$scope.init = {
    'count': 15,
    'page': 1,
    'sortBy': 'dt',
    'sortOrder': 'DESC',
    'filterBase': 1 // set false to disable
};

$scope.filterBy = {};

$scope.reloadCallback = function () {
    $scope.filterBy = {
        'id':  $scope.filterList.id,
        'fio': $scope.filterList.fio,
        'tip_statement': $scope.filterList.tip_statement,
        'user_o': $scope.filterList.user_o,
        'form_statement': $scope.filterList.form_statement,
        'theme_statement': $scope.filterList.theme_statement
    };
};

$scope.search = function () {
    $scope.reloadCallback();
};

$scope.getResource = function (params, paramsObj) {
    return $scope.promisesFiles = Stmt.files({}, paramsObj).$promise.then(function(res){
        return {
            'rows': res.data.rows,
            "header": [
                {
                    "key": "id",
                    "name": "Номер"
                },
                {
                    "key": "file_name",
                    "name": "Название"
                },
                {
                    "key": "dt",
                    "name": "Дата"
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