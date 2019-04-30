'use strict';
var ctrl = '/operator-first';
var myApp = angular.module('operatorFirstLevel',
    ['ngRoute', 'ngAnimate', 'ngTable', 'ngFlash', 'ngCookies', 'ngSanitize', 'angularSpinner', 'mgcrea.ngStrap', 'ui.mask',
        'ui.select', 'xeditable']);

myApp.config(function($datepickerProvider, $httpProvider, usSpinnerConfigProvider) {
    usSpinnerConfigProvider.setTheme('bigBlue', {color: 'blue', radius: 20});

    $httpProvider.interceptors.push('HttpInterceptor');
    angular.extend($datepickerProvider.defaults, {
        startWeek: 1
    });
});

angular.module('operatorFirstLevel').config(appconfig);
appconfig.$inject = ['$httpProvider'];

function appconfig($httpProvider){
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
}

myApp.config(['$routeProvider', '$httpProvider',
    function($routeProvider, $httpProvider) {
        $routeProvider.
        when('/', {
            templateUrl: '/partials/first/index.html',
        })
        .when('/index', {
            templateUrl: '/partials/first/index.html',
        })
        .when('/newTask', {
            templateUrl: '/partials/first/newTask.html',
        })
        .when('/stmt', {
            templateUrl: '/partials/first/stmt.html',
        })
        .when('/create', {
            templateUrl: '/partials/first/create/create.html',
        })
        .when('/complete', {
            templateUrl: '/partials/first/create/complete.html',
        })
        .when('/edit', {
            templateUrl: '/partials/first/edit.html',
        });
    }
]);

myApp.run(function($cookies, $http){
    if($cookies.get('sipRegister'))
    {
        $http.post(ctrl+'/get-sip-account')
            .then(function(response) {
                sipRegister(response.data);
            }, function(response) {
                console.log(response);
            });
    }
    
    if(oSipSessionCall){
        console.log('Идет Вызов');
    }
});

myApp.factory('HttpInterceptor', ['$q', 'usSpinnerService', function($q, usSpinnerService) {
    return {
        'request': function (config) {
            usSpinnerService.spin('spinner');
            return config || $q.when(config);
        },
        'requestError': function (rejection) {
            usSpinnerService.stop('spinner');
            return $q.reject(rejection);
        },
        'response': function (response) {
            usSpinnerService.stop('spinner');
            return response || $q.when(response);
        },
        'responseError': function (rejection) {
            usSpinnerService.stop('spinner');
            return $q.reject(rejection);
        }
    };
}]);

/*input только числовые значения*/
myApp.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});

/* Соединение с АТС по PJSIP */
myApp.controller('sipRegistrCtrl', function($rootScope, $scope, $http, $cookies, $timeout, $interval) {

    $http.post(ctrl+'/get-sip-account')
    .then(function(response) {
        $scope.posts = response.data;
    }, function(response) {
        console.log('Error - sipRegistrCtrl');
    });

    $scope.sipRegister = function () {
        sipRegister($scope.posts);
        // Setting a cookie
        if(!$cookies.get('sipRegister'))
            $cookies.put('sipRegister', Date.now());
    };

    $scope.sipUnRegister = function () {
        sipUnRegister();
        $cookies.remove('sipRegister');
    };

    $scope.sipPause = function () {
        sipUnRegister();
    };

    $scope.startTime = function () {
        setInterval(function () {
          $scope.$apply(function () {
              $scope.startDate = $cookies.get('sipRegister');
          })
      }, 1000);
    };

    $scope.startTime();

    $scope.init = function () {
        checkDeviceSupport(function() { console.log('init hasMicrophone: ' + hasMicrophone) });
    };

    $scope.micro = true;
    $scope.getMessage = function() {
        setTimeout(function () {
            setInterval(function() {
                $scope.$apply(function() {
                    //wrapped this within $apply
                    checkDeviceSupport(function() {});
                    $scope.micro = hasMicrophone;
                });
            }, 1000);
        }, 3000);
    };
    $scope.getMessage();
});

myApp.controller('sipNewTaskCtrl', function($scope, $http){

    $scope.sipChannelGetID = function() {
        $http.post(ctrl+'/getchannel')
            .then(function(response) {
               /* */
                console.log(response);
            }, function(response) {
                console.log('Error - sipNewTaskCtrl');
            });
    }
});

/* Открытие РКК */
myApp.controller('newTaskDataCtrl', function($scope, $rootScope, $http, $timeout, $alert, $location, $q) {

   // $scope.loading = true;
    // Service usage
    var myAlert = $alert({
        title: 'Обращение сохраненно!',
        content: '',
        placement: 'top',
        type: 'success',
        duration: '5',
        keyboard: true,
        show: false});

    $scope.GetSip = function() {
        $http.post(ctrl+'/getchannel')
            .then(function(response) {
                if(response.status != 200)
                {
                    $scope.task.channel_id = '';
                    $scope.sipError = 'Нет активного соединения';
                    return $q.reject("Error, server returned null");
                }
                $timeout(function() {
                    console.log('channel_id: '+ response.data);
                    $scope.task.channel_id = response.data;
                    $scope.task.currentDate = new Date();
                    $scope.loading = false;
                }, 1000);
            }, function() {
                console.log('Error - get ID');
                $scope.task.channel_id = '';
                $scope.sipError = 'Ошибка соединения с БД';
            });
    };

    $scope.states = [{
        "id": "1",
        "description": "Первичное",
        "name": "Первичное"},{
        "id": "2",
        "description": "Вторичное",
        "name": "Вторичное"
    }];

    $scope.task = {
        statement:"Web-приложение",
        stage_statement: 1
    };

    $scope.sRemoteNumber = '';

    if(oSipSessionCall)
    {
        $scope.sRemoteNumber = oSipSessionCall.getRemoteFriendlyName();
    }

    $scope.task.defer = {
        phone: $scope.sRemoteNumber
    };

    /* Новое обращение (запись в бд) */
    $scope.save = function(data) {
        $http.post(ctrl+'/settask', data)
            .then(function(response) {
                myAlert.show(); // or myAlert.$promise.then(myAlert.show) if you use an external html template
                $location.path("/");
            }, function(response) {
                console.log('Error - save');
            });
    };

    /* Сохранение отсроченного ответа */
    $scope.deferred = function(data) {
        $http.post(ctrl+'/settask', data)
            .then(function(response) {
                 myAlert.show(); // or myAlert.$promise.then(myAlert.show) if you use an external html template
                 $location.path("/");
            }, function(response) {
                 console.log('Error - deferred');
            });
    };

    /* Очистить тип обращения */
    $scope.clear = function () {
        $scope.task.theme_statement = "";
        $scope.task.theme_statement_description = null;
        $scope.script = "";
        $scope.answered = "";
    };

    $scope.send = function (task) {
        task.transfer = true;
        $scope.save(task);
        $timeout(sipTransfers(task.send_user), 3000);
    };

    /* Очистить перс. данные */
    $scope.clearPers = function () {
      $scope.task.f_name = "";
      $scope.task.l_name = "";
      $scope.task.name = "";
      $scope.task.date = "";
    };

    $scope.singleDemo = {};

    $scope.singleDemo.color = '';

    $scope.address = {};
    $scope.refreshStmt = function(data) {
        return $http.post(ctrl+'/getliststatement', data).then(function(response) {
            $scope.lists = response.data;
        });
    };

    $scope.getStmt = function(score){

        $http.post(ctrl+'/getlistanswerscript', score)
            .then(function(response){
                $scope.answers = response.data.current;
                $scope.allAnswers = response.data.all;
            }, function(error) {
                console.log('Error :: setupdatetask');
            });
            $scope.answered = score;
    };

    $scope.changeStmt = function () {
    };

    /* tabs */
    $scope.tabs = [
        {id:0, title:'Сценарий ответа', template: '../../partials/first/tpl/tabs/personal.html'},
        {id:1, title:'Личная информация', template: '../../partials/first/tpl/tabs/deferred.html'},
        {id:2, title:'История обращений', template: '../../partials/first/tpl/tabs/second.html'},
        {id:3, title:'Перевести', template: '../../partials/first/tpl/tabs/transfer.html'}
    ];

    $scope.tabs.activeTab = 0;

    $scope.renderTab = function (num) {
        $scope.tabs.activeTab = 3;
        $scope.task.send_user = num;
    };

    $scope.changeOkato = function () {
        $scope.task.defer.okato_erz = false;
    };

    $scope.address = {};
    $scope.refreshAddresses = function(address) {
        var params = {address: address};

        return $http.post(
            ctrl+'/getlistkladr',
            params
        ).then(function(response) {
            $scope.addresses = response.data
        });
    };

    /*История обращений*/
    $scope.getHistory = function (task) {
        $scope.history = task;
        $scope.tabs.activeTab = 2;
    };

});

/* Запрос данных в ЕРЗ */
myApp.controller('findERZCtrl', function($scope, $http, $rootScope) {

    var vm = this;
    /* Запрос на поиск по реестру застрахованных */
    vm.submitForm = function() {
        vm.loading = true;
        vm.erzData = '';

        $http.post(ctrl+'/getfinderz', vm.erz)
            .then(function(response) {
                vm.erzData = response.data;
                vm.loading = false;
                console.log(response);
            }, function(error) {
                console.log('Error erz');
            });
    };

    /* Очистить данные */
    vm.clearERZ = function () {
        vm.erz = {};
        vm.erzData = '';
    };

    this.dataERZ = function (data) {
        this.setERZ = data;
        console.log(data);
    };

    $scope.setERZ = function (data) {
        $rootScope.erz = data;

    this.data = [];

    this.data.defer = {
        fam: data.sName,
        im: data.Name,
        ot: data.pName,
        dt: $scope.formatDate(data.dateMan),
        id: data.id,
        phone: $scope.sRemoteNumber,
        enp: data.ENP,
        okato: data.okato_reg,
        okato_name: data.pbMan,
        okato_erz: true
    };

    $http.post(ctrl+'/gethistory', data)
        .then(function(response) {
            console.log(':::History:::');
            console.log(response.data);
            $rootScope.historyStmt = response.data;
         //   $scope.tabs.activeTab = 1;
        }, function(error) {
            console.log('Error History');
        });
    };

    vm.formatDate = function(date){
        var d = date;
        var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2));
        return dateOut;
    };
});

/* Таблица на главной странице */
myApp.controller("tableCtrl", function($scope, $http, $modal, $window, $filter,  NgTableParams){

    $scope.usersTable = new NgTableParams({
        page: 1,
        count: 15
    }, {
        counts: [],
        getData: function ($defer, params) {
            $http.post(ctrl+'/getstatement')
                .then(function(res) {
                        // params.total(res.data.length);
                        // $scope.data = res.data.slice((params.page() - 1) * params.count(), params.page() * params.count());
                        // $defer.resolve($scope.data);
                        var filterData = params.filter() ? $filter('filter')(res.data, params.filter()) : res.data;
                        var orderedData = params.sorting() ? $filter('orderBy')(filterData, params.orderBy()) : filterData;
                        var data = orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count());

                        params.total(orderedData.length);
                        $defer.resolve(data);

                    }, function(reason) {
                        $defer.reject();
                    }
                );
        }
    });

    this.tips = [
        {id: "", title: "Все обращения"},
        {id: '1', title: 'Жалоба'},
        {id: '2', title: 'Консультация'},
        {id: '3', title: 'Заявление'},
        {id: '4', title: 'Предложение'}
    ];

    /* Регистрационная карточка */
    $scope.invoke = function(val){

        $http.post(ctrl+'/getistask', val)
            .then(function(response) {
               $window.location.href = '#/complete?id='+response.data.id;
            }, function(error) {
                console.log('Error invoke');
                $window.location.href = '#/index';
            });
    };
    

    /* Просмотр завершенного обращения */
    $scope.view = function(val){
        var data =  val.data;
        $window.location.href = '#/view?id=2';
        console.log('view');
    };

    $scope.formatDate = function(date){
        var d = date;
        var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), d.substr(11, 2), d.substr(14, 2), d.substr(17, 2));
        return dateOut;
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
                return fam +" "+ im +" "+ ot;
            }
        }

        return "<code>анонимно</code>";
    };
});

/* Редактирование/закрытие обращения */
myApp.controller("editTaskCtrl", function($scope, $location, $window, $http, $alert, $timeout){

    $scope.loading = true;

    $http.post(ctrl+'/getstmtaction')
        .then(function(response){
            $scope.state_result = response.data;
        },
        function(error) {
            console.log('Error select actions');
        });

    var closeAlert = $alert({
        title: 'Обращение закрыто!',
        content: '',
        placement: 'top',
        type: 'info',
        duration: '5',
        keyboard: true,
        show: false});

    $scope.init = function(){
        var id = {"id": $location.search().id};

       $http.post(ctrl+'/getistask', id)
            .then(function(response){

                if(response.data == false){
                    $window.location.href = '#/';
                }

                $scope.task = response.data;
                $scope.task.expired = new Date(response.data.expired);
                $scope.task.statement_date = new Date(response.data.statement_date);

                $scope.task.saveDeffered = 0;

                $scope.loading = false;
                console.log(response.data);

            }, function(error) {
                console.log('Error init');
            });
    };

    $scope.showModalParams = function (data) {
        console.log('showModalParams');
        console.log(data);
        $scope.modal = {title: 'Информация об обращении', content: data};
    };

    /* закрытие обращения */
    $scope.update = function(task){
        $http.post(ctrl+'/setupdatetask', task)
            .then(function(response){
               closeAlert.show(); // or myAlert.$promise.then(myAlert.show) if you use an external html template
               $location.path("/");
            }, function(error) {
                console.log('Error :: setupdatetask');
            });
    };

    /* Перевести обращение */
    $scope.transfer = function(task){
        /* Если установленно соединение, то идет переадресация */
        if(oSipSessionCall)
        {
            console.log('TRANSFER STMT');
            /* Переадресация и новая запсь в StmtCall */
            $http.post(ctrl+'/setdelayedtransfer', task)
                .then(function(response){
                    if(response.data != false)
                    {
                        $timeout(sipTransfers(response.data.transfer.sip_private_identity), 3000);
                        $location.path("/");
                    }
                }, function(error) {
                    console.log('Error :: setdelayedresponse');
                });

        }
        /* Если нет соединения, то «Отсроченный ответ» */
        else{
            /* выбираем «Исполнителя»  и изменяю статус на «Отсроченный ответ» */
            $http.post(ctrl+'/setdelayed', task)
                .then(function(response){
                    $location.path("/");
                }, function(error) {
                    console.log('Error :: setdelayedresponse');
                });
        }
    };

    /* Ответственный оператор */

    //Все операторы для переадресации звонка
    $scope.refreshUsers = function(){
        return $http.post(ctrl+'/gettransferusers')
            .then(function(response){
                console.log('Transfer users');
                $scope.listUsers = response.data

            }, function(error) {
                console.log('Error :: gettransferusers');
            });
    };

    // Шаблон выпадающего списка операторов 1-го и 2-го уровня
    $scope.userHtml = function (item) {
        return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
            + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
            "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
    };

    /* Звонок - Отсроченный ответ */
    $scope.callDefferred = function (number) {
      sipCallDeffered('call-audio', number);
    };

    $scope.saveDeffered = function (task) {
        $http.post(ctrl+'/savedefferd', task)
            .then(function(response){
                $location.path("/");
            }, function(error) {
                console.log('Error savedefferd');
            });
    };


    /* Редактирование */

    //Тип обращения
    $scope.tipStmt =  [
        {value: "1", text: 'Жалоба'},
        {value: "2", text: 'Консультация'},
        {value: "3", text: 'Заявление'},
        {value: "4", text: 'Предложение'}
    ];

    $scope.refreshStmt = function(data) {
        return $http.post(ctrl+'/getliststatement', data).then(function(response) {
            $scope.lists = response.data;
        });
    };

    /* Очистить тип обращения */
    $scope.clear = function () {
       $scope.task.theme.theme_statement = '';
       $scope.task.theme = undefined;
       $scope.task.theme_statement = undefined;
        console.log($scope.task);
    };

    // Обновление данных;
    $scope.saveEditStmt = function(task) {
        // $scope.user already updated!
        console.log('saveEditStmt');
        console.log(task);
    };
});

/* Телефонный справочник */
myApp.controller("phonebookCtrl", function($scope, $http, $filter, ngTableParams){

    $scope.searchPhone = '';

    $scope.phoneBook = new ngTableParams({
        page: 1,
        count: 50,
    }, {
        counts: [],
        getData: function ($defer, params) {
            $http.post(ctrl+'/getlistphonebook')
                .then(function(res) {
                        console.log('phonebook');
                        $scope.result = res.data;
                        // params.total(res.data.length);
                        // $scope.data = res.data.slice((params.page() - 1) * params.count(), params.page() * params.count());
                        // $defer.resolve($scope.data);
                    }, function(reason) {
                        $defer.reject();
                    }
                );
        },
    });
});

/* Завершенное обращение */
myApp.controller("viewTaskCtrl", function ($scope, $http, $location) {
    init();

    $scope.loading = true;
    function init(){
        var id = {"id": $location.search().id};

        $http.post(ctrl+'/getviewtask', id)
            .then(function(response){
                if(response.data == false){
                    $window.location.href = '#/';
                }
                $scope.task = response.data;
                $scope.loading = false;
            }, function(error) {
                console.log('Error init');
            });
    };
    
    

});

myApp.controller('RKKCtrl', function ($scope, $http, $interval) {

    $scope.getID = function() {

        if(oSipSessionCall)
            console.log('oSipSessionCall' + oSipSessionCall);

        var id = {"sip": oSipSessionCall.getRemoteUri()};
        RKK.style.display="block";
        {
            $http.post(ctrl+'/getchnl', id)
                .then(function(response) {
                    $scope.name = response.data;
                    $scope.custom = true;
                }, function(response) {
                    console.log('Error - get ID');
                });
        }
    };

    $scope.getMessage = function() {
        $interval( function(){
            if(oSipSessionCall)
            {
                $scope.getID();
            }
        }, 2000, false);
    };

    $scope.getMessage();
});

myApp.controller('createStmt', function ($http, $timeout, $alert, $q, $window, $interval ) {

    var vm = this;

    var save = $alert({
        title: 'Обращение сохранено!',
        content: '',
        placement: 'bottom-right',
        type: 'success',
        duration: '5',
        keyboard: true,
        show: false});

    var error = $alert({
        title: 'Ошибка при сохранении!',
        content: '',
        placement: 'bottom-right',
        type: 'error',
        duration: '5',
        keyboard: true,
        show: false});

    vm.task = {
        statement:2,
        stage_statement: 1,
        form_statement:1
    };

    vm.getSip = function () {
        $http.post(ctrl+'/getchannel')
            .then(function(response) {
                $timeout(function() {
                    if(response.data)
                    {
                        vm.task.channel_id = response.data;
                    }
                    console.log(vm.task.channel_id);
                    vm.task.currentDate = new Date();
                    vm.loading = false;
                }, 1000);
            }, function() {
                console.log('Error - get ID');
                vm.task.channel_id = '';
                vm.sipError = 'Ошибка соединения с БД';
            });
    };

    vm.getChannelID = function() {
        $interval( function(){
            vm.getSip();
        }, 2000, false);
    };

    vm.getChannelID();

    vm.getStmt = function(score){
        $http.post(ctrl+'/getlistanswerscript', score)
            .then(function(response){
                vm.answers = response.data.current;
                vm.allAnswers = response.data.all;
            }, function(error) {
                console.log('Error :: setupdatetask');
            });
        vm.answered = score;
    };

    vm.refreshStmt = function(data) {
        return $http.post(ctrl+'/getliststatement', data).then(function(response) {
            vm.lists = response.data;
        });
    };

    // /* Очистить тип обращения */
    vm.clear = function () {
        vm.task.theme_statement = '';
        vm.script = "";
        vm.answered = "";
    };

    vm.saveStmt = function (task) {
        $http.post(ctrl+'/settask', task)
            .then(function(response) {
                save.show();
                vm.stmtID = response.data;
            }, function(response) {
                console.log('Error - save');
                error.show();
            });
    };

    vm.continueStmt = function (id) {
        $window.location.href = '#/complete?id='+id;

    };

});

myApp.controller('completeCtrl', function ($http, $alert, $location, $window, stmtList) {

    var vm = this;

    var save = $alert({
        title: 'Обращение сохранено!',
        content: '',
        placement: 'bottom-right',
        type: 'success',
        duration: '5',
        keyboard: true,
        show: false});

    var transfer = $alert({
        title: 'Обращение переадресованно!',
        content: '',
        placement: 'bottom-right',
        type: 'success',
        duration: '5',
        keyboard: true,
        show: false});

    /* Запрос на поиск по реестру застрахованных */
    vm.submitForm = function() {
        vm.loading = true;
        vm.erzData = '';

        $http.post(ctrl+'/getfinderz', vm.erz)
            .then(function(response) {
                vm.erzData = response.data;
                vm.loading = false;
                console.log(response);
            }, function(error) {
                console.log('Error erz');
            });
    };

    /* Очистить данные */
    vm.clearERZ = function () {
        vm.erz = {};
        vm.erzData = '';
    };

    vm.setERZ = function (data) {
        this.task.deffered = {
            fam: data.sName,
            im: data.Name,
            ot: data.pName,
            dt: vm.formatDate(data.dateMan),
            id: data.id,
         //   phone: $scope.sRemoteNumber,
            enp: data.ENP,
            okato: data.okato_reg,
            okato_name: data.pbMan,
            okato_erz: true
        };

        $http.post(ctrl+'/gethistory', data)
            .then(function(response) {
                console.log(':::History:::');
                console.log(response.data);
                vm.historyStmt = response.data;
                //   $scope.tabs.activeTab = 1;
            }, function(error) {
                console.log('Error History');
            });
    };

    vm.formatDate = function(date){
        var d = date;
        var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2));
        return dateOut;
    };

    
    /* Список тем обращений */
    vm.refreshStmt = function(tip) {
        stmtList.getList(tip).success(function(data){
            vm.lists = data;
        });
    };

    // /* Очистить тип обращения */
    vm.clear = function () {
        vm.task.theme_statement = '';
    };

    /* инициализация обращения */
    vm.init = function(){
        var id = {"id": $location.search().id};
        vm.load_page = true;
        $http.post(ctrl+'/getistask', id)
            .then(function(response){

                if(response.data == false){
                    $window.location.href = '#/';
                }

                vm.task = response.data;
                vm.task.expired = new Date(response.data.expired);
                vm.task.statement_date = new Date(response.data.statement_date);
                vm.task.saveDeffered = 0;
                vm.refreshStmt(vm.task.tip_statement);
                vm.load_page = false;

            }, function(error) {
                console.log('Error init');
                $window.location.href = '#/';
            });
    };

    $http.post(ctrl+'/getstmtaction')
        .then(function(response){
                vm.state_result = response.data;
            },
            function(error) {
                console.log('Error select actions');
            });

    /* Ответственный оператор */
    //Все операторы для переадресации звонка
    vm.refreshUsers = function(){
        return $http.post(ctrl+'/gettransferusers')
            .then(function(response){
                console.log('Transfer users');
                vm.listUsers = response.data

            }, function(error) {
                console.log('Error :: gettransferusers');
            });
    };

    // Шаблон выпадающего списка операторов 1-го и 2-го уровня
    vm.userHtml = function (item) {
        return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
            + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
            "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
    };

    /* закрытие обращения */
    vm.update = function(task){
        $http.post(ctrl+'/completestmt', task)
            .then(function(response){
                console.log(response);
                save.show();
                $location.path("/");
            }, function(error) {
                console.log('Error :: setupdatetask');
            });
    };

    /* Перевести обращение */
    vm.transfer = function(task){
        /* Если установленно соединение, то идет переадресация */
        if(oSipSessionCall)
        {
            console.log('TRANSFER STMT');
            /* Переадресация и новая запсь в StmtCall */
            $http.post(ctrl+'/setdelayedtransfer', task)
                .then(function(response){
                    if(response.data != false)
                    {
                        $timeout(sipTransfers(response.data.transfer.sip_private_identity), 3000);
                        transfer.show();
                        $location.path("/");
                    }
                }, function(error) {
                    console.log('Error :: setdelayedresponse');
                });

        }
        /* Если нет соединения, то «Отсроченный ответ» */
        else{
            /* выбираем «Исполнителя»  и изменяю статус на «Отсроченный ответ» */
            $http.post(ctrl+'/setdelayed', task)
                .then(function(response){
                    transfer.show();
                    $location.path("/");
                }, function(error) {
                    console.log('Error :: setdelayedresponse');
                });
        }
    };
});

myApp.factory('stmtList', function($http) {
    return {
        getList : function(data){
            var list = $http.post(ctrl+'/getliststatement', data).success(function(res){
                return res.data;
            });
            return list;
        },
        saveStmt : function (data) {
          return  $http.post(ctrl+'/settask', data).success(function (res) {
                return res.data;
            });
        }
    }
});