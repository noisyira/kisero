'use strict';
var ctrl = '/operator-second';
var myApp = angular.module('operatorSecondLevel', []);

// myApp.config(function($datepickerProvider, $httpProvider, usSpinnerConfigProvider) {
//     usSpinnerConfigProvider.setTheme('bigBlue', {color: 'blue', radius: 20});
//
//     $httpProvider.interceptors.push('HttpInterceptor');
//     angular.extend($datepickerProvider.defaults, {
//         startWeek: 1
//     });
// });

// angular.module('operatorSecondLevel').config(appconfig);
// appconfig.$inject = ['$httpProvider'];
//
// function appconfig($httpProvider){
//     $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//     $httpProvider.defaults.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
// }

// myApp.config(['$routeProvider', '$httpProvider',
//     function($routeProvider, $httpProvider) {
//         $routeProvider.
//         when('/', {
//             templateUrl: '/partials/second/index.html',
//         })
//         .when('/index', {
//             templateUrl: '/partials/second/index.html',
//         })
//         .when('/newTask', {
//             templateUrl: '/partials/second/newTask.html',
//         })
//         .when('/create', {
//             templateUrl: '/partials/second/create.html',
//         })
//         .when('/stmt', {
//             templateUrl: '/partials/second/stmt.html',
//         })
//         .when('/update', {
//             templateUrl: '/partials/second/update.html',
//         })
//         .when('/report', {
//             templateUrl: '/partials/second/report/report.html',
//         })
//         .when('/edit', {
//             templateUrl: '/partials/second/edit.html',
//         });
//     }
// ]);

// myApp.run(function($cookies, $http){
//     if($cookies.get('sipRegister'))
//     {
//         $http.post(ctrl+'/get-sip-account')
//             .then(function(response) {
//                 sipRegister(response.data);
//             }, function(response) {
//                 console.log('Error - SIP ACCOUNT');
//             });
//     }
// });

/* loader */
// myApp.factory('HttpInterceptor', ['$q', 'usSpinnerService', function($q, usSpinnerService) {
//     return {
//         'request': function (config) {
//             usSpinnerService.spin('spinner');
//             return config || $q.when(config);
//         },
//         'requestError': function (rejection) {
//             usSpinnerService.stop('spinner');
//             return $q.reject(rejection);
//         },
//         'response': function (response) {
//             usSpinnerService.stop('spinner');
//             return response || $q.when(response);
//         },
//         'responseError': function (rejection) {
//             usSpinnerService.stop('spinner');
//             return $q.reject(rejection);
//         }
//     };
// }]);

// myApp.controller('sipRegistrCtrl', function ($scope, $http, $cookies) {
//     $http.post(ctrl+'/get-sip-account')
//     .then(function(response) {
//         $scope.posts = response.data;
//     }, function(response) {
//         console.log('Error - SIP ACCOUNT');
//     });
//
//     $scope.sipRegister = function () {
//         sipRegister($scope.posts);
//         // Setting a cookie
//         if(!$cookies.get('sipRegister'))
//             $cookies.put('sipRegister', Date.now());
//     };
//
//     $scope.sipUnRegister = function () {
//         sipUnRegister();
//         $cookies.remove('sipRegister');
//     };
//
//     $scope.sipPause = function () {
//         sipUnRegister();
//     };
//
//     $scope.startTime = function () {
//         setInterval(function () {
//             $scope.$apply(function () {
//                 $scope.startDate = $cookies.get('sipRegister');
//             })
//         }, 1000);
//     };
//
//     $scope.startTime();
//
//     $scope.init = function () {
//         checkDeviceSupport(function() { console.log('init hasMicrophone: ' + hasMicrophone) });
//     };
//
//     $scope.micro = true;
//     $scope.getMessage = function() {
//         setTimeout(function () {
//             setInterval(function() {
//                 $scope.$apply(function() {
//                     //wrapped this within $apply
//                     checkDeviceSupport(function() {});
//                     $scope.micro = hasMicrophone;
//                 });
//             }, 1000);
//         }, 3000);
//     };
//     $scope.getMessage();
// });


//
// myApp.controller('webCtrl', function ($scope, $http) {
//     $scope.acceptCall = function () {
//         // if(oSipSessionCall){
//         //     var id = {"sip": oSipSessionCall.getRemoteUri()};
//         //     console.log('ПРИНЯТЫЙ ВЫЗОВ:::::::::::::::::::::=>');
//         //     $http.post(ctrl+'/acceptcall', id)
//         //         .then(function(response) {
//         //             $scope.name = response.data;
//         //             console.log('ACCEPT CALL::::=>');
//         //             console.log(response);
//         //         }, function(response) {
//         //             console.log('Error - get ID');
//         //         });
//         // }
//     };
// });
//
// myApp.controller('sipNewTaskCtrl', function($scope, $http){
//
//     $scope.sipChannelGetID = function() {
//         $http.post(ctrl+'/getchannel')
//             .then(function(response) {
//                /* */
//             }, function(response) {
//                 console.log('Error - sipChannelGetID');
//             });
//     }
// });
//
// /* Открытие РКК */
// myApp.controller('newTaskDataCtrl', function($scope, $rootScope, $http, $timeout, $alert, $location, $q) {
//
//     $scope.getID = function() {
//         $http.post(ctrl+'/getchannel')
//             .then(function(response) {
//                 $scope.task.id = response.data;
//             }, function(response) {
//                 console.log('Error - get ID');
//             });
//     };
//
//     $scope.task = {
//         statement:"Web-приложение"
//     };
//
//     /* Новое обращение (запись в бд) */
//     $scope.update = function(data) {
//         $http.post(ctrl+'/settask', data)
//             .then(function(response) {
//                 $scope.task = {
//                     statement:"Web-приложение"
//                 };
//
//             }, function(response) {
//                 console.log('Error - запись в бд');
//             });
//     };
//
//     /* Очистить тип обращения */
//     $scope.clear = function () {
//       $scope.task.theme_statement = "";
//       $scope.task.theme_statement_description = null;
//     };
//
//     /* Очистить перс. данные */
//     $scope.clearPers = function () {
//         $scope.task.f_name = "";
//         $scope.task.l_name = "";
//         $scope.task.name = "";
//         $scope.task.date = "";
//     };
//
//     $scope.singleDemo = {};
//
//     $scope.singleDemo.color = '';
//
//     $scope.address = {};
//     $scope.refreshStmt = function(data) {
//         return $http.post(ctrl+'/getliststatement', data).then(function(response) {
//             console.log(data);
//             $scope.lists = response.data;
//         });
//     };
//
//     $scope.getStmt = function(score){
//         $scope.answered = score;
//     };
//
//     /* tabs */
//     $scope.tabs = [
//         {id:0, title:'Сценарий ответа', template: '../../partials/first/tpl/tabs/personal.html'},
//         {id:1, title:'Личная информация', template: '../../partials/first/tpl/tabs/deferred.html'},
//         {id:2, title:'История обращений', template: '../../partials/first/tpl/tabs/second.html'},
//         {id:3, title:'Перевести на другого специалиста', template: '../../partials/first/tpl/tabs/transfer.html'}
//     ];
//
//     $scope.tabs.activeTab = 0;
//
//     $scope.renderTab = function (num) {
//         $scope.tabs.activeTab = 3;
//         $scope.task.send_user = num;
//     };
//
//     $scope.changeOkato = function () {
//         $scope.task.defer.okato_erz = false;
//     };
//
//     $scope.address = {};
//     $scope.refreshAddresses = function(address) {
//         var params = {address: address};
//         console.log(params);
//         return $http.post(
//             ctrl+'/getlistkladr',
//             params
//         ).then(function(response) {
//             console.log(response.data);
//             $scope.addresses = response.data
//         });
//     };
//
//     /*История обращений*/
//     $scope.getHistory = function (task) {
//         console.log(task);
//         $scope.history = task;
//         $scope.tabs.activeTab = 2;
//     };
//
// });
//
// /* Запрос данных в ЕРЗ */
// myApp.controller('findERZCtrl', function($scope, $http, $rootScope) {
//     /* Запрос на поиск по реестру застрахованных */
//     $scope.submitForm = function() {
//         $scope.loading = true;
//         $scope.erzData = '';
//
//         $http.post(ctrl+'/getfinderz', $scope.erz)
//             .then(function(response) {
//                 $scope.erzData = response.data;
//                 $scope.loading = false;
//                 console.log(response);
//             }, function(error) {
//                 console.log('Error erz');
//
//             });
//     };
//
//     /* Очистить данные */
//     $scope.clearERZ = function () {
//         $scope.erz = {};
//         $scope.erzData = '';
//     };
//
//     $scope.setERZ = function (data) {
//         $rootScope.erz = data;
//
//         $scope.task.defer = {
//             fam: data.sName,
//             im: data.Name,
//             ot: data.pName,
//             dt: $scope.formatDate(data.dateMan),
//             id: data.id,
//             enp: data.ENP,
//             okato: data.okato_reg,
//             okato_name: data.pbMan,
//             okato_erz: true
//         };
//
//         $http.post(ctrl+'/gethistory', data)
//             .then(function(response) {
//                 console.log(':::History:::');
//                 console.log(response.data);
//                 $rootScope.historyStmt = response.data;
//                 $scope.tabs.activeTab = 1;
//             }, function(error) {
//                 console.log('Error History');
//             });
//     };
//
//     $scope.formatDate = function(date){
//         var d = date;
//         var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2));
//         return dateOut;
//     };
// });
//
// /* таблица на главной странице */
// myApp.controller("tableCtrl", function($scope, $http, $modal, $window, $filter,  NgTableParams){
//
//     var vm = this;
//
//     $scope.usersTable = new NgTableParams({
//         page: 1,
//         count: 15
//     }, {
//         counts: [],
//         getData: function ($defer, params) {
//             $http.post(ctrl+'/getstatement')
//                 .then(function(res) {
//
//                     var filterData = params.filter() ? $filter('filter')(res.data, params.filter()) : res.data;
//                     var orderedData = params.sorting() ? $filter('orderBy')(filterData, params.orderBy()) : filterData;
//                     var data = orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
//
//                     params.total(orderedData.length);
//                     $defer.resolve(data);
//                     }, function(reason) {
//                         $defer.reject();
//                     }
//                 );
//         }
//     });
//
//     this.tips = [
//         {id: "", title: "Все обращения"},
//         {id: '1', title: 'Жалоба'},
//         {id: '2', title: 'Консультация'},
//         {id: '3', title: 'Заявление'},
//         {id: '4', title: 'Предложение'}
//     ];
//
//     /* Ответственный оператор */
//     //Все операторы для переадресации звонка
//     vm.refreshUsers = function(){
//         return $http.post(ctrl+'/filteruserlist')
//             .then(function(response){
//                 vm.listUsers = response.data;
//             }, function(error) {
//                 console.log('Error :: filteruserlist');
//             });
//     };
//
//     // Шаблон выпадающего списка операторов 1-го и 2-го уровня
//     vm.userHtml = function (item) {
//         return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
//             + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
//             "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
//     };
//
//     vm.refreshStmt = function() {
//         return $http.post(ctrl+'/getliststmt').then(function(response) {
//             vm.lists = response.data;
//         });
//     };
//
//     vm.clearFilters = function () {
//         $scope.usersTable.filter({});
//
//     };
//
//     $scope.status = false;
//
//     /* Регистрационная карточка */
//     $scope.invoke = function(val){
//         var data =  val;
//         $http.post(ctrl+'/getistask', data)
//             .then(function(response) {
//                 $window.location.href = '#/update?id='+response.data.id;
//             }, function(error) {
//                 console.log('Error invoke');
//                 $window.location.href = '#/index';
//             });
//     };
//
//     /* Просмотр завершенного обращения */
//     $scope.view = function(val){
//         var data =  val.data;
//         $window.location.href = '#/view?id=2';
//         console.log('view');
//     };
//
//     $scope.formatDate = function(date){
//         var d = date;
//         var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2), d.substr(11, 2), d.substr(14, 2), d.substr(17, 2));
//         return dateOut;
//     };
//
//     // Шаблон вывода ФИО заявителя
//     $scope.fioStmt = function (item) {
//         if(item)
//         {
//             var fam = item.fam ? item.fam : " ";
//             var im = item.im ? item.im : " ";
//             var ot = item.ot ? item.ot : " ";
//             if(item && (item.fam != null) && (item.im != null))
//             {
//                 return fam +" "+ im +" "+ ot;
//             }
//         }
//
//         return "<code>анонимно</code>";
//     };
// });
//
// /* редактирование/закрытие обращения */
// myApp.controller("editTaskCtrl", function($scope, $location, $window, $http, $alert, $timeout){
//    // init();
//     $scope.loading = true;
//
//     $http.post(ctrl+'/getstmtaction').then(function(response){
//                 $scope.state_result = response.data;
//             },
//             function(error) {
//                 console.log('Error select actions');
//             });
//
//     var closeAlert = $alert({
//         title: 'Обращение закрыто!',
//         content: '',
//         placement: 'top',
//         type: 'info',
//         duration: '5',
//         keyboard: true,
//         show: false});
//
//     $scope.init = function(){
//         var id = {"id": $location.search().id};
//
//         $http.post(ctrl+'/getistask', id).then(function(response){
//
//                 if(response.data == false){
//                     $window.location.href = '#/';
//                 }
//                 $scope.task = response.data;
//
//                 $scope.loading = false;
//
//                 $scope.files = {
//                     id: $scope.task.id,
//                     number:  angular.equals(0, $scope.task.attachment.length)?'':$scope.task.attachment[0].n_attach,
//                     date:  angular.equals(0, $scope.task.attachment.length)?'':$scope.task.attachment[0].date_attach
//                 };
//
//             }, function(error) {
//                 console.log('Error init');
//             });
//     };
//
//     $scope.showModalParams = function (data) {
//         console.log('showModalParams');
//         console.log(data);
//         $scope.modal = {title: 'Информация об обращении', content: data};
//     };
//
//     /* закрытие обращения */
//     $scope.update = function(task){
//         $http.post(ctrl+'/setupdatetask', task)
//             .then(function(response){
//                 closeAlert.show(); // or myAlert.$promise.then(myAlert.show) if you use an external html template
//                 $location.path("/");
//             }, function(error) {
//                 console.log('Error :: setupdatetask');
//             });
//     };
//
//     /* Перевести обращение */
//     $scope.transfer = function(task){
//         /* Если установленно соединение, то идет переадресация */
//         if(oSipSessionCall)
//         {
//             console.log('TRANSFER STMT');
//             /* Переадресация и новая запсь в StmtCall */
//             $http.post(ctrl+'/setdelayedtransfer', task)
//                 .then(function(response){
//                     console.log(response);
//                     if(response.data != false)
//                     {
//                         $timeout(sipTransfers(response.data.transfer.sip_private_identity), 3000);
//                         $location.path("/");
//                     }
//                 }, function(error) {
//                     console.log('Error :: setdelayedresponse');
//                 });
//
//         }
//         /* Если нет соединения, то «Отсроченный ответ» */
//         else{
//             /* выбираем «Исполнителя»  и изменяю статус на «Отсроченный ответ» */
//             $http.post(ctrl+'/setdelayed', task)
//                 .then(function(response){
//                     console.log(response);
//                 }, function(error) {
//                     console.log('Error :: setdelayedresponse');
//                 });
//         }
//     };
//
//     /* Ответственный оператор */
//     //Все операторы для переадресации звонка
//     $scope.refreshUsers = function(){
//         return $http.post(ctrl+'/gettransferusers')
//             .then(function(response){
//                 $scope.listUsers = response.data
//             }, function(error) {
//                 console.log('Error :: gettransferusers');
//             });
//     };
//
//     // Шаблон выпадающего списка операторов 1-го и 2-го уровня
//     $scope.userHtml = function (item) {
//         return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
//             + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
//             "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
//     };
// });
//
// myApp.controller('RKKCtrl', function ($scope, $http, $interval) {
//
//     $scope.getID = function() {
//
//     if(oSipSessionCall)
//         var id = "sip";
//         RKK.style.display="block";
//         {
//             $http.post(ctrl+'/getchnl', id)
//                 .then(function(response) {
//                     $scope.name = response.data;
//                     $scope.custom = true;
//                 }, function(response) {
//                     console.log('Error - get ID => getchnl');
//                 });
//         }
//     };
//
//     $scope.getMessage = function() {
//         $interval( function(){
//             if(oSipSessionCall)
//             {
//                 $scope.getID();
//             }
//         }, 2000, false);
//     };
//
//     $scope.getMessage();
// });
//
// /* Телефонный справочник */
// myApp.controller("phonebookCtrl", function($scope, $http, $filter, ngTableParams){
//
//     $scope.searchPhone = '';
//
//     $scope.phoneBook = new ngTableParams({
//         page: 1,
//         count: 50,
//     }, {
//         counts: [],
//         getData: function ($defer, params) {
//             $http.post(ctrl+'/getlistphonebook')
//                 .then(function(res) {
//                         $scope.result = res.data;
//                         // params.total(res.data.length);
//                         // $scope.data = res.data.slice((params.page() - 1) * params.count(), params.page() * params.count());
//                         // $defer.resolve($scope.data);
//                     }, function(reason) {
//                         $defer.reject();
//                     }
//                 );
//         },
//     });
// });
//
// /* Просмотр обращения*/
// myApp.controller("viewTaskCtrl", function ($scope, $http, $window, $location) {
//     init();
//
//     $scope.loading = true;
//     function init(){
//         var id = {"id": $location.search().id};
//
//         $http.post(ctrl+'/getviewtask', id)
//             .then(function(response){
//                 if(response.data == false){
//                     $window.location.href = '#/';
//                 }
//                 $scope.task = response.data;
//                 $scope.loading = false;
//             }, function(error) {
//                 console.log('Error init');
//             });
//     };
//
//     $scope.returnStmt = function (task) {
//         $http.post(ctrl+'/returnstmt', task)
//             .then(function(response){
//                 $location.path("/");
//             }, function(error) {
//                 console.log('Error returnstmt');
//             });
//     };
//
//     $scope.removeStmt = function (task) {
//         console.log('removestmt');
//         $http.post(ctrl+'/removestmt', task)
//             .then(function(response){
//                 $location.path("/");
//             }, function(error) {
//                 console.log('Error removestmt');
//             });
//     };
//
//
// });
//
// /* Создание РКК */
// myApp.controller('createTaskDataCtrl', function($scope, $rootScope, $http) {
//
//     $scope.task = {
//         form:0
//     };
//
//     /* Новое обращение (запись в бд) */
//     $scope.update = function(data) {
//         $http.post(ctrl+'/settask', data)
//             .then(function(response) {
//                 $scope.task = {
//                     statement:"Web-приложение"
//                 };
//             }, function(response) {
//                 console.log('Error - запись в бд');
//             });
//     };
//
//     /* Очистить тип обращения */
//     $scope.clear = function () {
//         $scope.task.theme_statement = "";
//         $scope.task.theme_statement_description = null;
//     };
//
//     /* Очистить перс. данные */
//     $scope.clearPers = function () {
//         $scope.task.f_name = "";
//         $scope.task.l_name = "";
//         $scope.task.name = "";
//         $scope.task.date = "";
//     };
//
//     $scope.address = {};
//     $scope.refreshStmt = function(data) {
//         return $http.post(ctrl+'/getliststatement', data).then(function(response) {
//             console.log(data);
//             $scope.lists = response.data;
//         });
//     };
//
//     $scope.changeOkato = function () {
//         $scope.task.defer.okato_erz = false;
//     };
//
//     $scope.address = {};
//     $scope.refreshAddresses = function(address) {
//         var params = {address: address};
//         console.log(params);
//         return $http.post(
//             ctrl+'/getlistkladr',
//             params
//         ).then(function(response) {
//             console.log(response.data);
//             $scope.addresses = response.data
//         });
//     };
//
//     /* Ответственный оператор */
//     //Все операторы для переадресации звонка
//     $scope.refreshUsers = function(){
//         return $http.post(ctrl+'/gettransferusers')
//             .then(function(response){
//                 $scope.listUsers = response.data;
//             }, function(error) {
//                 console.log('Error :: gettransferusers');
//             });
//     };
//
//     // Шаблон выпадающего списка операторов 1-го и 2-го уровня
//     $scope.userHtml = function (item) {
//         return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
//             + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
//             "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
//     };
//
//     //Все виды обращений
//     $scope.refreshModeStmt = function(){
//         return $http.post(ctrl+'/getmodestmt')
//             .then(function(response){
//                 console.log('Mode List');
//                 console.log(response.data);
//                 $scope.listMode = response.data;
//             }, function(error) {
//                 console.log('Error :: gettransferusers');
//             });
//     };
//
// });
//
// // Загрузка файлов
// myApp.controller('UploadCtrl', function($scope, $rootScope, $http, $alert, $location, FileUploader) {
//
//     var save = $alert({
//         title: 'Обращение сохранено!',
//         content: '',
//         placement: 'bottom-right',
//         type: 'success',
//         duration: '5',
//         keyboard: true,
//         show: false});
//
//     $scope.saveStmt = function (task) {
//         console.log(task);
//         return $http.post(ctrl+'/savestmt', task)
//             .then(function(response){
//                 $scope.resdata = response.data;
//                 if(uploader.queue.length > 0)
//                 {
//                     uploader.onBeforeUploadItem = function(item) {
//                         var formData = [{
//                             id:  $scope.resdata.id,
//                             number:  $scope.resdata.num,
//                             date:  $scope.resdata.date
//                         }];
//                     Array.prototype.push.apply(item.formData, formData);
//                     };
//                     uploader.uploadAll();
//
//                     uploader.onCompleteAll = function() {
//                         save.show();
//                         $location.path("/");
//                     };
//                 }
//                 save.show();
//                 $location.path("/");
//             }, function(error) {
//                 console.log('Error :: savestmt');
//             });
//     };
//
//     $scope.addFile = function (task, files) {
//         $scope.sendFiles = false;
//
//         if(uploader.queue.length > 0){
//
//             uploader.onBeforeUploadItem = function(item) {
//                 console.log(files);
//                 var formData = [{
//                     id:  task.id,
//                 //    number:  files.number,
//                 //    date:  files.date
//                 }];
//                 Array.prototype.push.apply(item.formData, formData);
//             };
//             uploader.uploadAll();
//
//
//             uploader.onCompleteAll = function() {
//                 $scope.sendFiles = true;
//             };
//         }
//     };
//
//     var uploader = $scope.uploader = new FileUploader({
//         url: ctrl+'/getupload'
//     });
//
//     // FILTERS
//     uploader.filters.push({
//         name: 'customFilter',
//         fn: function(item /*{File|FileLikeObject}*/, options) {
//             return this.queue.length < 10;
//         }
//     });
//
// });
//
// myApp.controller('updateCtrl', function ($http, $location, $alert, $window, stmt, FileSaver, Blob) {
//
//     var vm = this;
//
//     var save = $alert({
//         title: 'Обращение сохранено!',
//         content: '',
//         placement: 'bottom-right',
//         type: 'success',
//         duration: '5',
//         keyboard: true,
//         show: false});
//
//     /* Запрос на поиск по реестру застрахованных */
//     vm.submitForm = function() {
//         vm.loading = true;
//         vm.erzData = '';
//
//         $http.post(ctrl+'/getfinderz', vm.erz)
//             .then(function(response) {
//                 vm.erzData = response.data;
//                 vm.loading = false;
//                 console.log(response);
//             }, function(error) {
//                 console.log('Error erz');
//             });
//     };
//
//     /* Очистить данные */
//     vm.clearERZ = function () {
//         vm.erz = {};
//         vm.erzData = '';
//     };
//
//     vm.setERZ = function (data) {
//         this.task.deffered = {
//             fam: data.sName,
//             im: data.Name,
//             ot: data.pName,
//             dt: vm.formatDate(data.dateMan),
//             id: data.id,
//             //   phone: $scope.sRemoteNumber,
//             enp: data.ENP,
//             okato: data.okato_reg,
//             okato_name: data.pbMan,
//             okato_erz: true
//         };
//
//         $http.post(ctrl+'/gethistory', data)
//             .then(function(response) {
//                 console.log(':::History:::');
//                 vm.historyStmt = response.data;
//                 //   $scope.tabs.activeTab = 1;
//             }, function(error) {
//                 console.log('Error History');
//             });
//     };
//
//     vm.formatDate = function(date){
//         var d = date;
//         var dateOut = new Date(d.substr(0, 4), d.substr(5, 2) - 1, d.substr(8, 2));
//         return dateOut;
//     };
//
//     vm.init = function(){
//         var id = {"id": $location.search().id};
//
//         $http.post(ctrl+'/getistask', id).then(function(response){
//
//             if(response.data == false){
//                 $window.location.href = '#/';
//             }
//
//             vm.task = response.data;
//             if( vm.task.deffered.dt)
//             {
//                 vm.task.deffered.dt = new Date(response.data.deffered.dt);
//             }
//
//             vm.loading = false;
//
//             vm.files = {
//                 id: vm.task.id,
//                 number:  angular.equals(0, vm.task.attachment.length)?'':vm.task.attachment[0].n_attach,
//                 date:  angular.equals(0, vm.task.attachment.length)?'':vm.task.attachment[0].date_attach
//             };
//
//         }, function(error) {
//                 console.log('Error init');
//             $window.location.href = '#/';
//             });
//     };
//
//     /* Список типов обращений */
//     vm.tipStmt = stmt.getTip_stmt();
//
//     /* Список тем обращений */
//     vm.refreshStmt = function(tip) {
//         stmt.getList(tip).success(function(data){
//             vm.lists = data;
//         });
//     };
//
//     /* Очистить тематику обращения */
//     vm.clear = function () {
//         vm.task.theme = '';
//     };
//
//     $http.post(ctrl+'/getstmtaction')
//         .then(function(response){
//                 vm.state_result = response.data;
//             },
//             function(error) {
//                 console.log('Error select actions');
//             });
//
//     /* Ответственный оператор */
//     //Все операторы для переадресации звонка
//     vm.refreshUsers = function(){
//         return $http.post(ctrl+'/gettransferusers')
//             .then(function(response){
//                 console.log('Transfer users');
//                 vm.listUsers = response.data
//
//             }, function(error) {
//                 console.log('Error :: gettransferusers');
//             });
//     };
//
//     // Шаблон выпадающего списка операторов 1-го и 2-го уровня
//     vm.userHtml = function (item) {
//         return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
//             + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
//             "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
//     };
//
//     /* Обновление данных */
//     vm.update = function (task) {
//             stmt.updateStmt(task).success(function (data) {
//             save.show();
//             $window.location.href = '#/';
//         });
//     };
//
//     /* Скачать файл */
//     vm.saveFile = function (task) {
//         $http.post(ctrl+'/getsavefile', task, {
//             responseType:'arraybuffer'
//         }).then(function (response) {
//                 var data = new Blob([response.data], { type: task.file_type });
//                 var fileURL = URL.createObjectURL(data);
//                 FileSaver.saveAs(data, task.file_name);
//         });
//     };
//
// });
//
// myApp.controller('reportCtrl', function ($http, NgTableParams, FileSaver, Blob) {
//
//     var vm = this;
//     // vm.datePicker = [];
//     vm.datePicker = {startDate: moment().startOf('month'), endDate: moment()};
//
//
//     vm.opts = {
//         locale: {
//             applyClass: 'btn-green',
//             applyLabel: "Применить",
//             cancelLabel: 'Отмена',
//             customRangeLabel: 'Выбрать период',
//             firstDay: 1,
//             format: 'MMMM D, YYYY'
//     },
//         ranges: {
//             'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
//             'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
//             'За текущий месяц': [moment().startOf('month'), moment().add('months', 1).date(0)]
//         }
//     };
//
//     vm.sendReport = function (data) {
//         return $http.post(ctrl+'/getreport', data)
//             .then(function(response){
//                    console.log('getreport');
//                    console.log(response.data);
//                 vm.dataReport = response.data;
//
//             }, function(error) {
//                 console.log('Error :: getreport');
//             });
//     };
//
//     /* Сохранение отчётов */
//     vm.saveReport = function (data) {
//         $http.post(ctrl+'/sendreport', data, {
//             responseType:'arraybuffer'
//         }).then(function(response){
//             // console.log(response.data);
//             var data = new Blob([response.data], { type: "application/vnd.ms-excel" });
//             var fileURL = URL.createObjectURL(data);
//             FileSaver.saveAs(data, ' ОБРАЩЕНИЯ ЗАСТРАХОВАННЫХ ЛИЦ.xls');
//             }, function(error) {
//                 console.log('Error :: sendreport');
//             });
//     };
//
// });
//
// myApp.factory('stmt', function($http) {
//     var tip =  [
//         {value: "1", text: 'Жалоба'},
//         {value: "2", text: 'Консультация'},
//         {value: "3", text: 'Заявление'},
//         {value: "4", text: 'Предложение'}
//     ];
//
//     return {
//         getList : function(data){
//             var list = $http.post(ctrl+'/getliststatement', data).success(function(res){
//                 return res.data;
//             });
//             return list;
//         },
//         getTip_stmt: function() {
//             return tip;
//         },
//         updateStmt : function(task){
//             var resulst =  $http.post(ctrl+'/updatestmt', task).success(function(res){
//                 return res.data;
//             });
//             return resulst;
//         },
//         userList : function () {
//             var list = $http.post(ctrl+'/filteruserlist').success(function (res) {
//                     return res;
//                  // return item.user.fam + "&nbsp;" + item.user.im + "&nbsp;" + item.user.ot
//                  //     + "<br><small>" + item.user.company.name + "&nbsp;—&nbsp;" + item.user.role + "<br>" +
//                  //     "Вн. номер:&nbsp;" + item.sip_private_identity + "</small>";
//             });
//             return list;
//         }
//     }
// });