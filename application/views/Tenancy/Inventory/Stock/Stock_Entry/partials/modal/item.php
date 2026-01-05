<?php start_section('page-content-modal-item'); ?>
    <div class="modal fade" id="modal-item">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-capitalize">item</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbl-item" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">items</th>
                                    <th class="text-center text-capitalize">stock</th>
                                    <th class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-item"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php end_section('page-content-modal-item'); ?>

<?php start_section('page-script-content-modal-item'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl-item').DataTable();
        });

        function open_modal_item() {
            var building_code = document.getElementById("building_code");

            if (building_code.value == "") {
                alert("Please select area/building first.");
                return;
            }

            var selectedOption = building_code.options[building_code.selectedIndex];
            var area = selectedOption.getAttribute('data-area');

            var table = $('#tbl-item').DataTable();
            table.clear().draw();

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/load_item') ?>/" + area,

                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(data) {
                    if (data ) {
                        for (let index = 0; index < data.length; index++) {
                            table.row
                            .add([
                                data[index].KdBarang + ' - ' + data[index].NmBarang,
                                col_modal_item_custom(data[index], 'stock'),
                                col_modal_item_custom(encodeURIComponent(JSON.stringify(data[index])), 'button'),
                            ])
                            .draw(false);
                        }

                        $('#modal-item').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }

        function col_modal_item_custom(data, type) {
            var html = '';
            if (type == 'stock') {
                html += '<div style="display: flex; justify-content: right; align-items: center;">';
                    html += '<span class="text-capitalize">' + data.Jumlah + ' ' + data.Satuan + '</span>';
                html += '</div>';
            } else if (type == 'button') {
                html += '<div style="display: flex; justify-content: center; align-items: center;">';
                html += '<a href="#" class="label" style="background-color:black; color:white;"';
                    html += 'onclick="selectItem(`' + data + '`, `select`)"';
                    html += 'data-dismiss="modal"';
                html += '>';
                    html += '<span class="text-capitalize">select</span>';
                html += '</a>';
                html += '</div>';
            }

            return html;
        }

        function selectItem(data, type = 'select') {
            if (type == 'select') {
                var xdata = JSON.parse(decodeURIComponent(data));
            } else {
                var xdata = data;
            }

            add_item(xdata, type);
        }

        function add_item(data, type="select") {
            var total_element = $(".rowCount-item").length;
            if (total_element > 0) {
                if (!check_validation(data)) {
                    return;
                }
            }

            if (total_element == 0) {
                document.getElementById("tbody-item").innerHTML = "";
            }

            var total_element = $(".rowCount-item").length;
            var rows = 1;

            if (total_element > 0) {
                var lastid = $(".rowCount-item:last").attr("id");
                var split_id = lastid.split("_");
                rows = Number(split_id[1]) + 1;
            }

            if (type == "select") {
                var id = data.LedgerNo;
                var name = data.KdBarang + ' - ' + data.NmBarang;
                var beginning_qty = data.Jumlah ? data.Jumlah : 0;
                var qty = 1;
                var ending_qty = 1;
                var uom = data.Satuan;
                var status = null;
                var note = null;
            } else if (type == "old") {
                var id = data.LedgerNo;
                var name = data.NmBarang;
                var beginning_qty = formatDecimal(data.beginning_qty, 0);
                var qty = formatDecimal(data.qty, 0);
                var ending_qty = formatDecimal(data.ending_qty, 0);
                var uom = data.Satuan;
                var status = data.status;
                var note = data.note;
            }

            var html="";
            html +='<tr class="rowCount-item" id="rowCount-item_' + rows + '">';
                html +='<td class="fw-semibold">';
                    html +='<input type="hidden" class="form-control" id="item[' + rows + '][LedgerNo]" name="item[' + rows + '][LedgerNo]" value="' + id + '" />';
                    html +='<input type="hidden" class="form-control" id="item[' + rows + '][NmBarang]" name="item[' + rows + '][NmBarang]" value="' + name + '" />';
                    html +='<input type="hidden" class="form-control" id="item[' + rows + '][uom]" name="item[' + rows + '][uom]" value="' + uom + '" />';

                    html +=name;
                html +='</td>';

                html +='<td>';
                    html +='<input type="number" any="step" id="item[' + rows + '][beginning_qty]" name="item[' + rows + '][beginning_qty]" class="form-control beginning_qty" value="' + beginning_qty + '" style="text-align: right;" onkeyup="calculateQty()" readonly>'
                html +='</td>';

                html +='<td>';
                    html +='<input type="number" any="step" id="item[' + rows + '][qty]" name="item[' + rows + '][qty]" class="form-control qty" value="' + qty + '" style="text-align: right;" onkeyup="calculateQty()">'
                html +='</td>';

                html +='<td>';
                    html +='<input type="number" any="step" id="item[' + rows + '][ending_qty]" name="item[' + rows + '][ending_qty]" class="form-control ending_qty" value="' + ending_qty + '" style="text-align: right;" onkeyup="calculateQty()" readonly>'
                html +='</td>';

                html +='<td class="fw-semibold">';
                    html +=uom;
                html +='</td>';

                html +='<td class="fw-semibold">';
                    html +='<select name="item[' + rows + '][status]" data-id="' + rows + '" id="item[' + rows + '][status]" class="form-control" onchange="select_status(this)">';
                        for (let index = 0; index < fn_status.length; index++) {
                            html +='<option value="' + fn_status[index].name + '">' + fn_status[index].name + '</option>'
                        }
                    html +='</select>';
                html +='</td>';

                html +='<td class="fw-semibold">';
                    html +='<input type="text" id="item[' + rows + '][note]" name="item[' + rows + '][note]" class="form-control" value="">';
                html +='</td>';

                html +='<td class="text-center">';
                    html +='<button type="button" class="btn btn-danger btn-remove" onclick="remove_item(' + rows + ')"><i class="fa fa-trash"></i></button>';
                html +='</td>';
            html +='</tr>';

            $("#tbody-item").append(html);
            calculateQty();
        }

        function remove_item(rows) {
            if (document.getElementById("rowCount-item_" + rows)) {
                document.getElementById("rowCount-item_" + rows).remove();
            }

            var total_element = $(".rowCount-item").length;
            if (total_element == 0) {
                reset_item();
            }

            calculateQty();
        }

        function reset_item() {
            document.getElementById("tbody-item").innerHTML = '<tr><td class="text-center text-capitalize text-muted" colspan="6">no data record</td></tr>';
            calculateQty();
        }

        function check_validation(data) {
            // const rowCount = document.getElementsByClassName('rowCount-item');
            // if (rowCount) {
            // for (let index = 0; index < rowCount.length; index++) {
            //     var rows = 0;
            //     var lastid = rowCount[index].id;
            //     var split_id = lastid.split("_");
            //     rows = Number(split_id[1]);
                
            //     if (document.getElementById("item[" + rows + "][LedgerNo]").value == data.LedgerNo) {
            //             var qty = parseFloat(document.getElementById("item[" + rows + "][qty]").value);
            //             qty +=1;
            //             document.getElementById("item[" + rows + "][qty]").value = qty;
            //             calculateQty();
                        
            //             return false;
            //         }
            //     }
            // }

            return true;
        }

        function calculateQty() {
            const rowCount = document.getElementsByClassName('rowCount-item');
            var qty = 0;
            if (rowCount) {
                for (let index = 0; index < rowCount.length; index++) {
                    var rows = 0;
                    var lastid = rowCount[index].id;
                    var split_id = lastid.split("_");
                    rows = Number(split_id[1]);
                    
                    select_status(document.getElementById("item[" + rows + "][status]"));
                    qty += parseFloat(xnumber(document.getElementById("item[" + rows + "][qty]").value));
                }
            }

            document.getElementById("total_qty").value = qty
        }

        function select_status(element) {
            var row = element.getAttribute('data-id');

            if (element.value == "INVENTORY") {
                document.getElementById("item[" + row + "][ending_qty]").value = document.getElementById("item[" + row + "][qty]").value
            } else if (element.value == "ADJUSTMENT") {
                document.getElementById("item[" + row + "][ending_qty]").value = parseFloat(document.getElementById("item[" + row + "][beginning_qty]").value) + parseFloat(document.getElementById("item[" + row + "][qty]").value)
            } else if (element.value == "ABOLISH") {
                document.getElementById("item[" + row + "][ending_qty]").value = parseFloat(document.getElementById("item[" + row + "][beginning_qty]").value) - parseFloat(document.getElementById("item[" + row + "][qty]").value)
            }
        }
    </script>
<?php end_section('page-script-content-modal-item'); ?>