<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/modal/gate'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="GET" action="<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/index') ?>">
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
                                <label class="text-capitalize">location</label>
                                <select name="vanue_code" id="vanue_code" class="form-control" style="flex:1;"
                                    onchange="load_gate(this.value);"
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
                                <label for="gate_code" class="control-label text-capitalize">gate</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="gate_code" id="gate_code" class="form-control">
                                        <option value="all">--Select All--</option>
                                    </select>
                                    <button type="submit" class="btn" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2"></div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize" style="visibility: hidden;">Create</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <button type="button" class="btn form-control" style="background-color:black; color:white;"
                                            data-toggle="modal" data-target="#modal-gate"
                                        >
                                        <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('tbl_checkout')"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl_checkout" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">area</th>
                                    <th class="text-center text-capitalize">location</th>
                                    <th class="text-center text-capitalize">counter</th>
                                    <th class="text-center text-capitalize">gate</th>
                                    <th class="text-center text-capitalize">cashier trans</th>
                                    <th class="text-center text-capitalize">checkin</th>
                                    <th class="text-center text-capitalize">barcode</th>
                                    <th class="text-center text-capitalize">category</th>
                                    <th export=false class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= $row->area ?></td>
                                        <td class="text-capitalize"><?= $row->venue ?></td>
                                        <td class="text-capitalize"><?= $row->counter ?></td>
                                        <td class="text-capitalize"><?= $row->gate ?></td>
                                        <td class="text-capitalize text-center"><?= date("d-M-Y H:i:s", strtotime($row->checkin_date)) ?></td>
                                        <td class="text-capitalize text-center"><?= date("d-M-Y H:i:s", strtotime($row->checkin_scan_date)) ?></td>
                                        <td class="text-capitalize"><?= $row->barcode ?></td>
                                        <td class="text-capitalize"><?= $row->name ?></td>
                                        <td export=false class="text-center">
                                            <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/destroy/'.$row->checkin_id.'/'.$row->ticket_id.'/'.$row->seq.'/'.$row->gate_id); ?>" class="label label-danger" style="margin-left: 5px;">
                                                <i class="fa fa-trash"></i><span style="margin-left: 5px;">Delete</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

        <?php yield_section('content-modal-gate'); ?>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
<?php yield_section('page-style-partials-struk'); ?>

<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>

<?php yield_section('script-content-modal-gate'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        // reset state old
        if ($.fn.DataTable.isDataTable('#tbl_checkout')) {
            $('#tbl_checkout').DataTable().clear().destroy();
        }

        // init DataTables
        var table = $('#tbl_checkout').DataTable({
            stateSave: false,
            serverSide: false,
        });

        // remove query string xxxx_length from URL
        if (window.location.search.includes('tbl_checkout_length')) {
            let url = new URL(window.location);
            url.searchParams.delete('tbl_checkout_length');
            window.history.replaceState({}, '', url);
        }
    });

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

    var gate_code = <?php echo json_encode($gate_code); ?>;
    load_gate(document.getElementById("vanue_code").value);
    function load_gate(value) {
        document.getElementById("gate_code").innerHTML = '';

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/load_gate') ?>/" + value,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                document.getElementById("gate_code").innerHTML = '<option value="all">--Select All--</option>';

                if (data) {
                    data.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.code;
                        option.text = item.code;
                        if (gate_code == item.code) { option.selected = true; }
                        document.getElementById("gate_code").appendChild(option);
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(error);
                return;
            }
        });
    }
</script>