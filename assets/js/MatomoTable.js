import jQuery from 'jquery';
import Chartist from 'chartist';

// Styles
import 'chartist/dist/chartist.css';


(function (jQuery) {
  jQuery.matomoTable = {version: '1.00'};

  jQuery.fn.matomoTable = function () {
    return jQuery(this).each(function () {
      const table = jQuery('table', this);

      let labels = [];
      let data = [];

      jQuery('thead tr:first', table).children().each(function (index) {
        if (index > 0) {
          data.push([]);
        }
      });

      jQuery('tbody tr', table).each(function () {
        jQuery(this).children().each(function (index) {
          if (index > 0) {
            data[index - 1].push(parseInt(jQuery(this).text()));
          } else {
            labels.push(jQuery(this).text());
          }
        });
      });

      const id = 'chartist-' + Math.floor(Math.random() * 99999);
      const div = jQuery('<div>').attr({class: 'ct-chart', id: id});

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
  jQuery(function () {
    jQuery('[data-matomo]').matomoTable();
  });
})(jQuery);
