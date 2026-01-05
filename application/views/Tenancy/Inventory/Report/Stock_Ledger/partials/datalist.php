<?php start_section('page-script-datalist'); ?>
    <script>
        function load_datalist() {
            var date_trans = document.getElementById("date_trans").value;
            var area = document.getElementById("area").value;
            var departement = document.getElementById("departement").value;
            var category = document.getElementById("category").value;
            var code = document.getElementById("code").value;
            var name = document.getElementById("name").value;
            var stock = document.getElementById("stock").value;
            
            date_trans = date_trans == '' ? null : date_trans;
            area = area == '' ? null : area;
            departement = departement == '' ? null : departement;
            category = category == '' ? null : category;
            code = code == '' ? null : code;
            name = name == '' ? null : name;
            stock = stock == '' ? null : stock;

            if (date_trans == '') {
                alert('Date trans need value!');
                return;
            }

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/report_stock_datalist') ?>/" + date_trans + "/" + area + "/" + departement + "/" + category + "/" + code + "/" + name + "/" + stock,

                beforeSend: function() {
                    document.getElementById("panel-data").innerHTML = '<div class="text-capitalize text-muted loading" style="font-size: 24px;">loading<span class="dots"></div>';
                },
                complete: function() {

                },
                success: function(data) {
                    document.getElementById("panel-data").innerHTML = fill_datalist(data, date_trans);
                    if (data.length > 0) {
                        select_mode();
                        select_uom();
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }

        function fill_datalist(data, date_trans) {
            if (data.length == 0) {
                set_card(0, 0, 0, 0, 0);
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            } else {
                const today = new Date(date_trans);
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastDayOfLastMonth = moment(new Date(today.getFullYear(), today.getMonth(), 0)).format('DD-MMM-YYYY');

                var row_val = 0;
                var empty_val = 0;
                var low_val = 0;
                var medium_val = 0;
                var hight_val = 0;

                var html = '';
                html += '<table id="tbl-datalist" class="table table-bordered table-striped">';
                    html += '<thead style="background-color: #B3B3B3; color: black; position: sticky; top: 0; z-index: 10;">';
                        html += '<tr style="position: sticky; top: 0; z-index: 11;">';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Item Code</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Item Name</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Area</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Dept.</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Category</th>'
                            html += `<th rowspan="2" style="text-align: center; vertical-align: middle;">UOM's</th>`;
                            html += '<th colspan="2" style="text-align: center; vertical-align: middle;">Beginning (' + lastDayOfLastMonth + ')</th>';
                            html += '<th colspan="9" style="text-align: center; vertical-align: middle;" id="current_stock">Stock (' + moment(new Date(date_trans)).format('MMM-YYYY') + ')</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #DF91FA;">Stock Ending</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Stock Min</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Stock Max</th>';
                        html += '</tr>';

                        html += '<tr style="position: sticky; top: 35px; z-index: 10;">';
                            html += '<th style="text-align: center; vertical-align: middle;">Last Inventory</th>';
                            html += '<th style="text-align: center; vertical-align: middle;">Stock</th>';
                            html += '<th style="text-align: center; vertical-align: middle;">Last Inventory</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="current_stock">Inventory</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="current_stock">Adjustment</th>';
                            html += '<th style="text-align: center; vertical-align: middle; background-color: #FFA1A1;" class="current_stock">Abolish</th>';
                            html += '<th style="text-align: center; vertical-align: middle; background-color: #FFA1A1;" class="current_stock">Return</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="current_stock">Incoming</th>';
                            html += '<th style="text-align: center; vertical-align: middle; background-color: #FFA1A1;" class="current_stock">Outgoing</th>';
                            html += '<th style="text-align: center; vertical-align: middle; background-color: #FFA1A1;" class="current_stock">POS</th>';
                            html += '<th style="text-align: center; vertical-align: middle;">Stock</th>';
                        html += '</tr>';
                    html += '</thead>';

                    html += '<tbody>';
                        for (let index = 0; index < data.length; index++) {
                            row_val += 1;
                            var negative = '';
                            var sValue = '';

                            html += '<tr>';
                                if (data[index].KdBarang == null) { sValue = ''; } else { sValue = data[index].KdBarang; }
                                html += '<td class="text-center">' + sValue + '</td>';

                                if (data[index].NmBarang == null) { sValue = ''; } else { sValue = data[index].NmBarang; }
                                html += '<td>' + sValue + '</td>';

                                if (data[index].Area == null) { sValue = ''; } else { sValue = data[index].Area; }
                                html += '<td class="text-center">' + sValue + '</td>';

                                if (data[index].Departement == null) { sValue = ''; } else { sValue = data[index].Departement; }
                                html += '<td class="text-center">' + sValue + '</td>';

                                if (data[index].Kategori == null) { sValue = ''; } else { sValue = data[index].Kategori; }
                                html += '<td class="text-center">' + data[index].Kategori + '</td>';

                                html += '<td>';
                                    if (data[index].measure_1 == null) { sValue = ''; } else { sValue = data[index].measure_1; }
                                    html += '<span class="small">' + sValue + '</span>';
                                    if (data[index].measure_2 == null) { sValue = ''; } else { sValue = data[index].measure_2; }
                                    html += '<span class="middle">' + sValue + '</span>';
                                    if (data[index].measure_3 == null) { sValue = ''; } else { sValue = data[index].measure_3; }
                                    html += '<span class="big">' + sValue + '</span>';
                                html += '</td>';
                                
                                if (data[index].beginning_date == null) { sValue = ''; } else { sValue = moment(new Date(data[index].beginning_date)).format('DD-MMM-YYYY'); }
                                html += '<td class="text-center">' + sValue + '</td>';

                                html += '<td class="text-right" style="background-color: #EDFFF1;">';
                                    negative = '';
                                    if (data[index].beginning_stock_1 < 0) { negative = 'negative'; } else if (data[index].beginning_stock_1 > 0) { negative = 'positive'; }
                                    if (data[index].beginning_stock_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_stock_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].beginning_stock_2 < 0) { negative = 'negative'; } else if (data[index].beginning_stock_2 > 0) { negative = 'positive'; }
                                    if (data[index].beginning_stock_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_stock_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].beginning_stock_3 < 0) { negative = 'negative'; } else if (data[index].beginning_stock_3 > 0) { negative = 'positive'; }
                                    if (data[index].beginning_stock_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_stock_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                if (data[index].stock_date == null) { sValue = ''; } else { sValue = moment(new Date(data[index].stock_date)).format('DD-MMM-YYYY'); }
                                html += '<td class="text-center">' + sValue + '</td>';

                                html += '<td class="text-right current_stock">';
                                    negative = '';
                                    if (data[index].stock_inventory_1 < 0) { negative = 'negative'; } else if (data[index].stock_inventory_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_inventory_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_inventory_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_inventory_2 < 0) { negative = 'negative'; } else if (data[index].stock_inventory_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_inventory_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_inventory_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_inventory_3 < 0) { negative = 'negative'; } else if (data[index].stock_inventory_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_inventory_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_inventory_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock">';
                                    negative = '';
                                    if (data[index].stock_adjustment_1 < 0) { negative = 'negative'; } else if (data[index].stock_adjustment_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_adjustment_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_adjustment_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_adjustment_2 < 0) { negative = 'negative'; } else if (data[index].stock_adjustment_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_adjustment_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_adjustment_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_adjustment_3 < 0) { negative = 'negative'; } else if (data[index].stock_adjustment_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_adjustment_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_adjustment_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock" style="background-color: #FFE5E5;">';
                                    negative = '';
                                    if (data[index].stock_abolish_1 < 0) { negative = 'negative'; } else if (data[index].stock_abolish_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_abolish_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_abolish_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_abolish_2 < 0) { negative = 'negative'; } else if (data[index].stock_abolish_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_abolish_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_abolish_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_abolish_3 < 0) { negative = 'negative'; } else if (data[index].stock_abolish_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_abolish_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_abolish_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock" style="background-color: #FFE5E5;">';
                                    negative = '';
                                    if (data[index].stock_return_1 < 0) { negative = 'negative'; } else if (data[index].stock_return_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_return_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_return_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_return_2 < 0) { negative = 'negative'; } else if (data[index].stock_return_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_return_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_return_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_return_3 < 0) { negative = 'negative'; } else if (data[index].stock_return_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_return_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_return_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock">';
                                    negative = '';
                                    if (data[index].stock_incoming_1 < 0) { negative = 'negative'; } else if (data[index].stock_incoming_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_incoming_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_incoming_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_incoming_2 < 0) { negative = 'negative'; } else if (data[index].stock_incoming_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_incoming_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_incoming_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_incoming_3 < 0) { negative = 'negative'; } else if (data[index].stock_incoming_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_incoming_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_incoming_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock" style="background-color: #FFE5E5;">';
                                    negative = '';
                                    if (data[index].stock_outgoing_1 < 0) { negative = 'negative'; } else if (data[index].stock_outgoing_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_outgoing_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_outgoing_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_outgoing_2 < 0) { negative = 'negative'; } else if (data[index].stock_outgoing_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_outgoing_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_outgoing_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_outgoing_3 < 0) { negative = 'negative'; } else if (data[index].stock_outgoing_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_outgoing_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_outgoing_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right current_stock" style="background-color: #FFE5E5;">';
                                    negative = '';
                                    if (data[index].stock_pos_1 < 0) { negative = 'negative'; } else if (data[index].stock_pos_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_pos_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_pos_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_pos_2 < 0) { negative = 'negative'; } else if (data[index].stock_pos_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_pos_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_pos_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_pos_3 < 0) { negative = 'negative'; } else if (data[index].stock_pos_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_pos_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_pos_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right" style="background-color: #EDFFF1;">';
                                    negative = '';
                                    if (data[index].stock_total_1 < 0) { negative = 'negative'; } else if (data[index].stock_total_1 > 0) { negative = 'positive'; }
                                    if (data[index].stock_total_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_total_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_total_2 < 0) { negative = 'negative'; } else if (data[index].stock_total_2 > 0) { negative = 'positive'; }
                                    if (data[index].stock_total_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_total_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].stock_total_3 < 0) { negative = 'negative'; } else if (data[index].stock_total_3 > 0) { negative = 'positive'; }
                                    if (data[index].stock_total_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].stock_total_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                var color_ending = '';
                                if (data[index].stock_status == 'empty') {
                                    empty_val += 1;
                                    color_ending = 'bg-red';
                                } else if (data[index].stock_status == 'low') {
                                    low_val += 1;
                                    color_ending = 'bg-yellow';
                                } else if (data[index].stock_status == 'medium') {
                                    medium_val += 1;
                                    color_ending = 'bg-green';
                                } else if (data[index].stock_status == 'hight') {
                                    hight_val += 1;
                                    color_ending = 'bg-blue';
                                }

                                html += '<td class="text-right ' + color_ending + '">';
                                    negative = '';
                                    // if (data[index].ending_1 < 0) { negative = 'negative'; } else if (data[index].ending_1 > 0) { negative = 'positive'; }
                                    if (data[index].ending_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].ending_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    // if (data[index].ending_2 < 0) { negative = 'negative'; } else if (data[index].ending_2 > 0) { negative = 'positive'; }
                                    if (data[index].ending_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].ending_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    // if (data[index].ending_3 < 0) { negative = 'negative'; } else if (data[index].ending_3 > 0) { negative = 'positive'; }
                                    if (data[index].ending_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].ending_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right">';
                                    negative = '';
                                    if (data[index].MinStock_1 < 0) { negative = 'negative'; } else if (data[index].MinStock_1 > 0) { negative = 'positive'; }
                                    if (data[index].MinStock_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MinStock_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].MinStock_2 < 0) { negative = 'negative'; } else if (data[index].MinStock_2 > 0) { negative = 'positive'; }
                                    if (data[index].MinStock_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MinStock_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].MinStock_3 < 0) { negative = 'negative'; } else if (data[index].MinStock_3 > 0) { negative = 'positive'; }
                                    if (data[index].MinStock_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MinStock_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right">';
                                    negative = '';
                                    if (data[index].MaxStock_1 < 0) { negative = 'negative'; } else if (data[index].MaxStock_1 > 0) { negative = 'positive'; }
                                    if (data[index].MaxStock_1 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MaxStock_1, 2); }
                                    html += '<span class="small ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].MaxStock_2 < 0) { negative = 'negative'; } else if (data[index].MaxStock_2 > 0) { negative = 'positive'; }
                                    if (data[index].MaxStock_2 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MaxStock_2, 2); }
                                    html += '<span class="middle ' + negative + '">' + sValue + '</span>';

                                    negative = '';
                                    if (data[index].MaxStock_3 < 0) { negative = 'negative'; } else if (data[index].MaxStock_3 > 0) { negative = 'positive'; }
                                    if (data[index].MaxStock_3 == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].MaxStock_3, 2); }
                                    html += '<span class="big ' + negative + '">' + sValue + '</span>';
                                html += '</td>';
                            html += '</tr>';
                        }
                    html += '</tbody>';
                html += '</table>';

                set_card(row_val, empty_val, low_val, medium_val, hight_val);
                return html;
            }
        }

        function select_mode_datalist() {
            let selectedValue = document.getElementById("mode").value;

            const current_stock = document.getElementById("current_stock");
            chk = document.getElementsByClassName('current_stock');
            if (selectedValue == 'Simple') {
                current_stock.colSpan = 2;
                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = true;
                }
            } else if (selectedValue == 'Detail') {
                current_stock.colSpan = 9;
                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = false;
                }
            }
        }

        function export_datalist() {
            let table = document.getElementById("tbl-datalist");

            // create new table for export
            let exportTable = document.createElement("table");
            exportTable.border = "1";

            // Loop through rows
            Array.from(table.rows).forEach(row => {
                // Skip row that is not visible
                if (row.offsetParent === null) return;

                let newRow = document.createElement("tr");
                
                // Loop through cells (td/th)
                Array.from(row.cells).forEach(cell => {

                    // skip cell hidden by CSS / JS
                    let style = window.getComputedStyle(cell);

                    let hidden = (
                        style.display === "none" ||
                        style.visibility === "hidden" ||
                        cell.offsetWidth === 0 ||
                        cell.offsetHeight === 0
                    );

                    if (hidden) return;

                    // clone visible cell
                    let newCell = document.createElement(cell.tagName);

                    // ===== REMOVE SPAN HIDDEN INSIDE CELL =====
                    let cloneHTML = cell.cloneNode(true);

                    cloneHTML.querySelectorAll(
                        "span[hidden], span[hidden='true'], span[style*='display:none'], span[style*='visibility:hidden']"
                    ).forEach(sp => sp.remove());

                    newCell.innerHTML = cloneHTML.innerHTML;
                    // ===========================================

                    // keep colspan & rowspan
                    if (cell.colSpan > 1) newCell.colSpan = cell.colSpan;
                    if (cell.rowSpan > 1) newCell.rowSpan = cell.rowSpan;

                    newRow.appendChild(newCell);
                });

                // only append rows with visible cells
                if (newRow.cells.length > 0) exportTable.appendChild(newRow);
            });

            // Create Excel file
            let html = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:x="urn:schemas-microsoft-com:office:excel"
                    xmlns="http://www.w3.org/TR/REC-html40">
                <head><meta charset="UTF-8"></head>
                <body>${exportTable.outerHTML}</body></html>
            `;

            let blob = new Blob([html], { type: "application/vnd.ms-excel" });
            let a = document.createElement("a");
            a.href = URL.createObjectURL(blob);
            a.download = "datalist_" + moment(new Date()).format('DD_MM_YYYY_HH_mm_ss') + ".xls";
            a.click();
        }
    </script>
<?php end_section('page-script-datalist'); ?>

