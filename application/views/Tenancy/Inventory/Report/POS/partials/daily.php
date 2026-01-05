<?php start_section('page-script-daily'); ?>
    <script>
        function load_daily() {
            if (!results) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            var date_trans = document.getElementById("date_from").value;
            var html = '';
            html += '<table id="tbl_pos_daily" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">';
                html += '<colgroup>';
                    html += '<col style="width:300px;">';   // Area

                    // daily column
                    for (let i = 1; i <= 31; i++) {
                        html += '<col style="width:80px;">'; //qty
                        html += '<col style="width:150px;">'; //amount
                    }
                html += '</colgroup>';

                html += '<thead style="background-color: #B3B3B3; color: black; position:sticky; top:0; z-index:40;">';
                    html += '<tr style="position:sticky; top:0; z-index:41;">';
                        html += '<th rowspan="2" class="text-capitalize" style="text-align: center; vertical-align: middle; position:sticky; left:0; top:0; z-index:60; background:#B3B3B3;">area</th>';

                            // daily
                            for (let day = 1; day <= 31; day++) {
                                var dt = new Date(date_trans);
                                dt.setDate(dt.getDate() + (day - 1));

                                var color = '';
                                if (isWeekEnd(dt) == 'Saturday') {
                                    color = 'background-color: #618EED;';
                                } else if (isWeekEnd(dt) == 'Sunday') {
                                    color = 'background-color: #ED7461;';
                                }

                                html += '<th colspan="2" style="text-align: center; vertical-align: middle; ' + color + '">' + moment(dt).format('DD-MMM') + '</th>';
                            }
                    html += '</tr>';

                    html += '<tr style="position:sticky; top:34px; z-index:40;">';
                        // Daily
                        for (let day = 1; day <= 31; day++) {
                            html += '<th class="text-capitalize" style="text-align: center; vertical-align: middle;">qty</th>';
                            html += '<th class="text-capitalize" style="text-align: center; vertical-align: middle;">amount</th>';
                        }
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                    for (let index = 0; index < results.length; index++) {
                        html += '<tr>';
                            html += '<td class="text-capitalize" style="position:sticky; left:0; top:auto; background:#F2F2F2; z-index:30;">' + results[index].area + '</td>';
                            
                            // daily
                            for (let day = 1; day <= 31; day++) {
                                html += '<td class="text-capitalize text-right">' + formatNumber(results[index]["day_qty_" + day], 2) + '</td>';
                                html += '<td class="text-capitalize text-right">' + formatNumber(results[index]["day_" + day], 2) + '</td>';
                            }
                        html += '</tr>';
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }

        function chart_datalist_daily() {
            if (transChart) {
                transChart.destroy();
            }
            
            if (!chart_x) {
                return;
            }

            if (chart_x.length == 0) {
                return;
            }

            create_containerChart();
            chart_x.forEach(res => {
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
            
            // <-- remove null, undefined, ''
            const areas = [...new Set(rawDataArr
                .map(item => item.area)
                .filter(a => a)
            )];

            let dates = [...new Set(rawDataArr.map(item => removeYear(item.date)))];
            dates = dates.slice(0, 31);

            // area_color
            const rawJson = <?php echo json_encode($area_color); ?>;

            const areaColors = {};
            rawJson.forEach(item => {
                areaColors[item.name] = item.note;
            });

            const datasets = areas.map(area => {
                let values = dates.map(date => {
                    const f = rawDataArr.find(x => removeYear(x.date) === date && x.area === area);
                    return f ? f.total : 0;
                });

                values = maskAuto(values);

                return {
                    label: area,
                    data: values,
                    borderWidth: 2,
                    borderColor: areaColors[area] || '#' + Math.floor(Math.random()*16777215).toString(16), // fallback
                    backgroundColor: areaColors[area] || '#' + Math.floor(Math.random()*16777215).toString(16), // point color
                    fill: false,
                    tension: 0.2,
                    pointRadius: 5,
                    pointBackgroundColor: areaColors[area] || '#' + Math.floor(Math.random()*16777215).toString(16) // dot color
                };
            });

            const areaLabelPlugin = {
                id: 'areaLabelPlugin',
                afterDatasetsDraw(chart) {
                    const {ctx, scales} = chart;

                    chart.data.datasets.forEach((dataset, index) => {
                        const meta = chart.getDatasetMeta(index);

                        // find last dot valid (without null)
                        let lastPointIndex = -1;
                        for (let i = meta.data.length - 1; i >= 0; i--) {
                            if (dataset.data[i] !== null) {
                                lastPointIndex = i;
                                break;
                            }
                        }

                        if (lastPointIndex === -1) return;

                        const lastPoint = meta.data[lastPointIndex];
                        const value = dataset.data[lastPointIndex];

                        // Position the y label above the point, give it a larger offset so it doesn't cover the point.
                        const yOffset = 20; // jarak 20px di atas titik
                        const yPos = scales.y.getPixelForValue(value) - yOffset;

                        ctx.save();
                        ctx.font = 'bold 10px Arial';
                        ctx.fillStyle = dataset.borderColor;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        // label image right above the dot, clearly visible
                        ctx.fillText(dataset.label, lastPoint.x, yPos);

                        ctx.restore();
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
                    //flexible size ****
                    responsive: true,
                    maintainAspectRatio: false,
                    //flexible size ****
                    
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
                            font: { weight: 'bold', size: 10 },
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
<?php end_section('page-script-daily'); ?>

