import Chartist from 'chartist';

// Styles
import 'chartist/dist/chartist.css';

class MatomoTable {
  constructor(element) {
    this.element = element;
    this.table = element.querySelector('table');

    this.labels = [];
    this.data = [];

    this.getData();
    this.createGraph();
  }

  getData() {
    this.labels = [];
    this.data = [];

    Array.from(this.table.querySelector('thead tr').children)
      .forEach((item, index) => {
        if (index > 0)
          this.data.push([]);
      });

    this.table.querySelectorAll('tbody tr')
      .forEach(rowItem => {
        Array.from(rowItem.children).forEach((item, index) => {

          if (index > 0) {
            this.data[index - 1].push(parseInt(item.textContent));
          } else {
            this.labels.push(item.textContent);
          }
        });
      });
  }

  createGraph() {
    const id = 'chartist-' + Math.floor(Math.random() * 99999);
    const div = document.createElement('div');
    div.id = id;
    div.classList.add('ct-chart');

    this.element.append(div);

    new Chartist.Line('#' + id, {
      labels: this.labels,
      series: this.data
    }, {
      axisX: {
        labelInterpolationFnc: function (value, index) {
          return index % 7 === 0 ? '' + value : null;
        }
      },
      height: 200
    });

    this.table.classList.add('hidden');
  }
}

// Bind
const weakMap = new WeakMap();

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-matomo]').forEach((element) => {
    if (weakMap.has(element) && weakMap.get(element).matomoTable) {
      return;
    }

    weakMap.set(element, {
      matomoTable: new MatomoTable(element)
    });
  });
});
