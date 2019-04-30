var prefixApiUrl = "/user/rest-stmt";
var prefixDial = "/user/dial-stmt";
var prefixPoll = "/user/poll-stmt";
var prefixEir = "/user/eir-stmt";
var prefixArchive = "/user/archive-stmt";
var prefixDspPeople = "/user/dsp-stmt";


module.run(['Stmt', '$rootScope', '$location', '$cookies', function (Stmt, $scope, $location, $cookies) {
    $scope.isRouteActive = function (id){
        return $location.path().indexOf(id)===0;
    };

    Stmt.getPJSIP({}, function (r) {
        $scope.cfg = r;

        if($cookies.get('sipRegister'))
        {
            sipRegister($scope.cfg);
        }
    });

    if(oSipSessionCall){
        console.log('Идет Вызов');
    }

    $scope.sipRegister = function () {
        sipRegister($scope.cfg);
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

    $scope.init();
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
}]);

module.config(function($datepickerProvider, $locationProvider, NotificationProvider) {
    angular.extend($datepickerProvider.defaults, {
        startWeek: 1,
        startingDay: 1
    });
	
	$locationProvider.hashPrefix('');

    NotificationProvider.setOptions({
        delay: 5000,
        startTop: 20,
        startRight: 10,
        verticalSpacing: 20,
        horizontalSpacing: 20,
        positionX: 'right',
        positionY: 'bottom'
    });

});

module.value('cgBusyDefaults',{
    message:'Загрузка данных',
    backdrop: true,
 //   templateUrl: 'my_custom_template.html',
    delay: 0,
    minDuration: 200
});

module.factory('Stmt', ['$resource', function ($resource) {
    return $resource('/user/rest-stmt/', {}, {
        // query: {method: 'GET', url: '/user/rest-stmt/' + 'index'},
        // add: {method: 'POST', url: prefixApiUrl + 'route/add'},
        // remove: {method: 'POST', url: prefixApiUrl + 'route/remove'},
         get: {method: 'GET', url: prefixApiUrl + '/get-stmt'},
         update: {method: 'POST', url: prefixApiUrl + '/update-stmt' },
         close: {method: 'POST', url: prefixApiUrl + '/close-stmt' },
         save: {method: 'POST', url: prefixApiUrl + '/save-stmt'},
         transfer: {method: 'POST', url: prefixApiUrl + '/transfer-stmt'},
         rework: {method: 'GET', url: prefixApiUrl + '/rework-stmt'},
         report: {method: 'POST', url: prefixApiUrl + '/report-stmt'},
         reportIntraservice: {method: 'POST', url: prefixApiUrl + '/report-intraservice'},
         getPJSIP: {method: 'POST', url: prefixApiUrl + '/get-pjsip-account'},
         getList: {method: 'POST', url: prefixApiUrl + '/get-list-stmt', isArray:true},
         getUsers: {method: 'POST', url: prefixApiUrl + '/get-list-users', isArray:true},
         getMoList: {method: 'POST', url: prefixApiUrl + '/get-mo-list', isArray:true},
         erz: {method: 'POST', url: prefixApiUrl + '/get-erz-list', isArray:true},
         getModeStmt: {method: 'POST', url: prefixApiUrl + '/get-mode-stmt', isArray:true},
         getThemeStmt: {method: 'POST', url: prefixApiUrl + '/get-theme-stmt', isArray:true},
         getUsersList: {method: 'POST', url: prefixApiUrl + '/get-users-list'},
         getCloseList: {method: 'POST', url: prefixApiUrl + '/get-close-list', isArray:true},
         getPhoneBookList: {method: 'POST', url: prefixApiUrl + '/get-phone-list'},
         getAnswerScript: {method: 'POST', url: prefixApiUrl + '/get-answer-script'},
         deleteAttachment: {method: 'POST', url: prefixApiUrl + '/delete-attachment'},
         pagi: {method: 'POST', url: prefixApiUrl + '/pagi', headers: { 'Content-Type': 'application/json' } },
         files: {method: 'POST', url: prefixApiUrl + '/interaction-files' },
         stmtXml: {method: 'POST', url: prefixApiUrl + '/stmt-xml', isArray:true}
    });
}]);

module.factory( 'DialPeople', ['$resource', function ($resource) {
    return $resource('/user/dial-stmt/', {},{
        query: {method: 'GET', url: prefixDial + '/index'},
        get: {method: 'GET', url: prefixDial + '/get-dial'},
        call: {method: 'POST', url: prefixDial + '/call-dial'},
        save: {method: 'POST', url: prefixDial + '/save-dial'},
        notAnswer: {method: 'POST', url: prefixDial + '/not-answer-dial'},
        reCall: {method: 'POST', url: prefixDial + '/re-call-dial'},
        nextCall: {method: 'POST', url: prefixDial + '/next-call-dial'},
        reportUser: {method: 'POST', url: prefixDial + '/report-user', isArray:true},
        getListMO: {method: 'POST', url: prefixDial + '/get-list-mo'},
        settingMO: {method: 'POST', url: prefixDial + '/setting-mo'},
        getDialMO: {method: 'GET', url: prefixDial + '/get-dial-mo'},
        getPeople: {method: 'POST', url: prefixDial + '/get-people'},
        getReport: {method: 'POST', url: prefixDial + '/get-report'},
        saveParamsMO: {method: 'POST', url: prefixDial + '/save-params-mo'},
        saveParamsPeople: {method: 'POST', url: prefixDial + '/save-params-people'},
        monitoring: {method: 'POST', url: prefixDial + '/get-monitoring'},
        roszdravnadzor: {method: 'POST', url: prefixDial + '/roszdravnadzor'}
    });
}]);

module.factory('PollList', ['$resource', function ($resource) {
    return $resource('/user/poll-stmt/', {}, {
        get: {method: 'GET', url: prefixPoll + '/get-poll', isArray:true},
        list: {method: 'GET', url: prefixPoll + '/get-list', isArray:true},
        save: {method: 'POST', url: prefixPoll + '/save-poll'},
        getPeople: {method: 'GET', url: prefixPoll + '/get-people'},
        notAnswer: {method: 'POST', url: prefixPoll + '/not-answer-poll'},
        reCall: {method: 'POST', url: prefixPoll + '/re-call-poll'},
        nextCall: {method: 'POST', url: prefixPoll + '/next-poll'}
    });
}]);

module.factory('DspPeople', ['$resource', function ($resource) {
    return $resource('/user/dsp-stmt/', {}, {
        index: {method: 'GET', url: prefixDspPeople + '/index', isArray:true},
        list: {method: 'POST', url: prefixDspPeople + '/list'},
        people: {method: 'GET', url: prefixDspPeople + '/people'}
    });
}]);

module.factory('Eir', ['$resource', function ($resource) {
    return $resource('/user/eir-stmt/', {}, {
        query: {method: 'GET', url: prefixEir + '/index'},
        MO: {method: 'GET', url: prefixEir + '/get-mo-list', isArray:true},
        getMO: {method: 'GET', url: prefixEir + '/get-mo-async', isArray:true},
        eirSettingSave: {method: 'POST', url: prefixEir + '/eir-setting-save'},
        eirGet: {method: 'GET', url: prefixEir + '/eir-get'},
        eirGetPeople: {method: 'POST', url: prefixEir + '/eir-get-people'},
        save: {method: 'POST', url: prefixEir + '/eir-save-people'},
        eirReport: {method: 'POST', url: prefixEir + '/eir-report', isArray:true}
    });
}]);

module.factory('Archive', ['$resource', function ($resource) {
    return $resource('/user/archive-stmt/', {}, {
        get: {method: 'GET', url: prefixArchive + '/index'}
    });
}]);

module.filter('propsFilter', function() {
    return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function(item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();
                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});

module.filter('escape', function () {
    return window.encodeURI;
});

module.filter('myDropdownFilter', function($sce) {
    return function(label, query, item, options, element) {

        var fio = label.user.fam + ' ' + label.user.im + ' ' + label.user.ot;
        var company = label.user.company.name;
        var subdivision = label.user.subdivision;
        var role =  label.user.role;
        var phone = 'Вн. номер: ' + label.sip_private_identity;

        var html = '<p>' + fio + '<br>' +
            '<small>' + phone + '</small> <br>' +
            '<small>' + company + '</small> <br>' +
            '<small>' + subdivision + '</small> <br>' +
            '<small>' + role + '</small></p>';

        if(query)
        {
            html = html.replace(RegExp('('+ query + ')', 'g'), '<b>$1</b>');
        }

        return $sce.trustAsHtml(html);
    };
});

module.filter('filterMoList', function($sce) {
    return function(label, query, item, options, element) {
        var nameMo = label.NAMMO;
        var adress = label.ADRESMO;

        var html = '<p>' + nameMo + '<br>' +
            '<small>' + adress + '</small></p>';

        if(query)
        {
            html = html.replace(RegExp('('+ query + ')', 'g'), '<b>$1</b>');
        }

        return $sce.trustAsHtml(html);
    };
});

module.filter('searchFilterMoList', function($sce) {
    return function(label, query, item, options, element) {
        var fio = label.NAMMO;
        return $sce.trustAsHtml(fio);
    };
});

module.filter('listFilterMoList', function($filter) {
    return function (list, query, getLabel, options, element) {
        return $filter("filter")(list, query);
    }
});

module.filter('mySearchFilter', function($sce) {
    return function(label, query, item, options, element) {
        var data = item.item;
        var fio = data.user.fam + ' ' + data.user.im + ' ' + data.user.ot;
        return $sce.trustAsHtml(fio);
    };
});

module.filter('myListFilter', function($filter) {
    return function (list, query, getLabel, options, element) {
        return $filter("filter")(list, query);
    }
});

module.controller('RKKCtrl', function ($scope) {

});

// /* Телефонный справочник */
module.controller("phonebookCtrl", function($scope, Stmt){

    $scope.searchPhone = '';

    $scope.myPromise = Stmt.getPhoneBookList({}, function (r) {
        $scope.phoneLists = r;
    }).$promise;
});

module.controller("phoneCtrl", function ($rootScope, $scope, $interval, $routeParams, DialPeople, SharedSipPhone) {

    $scope.Phone = SharedSipPhone;
    $scope.roszdravTheme = '';

    $scope.dialA = function () {
        var interval = $interval(function () {
            if(oSipSessionCall)
            {
                console.log("oSipSessionCall = save BD");
                DialPeople.call({id:$routeParams.id, phone:$scope.Phone.number},function(row){
                    $scope.channel = row;
                })
            }else if(oSipSessionCall == null)
            {
                console.log(" cancel");
                $interval.cancel(interval);
            } else {  }
        }, 5000);
    };

    // Перевод обращения в Росздравнадзор
    $scope.sendData = function () {
        DialPeople.roszdravnadzor({theme:$scope.roszdravTheme}, function (row) {
            $('#modalR').modal('hide');
        },function(r){
            console.log("Error");
        });
    }

});

module.service('SharedSipPhone', function () {
    var Product = {
        name: '',
        number: ''
    };
    return Product;
});
