<?php start_section('page-script-datalist-simple'); ?>
    <script>
        function load_datalist_simple() {
            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            var html = '';
            html += '<table id="tbl_incoming_datalist_simple" class="table table-bordered table-striped">';
                html += '<thead style="background-color: #B3B3B3; color: black; position: sticky; top: 0; z-index: 10;">';
                    html += '<tr style="position: sticky; top: 0; z-index: 11;">';
                        html += '<th class="text-center text-capitalize">series</th>';
                        html += '<th class="text-center text-capitalize">trans date</th>';
                        html += '<th class="text-center text-capitalize">from</th>';
                        html += '<th class="text-center text-capitalize">to area</th>';
                        html += '<th class="text-center text-capitalize">total</th>';
                        html += '<th class="text-center text-capitalize">user</th>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                    for (let index = 0; index < results.length; index++) {
                        html += '<tr>';
                            html += '<td class="text-capitalize">' + results[index].series + '</td>';
                            html += '<td class="text-capitalize text-center">' + moment(results[index].date_trans).format('DD-MMM-YYYY') + '</td>';

                            if (results[index].from_type == "DEPARTEMENT") {
                                html += '<td class="text-capitalize">' + (results[index].from ? 'Dept - ' + results[index].from.code + ' - ' + results[index].from.name : '') + '</td>';
                            } else if (results[index].from_type == "CUSTOMER") {
                                html += '<td class="text-capitalize">' + (results[index].from ? 'Cust - ' + results[index].from.name : '') + '</td>';
                            } else if (results[index].from_type == "SUPPLIER") {
                                html += '<td class="text-capitalize">' + (results[index].from ? 'Supp - ' + results[index].from.name : '') + '</td>';
                            } else if (results[index].from_type == "BUILDING") {
                                html += '<td class="text-capitalize">' + (results[index].from ? 'Area - ' + results[index].from.code + ' - ' + results[index].from.name : '') + '</td>';
                            } else {
                                html += '<td class="text-capitalize"></td>';
                            }

                            if (results[index].building) {
                                html += '<td class="text-capitalize">' + results[index].building.code + ' - ' + results[index].building.name + '</td>';
                            } else {
                                html += '<td class="text-capitalize"></td>';
                            }

                            html += '<td class="text-capitalize text-right">' + formatNumber(results[index].qty, 2) + '</td>';
                            html += '<td class="text-capitalize">' + results[index].username + '</td>';
                        html += '</tr>';
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }
    </script>
<?php end_section('page-script-datalist-simple'); ?>

