<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Inventory/Report/Outgoing/partials/datalist_simple'); ?>
<?php include_view('Tenancy/Inventory/Report/Outgoing/partials/datalist_detail'); ?>
<?php include_view('Tenancy/Inventory/Report/Outgoing/partials/daily'); ?>
<?php include_view('Tenancy/Inventory/Report/Outgoing/partials/monthly'); ?>
<?php include_view('Tenancy/Inventory/Report/Outgoing/partials/yearly'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Inventory/Report/Outgoing/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <form method="GET" action="<?= site_url('Tenancy/Inventory/Report/Outgoing/index') ?>">
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
                                <label class="text-capitalize">user</label>
                                <input type="text" class="form-control" id="userlogin" name="userlogin" placeholder="User" value="<?= $userlogin ?>">
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

<?php yield_section('page-script-datalist-simple'); ?>
<?php yield_section('page-script-datalist-detail'); ?>
<?php yield_section('page-script-daily'); ?>
<?php yield_section('page-script-monthly'); ?>
<?php yield_section('page-script-yearly'); ?>

<script type="text/javascript">
    var results = <?php echo json_encode($results); ?>;
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

    search_data();
    function search_data() {
        if (document.getElementById("type").value == 'DataList') {
            if (document.getElementById("mode").value == 'Simple') {
                document.getElementById("panel-data").innerHTML = load_datalist_simple();
            } else if (document.getElementById("mode").value == 'Detail') {
                document.getElementById("panel-data").innerHTML = load_datalist_detail();
            } else {
                alert('unfortunately, unavailable');
                return;
            }
        } else if (document.getElementById("type").value == 'Daily') {
            document.getElementById("panel-data").innerHTML = load_daily();
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
                element = document.getElementById('tbl_outgoing_datalist_simple');
                if (element) {
                    export_data('tbl_outgoing_datalist_simple');
                } else {
                    alert('inquiry please!');
                }
            } else if (document.getElementById("mode").value == 'Detail') {
                element = document.getElementById('tbl_outgoing_datalist_detail');
                if (element) {
                    export_data('tbl_outgoing_datalist_detail');
                } else {
                    alert('inquiry please!');
                }
            } else {
                alert('unfortunately, unavailable');
                return;
            }
        } else if (document.getElementById("type").value == 'Daily') {
            element = document.getElementById('tbl_outgoing_daily');
            if (element) {
                export_data('tbl_outgoing_daily');
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Monthly') {
            element = document.getElementById('tbl_outgoing_monthly');
            if (element) {
                export_data('tbl_outgoing_monthly');
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Yearly') {
            element = document.getElementById('tbl_outgoing_yearly');
            if (element) {
                export_data('tbl_outgoing_yearly');
            } else {
                alert('inquiry please!');
            }
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function select_mode() {
        document.getElementById("panel-data").innerHTML = '';
    }

    function select_type() {
        document.getElementById("panel-data").innerHTML = '';
    }
</script>