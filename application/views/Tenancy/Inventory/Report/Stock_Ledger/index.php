<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Inventory/Report/Stock_Ledger/partials/datalist'); ?>
<?php include_view('Tenancy/Inventory/Report/Stock_Ledger/partials/daily'); ?>
<?php include_view('Tenancy/Inventory/Report/Stock_Ledger/partials/monthly'); ?>
<?php include_view('Tenancy/Inventory/Report/Stock_Ledger/partials/yearly'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Inventory/Report/Stock_Ledger/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="text-capitalize">date trans</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right datepicker" id="date_trans" name="date_trans" placeholder="dd-MMM-yyyy" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">stock</label>
                            <select name="stock" id="stock" class="form-control" style="flex:1;">
                                <option value="">--Select All--</option>
                                <option value="empty">Empty</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="hight">Hight</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">area</label>
                            <select name="area" id="area" class="form-control" style="flex:1;">
                                <option value="">--Select All--</option>
                                <?php foreach ($building as $row): ?>
                                    <option value="<?= $row->code ?>">
                                        <?= $row->code ?> - <?= $row->name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">departement</label>
                            <select name="departement" id="departement" class="form-control" style="flex:1;">
                                <option value="">--Select All--</option>
                                <?php foreach ($departement as $row): ?>
                                    <option value="<?= $row->code ?>">
                                        <?= $row->code ?> - <?= $row->name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">category</label>
                            <select name="category" id="category" class="form-control" style="flex:1;">
                                <option value="">--Select All--</option>
                                <?php foreach ($category as $row): ?>
                                    <option value="<?= $row->name ?>">
                                        <?= $row->name ?> - <?= $row->note ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="text-capitalize">item code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Code">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"></div>
                    
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">item name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">uom</label>
                            <select name="uom" id="uom" class="form-control"
                                style="flex:1;"
                                onchange="select_uom()">
                                <option value="small">Small</option>
                                <option value="middle">Middle</option>
                                <option value="big">Big</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">mode</label>
                            <select name="mode" id="mode" class="form-control"
                                style="flex:1;"
                                onchange="select_mode()">
                                <option value="Simple">Simple</option>
                                <option value="Detail">Detail</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label class="text-capitalize">type</label>
                            <div style="display:flex; gap:5px; align-items:center;">
                                <select name="type" id="type" class="form-control"
                                    style="flex:1;"
                                    onchange="select_type()">
                                    <option value="DataList">DataList</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>

                                <button type="button" class="btn text-capitalize" style="background-color:black; color:white;"
                                    onclick="search_data()"
                                >
                                    <i class="fa fa-search"></i>
                                </button>

                                <button type="button" class="btn btn-success text-capitalize"
                                    onclick="export_data()"
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
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><span id="card-empty">0<span><sup style="font-size: 20px">%</sup></h3>
                                <p class="text-capitalize">stock empty</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <!-- <a href="#" class="small-box-footer text-capitalize" onclick="select_stock('empty')">
                                inquery <i class="fa fa-arrow-circle-right"></i>
                            </a> -->
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><span id="card-low">0<span><sup style="font-size: 20px">%</sup></h3>
                                <p class="text-capitalize">stock low</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <!-- <a href="#" class="small-box-footer text-capitalize" onclick="select_stock('low')">
                                inquery <i class="fa fa-arrow-circle-right"></i>
                            </a> -->
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><span id="card-medium">0<span><sup style="font-size: 20px">%</sup></h3>
                                <p class="text-capitalize">stock medium</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <!-- <a href="#" class="small-box-footer text-capitalize" onclick="select_stock('medium')">
                                inquery <i class="fa fa-arrow-circle-right"></i>
                            </a> -->
                        </div>
                    </div>
        
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3><span id="card-hight">0<span><sup style="font-size: 20px">%</sup></h3>
                                <p class="text-capitalize">stock hight</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <!-- <a href="#" class="small-box-footer text-capitalize" onclick="select_stock('hight')">
                                inquery <i class="fa fa-arrow-circle-right"></i>
                            </a> -->
                        </div>
                    </div>
                </div>

                <div id="panel-data" style="max-height: 600px; overflow-y: auto;"></div>
            </div>
        </div>
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

<style>
    .negative {
        color: red;
        font-weight: bold;
    }

    .positive {
        color: green;
        font-weight: bold;
    }

    .loading {
        font-size: 24px;
        font-family: Arial, sans-serif;
        font-weight: bold;
    }

    .dots::after {
        content: "";
        animation: dots 1.2s steps(4, end) infinite;
    }

    @keyframes dots {
        0%   { content: ""; }
        25%  { content: "."; }
        50%  { content: ".."; }
        75%  { content: "..."; }
        100% { content: ""; }
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

<?php yield_section('page-script-datalist'); ?>
<?php yield_section('page-script-daily'); ?>
<?php yield_section('page-script-monthly'); ?>
<?php yield_section('page-script-yearly'); ?>

<script type="text/javascript">
    document.getElementById("date_trans").value = moment(new Date()).format('DD-MMM-YYYY');

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy'
    });

    $('.timepicker').timepicker({
        showInputs: false
    });

    function select_uom() {
        let selectedType = document.getElementById("type").value;
        if (selectedType == 'DataList') {
            // set default
            chk = document.getElementsByClassName('small');
            for (var i = 0; i < chk.length; i++) {
                chk[i].hidden = true;
            }

            chk = document.getElementsByClassName('middle');
            for (var i = 0; i < chk.length; i++) {
                chk[i].hidden = true;
            }

            chk = document.getElementsByClassName('big');
            for (var i = 0; i < chk.length; i++) {
                chk[i].hidden = true;
            }
            
            let selectedValue = document.getElementById("uom").value;

            // show
            chk = document.getElementsByClassName(selectedValue);
            for (var i = 0; i < chk.length; i++) {
                chk[i].hidden = false;
            }
        } else if (selectedType == 'Daily') {
            document.getElementById("panel-data").innerHTML = '';
        } else if (selectedType == 'Monthly') {
            document.getElementById("panel-data").innerHTML = '';
        } else if (selectedType == 'Yearly') {
            document.getElementById("panel-data").innerHTML = '';
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function select_mode() {
        let selectedValue = document.getElementById("type").value;
        if (selectedValue == 'DataList') {
            select_mode_datalist();
        } else if (selectedValue == 'Daily') {
            select_mode_daily();
        } else if (selectedValue == 'Monthly') {
            select_mode_monthly();
        } else if (selectedValue == 'Yearly') {
            select_mode_yearly();
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function select_type() {
        document.getElementById("panel-data").innerHTML = '';
    }

    function search_data() {
        if (document.getElementById("type").value == 'DataList') {
            load_datalist();
        } else if (document.getElementById("type").value == 'Daily') {
            load_daily();
        } else if (document.getElementById("type").value == 'Monthly') {
            load_monthly();
        } else if (document.getElementById("type").value == 'Yearly') {
            load_yearly();
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    function export_data() {
        var element = '';
        if (document.getElementById("type").value == 'DataList') {
            element = document.getElementById('tbl-datalist');
            if (element) {
                export_datalist();
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Daily') {
            element = document.getElementById('tbl-daily');
            if (element) {
                export_daily();
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Monthly') {
            element = document.getElementById('tbl-monthly');
            if (element) {
                export_monthly();
            } else {
                alert('inquiry please!');
            }
        } else if (document.getElementById("type").value == 'Yearly') {
            element = document.getElementById('tbl-yearly');
            if (element) {
                export_yearly();
            } else {
                alert('inquiry please!');
            }
        } else {
            alert('unfortunately, unavailable');
            return;
        }
    }

    document.addEventListener("click", function (e) {
        let row = e.target.closest("tbody tr");
        if (!row) return;

        document.querySelectorAll("tbody tr").forEach(r => r.classList.remove("selected"));
        row.classList.add("selected");
    });

    function set_bg_uom(min_val, max_val, result_val) {
        var color = '';
        if (parseFloat(result_val) <= 0) {
            // color = '#FFE5E5';
            color = 'bg-red';
        } else if (parseFloat(result_val) <= parseFloat(min_val) && parseFloat(result_val) != 0) {
            // color = '#FFFBBA';
            color = 'bg-yellow';
        } else if (parseFloat(result_val) > parseFloat(min_val) && parseFloat(result_val) <= parseFloat(max_val)) {
            // color = '#EDFFF1';
            color = 'bg-green';
        } else if (parseFloat(result_val) > parseFloat(max_val)) {
            // color = '#F4F2FF';
            color = 'bg-blue';
        }

        return color;
    }

    function set_card(row_val, empty_card, low_card, medium_card, hight_card) {
        document.getElementById("card-empty").innerHTML = formatNumber(empty_card/ row_val * 100, 2) + '<sup style="font-size: 20px">%</sup>';
        document.getElementById("card-low").innerHTML = formatNumber(low_card / row_val * 100, 2) + '<sup style="font-size: 20px">%</sup>';
        document.getElementById("card-medium").innerHTML = formatNumber(medium_card / row_val * 100, 2) + '<sup style="font-size: 20px">%</sup>';
        document.getElementById("card-hight").innerHTML = formatNumber(hight_card / row_val * 100, 2) + '<sup style="font-size: 20px">%</sup>';
    }

    function select_stock(type) {
        // document.getElementById('stock').value = type;
        // search_data();
    }
</script>