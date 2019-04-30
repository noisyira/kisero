$scope.datePicker = {startDate: moment().startOf('year'), endDate: moment().startOf('year').add(2, 'month').endOf('month')};

$scope.opts = {
    locale: {
        applyClass: 'btn-green',
        applyLabel: "Применить",
        cancelLabel: 'Отмена',
        customRangeLabel: 'Выбрать период',
        firstDay: 1,
        format: 'MMMM D, YYYY'
},
    ranges: {
        // 'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
        // 'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
        // 'За текущий месяц': [moment().startOf('month'), moment().add('months', 1).date(0)],
        'I - квартал': [moment().startOf('year'), moment().startOf('year').add(2, 'month').endOf('month')],
        "II - квартал":  [moment().startOf('year'), moment().startOf('year').add(5, 'month').endOf('month')],
        "III - квартал": [moment().startOf('year'), moment().startOf('year').add(8, 'month').endOf('month')],
        "IV - квартал":  [moment().startOf('year'), moment().startOf('year').add(11, 'month').endOf('month')]
    }
};

// Сформировать отчёт
$scope.getReport = function () {
    Stmt.report({data: $scope.datePicker}, function (r) {
        $scope.dataReport = r;
    });
};
