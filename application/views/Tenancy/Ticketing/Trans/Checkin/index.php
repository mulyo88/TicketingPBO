<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/modal/counter'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Trans/Checkin/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="GET" action="<?= site_url('Tenancy/Ticketing/Trans/Checkin/index') ?>">
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
                                    onchange="load_counter(this.value);"
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
                                <label for="counter_code" class="control-label text-capitalize">counter</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="counter_code" id="counter_code" class="form-control">
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
                                            data-toggle="modal" data-target="#modal-counter"
                                        >
                                        <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('tbl_checkin')"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl_checkin" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">series</th>
                                    <th class="text-center text-capitalize">trans date</th>
                                    <th class="text-center text-capitalize">area</th>
                                    <th class="text-center text-capitalize">location</th>
                                    <th class="text-center text-capitalize">counter</th>
                                    <th class="text-center text-capitalize">ticket</th>
                                    <th class="text-center text-capitalize">checkin</th>
                                    <th class="text-center text-capitalize">checkout</th>
                                    <th class="text-center text-capitalize">other</th>
                                    <th class="text-center text-capitalize">grand total</th>
                                    <th class="text-center text-capitalize">method</th>
                                    <th class="text-center text-capitalize">party</th>
                                    <th class="text-center text-capitalize">cashier</th>
                                    <th class="text-center text-capitalize">updated</th>
                                    <th export=false class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= $row->series ?></td>
                                        <td class="text-capitalize text-center"><?= date("d-M-Y", strtotime($row->date_trans)) ?></td>
                                        <td class="text-capitalize"><?= $row->building ? $row->building->code . ' - ' . $row->building->name : '' ?></td>
                                        <td class="text-capitalize"><?= $row->venue ? $row->venue->code . ' - ' . $row->venue->name : '' ?></td>
                                        <td class="text-capitalize"><?= $row->counter ? $row->counter->code : '' ?></td>
                                        <td class="text-capitalize text-right">
                                            <?php $tickets = 0; ?>
                                            <?php foreach ($row->checkin_barcode as $checkin_barcode): ?>
                                                <?php $tickets++; ?>
                                            <?php endforeach; ?>
                                            <?= $tickets ?> person
                                        </td>

                                        <?php $checkin_scan = 0; ?>
                                        <?php foreach ($row->checkin_scan as $data): ?>
                                            <?php $checkin_scan++; ?>
                                        <?php endforeach; ?>

                                        <td class="text-capitalize text-right 
                                            <?php if ($tickets != 0) { ?>
                                                <?php if ($tickets == $checkin_scan) { ?>
                                                    bg-green
                                                <?php } else if ($checkin_scan != 0) { ?>
                                                    bg-yellow
                                                <?php } else { ?>
                                                    bg-red
                                                <?php } ?>
                                            <?php } ?>
                                        ">
                                            <?= $checkin_scan ?> person
                                        </td>

                                        <?php $checkout_scan = 0; ?>
                                        <?php foreach ($row->checkout as $data): ?>
                                            <?php $checkout_scan++; ?>
                                        <?php endforeach; ?>

                                        <td class="text-capitalize text-right 
                                            <?php if ($checkin_scan != 0) { ?>
                                                <?php if ($checkout_scan == 0) { ?>
                                                    bg-red
                                                <?php } else if ($checkout_scan == $checkin_scan) { ?>
                                                    bg-green
                                                <?php } else if ($checkout_scan < $checkin_scan) { ?>
                                                    bg-yellow
                                                <?php } else { ?>
                                                    bg-red
                                                <?php } ?>
                                            <?php } ?>
                                        ">
                                            <?= $checkout_scan ?> person
                                        </td>

                                        <td class="text-capitalize text-right">
                                            <?php $count = 0; ?>
                                            <?php foreach ($row->detail as $detail): ?>
                                                <?php if ($detail->ticket) { ?>
                                                    <?php if ($detail->ticket->category != "Ticket") { ?>
                                                        <?php $count++; ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php endforeach ?>
                                            <?= $count ?> x
                                        </td>

                                        <td class="text-capitalize text-right"><?= number_format($row->subtotal, 0, '.', ',') ?></td>
                                        <td class="text-capitalize"><?= $row->methode ?></td>

                                        <?php if ($row->party_type == "BANK") { ?>
                                            <td class="text-capitalize"><?= $row->party ? $row->party->account_name : '' ?></td>
                                        <?php } else if ($row->party_type == "PAYMENT_GATE") { ?>
                                            <td class="text-capitalize"><?= $row->party ? $row->party->name : '' ?></td>
                                        <?php } else { ?>
                                            <td class="text-capitalize"></td>
                                        <?php } ?>

                                        <td class="text-capitalize"><?= $row->username ?></td>
                                        <td class="text-capitalize"><?php echo time_ago($row->updated_at); ?></td>
                                        <td export=false class="text-center">
                                            <!-- <a href="<?= site_url('Tenancy/Ticketing/Trans/Checkin/edit/'.$row->id); ?>" class="label label-warning">
                                                <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                            </a> -->

                                            <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Ticketing/Trans/Checkin/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
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

        <?php yield_section('content-modal-counter'); ?>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>

<?php yield_section('script-content-modal-counter'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        // reset state old
        if ($.fn.DataTable.isDataTable('#tbl_checkin')) {
            $('#tbl_checkin').DataTable().clear().destroy();
        }

        // init DataTables
        var table = $('#tbl_checkin').DataTable({
            stateSave: false,
            serverSide: false,
        });

        // remove query string xxxx_length from URL
        if (window.location.search.includes('tbl_checkin_length')) {
            let url = new URL(window.location);
            url.searchParams.delete('tbl_checkin_length');
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

    var counter_code = <?php echo json_encode($counter_code); ?>;
    load_counter(document.getElementById("vanue_code").value);
    function load_counter(value) {
        document.getElementById("counter_code").innerHTML = '';

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/load_counter') ?>/" + value,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                document.getElementById("counter_code").innerHTML = '<option value="all">--Select All--</option>';

                if (data) {
                    data.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.code;
                        option.text = item.code;
                        if (counter_code == item.code) { option.selected = true; }
                        document.getElementById("counter_code").appendChild(option);
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