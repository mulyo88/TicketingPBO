<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="GET" action="<?= site_url('Tenancy/Inventory/Stock/Stock_Entry/index') ?>">
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
                                <label class="text-capitalize">status</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <select name="status" id="status" class="form-control" style="flex:1;">
                                        <option value="all">--Select All--</option>
                                        <?php foreach ($status_x as $row): ?>
                                            <option value="<?= $row->name ?>" <?= ($status == $row->name ? 'selected' : '') ?>>
                                                <?= $row->name ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                    <button type="submit" class="btn" style="background-color:black; color:white;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize" style="visibility: hidden;">Create</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <button type="button" class="btn form-control" style="background-color:black; color:white;"
                                        onclick="location.href='<?=site_url('Tenancy/Inventory/Stock/Stock_Entry/create')?>'">
                                        <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('tbl_stock_entry')"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl_stock_entry" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">trans date</th>
                                    <th class="text-center text-capitalize">area</th>
                                    <th class="text-center text-capitalize">item code</th>
                                    <th class="text-center text-capitalize">item name</th>
                                    <th class="text-center text-capitalize">qty</th>
                                    <th class="text-center">UOM's</th>
                                    <th class="text-center text-capitalize">status</th>
                                    <th class="text-center text-capitalize">note</th>
                                    <th class="text-center text-capitalize">created</th>
                                    <th class="text-center text-capitalize">updated</th>
                                    <th export=false class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td class="text-capitalize text-center"><?= date("d-M-Y", strtotime($row->date_trans)) ?></td>
                                        <td class="text-capitalize"><?= $row->building ? $row->building->code . ' - ' . $row->building->name : '' ?></td>
                                        <td class="text-capitalize"><?= $row->item ? $row->item->KdBarang : '' ?></td>
                                        <td class="text-capitalize"><?= $row->item ? $row->item->NmBarang : '' ?></td>
                                        <td class="text-capitalize text-right"><?= number_format($row->qty, 0, '.', ',') ?></td>
                                        <td class="text-capitalize"><?= $row->item ? $row->item->Satuan : 'ea' ?></td>
                                        <td class="text-capitalize"><?= $row->status ?></td>
                                        <td class="text-capitalize"><?= $row->note ?></td>
                                        <td class="text-capitalize"><?= $row->username ?></td>
                                        <td class="text-capitalize"><?php echo time_ago($row->updated_at); ?></td>
                                        <td export=false class="text-center">
                                            <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Inventory/Stock/Stock_Entry/destroy/'.$row->id); ?>" class="label label-danger">
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
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/export-excel-xls.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        // reset state old
        if ($.fn.DataTable.isDataTable('#tbl_stock_entry')) {
            $('#tbl_stock_entry').DataTable().clear().destroy();
        }

        // init DataTables
        var table = $('#tbl_stock_entry').DataTable({
            stateSave: false,
            serverSide: false,
        });

        // remove query string tbl_stock_entry_length from URL
        if (window.location.search.includes('tbl_stock_entry_length')) {
            let url = new URL(window.location);
            url.searchParams.delete('tbl_stock_entry_length');
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
</script>