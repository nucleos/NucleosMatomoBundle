/*!
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function MatomoGraph(element: HTMLElement, responsive: boolean) {
    const table: HTMLTableElement | null = element.querySelector('table');

    if (table === null) {
        return;
    }

    let labels: any[] = [];
    let data: any[] = [];

    const fetchData = () => {
        labels = [];
        data = [];

        Array.from(table.querySelector('thead tr')!.children).forEach((item, index) => {
            if (index > 0)
                data.push({
                    label: item.textContent,
                    rows: [],
                });
        });

        table.querySelectorAll('tbody tr').forEach((rowItem) => {
            Array.from(rowItem.children).forEach((item, index) => {
                if (index > 0) {
                    data[index - 1].rows.push(parseInt(item.textContent ?? '0'));
                } else {
                    labels.push(item.textContent);
                }
            });
        });
    };

    const createGraph = () => {
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

        element.append(canvas);

        const datasets: any[] = [];

        data.forEach((item, index) => {
            datasets.push({
                data: item.rows,
                yAxisID: index < 2 ? 'y' : 'y1',
                label: item.label,
                backgroundColor: colors[index % colors.length],
                borderColor: colors[index % colors.length],
                borderWidth: 1,
                fill: false,
            });
        });

        import('chart.js/auto').then(({ default: Chart }) => {
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: {
                    responsive: responsive,
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    },
                },
            });
        });

        table.classList.add('hidden');
    };

    fetchData();
    createGraph();

    return this;
}

export default MatomoGraph;
