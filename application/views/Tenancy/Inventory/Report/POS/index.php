<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Inventory/Report/POS/partials/datalist_simple'); ?>
<?php include_view('Tenancy/Inventory/Report/POS/partials/datalist_detail'); ?>
<?php include_view('Tenancy/Inventory/Report/POS/partials/daily'); ?>
<?php include_view('Tenancy/Inventory/Report/POS/partials/monthly'); ?>
<?php include_view('Tenancy/Inventory/Report/POS/partials/yearly'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Inventory/Report/POS/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <form method="GET" action="<?= site_url('Tenancy/Inventory/Report/POS/index') ?>">
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

                        <div class="col-md-2 col-xs-6">
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

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">methode</label>
                                <select name="method" id="method" class="form-control" style="flex:1;">
                                    <option value="all">--Select All--</option>
                                    <?php foreach ($methods as $row): ?>
                                        <option value="<?= $row->name ?>" <?= ($method == $row->name ? 'selected' : '') ?>>
                                            <?= $row->name ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">item code</label>
                                <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Code" value="<?= $item_code ?>">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">item name</label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Name" value="<?= $item_name ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6"></div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">cashier</label>
                                <input type="text" class="form-control" id="cashier" name="cashier" placeholder="Cashier" value="<?= $cashier ?>">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-6">
                            <div class="form-group">
                                <label class="text-capitalize">mode</label>
                                <select name="mode" id="mode" class="form-control"
                                    style="flex:1;"
                                    onchange="select_mode()"
                                >
                                    <option value="Simple" <?= ($mode == "Simple" ? 'selected' : '') ?>>Simple</option>
                                    <option value="Detail" <?= ($mode == "Detail" ? 'selected' : '') ?>>Detail</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize">type</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="type" id="type" class="form-control"
                                        style="flex:1;"
                                        onchange="select_type()"
                                    >
                                        <option value="DataList" <?= ($type == "DataList" ? 'selected' : '') ?>>DataList</option>
                                        <option value="Daily" <?= ($type == "Daily" ? 'selected' : '') ?>>Daily</option>
                                        <option value="Monthly" <?= ($type == "Monthly" ? 'selected' : '') ?>>Monthly</option>
                                        <option value="Yearly" <?= ($type == "Yearly" ? 'selected' : '') ?>>Yearly</option>
                                    </select>

                                    <button type="submit" class="btn" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_mode()"
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
                    <div id="containerChart">
                        <div class="chart-container">
                            <canvas id="transChart"></canvas>
                        </div>
                    </div>

                    <div id="panel-data" style="max-height: 500px; overflow-y: auto;"></div>
                </div>
            </div>
        </form>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/is-weekend.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-number-k.js"></script>
<script src="<?php echo base_url() ?>assets/external/chart/chart.js"></script>
<script src="<?php echo base_url() ?>assets/external/chart/chartjs-plugin-datalabels-2.js"></script>

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

    .chart-container {
        height: 200px;
        width: 100%;
        margin: 30px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<?php yield_section('page-script-datalist-simple'); ?>
<?php yield_section('page-script-datalist-detail'); ?>
<?php yield_section('page-script-daily'); ?>
<?php yield_section('page-script-monthly'); ?>
<?php yield_section('page-script-yearly'); ?>

<script type="text/javascript">
    var query = <?php echo json_encode($results); ?>;
    if (query) {
        if (query.data) {
            var results = query.data;
            var chart_x = query.chart;
        } else {
            var results = query;
        }
    }
    
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

    document.addEventListener("click", function (e) {
        let row = e.target.closest("tbody tr");
        if (!row) return;

        document.querySelectorAll("tbody tr").forEach(r => r.classList.remove("selected"));
        row.classList.add("selected");
    });

    // chart *********************
    const rawData = [];
    const grouped = {};
    let transChart = null;
    // chart *********************

    function removeYear(dateStr) {
        const parts = dateStr.split("-");
        return parts[0] + "-" + parts[1];
    }

    search_data();
    function search_data() {
        clear_containerChart();
        if (transChart) {
            transChart.destroy();
        }

        if (document.getElementById("type").value == 'DataList') {
            if (document.getElementById("mode").value == 'Simple') {
                document.getElementById("panel-data").innerHTML = load_datalist_simple();
                chart_datalist_simple();
            } else if (document.getElementById("mode").value == 'Detail') {
                document.getElementById("panel-data").innerHTML = load_datalist_detail();
                chart_datalist_detail();
            } else {
                alert('unfortunately, unavailable');
                return;
            }
        } else if (document.getElementById("type").value == 'Daily') {
            document.getElementById("panel-data").innerHTML = load_daily();
            chart_datalist_daily();
        } else if (document.getElementById("type").value == 'Monthly') {
            document.getElementById("panel-data").innerHTML = load_monthly();
        } else if (document.getElementById("type").value == 'Yearly') {
            document.getElementById("panel-data").innerHTML = load_yearly();
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function export_mode() {
        var element = '';
        if (document.getElementById("type").value == 'DataList') {
            if (document.getElementById("mode").value == 'Simple') {
                element = document.getElementById('tbl_pos_datalist_simple');
                if (element) {
                    export_data('tbl_pos_datalist_simple');
                } else {
                    alert('inquiry please!');
                }
            } else if (document.getElementById("mode").value == 'Detail') {
                element = document.getElementById('tbl_pos_datalist_detail');
                if (element) {
                    export_data('tbl_pos_datalist_detail');
                } else {
                    alert('inquiry please!');
                }
            } else {
                alert('unfortunately, unavailable');
                return;
            }
        } else if (document.getElementById("type").value == 'Daily') {
            element = document.getElementById('tbl_pos_daily');
            if (element) {
                export_data('tbl_pos_daily');
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Monthly') {
            element = document.getElementById('tbl_pos_monthly');
            if (element) {
                export_data('tbl_pos_monthly');
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Yearly') {
            element = document.getElementById('tbl_pos_yearly');
            if (element) {
                export_data('tbl_pos_yearly');
            } else {
                alert('inquiry please!');
            }
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function create_containerChart() {
        document.getElementById("containerChart").innerHTML = '<div class="chart-container"><canvas id="transChart"></canvas></div>';
    }

    function clear_containerChart() {
        document.getElementById("containerChart").innerHTML = '';
    }

    function select_mode() {
        document.getElementById("panel-data").innerHTML = '<div class="text-capitalize text-muted" style="font-size: 24px;">click inquery</div>';
        clear_containerChart();

        if (transChart) {
            transChart.destroy();
        }
    }

    function select_type() {
        document.getElementById("panel-data").innerHTML = '<div class="text-capitalize text-muted" style="font-size: 24px;">click inquery</div>';
        clear_containerChart();

        if (transChart) {
            transChart.destroy();
        }
    }

    function maskAuto(values) {
        const firstNonZero = values.findIndex(v => v !== 0);
        const lastNonZero = values.length - 1 - [...values].reverse().findIndex(v => v !== 0);

        if (firstNonZero === -1) {
            return values.map(() => null);
        }

        const lastAllowedZero = lastNonZero + 1;

        return values.map((v, i) => {
            if (i < firstNonZero - 1) return null;
            if (i > lastAllowedZero) return null;
            return v;
        });
    }

</script>