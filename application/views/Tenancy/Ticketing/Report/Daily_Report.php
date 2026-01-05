<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Report/Daily_Report') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <form method="GET" action="<?= site_url('Tenancy/Ticketing/Report/Daily_Report/index') ?>">
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

                        <div class="col-md-2 col-xs-6"></div>
                        <div class="col-md-2 col-xs-6"></div>

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
                                <label class="text-capitalize">category</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="category" id="category" class="form-control">
                                        <option value="all"<?= ($category == 'all' ? 'selected' : '') ?>>--Select All--</option>
                                        <?php foreach ($categories as $row): ?>
                                            <option value="<?= $row->name ?>"<?= ($category == $row->name ? 'selected' : '') ?>><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>

                                    <button type="submit" class="btn text-capitalize" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('report_daily_report')"
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
                <div class="table-responsive">
                    <?php
                        $qty = 0;
                        $subtotal = 0;
                        $subtotal = 0;
                        $discount_amount = 0;
                        $tax_amount = 0;
                        $total = 0;
                    ?>

                    <table id="report_daily_report" class="table table-bordered">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="text-capitalize">No</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">date trans</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">area</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">location</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">category</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">qty</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">subtotal</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">discount amount</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">tax amount</th>
                                <th class="text-capitalize text-center" style="font-weight: bold;">total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($results): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td class="text-right"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row->date_trans ? date('d-M-Y', strtotime($row->date_trans)) : '' ?></td>
                                        <td class="text-center"><?= $row->area ?></td>
                                        <td class="text-center"><?= $row->location ?></td>
                                        <td><?= $row->category ?></td>
                                        <td class="text-right"><?= number_format($row->qty, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->subtotal, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->discount_amount, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->tax_amount, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($row->total, 2, '.', ',') ?></td>
                                    </tr>

                                    <?php
                                        $qty += $row->qty;
                                        $subtotal += $row->subtotal;
                                        $discount_amount += $row->discount_amount;
                                        $tax_amount += $row->tax_amount;
                                        $total += $row->total;
                                    ?>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No data available</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                        <?php if ($results): ?>
                            <tfoot>
                                <tr class="text-right" style="font-weight: bold;">
                                    <td colspan="5" class="text-capitalize text-center">total</td>
                                    <td><?= number_format($qty, 2, '.', ',') ?></td>
                                    <td><?= number_format($subtotal, 2, '.', ',') ?></td>
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
    var date_from_raw = <?= json_encode($date_from ?? null); ?>;
    var date_to_raw   = <?= json_encode($date_to ?? null); ?>;

    var today = new Date();

    var date_from = date_from_raw ? new Date(date_from_raw) : today;
    var date_to   = date_to_raw   ? new Date(date_to_raw)   : today;

    document.getElementById("date_from").value = moment(date_from).format('DD-MMM-YYYY');
    document.getElementById("date_to").value = moment(date_to).format('DD-MMM-YYYY');

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
    });

    $('.timepicker').timepicker({
        showInputs: false
    });
</script>