<?php start_section('view_detail'); ?>
    <!-- === START TENANT DETAIL === -->
    <div class="row">
        <div class="col">
            <label class="text-capitalize text-muted" style="padding-left: 15px; padding-right: 15px;">detail & summary</label>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="col-sm-3 control-label text-capitalize">owner</label>

                <div class="col-sm-9" style="border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEDED;">
                    <label class="text-capitalize">:</label>
                    <label class="text-capitalize" id="tenant_owner"></label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="col-sm-3 control-label text-capitalize">brand</label>

                <div class="col-sm-9" style="border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEDED;">
                    <label class="text-capitalize">:</label>
                    <label class="text-capitalize" id="tenant_brand"></label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label class="col-sm-3 control-label text-capitalize">product</label>

                <div class="col-sm-9" style="border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEDED;">
                    <label class="text-capitalize">:</label>
                    <label class="text-capitalize" id="tenant_product"></label>
                </div>
            </div>
        </div>
    </div>
    <!-- === END TENANT DETAIL === -->






    <!-- === START TAB UNIT & SUMMARY === -->
    <ul class="nav nav-tabs" style="margin-top: 20px;">
        <li class="active">
            <a data-toggle="tab" href="#tab_detail_unit" class="text-capitalize">detail</a>
        </li>

        <li>
            <a data-toggle="tab" href="#tab_detail_summary" class="text-capitalize">summary</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- === START UNIT === -->
        <div id="tab_detail_unit" class="tab-pane fade in active">
            <div id="detail_unit" style="margin: 15px;">
                <div class="row" style="margin-top: 20px;">
                    <div class="col rounded-md" style="padding: 15px; border-radius: 5px; background-color:#B500FA;">
                        <!-- === START SIZE === -->
                        <div class="row">
                            <div class="col">
                                <label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">sized</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">type</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Unit Type" required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">unit</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Unit Name" required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">size</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Size" required readonly>
                                            <span class="input-group-addon">m<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- === END SIZE === -->





                        <!-- === START PRICE === -->
                        <div class="row" style="padding-top: 30px;">
                            <div class="col">
                                <label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">price</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">price</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="Price" required readonly>
                                            <span class="input-group-addon">Rp / m<sup>2</sup> (Inc. Tax)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">dicount</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="Dicount" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">after discount</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="After Dicount" required readonly>
                                            <span class="input-group-addon">Rp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- === END PRICE === -->





                        <!-- === START PRICE === -->
                        <div class="row" style="padding-top: 30px;">
                            <div class="col">
                                <label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">service charge</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">rate</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="Rate" required>
                                            <span class="input-group-addon">Rp / m<sup>2</sup> (Excl. Tax)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">dicount</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="Dicount" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label text-capitalize" style="color:white;">after discount</label>

                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="number" any="step" class="form-control" placeholder="After Dicount" required readonly>
                                            <span class="input-group-addon">Rp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- === END PRICE === -->



                        <div class="row" style="margin-top: 20px; margin-right: 2px;">
                            <div class="col" style="display: flex; justify-content: right; align-items: center;">
                                <button type="button" class="btn btn-danger btn-sm align-middle">
                                    <i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">remove</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- === END UNIT === -->

        <!-- === START RESUME === -->
        <div id="tab_detail_summary" class="tab-pane fade">
            <div class="row">
                <div class="col" style="padding-top: 15px; padding-left: 15px; padding-right: 15px;">
                    <table class="table table-bordered table-sm">
                        <thead style="background-color:black; color:white;">
                            <tr>
                                <th class="text-center text-capitalize" style="padding:4px;">summary</th>
                                <th class="text-center text-capitalize" style="padding:4px; width:90px;">per items</th>
                                <th class="text-center text-capitalize" style="padding:4px; width:90px;">per month</th>
                                <th class="text-center text-capitalize" style="padding:4px; width:90px;">total</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-summary">
                            <tr><td class="text-capitalize text-center text-muted" colspan="4">no data record</td></tr>
                        </tbody>
                        <tfooter>
                            <tr style="background-color:#666666; color:white;">
                                <td class="text-capitalize text-left" colspan="2">total (without tax)</td>
                                <td class="text-capitalize text-right" id="sum_month_without_tax">0</td>
                                <td class="text-capitalize text-right" id="sum_total_without_tax">0</td>
                            </tr>
                            <tr style="background-color:#666666; color:white;">
                                <td class="text-capitalize text-left" colspan="3">total (with tax)</td>
                                <td class="text-capitalize text-right" id="sum_total_with_tax">0</td>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
        <!-- === END RESUME === -->
    </div>
    <!-- === END TAB UNIT & SUMMARY === -->






    <!-- === START ADD & REMOVE ALL UNIT === -->
    <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="col" style="display: flex; justify-content: center; align-items: center;">
            <button type="button" class="btn btn-sm align-middle" style="background-color:black; color:white; margin-right: 5px;"
                onclick="open_unit()"
            >
                <i class="fa fa-plus"></i><span class = "text-capitalize" style="margin-left: 5px;">add unit</span>
            </button>

            <button type="button" class="btn btn-danger btn-sm align-middle"
                onclick="clear_unit()"
            >
                <i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">clear</span>
            </button>
        </div>
    </div>
    <!-- === END ADD & REMOVE ALL UNIT === -->





    <!-- === START BACKGROUD EMPTY === -->
    <div class="row" id="detail_empty" style="margin: 1px; margin-bottom: 10px;">
        <div class="col rounded-md" style="padding: 15px; border-radius: 5px; background-color:#B500FA; display: flex; justify-content: center; align-items: center;">
            <div style="width: 85%;">
                <?php $this->view('component/svg/office-amico.svg'); ?>
            </div>
        </div>
    </div>
    <!-- === END BACKGROUD EMPTY === -->
<?php end_section('view_detail'); ?>


<?php start_section('script_detail'); ?>
    <script type="text/javascript">
        init_detail();
        function init_detail() {
            document.getElementById("tenant_owner").innerHTML = '';
            document.getElementById("tenant_brand").innerHTML = '';
            document.getElementById("tenant_product").innerHTML = '';
            document.getElementById("detail_unit").innerHTML = '';
            document.getElementById("tbody-summary").innerHTML = reset_detail_tbl();
            document.getElementById("detail_empty").hidden = false;

            set_tenant(fn_tenant_id);
            set_detail();
        }

        function set_tenant(id) {
            if (id) {
                if (fn_input_type == 'edit') {
                    document.getElementById("tenant_owner").innerHTML = fn_result.tenant ? fn_result.tenant.owner : null;
                    document.getElementById("tenant_brand").innerHTML = fn_result.tenant ? fn_result.tenant.brand : null;
                    document.getElementById("tenant_product").innerHTML = fn_result.tenant ? fn_result.tenant.product : null;
                } else if (fn_input_type == 'create') {
                    var tenant = <?php echo json_encode($tenant); ?>;
                    for (let index = 0; index < tenant.length; index++) {
                        if (tenant[index].id == id) {
                            document.getElementById("tenant_owner").innerHTML = tenant[index].owner;
                            document.getElementById("tenant_brand").innerHTML = tenant[index].brand;
                            document.getElementById("tenant_product").innerHTML = tenant[index].product;

                            return;
                        }
                    }
                }
            }
        }

        function set_detail() {
            if (fn_detail) {
                Object.keys(fn_detail).forEach(key => {
                    if (old) {
                        selectUnit(fn_detail[key], true);
                    } else {
                        selectUnit(fn_detail[key], false);
                    }
                });
            }
        }

        function reset_detail_tbl() {
            var html = '';
            html += '<tr><td class="text-capitalize text-center text-muted" colspan="4">no data record</td></tr>';
            return html;
        }

        function open_unit() {
            if (document.getElementById("building_id").value == '') {
                alert('Building need value!');
                $('#modal-building').modal('toggle');
            } else {
                var id = document.getElementById("building_id").value;
                var table = $('#tbl-unit').DataTable();
                table.clear().draw();

                $.ajax({
                    dataType: "json",
                    type: "GET",
                    url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/select_unit_by_building') ?>/" + id,

                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(data) {
                        for (let index = 0; index < data.length; index++) {
                            table.row
                            .add([
                                data[index].code,
                                data[index].name,
                                data[index].unit_type,
                                col_modal_unit_custom(data[index].unit_size, 'unit_size'),
                                col_modal_unit_custom(data[index].basic_price, 'basic_price'),
                                col_modal_unit_custom(data[index].is_active, 'is_active'),
                                col_modal_unit_custom(encodeURIComponent(JSON.stringify(data[index])), 'button'),
                            ])
                            .draw(false);
                        }

                        $('#modal-unit').modal('toggle');
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        return;
                    }
                });
            }
        }

        function clear_unit() {
            document.getElementById("detail_unit").innerHTML = '';
            document.getElementById("detail_empty").hidden = false;
            document.getElementById("tbody-summary").innerHTML = reset_detail_tbl();
            calculate();
        }

        function col_modal_unit_custom(data, type) {
            var html = '';
            if (type == 'unit_size') {
                return '<span class="pull-right">' + formatDecimal(data, 2) + ' m<sup>2</sup></span>';
            } else if (type == 'basic_price') {
                return '<span class="pull-right">' + formatNumber(data, 2) + '</span>';
            } else if (type == 'is_active') {
                var checked = data == 1 ? "checked" : "";
                html += '<div style="display: flex; justify-content: center; align-items: center;">';
                    html += '<input type="checkbox" ' + checked + ' disabled>';
                html += '</div>';
                
                return html;
            } else if (type == 'button') {
                html += '<div style="display: flex; justify-content: center; align-items: center;">';
                    html += '<a href="#" class="label" style="background-color:black; color:white;"';
                        html += 'onclick="selectUnit(`' + data + '`, false, true)"';
                        html += 'data-dismiss="modal"';
                    html += '>';
                        html += '<span class="text-capitalize">select</span>';
                    html += '</a>';
                html += '</div>';

                return html;
            }
        }

        function selectUnit(data, old = false, select = false) {
            if (select) {
                var xdata = JSON.parse(decodeURIComponent(data));
            } else {
                var xdata = data;
            }

            if (select) {
                if (!check_row_detail(xdata)) {
                    alert('Unit already exists!');
                    return;
                }
            }

            document.getElementById("detail_empty").hidden = true;
            create_row_detail(data, old, select);
            create_row_summary(data, old, select);
            calculate();
        }

        function check_row_detail(data) {
            const rowCount = document.getElementsByClassName('rowCount-unit');
            if (rowCount) {
                for (let index = 0; index < rowCount.length; index++) {
                    var rows = 0;
                    var lastid = rowCount[index].id;
                    var split_id = lastid.split("_");
                    rows = Number(split_id[1]);

                    if (document.getElementById("unit[" + rows + "][unit_id]").value == data.id) {
                        return false;
                    }
                }
            }

            return true;
        }

        function create_row_detail(data, old = false, select = false) {
            if (select) {
                data = JSON.parse(decodeURIComponent(data));
            }

            var total_element = $(".rowCount-unit").length;
            if (total_element == 0) {
                document.getElementById("detail_unit").innerHTML = '';
            }

            var total_element = $(".rowCount-unit").length;
            var rows = 1;

            if (total_element > 0) {
                var lastid = $(".rowCount-unit:last").attr("id");
                var split_id = lastid.split("_");
                rows = Number(split_id[1]) + 1;
            }

            var html="";
            html +='<div class="row rowCount-unit" id="rowCount-unit_' + rows + '" style="margin-top: 20px;">';
                html +='<div class="col rounded-md" style="padding: 15px; border-radius: 5px; background-color:#B500FA;">';
                    // START SIZE **********************
                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">sized</label>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">type</label>';

                                html +='<div class="col-sm-9">';
                                    if (select) {
                                        var id = data.id;
                                    } else if (old) {
                                        var id = data.unit_id;
                                    } else if (fn_input_type == 'edit') {
                                        var id = data.unit_id;
                                    }

                                    html +='<input type="hidden" class="form-control" id="unit[' + rows + '][unit_id]" name="unit[' + rows + '][unit_id]" value="' + id + '">';

                                    if (select) {
                                        var unit_type = data.unit_type;
                                    } else if (old) {
                                        var unit_type = data.unit_type;
                                    } else if (fn_input_type == 'edit') {
                                        var unit_type = data.unit ? data.unit.unit_type : null;
                                    }

                                    html +='<input type="text" class="form-control" placeholder="Unit Type" id="unit[' + rows + '][unit_type]" name="unit[' + rows + '][unit_type]" value="' + unit_type + '" required readonly>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">unit</label>';

                                html +='<div class="col-sm-9">';
                                    if (select) {
                                        var name = data.name;
                                    } else if (old) {
                                        var name = data.unit_name;
                                    } else if (fn_input_type == 'edit') {
                                        var name = data.unit ? data.unit.name : null;
                                    }

                                    html +='<input type="text" class="form-control" placeholder="Unit Name" id="unit[' + rows + '][unit_name]" name="unit[' + rows + '][unit_name]" value="' + name + '" required readonly>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">size</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">m<sup>2</sup></span>';
                                        html +='<input type="text" class="form-control" placeholder="Size" id="unit[' + rows + '][unit_size]" name="unit[' + rows + '][unit_size]" value="' + formatNumber(xnumber(data.unit_size), 2) + '" required readonly>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';
                    // END SIZE **********************





                    // START PRICE **********************
                    html +='<div class="row" style="padding-top: 30px;">';
                        html +='<div class="col">';
                            html +='<label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">price</label>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">price</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">Rp / m<sup>2</sup> (Inc. Tax)</span>';
                                        if (select) {
                                            var basic_price = data.basic_price;
                                        } else if (old) {
                                            var basic_price = data.unit_price;
                                        } else if (fn_input_type == 'edit') {
                                            var basic_price = data.unit ? data.unit.basic_price : null;
                                        }

                                        html +='<input type="text" class="form-control" placeholder="Price" id="unit[' + rows + '][unit_price]" name="unit[' + rows + '][unit_price]" value="' + formatNumber(xnumber(basic_price), 2) + '" required readonly>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">dicount</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">%</span>';
                                        if (select) {
                                            var unit_discount = "0";
                                        } else if (old) {
                                            var unit_discount = data.unit_discount;
                                        } else if (fn_input_type == 'edit') {
                                            var unit_discount = data.unit_discount;
                                        }

                                        html +='<input type="number" any="step" class="form-control" placeholder="Dicount" id="unit[' + rows + '][unit_discount]" name="unit[' + rows + '][unit_discount]" value="' + formatDecimal(xnumber(unit_discount), 2) + '" required onkeyup="calculate()">';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">after discount</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">Rp</span>';
                                        html +='<input type="text" class="form-control" placeholder="After Dicount" id="unit[' + rows + '][unit_after_discount]" name="unit[' + rows + '][unit_after_discount]" value="' + formatNumber(xnumber(basic_price), 2) + '" required readonly>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';
                    // END PRICE ***************************
                    




                    // START CHARGE ***************************
                    html +='<div class="row" style="padding-top: 30px;">';
                        html +='<div class="col">';
                            html +='<label class="text-capitalize" style="padding-left: 15px; padding-right: 15px; color:white; font-size: 20px;text-decoration: underline;">service charge</label>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">rate</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">Rp / m<sup>2</sup> (Excl. Tax)</span>';
                                        if (select) {
                                            var charge_rate = "0";
                                        } else if (old) {
                                            var charge_rate = data.charge_rate;
                                        } else if (fn_input_type == 'edit') {
                                            var charge_rate = data.charge_rate;
                                        }

                                        html +='<input type="number" any="step" class="form-control" id="unit[' + rows + '][charge_rate]" name="unit[' + rows + '][charge_rate]" value="' + formatDecimal(xnumber(charge_rate), 2) + '" onkeyup="calculate()" placeholder="Rate" required>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">dicount</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">%</span>';
                                        if (select) {
                                            var charge_discount = "0";
                                        } else if (old) {
                                            var charge_discount = data.charge_discount;
                                        } else if (fn_input_type == 'edit') {
                                            var charge_discount = data.charge_discount;
                                        }

                                        html +='<input type="number" any="step" class="form-control" id="unit[' + rows + '][charge_discount]" name="unit[' + rows + '][charge_discount]" value="' + formatDecimal(xnumber(charge_discount)) + '" onkeyup="calculate()" placeholder="Discount" required>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';

                    html +='<div class="row">';
                        html +='<div class="col">';
                            html +='<div class="form-group">';
                                html +='<label class="col-sm-3 control-label text-capitalize" style="color:white;">after discount tax</label>';

                                html +='<div class="col-sm-9">';
                                    html +='<div class="input-group">';
                                        html +='<span class="input-group-addon">Rp</span>';
                                        html +='<input type="text" class="form-control" placeholder="After Dicount" id="unit[' + rows + '][charge_after_discount]" name="unit[' + rows + '][charge_after_discount]" required readonly>';
                                    html +='</div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';
                    // END CHARGE ***********************************
                    


                    html +='<div class="row" style="margin-top: 20px; margin-right: 2px;">';
                        html +='<div class="col" style="display: flex; justify-content: right; align-items: center;">';
                            html +='<button type="button" class="btn btn-danger btn-sm align-middle" onclick="remove_unit(' + rows + ')">';
                                html +='<i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">remove</span>';
                            html +='</button>';
                        html +='</div>';
                    html +='</div>';
                html +='</div>';
            html +='</div>';

            $("#detail_unit").append(html);
        }

        function create_row_summary(data, old = false, select = false) {
            if (select) {
                data = JSON.parse(decodeURIComponent(data));
            }

            var total_element = $(".rowCount-summary").length;
            if (total_element == 0) {
                document.getElementById("tbody-summary").innerHTML = '';
            }

            var total_element = $(".rowCount-summary").length;
            var rows = 1;

            if (total_element > 0) {
                var lastid = $(".rowCount-summary:last").attr("id");
                var split_id = lastid.split("_");
                rows = Number(split_id[1]) + 1;
            }

            var html="";
            if (select) {
                var name = data.name;
            } else if (old) {
                var name = data.unit_name;
            } else if (fn_input_type == 'edit') {
                var name = data.unit ? data.unit.name : null;
            }

            if (select) {
                var unit_type = data.unit_type;
            } else if (old) {
                var unit_type = data.unit_type;
            } else if (fn_input_type == 'edit') {
                var unit_type = data.unit ? data.unit.unit_type : null;
            }

            html +='<tr class="rowCount-summary" id="rowCount-summary_' + rows + '">';
                html +='<td>';
                    html += 'Unit ' + unit_type + '-' + name;
                html +='</td">';

                html +='<td class="text-right" id="col_sum_unit_per_item_' + rows + '"></td">';
                html +='<td class="text-right" id="col_sum_unit_per_month_' + rows + '"></td">';
                html +='<td class="text-right" id="col_sum_unit_per_total_' + rows + '"></td">';
            html +='</tr">';

            html +='<tr class="rowCount-summary-charge" id="rowCount-summary-charge_' + rows + '">';
                html +='<td>';
                    html += 'Service Charge ' + unit_type + '-' + name;
                html +='</td">';

                html +='<td class="text-right" id="col_sum_charge_per_item_' + rows + '"></td">';
                html +='<td class="text-right" id="col_sum_charge_per_month_' + rows + '"></td">';
                html +='<td class="text-right" id="col_sum_charge_per_total_' + rows + '"></td">';
            html +='</tr">';

            $("#tbody-summary").append(html);
        }

        function remove_unit(rows) {
            if (document.getElementById("rowCount-unit_" + rows)) {
                document.getElementById("rowCount-unit_" + rows).remove();
            }

            if (document.getElementById("rowCount-summary_" + rows)) {
                document.getElementById("rowCount-summary_" + rows).remove();
            }

            if (document.getElementById("rowCount-summary-charge_" + rows)) {
                document.getElementById("rowCount-summary-charge_" + rows).remove();
            }

            var total_element = $(".rowCount-unit").length;
            if (total_element == 0) {
                clear_unit();
            }

            calculate();
        }

        function calculate_detail(rows) {
            var unit_size = xnumber(document.getElementById("unit[" + rows + "][unit_size]").value);
            var month_period = xnumber(document.getElementById("month_period").value);

            // unit **************
            var unit_price = xnumber(document.getElementById("unit[" + rows + "][unit_price]").value);
            var unit_discount = xnumber(document.getElementById("unit[" + rows + "][unit_discount]").value);
            var unit_after_discount = parseFloat(unit_price) - (parseFloat(unit_price) * parseFloat(unit_discount) / 100);
            document.getElementById("unit[" + rows + "][unit_after_discount]").value = formatNumber(unit_after_discount, 2);
            document.getElementById("col_sum_unit_per_item_" + rows).innerHTML = formatNumber(unit_after_discount, 2);
            document.getElementById("col_sum_unit_per_month_" + rows).innerHTML = formatNumber(parseFloat(unit_after_discount) * parseFloat(unit_size), 2);
            document.getElementById("col_sum_unit_per_total_" + rows).innerHTML = formatNumber((parseFloat(unit_after_discount) * parseFloat(unit_size)) * parseFloat(month_period), 2);

            // charge **************
            var charge_rate = xnumber(document.getElementById("unit[" + rows + "][charge_rate]").value);
            var charge_discount = xnumber(document.getElementById("unit[" + rows + "][charge_discount]").value);
            var charge_after_discount = parseFloat(charge_rate) - (parseFloat(charge_rate) * parseFloat(charge_discount) / 100);
            var tax = xnumber(document.getElementById("tax").value);
            var charge_after_tax = parseFloat(charge_after_discount) + (parseFloat(charge_after_discount) * parseFloat(tax) / 100);
            document.getElementById("unit[" + rows + "][charge_after_discount]").value = formatNumber(charge_after_tax, 2);
            document.getElementById("col_sum_charge_per_item_" + rows).innerHTML = formatNumber(charge_after_tax, 2);
            document.getElementById("col_sum_charge_per_month_" + rows).innerHTML = formatNumber(parseFloat(charge_after_tax) * parseFloat(unit_size), 2);

            var month_of_year = 12; // 1 year = 12 month (on first year)
            document.getElementById("col_sum_charge_per_total_" + rows).innerHTML = formatNumber((parseFloat(charge_after_tax) * parseFloat(unit_size)) * parseFloat(month_of_year), 2);
        }
    </script>
<?php end_section('script_detail'); ?>

