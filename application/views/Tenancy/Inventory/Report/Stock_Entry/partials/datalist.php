<?php start_section('page-script-datalist'); ?>
    <script>
        function load_datalist() {
            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            var html = '';
            html += '<table id="tbl_stock_entry_datalist" class="table table-bordered table-striped">';
                html += '<thead style="background-color: #B3B3B3; color: black; position: sticky; top: 0; z-index: 10;">';
                    html += '<tr style="position: sticky; top: 0; z-index: 11;">';
                        html += '<th class="text-center text-capitalize">trans date</th>';
                        html += '<th class="text-center text-capitalize">area</th>';
                        html += '<th class="text-center text-capitalize">item code</th>';
                        html += '<th class="text-center text-capitalize">item name</th>';
                        html += '<th class="text-center text-capitalize">qty</th>';
                        html += '<th class="text-center text-capitalize">uom</th>';
                        html += '<th class="text-center text-capitalize">status</th>';
                        html += '<th class="text-center text-capitalize">note</th>';
                        html += '<th class="text-center text-capitalize">user</th>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                    for (let index = 0; index < results.length; index++) {
                        html += '<tr>';
                            html += '<td class="text-capitalize text-center">' + moment(results[index].date_trans).format('DD-MMM-YYYY') + '</td>';
                            html += '<td class="text-capitalize">' + results[index].area + '</td>';
                            html += '<td class="text-capitalize">' + results[index].KdBarang + '</td>';
                            html += '<td class="text-capitalize">' + results[index].NmBarang + '</td>';
                            html += '<td class="text-capitalize text-right">' + formatNumber(results[index].qty, 2) + '</td>';
                            html += '<td class="text-capitalize">' + results[index].Satuan + '</td>';
                            html += '<td class="text-capitalize">' + results[index].status + '</td>';
                            html += '<td class="text-capitalize">' + (results[index].note == null ?  "" : results[index].note) + '</td>';
                            html += '<td class="text-capitalize">' + results[index].username + '</td>';
                        html += '</tr>';
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }
    </script>
<?php end_section('page-script-datalist'); ?>

