<?php start_section('page-script-yearly'); ?>
    <script>
        function load_yearly() {
            var date_trans = document.getElementById("date_trans").value;
            var area = document.getElementById("area").value;
            var departement = document.getElementById("departement").value;
            var category = document.getElementById("category").value;
            var code = document.getElementById("code").value;
            var name = document.getElementById("name").value;
            var seq_uom = document.getElementById("uom").value;
            
            if (seq_uom == 'small') {
                seq_uom = 1;
            } else if (seq_uom == 'middle') {
                seq_uom = 2;
            } else if (seq_uom == 'big') {
                seq_uom = 3;
            }
            
            date_trans = date_trans == '' ? null : date_trans;
            area = area == '' ? null : area;
            departement = departement == '' ? null : departement;
            category = category == '' ? null : category;
            code = code == '' ? null : code;
            name = name == '' ? null : name;

            if (date_trans == '') {
                alert('Date trans need value!');
                return;
            }

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/report_stock_yearly') ?>/" + date_trans + "/" + area + "/" + departement + "/" + category + "/" + code + "/" + name + "/" + seq_uom,

                beforeSend: function() {
                    document.getElementById("panel-data").innerHTML = '<div class="text-capitalize text-muted loading" style="font-size: 24px;">loading<span class="dots"></div>';
                },
                complete: function() {

                },
                success: function(data) {
                    document.getElementById("panel-data").innerHTML = fill_yearly(data, date_trans);
                    if (data.length > 0) {
                        select_mode();
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }

        function fill_yearly(data, date_trans) {
            if (data.length == 0) {
                set_card(0, 0, 0, 0, 0);
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            } else {
                var row_val = 0;
                var empty_val = 0;
                var low_val = 0;
                var medium_val = 0;
                var hight_val = 0;

                var html = '';
                html += '<table id="tbl-yearly" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">';
                    html += '<colgroup>';
                        html += '<col style="width:150px;">';  // Code
                        html += '<col style="width:250px;">';  // Name
                        html += '<col style="width:50px;">';   // Area
                        html += '<col style="width:50px;">';   // Dept
                        html += '<col style="width:80px;">';   // Category
                        html += '<col style="width:60px;">';   // UOM
                        html += '<col style="width:100px;">';  // Beginning Last Invent
                        html += '<col style="width:80px;">';  // Beginning Inven
                        html += '<col style="width:80px;">';  // Beginning Adj
                        html += '<col style="width:80px;">';  // Beginning In
                        html += '<col style="width:80px;">';  // Beginning Out
                        html += '<col style="width:80px;">';  // Beginning Stock

                        // yearly column
                        for (let i = 1; i <= 31; i++) {
                            html += '<col style="width:100px;">'; //yearly Inven
                            html += '<col style="width:100px;">'; //yearly Adj
                            html += '<col style="width:100px;">'; //yearly In
                            html += '<col style="width:100px;">'; //yearly Out
                            html += '<col style="width:100px;">'; //yearly Total
                            html += '<col style="width:100px;">'; //yearly Stock
                        }
                    html += '</colgroup>';

                    html += '<thead style="background-color: #B3B3B3; color: black; position:sticky; top:0; z-index:40;">';
                        html += '<tr style="position:sticky; top:0; z-index:41;">';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle; position:sticky; left:0; top:0; z-index:60; background:#B3B3B3;">Item Code</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle; position:sticky; left:150px; top:0; z-index:59; background:#B3B3B3;">Item Name</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Area</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Dept.</th>';
                            html += '<th rowspan="2" style="text-align: center; vertical-align: middle;">Category</th>'
                            html += `<th rowspan="2" style="text-align: center; vertical-align: middle;">UOM's</th>`;
                            html += `<th rowspan="2" style="text-align: center; vertical-align: middle;">Min</th>`;
                            html += `<th rowspan="2" style="text-align: center; vertical-align: middle;">Max</th>`;

                            var lastDayDecember = new Date(date_trans);
                            lastDayDecember.setFullYear(lastDayDecember.getFullYear() - 1);
                            lastDayDecember = new Date(lastDayDecember.getFullYear() + 1, 0, 0);
                            
                            html += '<th colspan="6" style="text-align: center; vertical-align: middle; background-color: #EDD861;" class="colspan-beginning">Beginning ' + moment(lastDayDecember).format('YYYY-MMM-DD') + '</th>';

                            // yearly
                            for (let day = 1; day <= 12; day++) {
                                var dt = new Date(date_trans);
                                dt.setFullYear(dt.getFullYear() + (day - 1));

                                html += '<th colspan="6" style="text-align: center; vertical-align: middle;" class="colspan-day">' + moment(dt).format('YYYY') + '</th>';
                            }
                        html += '</tr>';

                        html += '<tr style="position:sticky; top:34px; z-index:40;">';
                            // Beginning
                            html += '<th style="text-align: center; vertical-align: middle;">Last Inven.</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="detail">Inven.</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="detail">Adj.</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="detail">In</th>';
                            html += '<th style="text-align: center; vertical-align: middle;" class="detail">Out</th>';
                            html += '<th style="text-align: center; vertical-align: middle;">Stock</th>';
                            
                            // yearly
                            for (let day = 1; day <= 12; day++) {
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail">Inven.</th>';
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail">Adj.</th>';
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail">In</th>';
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail">Out</th>';
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail">Total</th>';
                                html += '<th style="text-align: center; vertical-align: middle;" class="detail_stock">Stock</th>';
                            }
                        html += '</tr>';
                    html += '</thead>';

                    html += '<tbody>';
                        for (let index = 0; index < data.length; index++) {
                            var negative = '';
                            var sValue = '';

                            html += '<tr>';
                                if (data[index].KdBarang == null) { sValue = ''; } else { sValue = data[index].KdBarang; }
                                html += '<td class="text-center" style="position:sticky; left:0; top:auto; background:#F2F2F2; z-index:30;">' + sValue + '</td>';

                                if (data[index].NmBarang == null) { sValue = ''; } else { sValue = data[index].NmBarang; }
                                html += '<td style="position:sticky; left:150px; top:auto; background:#F2F2F2; z-index:29;">' + sValue + '</td>';

                                if (data[index].Area == null) { sValue = ''; } else { sValue = data[index].Area; }
                                html += '<td class="text-center">' + sValue + '</td>';

                                if (data[index].Departement == null) { sValue = ''; } else { sValue = data[index].Departement; }
                                html += '<td class="text-center">' + sValue + '</td>';

                                if (data[index].Kategori == null) { sValue = ''; } else { sValue = data[index].Kategori; }
                                html += '<td class="text-center">' + data[index].Kategori + '</td>';

                                if (data[index].uom == null) { sValue = ''; } else { sValue = data[index].uom; }
                                html += '<td>' + sValue + '</td>';
                                
                                if (data[index].stock_min == null) { sValue = '0'; } else { sValue = data[index].stock_min; }
                                negative = '';
                                if (sValue < 0) { negative = 'negative'; } else if (sValue> 0) { negative = 'positive'; }
                                html += '<td class="text-right ' + negative + '">' + formatNumber(sValue, 2) + '</td>';

                                if (data[index].stock_max == null) { sValue = '0'; } else { sValue = data[index].stock_max; }
                                negative = '';
                                if (sValue < 0) { negative = 'negative'; } else if (sValue> 0) { negative = 'positive'; }
                                html += '<td class="text-right ' + negative + '">' + formatNumber(sValue, 2) + '</td>';
                                
                                if (data[index].beginning_date == null) { sValue = ''; } else { sValue = moment(new Date(data[index].beginning_date)).format('DD-MMM-YYYY'); }
                                html += '<td class="text-center">' + sValue + '</td>';

                                // beginning
                                html += '<td class="text-right detail">';
                                    negative = '';
                                    if (data[index].beginning_inv < 0) { negative = 'negative'; } else if (data[index].beginning_inv > 0) { negative = 'positive'; }
                                    if (data[index].beginning_inv == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_inv, 2); }
                                    html += '<span class="' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right detail">';
                                    negative = '';
                                    if (data[index].beginning_adj < 0) { negative = 'negative'; } else if (data[index].beginning_adj > 0) { negative = 'positive'; }
                                    if (data[index].beginning_adj == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_adj, 2); }
                                    html += '<span class="' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right detail">';
                                    negative = '';
                                    if (data[index].beginning_in < 0) { negative = 'negative'; } else if (data[index].beginning_in > 0) { negative = 'positive'; }
                                    if (data[index].beginning_in == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_in, 2); }
                                    html += '<span class="' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                html += '<td class="text-right detail">';
                                    negative = '';
                                    if (data[index].beginning_out < 0) { negative = 'negative'; } else if (data[index].beginning_in > 0) { negative = 'positive'; }
                                    if (data[index].beginning_out == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_out, 2); }
                                    html += '<span class="' + negative + '">' + sValue + '</span>';
                                html += '</td>';

                                // html += '<td class="text-right">';
                                //     negative = '';
                                //     if (data[index].beginning_stock < 0) { negative = 'negative'; } else if (data[index].beginning_stock > 0) { negative = 'positive'; }
                                //     if (data[index].beginning_stock == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index].beginning_stock, 2); }
                                //     html += '<span class="' + negative + '">' + sValue + '</span>';
                                // html += '</td>';

                                if (data[index].beginning_stock == null) { sValue = 0; } else { sValue = data[index].beginning_stock; }
                                // html += '<td class="text-right" style="background-color: ' + set_bg_uom(data[index].stock_min, data[index].stock_max, sValue) + ';">';
                                html += '<td class="text-right ' + set_bg_uom(data[index].stock_min, data[index].stock_max, sValue) + '">';
                                    negative = '';
                                    // if (sValue < 0) { negative = 'negative'; } else if (sValue > 0) { negative = 'positive'; }
                                    html += '<span class="' + negative + '">' + formatNumber(sValue, 2) + '</span>';
                                html += '</td>';

                                // yearly
                                for (let day = 1; day <= 12; day++) {
                                    html += '<td class="text-right detail">';
                                        negative = '';
                                        if (data[index]["stock_inv_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_inv_" + day] > 0) { negative = 'positive'; }
                                        if (data[index]["stock_inv_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_inv_" + day], 2); }
                                        html += '<span class="' + negative + '">' + sValue + '</span>';
                                    html += '</td>';

                                    html += '<td class="text-right detail">';
                                        negative = '';
                                        if (data[index]["stock_adj_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_adj_" + day] > 0) { negative = 'positive'; }
                                        if (data[index]["stock_adj_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_adj_" + day], 2); }
                                        html += '<span class="' + negative + '">' + sValue + '</span>';
                                    html += '</td>';

                                    html += '<td class="text-right detail">';
                                        negative = '';
                                        if (data[index]["stock_in_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_in_" + day] > 0) { negative = 'positive'; }
                                        if (data[index]["stock_in_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_in_" + day], 2); }
                                        html += '<span class="' + negative + '">' + sValue + '</span>';
                                    html += '</td>';

                                    html += '<td class="text-right detail">';
                                        negative = '';
                                        if (data[index]["stock_out_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_out_" + day] > 0) { negative = 'positive'; }
                                        if (data[index]["stock_out_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_out_" + day], 2); }
                                        html += '<span class="' + negative + '">' + sValue + '</span>';
                                    html += '</td>';

                                    html += '<td class="text-right detail">';
                                        negative = '';
                                        if (data[index]["stock_total_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_total_" + day] > 0) { negative = 'positive'; }
                                        if (data[index]["stock_total_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_total_" + day], 2); }
                                        html += '<span class="' + negative + '">' + sValue + '</span>';
                                    html += '</td>';

                                    // html += '<td class="text-right">';
                                    //     negative = '';
                                    //     if (data[index]["stock_" + day] < 0) { negative = 'negative'; } else if (data[index]["stock_" + day] > 0) { negative = 'positive'; }
                                    //     if (data[index]["stock_" + day] == null) { sValue = formatNumber(0, 2); } else { sValue = formatNumber(data[index]["stock_" + day], 2); }
                                    //     html += '<span class="' + negative + '">' + sValue + '</span>';
                                    // html += '</td>';

                                    row_val += 1;
                                    if (data[index]["stock_" + day] == null) { sValue = 0; } else { sValue = data[index]["stock_" + day]; }
                                    var ccolor = set_bg_uom(data[index].stock_min, data[index].stock_max, sValue);
                                    if (ccolor == 'bg-red') {
                                        empty_val += 1;
                                    } else if (ccolor == 'bg-yellow') {
                                        low_val += 1;
                                    } else if (ccolor == 'bg-green') {
                                        medium_val += 1;
                                    } else if (ccolor == 'bg-blue') {
                                        hight_val += 1;
                                    }

                                    // html += '<td class="text-right" style="background-color: ' + set_bg_uom(data[index].stock_min, data[index].stock_max, sValue) + ';">';
                                    html += '<td class="text-right ' + ccolor + '">';
                                        negative = '';
                                        // if (sValue < 0) { negative = 'negative'; } else if (sValue > 0) { negative = 'positive'; }
                                        html += '<span class="' + negative + '">' + formatNumber(sValue, 2) + '</span>';
                                    html += '</td>';
                                }
                            html += '</tr>';
                        }
                    html += '</tbody>';
                html += '</table>';

                set_card(row_val, empty_val, low_val, medium_val, hight_val);
                return html;
            }
        }

        function select_mode_yearly() {
            let selectedValue = document.getElementById("mode").value;

            chk = document.getElementsByClassName('detail');
            detail_stock = document.getElementsByClassName('detail_stock');
            if (selectedValue == 'Simple') {
                document.querySelector('.colspan-beginning').colSpan = 2;

                document.querySelectorAll('.colspan-day').forEach(c => {
                    c.colSpan = 1;
                    c.rowSpan = 2;
                });

                // detail_stock

                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = true;
                }

                for (var i = 0; i < detail_stock.length; i++) {
                    detail_stock[i].hidden = true;
                }
            } else if (selectedValue == 'Detail') {
                document.querySelector('.colspan-beginning').colSpan = 6;

                document.querySelectorAll('.colspan-day').forEach(c => {
                    c.colSpan = 6;
                    c.rowSpan = 1;
                });

                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = false;
                }

                for (var i = 0; i < detail_stock.length; i++) {
                    detail_stock[i].hidden = false;
                }
            }
        }

        function export_yearly() {
            let table = document.getElementById("tbl-yearly");

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
            a.download = "yearly_" + moment(new Date()).format('DD_MM_YYYY_HH_mm_ss') + ".xls";
            a.click();
        }
    </script>
<?php end_section('page-script-yearly'); ?>

