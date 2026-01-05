<?php start_section('page-script-monthly'); ?>
    <script>
        function load_monthly() {
            if (results.length == 0) {
                return '<div class="text-capitalize text-muted" style="font-size: 24px;">no data record</div>';
            }

            var date_trans = document.getElementById("date_from").value;
            var html = '';
            html += '<table id="tbl_incoming_monthly" class="table table-bordered table-striped" style="table-layout: fixed; width: 100%;">';
                html += '<colgroup>';
                    html += '<col style="width:300px;">';   // Area

                    // monthly column
                    for (let i = 1; i <= 12; i++) {
                        html += '<col style="width:80px;">'; //qty
                    }
                html += '</colgroup>';

                html += '<thead style="background-color: #B3B3B3; color: black; position:sticky; top:0; z-index:40;">';
                    html += '<tr style="position:sticky; top:0; z-index:41;">';
                        html += '<th class="text-capitalize" style="text-align: center; vertical-align: middle; position:sticky; left:0; top:0; z-index:60; background:#B3B3B3;">area</th>';

                            // monthly
                            for (let day = 1; day <= 12; day++) {
                                var dt = new Date(date_trans);
                                dt.setMonth(dt.getMonth() + (day - 1));

                                html += '<th style="text-align: center; vertical-align: middle;">' + moment(dt).format('YYYY-MMM') + '</th>';
                            }
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                    for (let index = 0; index < results.length; index++) {
                        html += '<tr>';
                            html += '<td class="text-capitalize" style="position:sticky; left:0; top:auto; background:#F2F2F2; z-index:30;">' + results[index].area + '</td>';
                            
                            // monthly
                            for (let day = 1; day <= 12; day++) {
                                html += '<td class="text-capitalize text-right">' + formatNumber(results[index]["day_" + day], 2) + '</td>';
                            }
                        html += '</tr>';
                    }
                html += '</tbody>';
            html += '</table>';

            return html;
        }
    </script>
<?php end_section('page-script-monthly'); ?>

