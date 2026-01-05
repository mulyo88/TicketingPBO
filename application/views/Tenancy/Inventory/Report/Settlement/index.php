<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Inventory/Report/Settlement/struk-boot3'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Inventory/Report/Settlement') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <form method="GET" action="<?= site_url('Tenancy/Inventory/Report/Settlement') ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">date from</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datetimepicker" id="date_from" name="date_from" placeholder="dd-MMM-yyyy HH:mm" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">date to</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datetimepicker" id="date_to" name="date_to" placeholder="dd-MMM-yyyy HH:mm" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2"></div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize">area</label>
                                <select name="area" id="area" class="form-control" style="flex:1;">
                                    <option value="all">--Select All--</option>
                                    <?php foreach ($building as $row): ?>
                                        <option value="<?= $row->code ?>" <?= ($area == $row->code ? 'selected' : '') ?>>
                                            <?= $row->code ?> - <?= $row->name ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-capitalize">methode</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="method" id="method" class="form-control" style="flex:1;">
                                        <option value="all">--Select All--</option>
                                        <?php foreach ($methods as $row): ?>
                                            <option value="<?= $row->name ?>" <?= ($method == $row->name ? 'selected' : '') ?>>
                                                <?= $row->name ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>

                                    <button type="submit" class="btn" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>

                                    <button type="button" class="btn btn-primary text-capitalize"
                                        onclick="printStruk()"
                                    >
                                        <i class="fa fa-print"></i>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_raw()"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <h3>Settlement Summary</h3>

                    <div class="table-responsive">
                        <table id="report_summary_settlement" class="table table-bordered">
                            <thead>
                                <tr class="bg-black text-white">
                                    <th class="text-capitalize text-center">no</th>
                                    <th class="text-capitalize text-center">date trans</th>
                                    <th class="text-capitalize text-center">area</th>
                                    <th class="text-capitalize text-center">qty</th>
                                    <th class="text-capitalize text-center">total</th>
                                    <th class="text-capitalize text-center">discount</th>
                                    <th class="text-capitalize text-center">tax</th>
                                    <th class="text-capitalize text-center">grandtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($results): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($results->summary as $row): ?>
                                        <tr>
                                            <td class="text-right"><?= $no++ ?></td>
                                            <td class="text-center"><?= $row->date_trans ? date('d-M-Y', strtotime($row->date_trans)) : '' ?></td>
                                            <td class="text-left"><?= $row->building->code . ' - ' . $row->building->name ?></td>
                                            <td class="text-right"><?= number_format($row->qty, 2, '.', ',') ?></td>
                                            <td class="text-right"><?= number_format($row->total, 2, '.', ',') ?></td>
                                            <td class="text-right"><?= number_format($row->discount, 2, '.', ',') ?></td>
                                            <td class="text-right"><?= number_format(($row->total - $row->discount) * $row->tax / 100, 2, '.', ',') ?></td>
                                            <td class="text-right"><?= number_format($row->grandtotal, 2, '.', ',') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No data available</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                            <?php if ($results): ?>
                                <?php
                                    $total_qty = 0;
                                    $total_total = 0;
                                    $total_discount = 0;
                                    $total_tax = 0;
                                    $total_grand = 0;

                                    foreach ($results->summary as $row) {
                                        $total_qty      += $row->qty;
                                        $total_total    += $row->total;
                                        $total_discount += $row->discount;

                                        $tax_value = ($row->total - $row->discount) * $row->tax / 100;
                                        $total_tax += $tax_value;

                                        $total_grand += $row->grandtotal;
                                    }
                                ?>

                                <tfooter>
                                    <tr>
                                        <td colspan="3" class="text-center text-capitalize">total</td>
                                        <td class="text-right"><?= number_format($total_qty, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($total_total, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($total_discount, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($total_tax, 2, '.', ',') ?></td>
                                        <td class="text-right"><?= number_format($total_grand, 2, '.', ',') ?></td>
                                    </tr>
                                </tfooter>
                            <?php endif ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <h3>Settlement Details</h3>

                    <div class="table-responsive">
                        <table id="report_datalist_settlement" class="table table-bordered">
                            <thead>
                                <tr class="bg-black text-white">
                                    <th class="text-capitalize text-center" colspan="5">header</th>
                                    <th class="text-capitalize text-center" colspan="3">detail</th>
                                    <th class="text-capitalize text-center" colspan="6">payment</th>
                                </tr>
                                <tr class="bg-black text-white">
                                    <!-- header -->
                                    <th class="text-capitalize text-center">no</th>
                                    <th class="text-capitalize text-center">area</th>
                                    <th class="text-capitalize text-center">series</th>
                                    <th class="text-capitalize text-center">date trans</th>
                                    <th class="text-capitalize text-center">cashier</th>

                                    <!-- detail -->
                                    <th class="text-capitalize text-center">items</th>
                                    <th class="text-capitalize text-center">qty</th>
                                    <th class="text-capitalize text-center">total</th>

                                    <!-- payment -->
                                    <th class="text-capitalize text-center">qty</th>
                                    <th class="text-capitalize text-center">total</th>
                                    <th class="text-capitalize text-center">discount</th>
                                    <th class="text-capitalize text-center">tax</th>
                                    <th class="text-capitalize text-center">grandtotal</th>
                                    <th class="text-capitalize text-center">methode</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($results): ?>
                                    <?php
                                        // group by series
                                        $groups = [];
                                        foreach ($results->datalist as $row) {
                                            $groups[$row->series][] = $row;
                                        }

                                        $no = 1;

                                        // render table with rowspan
                                        foreach ($groups as $series => $rows):
                                            $rowspan = count($rows);
                                            $first = true;

                                            foreach ($rows as $row):
                                                ?>
                                                    <tr>
                                                        <?php if ($first): ?>
                                                            <!-- header group -->
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                                                            <td class="text-left" rowspan="<?= $rowspan ?>"><?= $row->area_code . ' - ' . $row->area_name ?></td>
                                                            <td class="text-left" rowspan="<?= $rowspan ?>"><?= $row->series ?></td>
                                                            <td class="text-center" rowspan="<?= $rowspan ?>"><?= date('d-M-Y H:i:s', strtotime($row->date_trans)) ?></td>
                                                            <td class="text-left" rowspan="<?= $rowspan ?>"><?= $row->username ?></td>
                                                        <?php endif ?>

                                                        <!-- detail per item -->
                                                        <td class="text-left"><?= $row->NmBarang ?></td>
                                                        <td class="text-right"><?= number_format($row->qty, 2, '.', ',') ?> <?= $row->Satuan ?></td>
                                                        <td class="text-right"><?= number_format($row->total, 2, '.', ',') ?></td>

                                                        <?php if ($first): ?>
                                                            <!-- payment group -->
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= number_format($row->total_qty, 2, '.', ',') ?></td>
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= number_format($row->total_total, 2, '.', ',') ?></td>
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= number_format($row->discount, 2, '.', ',') ?></td>
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= number_format($row->tax, 2, '.', ',') ?></td>
                                                            <td class="text-right" rowspan="<?= $rowspan ?>"><?= number_format($row->grandtotal, 2, '.', ',') ?></td>
                                                            <td class="text-left" rowspan="<?= $rowspan ?>"><?= $row->methode ?></td>
                                                        <?php endif ?>
                                                    </tr>
                                                <?php

                                                $first = false;
                                            endforeach;
                                        endforeach;
                                    ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="14" class="text-center">No data available</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
<?php yield_section('page-style-partials-struk'); ?>

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/is-weekend.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-number-k.js"></script>

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
</style>

<?php yield_section('page-script-partials-struk'); ?>

<script type="text/javascript">
    var date_from = new Date(<?= json_encode($date_from ?? null); ?>);
    var date_to = new Date(<?= json_encode($date_to ?? null); ?>);

    document.getElementById("date_from").value = moment(date_from).format('DD-MMM-YYYY HH:mm');
    document.getElementById("date_to").value = moment(date_to).format('DD-MMM-YYYY HH:mm');

    $('.datetimepicker').datetimepicker({
        format: 'DD-MMM-YYYY HH:mm',
        showTodayButton: true,
        sideBySide: true
    });

    document.addEventListener("click", function (e) {
        let row = e.target.closest("tbody tr");
        if (!row) return;

        document.querySelectorAll("tbody tr").forEach(r => r.classList.remove("selected"));
        row.classList.add("selected");
    });

    function export_raw() {
        export_data('report_summary_settlement');
        export_data('report_datalist_settlement');
    }
</script>