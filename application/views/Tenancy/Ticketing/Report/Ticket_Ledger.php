<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Report/Ticket_Ledger') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <form method="GET" action="<?= site_url('Tenancy/Ticketing/Report/Ticket_Ledger/index') ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">date from</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="date_from" name="date_from" placeholder="dd-MMM-yyyy" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">date to</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="date_to" name="date_to" placeholder="dd-MMM-yyyy" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize">location</label>
                                <select name="vanue_code" id="vanue_code" class="form-control" style="flex:1;"
                                    onchange="load_counter_gate(this.value);"
                                >
                                    <option value="all">--Select All--</option>
                                    <?php foreach ($venue as $row): ?>
                                        <option value="<?= $row->code ?>" <?= ($vanue_code == $row->code ? 'selected' : '') ?>>
                                            <?= $row->area ?> - <?= $row->code ?> - <?= $row->name ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="counter_code" class="control-label text-capitalize">cashier</label>
                                <select name="counter_code" id="counter_code" class="form-control">
                                    <option value="all">--Select All--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="gate_code" class="control-label text-capitalize">gate checkin</label>
                                <select name="gate_code" id="gate_code" class="form-control">
                                    <option value="all">--Select All--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="gate_checkout" class="control-label text-capitalize">gate checkout</label>
                                <select name="gate_checkout" id="gate_checkout" class="form-control">
                                    <option value="all">--Select All--</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6"></div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="barcode" class="control-label text-capitalize">category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="all"<?= ($category == 'all' ? 'selected' : '') ?>>--Select All--</option>
                                    <?php foreach ($categories as $row): ?>
                                        <option value="<?= $row->name ?>"<?= ($category == $row->name ? 'selected' : '') ?>><?= $row->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label for="barcode" class="control-label text-capitalize">barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" value="<?= isset($barcode) ? $barcode : '' ?>" placeholder="Barcode">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">checkout</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="is_checkout" id="is_checkout" class="form-control"
                                        style="flex:1;"
                                    >
                                        <option value="all"<?= ($is_checkout == 'all' ? 'selected' : '') ?>>--Select All--</option>
                                        <option value="checkout"<?= ($is_checkout == 'checkout' ? 'selected' : '') ?>>Checkout</option>
                                        <option value="not_yet"<?= ($is_checkout == 'not_yet' ? 'selected' : '') ?>>Not Yet</option>
                                    </select>

                                    <button type="submit" class="btn text-capitalize" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('report_ticket_ledger')"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-20">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3 id='checkin_statistic'>0</h3>
                                <p>Cashier (person)</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-20">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3 id='checkin_scan_statistic'>0</h3>
                                <p>Checkin (person)</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-20">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3 id='checkout_statistic'>0</h3>
                                <p>Checkout (person)</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-20">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3 id='balance_statistic'>0</h3>
                                <p>Balance (person)</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-20">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3 id='amount_statistic'>0</h3>
                                <p>Amount (IDR)</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <?php
                        $price = 0;
                        $discount_amount = 0;
                        $tax_amount = 0;
                        $total = 0;
                    ?>

                    <table id="report_ticket_ledger" class="table table-bordered">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="text-capitalize">No</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">area</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">location</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">series</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">barcode</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">date cashier</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">pos cashier</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">date checkin</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">gate checkin</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">date checkout</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">gate checkout</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">category</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">name</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">price</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">discount</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">tax</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($results): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($results as $row): ?>
                                    <!-- <tr class="<?= $row->checkout_date ? '' : 'bg-danger' ?>"> -->
                                    <tr class="
                                        <?php if ($row->seq) { ?>
                                            <?= $row->checkin_date ? ($row->checkout_date ? 'bg-success' : 'bg-warning') : 'bg-danger' ?>
                                        <?php } ?>
                                    ">
                                        <td class="text-right"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row->area_code ?></td>
                                        <td class="text-center"><?= $row->veneu_code ?></td>
                                        <td><?= $row->series ?></td>
                                        <td><?= $row->barcode ?></td>
                                        <td class="text-center"><?= $row->cashier_date ? date('d-M-Y H:i:s', strtotime($row->cashier_date)) : '' ?></td>
                                        <td class="text-center"><?= $row->pos_cashier ?></td>
                                        <td class="text-center"><?= $row->checkin_date ? date('d-M-Y H:i:s', strtotime($row->checkin_date)) : '' ?></td>
                                        <td class="text-center"><?= $row->gate_checkin ?></td>
                                        <td class="text-center"><?= $row->checkout_date ? date('d-M-Y H:i:s', strtotime($row->checkout_date)) : '' ?></td>
                                        <td class="text-center"><?= $row->gate_checkout ?></td>
                                        <td><?= $row->category ?></td>
                                        <td><?= $row->name ?></td>
                                        <td class="text-right"><?= number_format($row->price, 0, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->discount_amount, 0, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->tax_amount, 0, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->total, 0, '.', ',') ?></td>
                                    </tr>

                                    <?php
                                        $price += $row->price;
                                        $discount_amount += $row->discount_amount;
                                        $tax_amount += $row->tax_amount;
                                        $total += $row->total;
                                    ?>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="17" class="text-center">No data available</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                        <?php if ($results): ?>
                            <tfoot>
                                <tr class="text-right" style="font-weight: bold;">
                                    <td colspan="13" class="text-capitalize text-center">total</td>
                                    <td><?= number_format($price, 2, '.', ',') ?></td>
                                    <td><?= number_format($discount_amount, 2, '.', ',') ?></td>
                                    <td><?= number_format($tax_amount, 2, '.', ',') ?></td>
                                    <td><?= number_format($total, 2, '.', ',') ?></td>
                                </tr>
                            </tfoot>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>

<style>
    .negative {
        color: red;
        font-weight: bold;
    }

    .positive {
        color: green;
        font-weight: bold;
    }

    tbody tr {
        transition: background-color 0.2s ease-in-out;
    }

    tbody tr:hover {
        background-color: #edf7ff;
        cursor: pointer;
    }

    tbody tr.selected {
        background-color: #cfe2ff !important;
    }

    @media (min-width: 992px) {
        .col-md-20 {
            width: 20%;
            float: left;
        }
    }
</style>

<script type="text/javascript">
    var date_from = new Date(<?= json_encode($date_from ?? null); ?>);
    var date_to = new Date(<?= json_encode($date_to ?? null); ?>);

    document.getElementById("date_from").value = moment(date_from).format('DD-MMM-YYYY');
    document.getElementById("date_to").value = moment(date_to).format('DD-MMM-YYYY');

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
    });

    $('.timepicker').timepicker({
        showInputs: false
    });

    var counter_code = <?php echo json_encode($counter_code); ?>;
    var gate_code = <?php echo json_encode($gate_code); ?>;
    var gate_checkout = <?php echo json_encode($gate_checkout); ?>;

    load_counter_gate(document.getElementById("vanue_code").value);
    function load_counter_gate(value) {
        document.getElementById("counter_code").innerHTML = '';
        document.getElementById("gate_code").innerHTML = '';
        document.getElementById("gate_checkout").innerHTML = '';

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/load_counter_gate') ?>/" + value,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                document.getElementById("counter_code").innerHTML = '<option value="all">--Select All--</option>';
                document.getElementById("gate_code").innerHTML = '<option value="all">--Select All--</option>';
                document.getElementById("gate_checkout").innerHTML = '<option value="all">--Select All--</option>';

                if (data) {
                    var counter = data.counter;
                    counter.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.code;
                        option.text = item.code;
                        if (counter_code == item.code) { option.selected = true; }
                        document.getElementById("counter_code").appendChild(option);
                    });

                    var gate = data.gate;
                    gate.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.code;
                        option.text = item.code;
                        if (gate_code == item.code) { option.selected = true; }

                        document.getElementById("gate_code").appendChild(option);
                    });

                    gate.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.code;
                        option.text = item.code;
                        if (gate_checkout == item.code) { option.selected = true; }

                        document.getElementById("gate_checkout").appendChild(option);
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(error);
                return;
            }
        });
    }

    count_statistic(<?php echo json_encode($results); ?>);
    function count_statistic(data) {
        var checkin = 0;
        var checkin_scan = 0;
        var checkout = 0;
        var balance = 0;
        var amount = 0;
        
        if (data) {
            // checkin = data.length;
            for (let index = 0; index < data.length; index++) {
                if (data[index].seq) {
                    checkin += 1;
                }

                if (data[index].checkin_date) {
                    checkin_scan += 1;
                }

                if (data[index].checkout_date) {
                    checkout += 1;
                }

                amount += parseFloat(data[index].total ? data[index].total : 0);
            }
        }

        balance = checkin_scan - checkout;

        document.getElementById("checkin_statistic").innerHTML = formatNumber(checkin, 0);
        document.getElementById("checkin_scan_statistic").innerHTML = formatNumber(checkin_scan, 0);
        document.getElementById("checkout_statistic").innerHTML = formatNumber(checkout, 0);
        document.getElementById("balance_statistic").innerHTML = formatNumber(balance, 0);
        document.getElementById("amount_statistic").innerHTML = formatNumber(amount, 0);
    }
</script>