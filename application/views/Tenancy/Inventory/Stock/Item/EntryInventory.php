<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- jQuery UI CSS & JS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- PDF.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- DataTables Responsive -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"> -->

<!-- DataTables JS -->
<!-- <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<style>
    #pdfViewer {
        transition: transform 0.3s ease;
        transform-origin: top left;
    }

    .btn-orange {
        background-color: orange;
        color: white;
        border-color: darkorange;
    }

    table.dataTable thead input {
        width: 100%;
        font-size: 10px;
        box-sizing: border-box;
    }
</style>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
        </script>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $this->session->flashdata('error') ?>',
            showConfirmButton: true
        });
    </script>
<?php endif; ?>



<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
        <div class="panel">
            <div class="panel-body">
                <div class="table-responsive">
                    <div style="display: flex; margin-bottom: 10px;">
                        <a class="btn btn-primary" href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/add') ?>" style="margin-right: 10px"><i class="fa fa-plus"></i> Tambah Item</a>

                        <button type="button" id="printSelected" class="btn btn-success mb-3" style="margin-right: 10px">
                            <i class="fa fa-print" style="margin-right: 10px;"></i>Cetak QR Terpilih
                        </button>

                        <button type="button" class="btn btn-danger mb-3" onclick="update()">
                            <i class="fa fa-refresh" style="margin-right: 10px;"></i>Update Stock
                        </button>

                        <div class="form-check" style="margin-left: 50px;">
                            <input class="form-check-input rb" type="radio" name="rb_measure" id="rb_big" value="big"
                                onclick="select_measure()"
                            >
                            <label class="form-check-label" for="rb_big">
                                Big
                            </label>
                        </div>

                        <div class="form-check" style="margin-left: 10px;">
                            <input class="form-check-input rb" type="radio" name="rb_measure" id="rb_middle" value="middle"
                                onclick="select_measure()"
                            >
                            <label class="form-check-label" for="rb_middle">
                                Medium
                            </label>
                        </div>

                        <div class="form-check" style="margin-left: 10px;">
                            <input class="form-check-input rb" type="radio" name="rb_measure" id="rb_small" value="small" checked
                                onclick="select_measure()"
                            >
                            <label class="form-check-label" for="rb_small">
                                Small
                            </label>
                        </div>



                        <div class="form-check" style="margin-left: 50px;">
                            <input class="form-check-input rb2" type="radio" name="rb_current_stock" id="rb_simple" value="simple" checked
                                onclick="select_simple()"
                            >
                            <label class="form-check-label" for="rb_simple">
                                Simple
                            </label>
                        </div>

                        <div class="form-check" style="margin-left: 10px;">
                            <input class="form-check-input rb2" type="radio" name="rb_current_stock" id="rb_detail" value="detail"
                                onclick="select_simple()"
                            >
                            <label class="form-check-label" for="rb_detail">
                                Detail
                            </label>
                        </div>
                    </div>

                    <table id="Inventory" class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th rowspan="3" style="text-align: center; vertical-align: middle;"><input type="checkbox" id="selectAll"></th>
                                <th rowspan="2" colspan="4" style="text-align: center; vertical-align: middle;">#</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Code</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Name</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Spec.</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Area/Cabang</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Departement</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Category</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">UOM's</th>
                                <th colspan="2" style="text-align: center; vertical-align: middle;">Beginning (<?= date('M-Y-t', strtotime('-1 month')); ?>)</th>
                                <th colspan="9" style="text-align: center; vertical-align: middle;" id="current_stock">Stock (<?= date('M-Y'); ?>)</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #B8A9F5;">Stock Ending</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #FF9200;">Jumlah (Transaction)</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Min Stock</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Max Stock</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Tgl Pembelian</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Tgl Kadaluarsa</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Status</th>
                                <th colspan="2" style="text-align: center; vertical-align: middle;">Price</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Supplier/Vendor</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Catatan</th>
                            </tr>
                            <tr>
                                <th style="text-align: center; vertical-align: middle;">Last Inventory</th>
                                <th style="text-align: center; vertical-align: middle;">Stock</th>
                                <th style="text-align: center; vertical-align: middle;">Last Inventory</th>
                                <th style="text-align: center; vertical-align: middle;" class="current_stock">Inventory</th>
                                <th style="text-align: center; vertical-align: middle;" class="current_stock">Adjustment</th>
                                <th style="text-align: center; vertical-align: middle; background-color: #FFB3B3;" class="current_stock">Abolish</th>
                                <th style="text-align: center; vertical-align: middle; background-color: #FFB3B3;" class="current_stock">Return</th>
                                <th style="text-align: center; vertical-align: middle;" class="current_stock">Incoming</th>
                                <th style="text-align: center; vertical-align: middle; background-color: #FFB3B3;" class="current_stock">Outgoing</th>
                                <th style="text-align: center; vertical-align: middle; background-color: #FFB3B3;" class="current_stock">POS</th>
                                <th style="text-align: center; vertical-align: middle;">Stock</th>
                                <th style="text-align: center; vertical-align: middle;">Buying</th>
                                <th style="text-align: center; vertical-align: middle;">Selling</th>
                            </tr>
                            <tr>
                                <th></th> <!-- No -->
                                <th></th> <!-- print -->
                                <th></th> <!-- edit -->
                                <th></th> <!-- delete -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- KdBarang -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Nama Barang -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Spesifikasi Detail -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Area/Cabang -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Departement -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Kategori -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Satuan -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Date Beginning -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Beginning -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Date Inventory -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Inventory -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Adjustment -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Abolish -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Return -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Incoming -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Outgoing -->
                                <th class="current_stock"><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock POS -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Current -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Stock Ending -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Jumlah (Master) -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Min Stock -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Max Stock -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Tgl Pembelian -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Tgl Kadaluarsa -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Status -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Harga Beli -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Harga Jual -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Supplier/Vendor -->
                                <th><input type="text" placeholder="Cari" class="form-control"></th> <!-- Catatan -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($items as $item): ?>
                                <tr>
                                    <td><input type="checkbox" class="select-item" value="<?= $item->KdBarang ?>"></td>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-print-qrcode" 
                                            data-kode="<?= $item->KdBarang ?>">
                                            <i class="fa fa-qrcode"></i> Cetak QR
                                        </button>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/edit/'.$item->KdBarang) ?>" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/delete/'.$item->KdBarang) ?>" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                    <td class="text-center"><?= $item->KdBarang ?></td>
                                    <td><?= $item->NmBarang ?></td>
                                    <td><?= $item->Spesifikasi ?></td>
                                    <td class="text-center"><?= $item->Area ?></td>
                                    <td class="text-center"><?= $item->Departement ?></td>
                                    <td class="text-center"><?= $item->Kategori ?></td>
                                    <td>
                                        <span class="small"><?= $item->measure_1 ?></span>
                                        <span class="middle"><?= $item->measure_2 ?></span>
                                        <span class="big"><?= $item->measure_3 ?></span>
                                    </td>
                                    <td class="text-center"><?= TglIndonesia($item->beginning_date) ?></td>
                                    <td class="text-right" style="background-color: #EDFFF1;">
                                        <span class="small <?= ($item->beginning_stock_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->beginning_stock_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->beginning_stock_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->beginning_stock_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->beginning_stock_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->beginning_stock_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-center"><?= TglIndonesia($item->stock_date) ?></td>
                                    <td class="text-right current_stock">
                                        <span class="small <?= ($item->stock_inventory_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_inventory_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_inventory_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_inventory_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_inventory_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_inventory_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock">
                                        <span class="small <?= ($item->stock_adjustment_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_adjustment_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_adjustment_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_adjustment_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_adjustment_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_adjustment_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock" style="background-color: #FFE5E5;">
                                        <span class="small <?= ($item->stock_abolish_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_abolish_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_abolish_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_abolish_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_abolish_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_abolish_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock" style="background-color: #FFE5E5;">
                                        <span class="small <?= ($item->stock_return_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_return_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_return_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_return_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_return_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_return_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock">
                                        <span class="small <?= ($item->stock_incoming_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_incoming_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_incoming_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_incoming_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_incoming_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_incoming_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock" style="background-color: #FFE5E5;">
                                        <span class="small <?= ($item->stock_outgoing_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_outgoing_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_outgoing_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_outgoing_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_outgoing_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_outgoing_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right current_stock" style="background-color: #FFE5E5;">
                                        <span class="small <?= ($item->stock_pos_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_pos_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_pos_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_pos_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_pos_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_pos_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right" style="background-color: #EDFFF1;">
                                        <span class="small <?= ($item->stock_total_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_total_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->stock_total_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_total_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->stock_total_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->stock_total_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right" style="background-color: #F4F2FF;">
                                        <span class="small <?= ($item->ending_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->ending_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->ending_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->ending_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->ending_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->ending_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right" style="background-color: #FFE2BF;">
                                        <span class="small <?= ($item->qty_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->qty_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->qty_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->qty_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->qty_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->qty_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="small <?= ($item->MinStock_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->MinStock_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->MinStock_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->MinStock_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->MinStock_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->MinStock_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="small <?= ($item->MaxStock_1 < 0) ? 'negative' : '' ?>"><?= number_format($item->MaxStock_1, 2, '.', ',') ?></span>
                                        <span class="middle <?= ($item->MaxStock_2 < 0) ? 'negative' : '' ?>"><?= number_format($item->MaxStock_2, 2, '.', ',') ?></span>
                                        <span class="big <?= ($item->MaxStock_3 < 0) ? 'negative' : '' ?>"><?= number_format($item->MaxStock_3, 2, '.', ',') ?></span>
                                    </td>
                                    <td class="text-center"><?= TglIndonesia($item->TglBeli) ?></td>
                                    <td class="text-center"><?= TglIndonesia($item->TglKadaluarsa) ?></td>
                                    <td class="text-center"><?= $item->Status ?></td>
                                    <td class="text-right"><?= number_format($item->Harga, 2, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($item->harga_jual, 2, ',', '.') ?></td>
                                    <td><?= $item->Vendor ?></td>
                                    <td><?= $item->Catatan ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</section>



    <style>
		th select.select2 {
			font-size: 12px;
			height: 30px;
		}
		.select2-container .select2-selection--single {
			height: 30px !important;
			padding: 2px;
		}
		.select2-selection__rendered {
			font-size: 12px;
			line-height: 26px;
		}

        .negative {
            color: red;
            font-weight: bold;
        }
	</style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
	<script>
        $(document).ready(function() {
            select_measure();
            select_simple();

            // Inisialisasi DataTable
            var table = $('#Inventory').DataTable({
                // responsive: true,
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "View All"]
                ]
            });

            // // Pasang event listener pada setiap input kolom
            // $('#Inventory thead tr:eq(1) th').each(function(i) {
            //     $('input', this).on('keyup change', function() {
            //         if (table.column(i).search() !== this.value) {
            //             table.column(i).search(this.value).draw();
            //         }
            //     });
            // });

            var columnMap = {
                4: 5,  // KdBarang
                5: 6,  // Nama Barang
                6: 7,  // Spesifikasi
                7: 8,  // Area/Cabang
                8: 9,  // Departement
                9: 10, // Kategori
                10: 11, // Satuan
                11: 12, // Date Beginning
                12: 13, // Stock Beginning
                13: 14, // Date Inventory
                14: 15, // Stock Inventory
                15: 16, // Stock Adjustment
                16: 17, // Stock Abolish
                17: 18, // Stock Return
                18: 19, // Stock Incoming
                19: 20, // Stock Outgoing
                20: 21, // Stock POS
                21: 22, // Stock Current
                22: 23, // Stock Ending
                23: 24, // Jumlah
                24: 25, // Min Stock
                25: 26, // Max Stock
                26: 27, // Tgl Pembelian
                27: 28, // Tgl Kadaluarsa
                28: 29, // Status
                29: 30, // Harga Beli
                30: 31, // Harga Jual
                31: 32, // Supplier/Vendor
                32: 33 // Catatan
            };

            $('#Inventory thead tr:eq(2) th').each(function(inputIdx) {
                var input = $(this).find('input');
                if (input.length && columnMap[inputIdx] !== undefined) {
                    var colIndex = columnMap[inputIdx];
                    input.on('keyup change', function() {
                        table
                            .column(colIndex)
                            .search(this.value)
                            .draw();
                    });
                }
            });
        });

        // Cetak QR Code
        $(document).on('click', '.btn-print-qrcode', function() {
            const kode = $(this).data('kode');

            const printWindow = window.open('', '_blank', 'width=400,height=400');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Cetak QR - ${kode}</title>
                    <style>
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            font-family: Arial, sans-serif;
                        }
                        .label-container {
                            display: inline-block;
                            text-align: center;
                        }
                        #qrcode {
                            margin: 0 auto;
                        }
                        .kode {
                            font-size: 16px;
                            font-weight: bold;
                            margin-top: 8px;
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <div class="label-container">
                        <div id="qrcode"></div>
                        <div class="kode">${kode}</div>
                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>
                    <script>
                        var qrcode = new QRCode(document.getElementById("qrcode"), {
                            text: "${kode}",
                            width: 180,
                            height: 180
                        });

                        setTimeout(function() {
                            window.print();
                            window.onafterprint = function() { window.close(); };
                        }, 600);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        });


        // Checkbox Select All
        $('#selectAll').on('change', function() {
            $('.select-item').prop('checked', this.checked);
        });

        // Cetak QR Code massal
        $('#printSelected').on('click', function() {
            const selectedCodes = $('.select-item:checked').map(function() {
                return this.value;
            }).get();

            if (selectedCodes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih minimal 1 item untuk dicetak QR-nya!'
                });
                return;
            }

            const printWindow = window.open('', '_blank', 'width=600,height=800');
            let htmlContent = `
                <html>
                <head>
                    <title>Cetak QR Massal</title>
                    <style>
                        body {
                                font-family: Arial, sans-serif;
                                display: flex;
                                flex-wrap: wrap;
                                gap: 15px;
                                padding: 20px;
                            }

                            .label {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                width: 180px; /* harus sama dengan width QRCode */
                            }

                            .label canvas,
                            .label img {
                                display: block;
                                width: 180px;  /* samakan dengan QR */
                                height: 180px;
                            }

                            .kode {
                                display: block;
                                width: 180px;  /* sama dengan QR */
                                font-weight: bold;
                                font-size: 14px;
                                text-align: center;  /* tengah secara horizontal */
                                word-wrap: break-word;
                                margin-top: 5px;
                            }
                        }


                    </style>
                </head>
                <body>
            `;

            selectedCodes.forEach(kode => {
                htmlContent += `
                    <div class="label">
                        <div id="qrcode-${kode}"></div>
                        <div class="kode">${kode}</div>
                    </div>
                `;
            });

            htmlContent += `
                <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>
                <script>
                    ${selectedCodes.map(kode => `
                        new QRCode(document.getElementById("qrcode-${kode}"), {
                            text: "${kode}",
                            width: 120,
                            height: 120
                        });
                    `).join('')}
                    setTimeout(() => { window.print(); window.onafterprint = () => window.close(); }, 800);
                <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(htmlContent);
            printWindow.document.close();
        });

        // $(document).idle({
        //     onIdle: function() {
        //         window.location = "/logout.php";
        //     },
        //     idle: 10000
        // });

        function select_measure() {
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
            
            const radioButtons = document.getElementsByClassName('rb');
            let selectedValue = '';

            for (const radio of radioButtons) {
                if (radio.checked) {
                    selectedValue = radio.value;
                    break; // Exit the loop once the selected button is found
                }
            }

            // show
            chk = document.getElementsByClassName(selectedValue);
            for (var i = 0; i < chk.length; i++) {
                chk[i].hidden = false;
            }
        }

        function select_simple() {
            const radioButtons = document.getElementsByClassName('rb2');
            let selectedValue = '';

            for (const radio of radioButtons) {
                if (radio.checked) {
                    selectedValue = radio.value;
                    break; // Exit the loop once the selected button is found
                }
            }

            const current_stock = document.getElementById("current_stock");
            chk = document.getElementsByClassName('current_stock');
            if (selectedValue == 'simple') {
                current_stock.colSpan = 2;
                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = true;
                }
            } else if (selectedValue == 'detail') {
                current_stock.colSpan = 9;
                for (var i = 0; i < chk.length; i++) {
                    chk[i].hidden = false;
                }
            }
        }

        function update() {
            if (confirm("Are you sure?")) {
                $.ajax({
                    dataType: "json",
                    type: "GET",
                    url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/update_stock_all') ?>",

                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(data) {
                        alert(data);
                        if (data == 'Update successfully!') {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        return;
                    }
                });
            }
        }
	</script>
</div>


