<?php
    error_reporting(E_ALL);
?>

<?php include_view('Tenancy/Inventory/Selling/POS/partials/modal/building'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
        
        <form method="GET" action="<?= site_url('Tenancy/Inventory/Selling/POS/index') ?>">
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize">item code</label>
                                <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Item Code" value="<?= $item_code ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="text-capitalize">item name</label>
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name" value="<?= $item_name ?>">
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
                                            data-toggle="modal" data-target="#modal-building"
                                        >
                                        <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                                    </button>

                                    <button type="button" class="btn btn-success text-capitalize"
                                        onclick="export_data('tbl_pos')"
                                    >
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl_pos" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th export=false></th>
                                    <th class="text-center text-capitalize">series</th>
                                    <th class="text-center text-capitalize">trans date</th>
                                    <th class="text-center text-capitalize">area</th>
                                    <th class="text-center text-capitalize">grand total</th>
                                    <th class="text-center text-capitalize">method</th>
                                    <th class="text-center text-capitalize">party</th>
                                    <th class="text-center text-capitalize">cashier</th>
                                    <th class="text-center text-capitalize">updated</th>
                                    <th class="text-center text-capitalize" hidden>detail</th>
                                    <th class="text-center text-capitalize" hidden>header</th>
                                    <th export=false class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td export=false></td>
                                        <td class="text-capitalize"><?= $row->series ?></td>
                                        <td class="text-capitalize text-center"><?= date("d-M-Y", strtotime($row->date_trans)) ?></td>
                                        <td class="text-capitalize"><?= $row->building ? $row->building->name : '' ?></td>
                                        <td class="text-capitalize text-right"><?= number_format($row->grandtotal, 0, '.', ',') ?></td>
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
                                        <td class="text-capitalize" hidden><?= json_encode($row->detail) ?></td>
                                        <td class="text-capitalize" hidden><?= json_encode($row) ?></td>
                                        <td export=false class="text-center">
                                            <a href="<?= site_url('Tenancy/Inventory/Selling/POS/edit/'.$row->id); ?>" class="label label-warning">
                                                <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                            </a>

                                            <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Inventory/Selling/POS/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
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

        <?php yield_section('content-modal-building'); ?>
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

<?php yield_section('script-content-modal-building'); ?>

<style>
    td.dt-control {
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.dt-control {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        // reset state old
        if ($.fn.DataTable.isDataTable('#tbl_pos')) {
            $('#tbl_pos').DataTable().clear().destroy();
        }

        // init DataTables
        var table = $('#tbl_pos').DataTable({
            stateSave: false,
            serverSide: false,
            "columnDefs": [
                {
                    "className":      'dt-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": '',
                    "targets": 0
                }
            ],
            "order": [[1, 'asc']],
        });

        // remove query string tbl_pos_length from URL
        if (window.location.search.includes('tbl_pos_length')) {
            let url = new URL(window.location);
            url.searchParams.delete('tbl_pos_length');
            window.history.replaceState({}, '', url);
        }

        // create data detail
        function format (d) {
            var html = '';
            html += '<div class="container">';
                html += '<table class="table table-sm table-bordered">';
                    html += '<thead>';
                        html += '<tr style="background-color: black; color: white; font-weight:bold;">';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">code</th>';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">item</th>';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">uom</th>';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">price</th>';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">qty</th>';
                            html += '<th class="text-center text-capitalize" style="vertical-align: middle;">subtotal</th>';
                        html += '</tr>';
                    html += '</thead>';

                    var qty = 0;
                    var total = 0;

                    html += '<tbody>';
                        var detail = JSON.parse(d[9]);
                        var header = JSON.parse(d[10]);

                        if (detail.length == 0) {
                            html += '<tr><td class="text-center text-capitalize text-muted" colspan="6">no data record</td></tr>';
                        } else {
                            for (let index = 0; index < detail.length; index++) {
                                qty += parseFloat(detail[index].qty);
                                total += parseFloat(detail[index].total);

                                html += '<tr>';
                                    html += '<td class="text-capitalize">' + (detail[index].item ? detail[index].item.KdBarang : '') + '</td>';
                                    html += '<td class="text-capitalize">' + (detail[index].item ? detail[index].item.NmBarang : '') + '</td>';
                                    html += '<td class="text-capitalize text-right">' + (detail[index].item ? detail[index].item.Satuan : '') + '</td>';
                                    html += '<td class="text-capitalize text-right">' + formatNumber(detail[index].rate, 2) + '</td>';
                                    html += '<td class="text-capitalize text-right">' + formatNumber(detail[index].qty, 2) + '</td>';
                                    html += '<td class="text-capitalize text-right">' + formatNumber(detail[index].total, 2) + '</td>';
                                html += '</tr>';
                            }
                        }
                    html += '</tbody>';

                    html += '<tfoot1>';
                        html += '<tr style="font-weight:bold;">';
                            html += '<th class="text-center text-capitalize" colspan="4" rowspan="4" style="vertical-align: middle;">grandtotal</th>';
                            html += '<th class="text-right text-capitalize">' + formatNumber(qty, 2) + '</th>';
                            html += '<th class="text-right text-capitalize">' + formatNumber(total, 2) + '</th>';
                        html += '</tr>';
                    html += '</tfoot1>';

                    html += '<tfoot2>';
                        html += '<tr style="font-weight:bold;">';
                            html += '<th class="text-right text-capitalize">discount</th>';
                            html += '<th class="text-right text-capitalize">' + formatNumber(header.discount, 2) + '</th>';
                        html += '</tr>';
                    html += '</tfoot2>';

                    html += '<tfoot3>';
                        html += '<tr style="font-weight:bold;">';
                            html += '<th class="text-right text-capitalize">tax</th>';
                            html += '<th class="text-right text-capitalize">' + formatNumber(header.tax, 2) + '%</th>';
                        html += '</tr>';
                    html += '</tfoot3>';

                    html += '<tfoot4>';
                        html += '<tr style="font-weight:bold;">';
                            html += '<th class="text-right text-capitalize">grandtotal</th>';
                            html += '<th class="text-right text-capitalize">' + formatNumber(header.grandtotal, 2) + '</th>';
                        html += '</tr>';
                    html += '</tfoot4>';

                html += '</table>';

                html += '<button type="button" class="btn" style="background-color:black; color:white; float: right;" onclick="printStruk(' + header.id + ')">'
                    html += '<i class="fa fa-print"></i><span style="margin-left: 5px;">Print</span>';
                html += '</button>';
            html += '</div>';

            return html;
        }

        $('#tbl_pos tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
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

    function printStruk(id) {
        const w = 750;
        const h = 600;

        const left = (screen.width / 2) - (w / 2);
        const top  = (screen.height / 2) - (h / 2);

        const win = window.open(
            "<?= site_url('Tenancy/Inventory/Selling/POS/print_struk/') ?>" + id,
            "_blank",
            `width=${w},height=${h},top=${top},left=${left}`
        );

        if (win) {
            win.focus();
        } else {
            alert('Popups are blocked by the browser. Allow popups to print receipts.');
        }
    }
</script>