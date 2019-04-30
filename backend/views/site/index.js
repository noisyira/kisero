var prefixApiUrl = options.currentUrl + '/';

module.run(['$rootScope', '$location', function ($scope, $location) {
    $scope.headerMenu = [];
    $scope.isRouteActive = function (id){
        return $location.path().indexOf(id)===0;
    };
    angular.forEach(options.headerMenus, function (label, id) {
        $scope.headerMenu.push({
            id:id,
            label: label,
            url: options.currentUrl + '#' + id,
        });
    });
}]);

module.factory('Stmt', ['$resource', function ($resource) {
    return $resource(prefixApiUrl + 'route', {}, {
        query: {method: 'GET', isArray: false},
        add: {method: 'POST', url: prefixApiUrl + 'route/add'},
        remove: {method: 'POST', url: prefixApiUrl + 'route/remove'},
    });
}]);

module.filter('escape', function () {
    return window.encodeURI;
});