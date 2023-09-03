import Chart from 'chart.js/auto';

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
    const colors = [
      '#4dc9f6',
      '#f67019',
      '#f53794',
      '#537bc4',
      '#acc236',
      '#166a8f',
      '#00a950',
      '#58595b',
      '#8549ba',
    ];

    const canvas = document.createElement('canvas');
    canvas.width = 600;
    canvas.height = 300;

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
