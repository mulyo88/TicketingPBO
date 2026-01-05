<?php start_section('page-script-datalist-detail'); ?>
    <script>
        function load_datalist_detail() {
            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            let grouped = {};
            results.forEach(r => {
                if (!grouped[r.series]) grouped[r.series] = [];
                grouped[r.series].push(r);
            });

            var html = '';
            html += '<table id="tbl_outgoing_datalist_detail" class="table table-bordered">';
                html += '<thead style="background-color: #B3B3B3; color: black; position: sticky; top: 0; z-index: 10;">';
                    html += '<tr style="position: sticky; top: 0; z-index: 11;">';
                        html += '<th class="text-center text-capitalize" colspan="6">header</th>';
                        html += '<th class="text-center text-capitalize" colspan="4">detail</th>';
                    html += '</tr>';

                    html += '<tr style="position: sticky; top: 35px; z-index: 10;">';
                        html += '<th class="text-center text-capitalize">series</th>';
                        html += '<th class="text-center text-capitalize">trans date</th>';
                        html += '<th class="text-center text-capitalize">from area</th>';
                        html += '<th class="text-center text-capitalize">to</th>';
                        html += '<th class="text-center text-capitalize">total</th>';
                        html += '<th class="text-center text-capitalize">user</th>';

                        html += '<th class="text-center text-capitalize">item code</th>';
                        html += '<th class="text-center text-capitalize">item name</th>';
                        html += '<th class="text-center text-capitalize">uom</th>';
                        html += '<th class="text-center text-capitalize">qty</th>';
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
                                html += `<td rowspan="${rowspan}">${r.from_x}</td>`;
                                html += `<td rowspan="${rowspan}">${r.to_area}</td>`;
                                html += `<td rowspan="${rowspan}" class="text-right">${formatNumber(r.total_qty, 2)}</td>`;
                                html += `<td rowspan="${rowspan}">${r.username}</td>`;
                                first = false;
                            }

                            html += `<td>${r.KdBarang}</td>`;
                            html += `<td>${r.NmBarang}</td>`;
                            html += `<td>${r.Satuan}</td>`;
                            html += `<td class="text-right">${formatNumber(r.qty, 2)}</td>`;
                            html += '</tr>';
                        });
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }


    </script>
<?php end_section('page-script-datalist-detail'); ?>

