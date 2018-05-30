/*! (c) Christian Gripp, core23 - webdesign & more | core23.de */
(function ($) {
    $.matomoTable = {version: '1.00'};

    $.fn.matomoTable = function () {
        return $(this).each(function () {
            var table = $('table', this);

            var labels = [];
            var data = [];

            $('thead tr:first', table).children().each(function (index) {
                if (index > 0) {
                    data.push([]);
                }
            });

            $('tbody tr', table).each(function () {
                $(this).children().each(function (index) {
                    if (index > 0) {
                        data[index - 1].push(parseInt($(this).text()));
                    } else {
                        labels.push($(this).text());
                    }
                });
            });

            var id = 'chartist-' + Math.floor(Math.random() * 99999);
            var div = $('<div>').attr({class: 'ct-chart', id: id});

            table.hide().after(div);

            new Chartist.Line('#' + id, {
                labels: labels,
                series: data
            }, {
                axisX: {
                    labelInterpolationFnc: function (value, index) {
                        return index % 7 === 0 ? '' + value : null;
                    }
                },
                height: 200
            });
        });
    };

    // auto-initialize plugin
    $(function () {
        $('[data-matomo]').matomoTable();
    });
})(jQuery);
