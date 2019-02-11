import Chart from 'chart.js';

export default class MatomoTable {
  constructor(element) {
    this.element = element;
    this.table = element.querySelector('table');

    this.labels = [];
    this.data = [];

    this.fetchData();
    this.createGraph();
  }

  fetchData() {
    this.labels = [];
    this.data = [];

    Array.from(this.table.querySelector('thead tr').children)
      .forEach((item, index) => {
        if (index > 0)
          this.data.push({
            'label': item.textContent,
            'rows': [],
          });
      });

    this.table.querySelectorAll('tbody tr')
      .forEach(rowItem => {
        Array.from(rowItem.children).forEach((item, index) => {

          if (index > 0) {
            this.data[index - 1].rows.push(parseInt(item.textContent));
          } else {
            this.labels.push(item.textContent);
          }
        });
      });
  }

  createGraph() {
    const colors = {
      red: 'rgb(255, 99, 132)',
      orange: 'rgb(255, 159, 64)',
      yellow: 'rgb(255, 205, 86)',
      green: 'rgb(75, 192, 192)',
      blue: 'rgb(54, 162, 235)',
      purple: 'rgb(153, 102, 255)',
      grey: 'rgb(201, 203, 207)'
    };

    const canvas = document.createElement('canvas');
    canvas.width = 600;
    canvas.height = 200;

    this.element.append(canvas);

    let datasets = [];

    this.data.forEach((item, index) => {
      datasets.push({
        data: item.rows,
        label: item.label,
        backgroundColor: colors[index % colors.length],
        borderColor: colors[index % colors.length],
        borderWidth: 1
      });
    });

    new Chart(canvas, {
      type: 'line',
      data: {
        labels: this.labels,
        datasets: datasets
      }
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
