<?php start_section('page-script-datalist-detail'); ?>
    <script>
        function load_datalist_detail() {
            if (!results) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            let grouped = {};
            results.forEach(r => {
                if (!grouped[r.series]) grouped[r.series] = [];
                grouped[r.series].push(r);
            });

            var html = '';
            html += '<table id="tbl_pos_datalist_detail" class="table table-bordered">';
                html += '<thead style="background-color: #B3B3B3; color: black; position: sticky; top: 0; z-index: 10;">';
                    html += '<tr style="position: sticky; top: 0; z-index: 11;">';
                        html += '<th class="text-center text-capitalize" colspan="6">header</th>';
                        html += '<th class="text-center text-capitalize" colspan="6">detail</th>';
                    html += '</tr>';

                    html += '<tr style="position: sticky; top: 35px; z-index: 10;">';
                        html += '<th class="text-center text-capitalize">series</th>';
                        html += '<th class="text-center text-capitalize">trans date</th>';
                        html += '<th class="text-center text-capitalize">area</th>';
                        html += '<th class="text-center text-capitalize">grand total</th>';
                        html += '<th class="text-center text-capitalize">method</th>';
                        html += '<th class="text-center text-capitalize">cashier</th>';

                        html += '<th class="text-center text-capitalize">item code</th>';
                        html += '<th class="text-center text-capitalize">item name</th>';
                        html += '<th class="text-center text-capitalize">uom</th>';
                        html += '<th class="text-center text-capitalize">qty</th>';
                        html += '<th class="text-center text-capitalize">rate</th>';
                        html += '<th class="text-center text-capitalize">subtotal</th>';
                    html += '</tr>';
                html += '</thead>';

                html += '<tbody>';
                    let groupIndex = 0;

                    for (let series in grouped) {
                        let rows = grouped[series];
                        let rowspan = rows.length;
                        let first = true;

                        let bgColor = (groupIndex % 2 === 0) ? "#f9f9f9" : "#ffffff";
                        groupIndex++;

                        rows.forEach((r) => {
                            html += `<tr style="background-color:${bgColor}">`;

                            if (first) {
                                html += `<td rowspan="${rowspan}">${r.series}</td>`;
                                html += `<td rowspan="${rowspan}" class="text-center">${moment(r.date_trans).format('DD-MMM-YYYY')}</td>`;
                                html += `<td rowspan="${rowspan}">${r.area}</td>`;
                                html += `<td rowspan="${rowspan}" class="text-right">${formatNumber(r.grandtotal, 2)}</td>`;
                                html += `<td rowspan="${rowspan}">${r.methode}</td>`;
                                html += `<td rowspan="${rowspan}">${r.username}</td>`;
                                first = false;
                            }

                            html += `<td>${r.KdBarang}</td>`;
                            html += `<td>${r.NmBarang}</td>`;
                            html += `<td>${r.Satuan}</td>`;
                            html += `<td class="text-right">${formatNumber(r.qty, 2)}</td>`;
                            html += `<td class="text-right">${formatNumber(r.rate, 2)}</td>`;
                            html += `<td class="text-right">${formatNumber(r.total, 2)}</td>`;
                            html += '</tr>';
                        });
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }

        function chart_datalist_detail() {
            if (transChart) {
                transChart.destroy();
            }
            
            if (!results) {
                return;
            }

            if (results.length == 0) {
                return;
            }

            create_containerChart();
            results.forEach(res => {
                const date = new Date(res.date_trans);
                const formattedDate = moment(date).format('DD-MMM-YYYY');
                const area = res.code;
                const key = formattedDate + "|" + area;
                const grandtotal = parseFloat(res.grandtotal) || 0;

                if (!grouped[key]) {
                    grouped[key] = {
                        date: formattedDate,
                        area: area,
                        total: 0
                    };
                }

                grouped[key].total += grandtotal;
            });

            const rawDataArr = Object.values(grouped);
            const areas = [...new Set(rawDataArr.map(item => item.area))];

            let dates = [...new Set(rawDataArr.map(item => removeYear(item.date)))];
            dates = dates.slice(0, 31);

            const datasets = areas.map(area => {
                return {
                    label: area,
                    data: dates.map(date => {
                        const f = rawDataArr.find(x => removeYear(x.date) === date && x.area === area);
                        return f ? f.total : 0;
                    }),
                    borderWidth: 2,
                    borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
                    fill: false,
                    tension: 0.2,
                    pointRadius: 5
                };
            });

            const areaLabelPlugin = {
                id: 'areaLabelPlugin',
                afterDatasetsDraw(chart) {
                    const {ctx} = chart;

                    chart.data.datasets.forEach((dataset, index) => {
                        const meta = chart.getDatasetMeta(index);
                        const lastPoint = meta.data[meta.data.length - 1];

                        if (lastPoint) {
                            ctx.save();
                            ctx.font = 'bold 14px Arial';
                            ctx.fillStyle = dataset.borderColor;
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';

                            ctx.fillText(
                                dataset.label,
                                lastPoint.x + 10,
                                lastPoint.y
                            );

                            ctx.restore();
                        }
                    });
                }
            };

            const ctx = document.getElementById("transChart").getContext('2d');

            transChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: datasets
                },
                plugins: [ChartDataLabels, areaLabelPlugin],
                options: {
                    layout: { padding: {
                        top: 20,
                        right: 80,
                        bottom: 20 }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 10,
                                font: { size: 12, weight: 'bold' }
                            }
                        },
                        datalabels: {
                            anchor: 'top',
                            align: 'top',
                            color: '#000',
                            font: { weight: 'bold', size: 12 },
                            formatter: v => formatNumberK(v)
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => formatNumberK(value)
                            }
                        }
                    }
                }
            });
        }
    </script>
<?php end_section('page-script-datalist-detail'); ?>

