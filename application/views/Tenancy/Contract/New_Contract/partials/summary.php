<?php start_section('view_summary'); ?>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="unit_qty" class="col-sm-3 control-label text-capitalize">unit</label>

                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" any="step" class="form-control" id="unit_qty" name="unit_qty" placeholder="Qty Unit"
                            style="text-align: right;"
                            <?= invalid('unit_qty') ?>
                            required readonly
                        >
                        <span class="input-group-addon">Unit</span>
                    </div>
                    <?= error('unit_qty') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="month_period" class="col-sm-3 control-label text-capitalize">period</label>

                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" any="step" class="form-control" id="month_period" name="month_period" placeholder="Month Period" style="text-align: right;" required
                            onkeyup="set_date_to()"
                            <?= invalid('month_period') ?>
                        >
                        <span class="input-group-addon">Month</span>
                    </div>
                    <?= error('month_period') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- === START UNIT PRICE === -->
    <div class="row" style="margin-top: 20px;">
        <div class="col">
            <label class="text-capitalize text-muted" style="padding-left: 15px; padding-right: 15px;">unit price m<sup>2</sup>/month</label>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="total_indoor" class="col-sm-3 control-label text-capitalize">indoor</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="total_indoor" name="total_indoor" placeholder="Total Indoor" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="total_outdoor" class="col-sm-3 control-label text-capitalize">outdoor</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="total_outdoor" name="total_outdoor" placeholder="Total Outdoor" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>
    <!-- === END UNIT PRICE === -->




    <!-- === START CHARGE === -->
    <div class="row" style="margin-top: 20px;">
        <div class="col">
            <label class="text-capitalize text-muted" style="padding-left: 15px; padding-right: 15px;">service charge m<sup>2</sup>/month</label>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="charge_indoor" class="col-sm-3 control-label text-capitalize">indoor</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="charge_indoor" name="charge_indoor" placeholder="Charge Indoor" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="charge_outdoor" class="col-sm-3 control-label text-capitalize">outdoor</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="charge_outdoor" name="charge_outdoor" placeholder="Charge Outdoor" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>
    <!-- === END CHARGE === -->




    <!-- === START TOTAL === -->
    <div class="row" style="margin-top: 20px;">
        <div class="col" style="margin-left: 15px; margin-right: 15px;">
            <ul class="nav nav-tabs">
                <li>
                    <a data-toggle="tab" href="#tab_item" class="text-capitalize">per item</a>
                </li>

                <li>
                    <a data-toggle="tab" href="#tab_month" class="text-capitalize">per month</a>
                </li>

                <li class="active">
                    <a data-toggle="tab" href="#tab_total" class="text-capitalize">total</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab_item" class="tab-pane fade">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="unit_total_per_item" class="col-sm-3 control-label text-capitalize">unit</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="unit_total_per_item" name="unit_total_per_item" placeholder="Unit Per Item" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="charge_total_per_item" class="col-sm-3 control-label text-capitalize">charge</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="charge_total_per_item" name="charge_total_per_item" placeholder="Charge Per Item" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" hidden>
                        <div class="col">
                            <div class="form-group">
                                <label for="without_tax_total_per_item" class="col-sm-3 control-label text-capitalize">Without Tax</label>

                                <div class="col-sm-9">
                                    <input type="number" any="step" class="form-control" id="without_tax_total_per_item" name="without_tax_total_per_item" placeholder="Without Tax Per Item" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" hidden>
                        <div class="col">
                            <div class="form-group">
                                <label for="with_tax_total_per_item" class="col-sm-3 control-label text-capitalize">With Tax</label>

                                <div class="col-sm-9">
                                    <input type="number" any="step" class="form-control" id="with_tax_total_per_item" name="with_tax_total_per_item" placeholder="With Tax Per Item" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab_month" class="tab-pane fade">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="unit_total_per_month" class="col-sm-3 control-label text-capitalize">unit</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="unit_total_per_month" name="unit_total_per_month" placeholder="Unit Per Month" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="charge_total_per_month" class="col-sm-3 control-label text-capitalize">charge</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="charge_total_per_month" name="charge_total_per_month" placeholder="Charge Per Month" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="without_tax_total_per_month" class="col-sm-3 control-label text-capitalize">Without Tax</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="without_tax_total_per_month" name="without_tax_total_per_month" placeholder="Without Tax Per Month" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" hidden>
                        <div class="col">
                            <div class="form-group">
                                <label for="with_tax_total_per_month" class="col-sm-3 control-label text-capitalize">With Tax</label>

                                <div class="col-sm-9">
                                    <input type="number" any="step" class="form-control" id="with_tax_total_per_month" name="with_tax_total_per_month" placeholder="With Tax Per Month" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab_total" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="unit_total_per_grand" class="col-sm-3 control-label text-capitalize">unit</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="unit_total_per_grand" name="unit_total_per_grand" placeholder="Unit Total" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="charge_total_per_grand" class="col-sm-3 control-label text-capitalize">charge</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="charge_total_per_grand" name="charge_total_per_grand" placeholder="Charge Total" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="without_tax_total_per_grand" class="col-sm-3 control-label text-capitalize">Without Tax</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="without_tax_total_per_grand" name="without_tax_total_per_grand" placeholder="Without Tax Total" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="with_tax_total_per_grand" class="col-sm-3 control-label text-capitalize">With Tax</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="with_tax_total_per_grand" name="with_tax_total_per_grand" placeholder="With Tax Total" style="text-align: right;" required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- === END TOTAL === -->




    <!-- === START CALCULATE === -->
    <div class="row" style="margin-top: 20px;">
        <div class="col">
            <label class="text-capitalize text-muted" style="padding-left: 15px; padding-right: 15px;">calculate</label>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="tax" class="col-sm-3 control-label text-capitalize">tax</label>

                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" any="step" class="form-control" id="tax" name="tax" placeholder="Tax" style="text-align: right;" required onkeyup="calculate()">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="total_total" class="col-sm-3 control-label text-capitalize">grandtotal</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="total_total" name="total_total" placeholder="Grandtotal" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="charge_indoor" class="col-sm-3 control-label">DP</label>

                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" any="step" class="form-control" id="down_payment" name="down_payment" placeholder="Down Payment" style="text-align: right;" required onkeyup="calculate_payment()">
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="security_deposite" class="col-sm-3 control-label text-capitalize">deposit</label>

                <div class="col-sm-9">
                    <input type="number" any="step" class="form-control" id="security_deposite" name="security_deposite" placeholder="Security Deposit" style="text-align: right;" required onkeyup="calculate_payment()">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="fitting_out" class="col-sm-3 control-label text-capitalize">fitting</label>

                <div class="col-sm-9">
                    <input type="number" any="step" class="form-control" id="fitting_out" name="fitting_out" placeholder="Fitting Out" style="text-align: right;" required onkeyup="calculate_payment()">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="total_payment" class="col-sm-3 control-label text-capitalize">payment</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="total_payment" name="total_payment" placeholder="Total Payment"
                        style="text-align: right;" required readonly
                        <?= invalid('total_payment') ?>
                    >
                    <?= error('total_payment') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="total_balance" class="col-sm-3 control-label text-capitalize">balance</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" id="total_balance" name="total_balance" placeholder="Balance" style="text-align: right;" required readonly>
                </div>
            </div>
        </div>
    </div>
    <!-- === END CALCULATE === -->


    <div class="row" style="margin-top: 40px; margin-right: 2px;">
        <div class="col">
            <button type="button" class="btn pull-right" style="background-color:black; color:white;"
                onclick="location.href='<?=site_url('Tenancy/Contract/New_Contract/index')?>'"
            >
                <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
            </button>

            <?php if ($input_type == 'create') { ?>
                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">
                    <i class="fa fa-save"></i><span style="margin-left: 5px;">Save</span>
                </button>
            <?php } else if ($input_type == 'edit') { ?>
                <button type="submit" class="btn btn-warning pull-right" style="margin-right: 10px;">
                    <i class="fa fa-save"></i><span style="margin-left: 5px;">Update</span>
                </button>
            <?php } ?>
        </div>
    </div>
<?php end_section('view_summary'); ?>






<?php start_section('script_summary'); ?>
    <script type="text/javascript">
        init_summary();
        function init_summary() {
            document.getElementById("month_period").value = fn_month_period;
            document.getElementById("total_indoor").value = fn_total_indoor;
            document.getElementById("total_outdoor").value = fn_total_outdoor;
            document.getElementById("charge_indoor").value = fn_charge_indoor;
            document.getElementById("charge_outdoor").value = fn_charge_outdoor;
            
            document.getElementById("unit_total_per_item").value = fn_unit_total_per_item;
            document.getElementById("charge_total_per_item").value = fn_charge_total_per_item;
            document.getElementById("without_tax_total_per_item").value = fn_without_tax_total_per_item;
            document.getElementById("with_tax_total_per_item").value = fn_with_tax_total_per_item;
            document.getElementById("unit_qty").value = fn_unit_qty;

            document.getElementById("unit_total_per_month").value = fn_unit_total_per_month;
            document.getElementById("charge_total_per_month").value = fn_charge_total_per_month;
            document.getElementById("without_tax_total_per_month").value = fn_without_tax_total_per_month;
            document.getElementById("with_tax_total_per_month").value = fn_with_tax_total_per_month;

            document.getElementById("unit_total_per_grand").value = fn_unit_total_per_grand;
            document.getElementById("charge_total_per_grand").value = fn_charge_total_per_grand;
            document.getElementById("without_tax_total_per_grand").value = fn_without_tax_total_per_grand;
            document.getElementById("with_tax_total_per_grand").value = fn_with_tax_total_per_grand;

            document.getElementById("tax").value = fn_tax;
            document.getElementById("total_total").value = fn_with_tax_total_per_grand;
            document.getElementById("down_payment").value = fn_down_payment;
            document.getElementById("security_deposite").value = fn_security_deposite;
            document.getElementById("fitting_out").value = fn_fitting_out;
            document.getElementById("total_payment").value = 0;
            document.getElementById("total_balance").value = 0;

            set_period();
            calculate();
        }

        function set_period(){
            const start_date = new Date(document.getElementById("tenant_start_date_operation").value);
            const end_date = new Date(document.getElementById("tenant_end_date_operation").value);
            const diffDays = (end_date.getFullYear() - start_date.getFullYear())*12 + (end_date.getMonth() - start_date.getMonth());
            document.getElementById("month_period").value = formatDecimal(diffDays, 0);

            calculate();
        }

        function set_date_to() {
            const day_from = new Date(document.getElementById("tenant_start_date_operation").value);
            var day_to = moment(day_from).add(document.getElementById("month_period").value, 'months');
            day_to = new Date(day_to);
            document.getElementById("tenant_end_date_operation").value = moment(day_to).format('DD-MMM-YYYY');

            calculate();
        }

        function calculate() {
            // calculate detail *****************
            const rowCount = document.getElementsByClassName('rowCount-unit');
            var total_unit = 0;
            var unit_indoor = 0;
            var unit_outdoor = 0;
            var charge_indoor = 0;
            var charge_outdoor = 0;
            var total_indoor_outdoor_unit = 0;
            var total_indoor_outdoor_charge = 0;

            var total_unit_per_item = 0;
            var total_charge_per_item = 0;
            var total_unit_per_month = 0;
            var total_charge_per_month = 0;
            var total_unit_per_total = 0;
            var total_charge_per_total = 0;

            if (rowCount) {
                for (let index = 0; index < rowCount.length; index++) {
                    total_unit += 1;
                    var rows = 0;
                    var lastid = rowCount[index].id;
                    var split_id = lastid.split("_");
                    rows = Number(split_id[1]);

                    calculate_detail(rows);

                    var unit_type = document.getElementById("unit[" + rows + "][unit_type]").value;
                    var unit_size = xnumber(document.getElementById("unit[" + rows + "][unit_size]").value);

                    total_indoor_outdoor_unit = xnumber(document.getElementById("unit[" + rows + "][unit_after_discount]").value);
                    total_indoor_outdoor_charge = xnumber(document.getElementById("unit[" + rows + "][charge_after_discount]").value);
                    
                    if (unit_type == "Indoor") {
                        unit_indoor += parseFloat(unit_size) * parseFloat(total_indoor_outdoor_unit);
                        charge_indoor += parseFloat(unit_size) * parseFloat(total_indoor_outdoor_charge);
                    } else if (unit_type == "Outdoor") {
                        unit_outdoor += parseFloat(unit_size) * parseFloat(total_indoor_outdoor_unit);
                        charge_outdoor += parseFloat(unit_size) * parseFloat(total_indoor_outdoor_charge);
                    }

                    total_unit_per_item += parseFloat(xnumber(document.getElementById("col_sum_unit_per_item_" + rows).innerHTML));
                    total_charge_per_item += parseFloat(xnumber(document.getElementById("col_sum_charge_per_item_" + rows).innerHTML));

                    total_unit_per_month += parseFloat(xnumber(document.getElementById("col_sum_unit_per_month_" + rows).innerHTML));
                    total_charge_per_month += parseFloat(xnumber(document.getElementById("col_sum_charge_per_month_" + rows).innerHTML));

                    total_unit_per_total += parseFloat(xnumber(document.getElementById("col_sum_unit_per_total_" + rows).innerHTML));
                    total_charge_per_total += parseFloat(xnumber(document.getElementById("col_sum_charge_per_total_" + rows).innerHTML));
                }
            }
            // calculate detail *****************

            document.getElementById("unit_qty").value = formatNumber(total_unit, 0);
            document.getElementById("total_indoor").value = formatNumber(unit_indoor, 2);
            document.getElementById("total_outdoor").value = formatNumber(unit_outdoor, 2);
            document.getElementById("charge_indoor").value = formatNumber(charge_indoor, 2);
            document.getElementById("charge_outdoor").value = formatNumber(charge_outdoor, 2);

            document.getElementById("sum_month_without_tax").innerHTML = formatNumber(parseFloat(total_unit_per_month) + parseFloat(total_charge_per_month), 2);
            document.getElementById("sum_total_without_tax").innerHTML = formatNumber(parseFloat(total_unit_per_total) + parseFloat(total_charge_per_total), 2);
            document.getElementById("sum_total_with_tax").innerHTML = formatNumber(parseFloat(total_unit_per_total) + parseFloat(total_charge_per_total), 2);

            document.getElementById("unit_total_per_item").value = formatNumber(total_unit_per_item, 2);
            document.getElementById("charge_total_per_item").value = formatNumber(total_charge_per_item, 2);

            document.getElementById("unit_total_per_month").value = formatNumber(total_unit_per_month, 2);
            document.getElementById("charge_total_per_month").value = formatNumber(total_charge_per_month, 2);
            document.getElementById("without_tax_total_per_month").value = formatNumber(parseFloat(total_unit_per_month) + parseFloat(total_charge_per_month), 2);

            document.getElementById("unit_total_per_grand").value = formatNumber(total_unit_per_total, 2);
            document.getElementById("charge_total_per_grand").value = formatNumber(total_charge_per_total, 2);
            document.getElementById("without_tax_total_per_grand").value = formatNumber(parseFloat(total_unit_per_total) + parseFloat(total_charge_per_total), 2);
            document.getElementById("with_tax_total_per_grand").value = formatNumber(parseFloat(total_unit_per_total) + parseFloat(total_charge_per_total), 2);

            document.getElementById("total_total").value = formatNumber(xnumber(document.getElementById("with_tax_total_per_grand").value), 2);
            calculate_payment();
        }

        function calculate_payment() {
            var grand_total = xnumber(document.getElementById("total_total").value);
            var dp = xnumber(document.getElementById("down_payment").value);
            var dp_value = parseFloat(grand_total) * parseFloat(dp) / 100;
            var deposite = xnumber(document.getElementById("security_deposite").value);
            var fitting = xnumber(document.getElementById("fitting_out").value);
            var payment = parseFloat(dp_value) + parseFloat(deposite) + parseFloat(fitting);
            var balance = parseFloat(payment) - parseFloat(grand_total);
            
            document.getElementById("total_payment").value = formatNumber(payment, 2);
            document.getElementById("total_balance").value = formatNumber(balance, 2);
        }
    </script>
<?php end_section('script_summary'); ?>