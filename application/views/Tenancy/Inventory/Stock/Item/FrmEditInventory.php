<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php elseif($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<div class="panel">
			<div class="panel-body">
				<form action="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/AksiEdit/'.$item->KdBarang) ?>" method="post" enctype="multipart/form-data" role="form">
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered" style="width: 100%;">
								<tr>
									<th>Code</th>
									<td>:</td>
									<td colspan="4"><input type="text" class="form-control" id="KdBarang" name="KdBarang" placeholder="Code" value="<?= $item->KdBarang ?>" readonly></td>
								</tr>
								<tr>
									<th>Name</th>
									<td>:</td>
									<td colspan="4"><input type="text" class="form-control" id="NmBarang" name="NmBarang" placeholder="Name" value="<?= $item->NmBarang ?>" required></td>
								</tr>
								<tr>
									<th>Specification</th>
									<td>:</td>
									<td colspan="4"><input type="text" class="form-control" id="Spesifikasi" name="Spesifikasi" placeholder="Specification" value="<?= $item->Spesifikasi ?>" required></td>
								</tr>
								<tr>
									<th>Qty</th>
									<td>:</td>
									<td colspan="4">
										<div class="input-group">
											<input type="number" any="step" class="form-control" id="Jumlah" name="Jumlah" placeholder="Qty"
												style="text-align: right;"
												value="<?= $item->Jumlah ?>"
												required
											>
											<span class="input-group-addon uom">UOM</span>
										</div>
									</td>
								</tr>

								<tr>
									<th rowspan="3">UOM</th>
									<td rowspan="3">:</td>
									
									<?= $x_uom = ''; $seq = 0; $size = 0; ?>
									<th>Small</th>
									<td>
										<select name="uom_small" id="uom_small" class="form-control select2 input-sm" required
											onchange="select_uom()"
										>
											<?= $x_uom = $row->Satuan; $seq = 0; $size = 1; ?>
											<?php foreach ($uom_has_item as $data): ?>
												<?= $seq += 1 ?>
												<?php if ($seq == 1) {
													$x_uom = $data->name;
													$size = $data->size;
													break;
												}?>
											<?php endforeach; ?>

											<option value="">--Select UOM--</option>
											<?php foreach ($uom as $row): ?>
												<option value="<?= $row->name ?>" <?= strtolower($row->name) == strtolower($x_uom) ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
									<th>Size</th>
									<td>
										<input type="number" any="step" class="form-control" id="size_small" name="size_small" placeholder="Size Small" style="text-align: right;" value="<?= number_format($size, 0) ?>" readonly required>
									</td>
								</tr>

								<tr>
									<?= $x_uom = ''; $seq = 0; $size = 0; ?>
									<th>Medium</th>
									<td>
										<select name="uom_middle" id="uom_middle" class="form-control select2 input-sm">
											<?= $x_uom = ''; $seq = 0; $size = 0; ?>
											<?php foreach ($uom_has_item as $data): ?>
												<?= $seq += 1 ?>
												<?php if ($seq == 2) {
													$x_uom = $data->name;
													$size = $data->size;
													break;
												}?>
											<?php endforeach; ?>

											<option value="">--Select UOM--</option>
											<?php foreach ($uom as $row): ?>
												<option value="<?=$row->name?>" <?= strtolower($row->name) == strtolower($x_uom) ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
									<th>Size</th>
									<td>
										<input type="number" any="step" class="form-control" id="size_middle" name="size_middle" placeholder="Size Medium" style="text-align: right;" value="<?= number_format($size, 2) ?>">
									</td>
								</tr>

								<tr>
									<?= $x_uom = ''; $seq = 0; $size = 0; ?>
									<th>Big</th>
									<td>
										<select name="uom_big" id="uom_big" class="form-control select2 input-sm">
											<?= $x_uom = ''; $seq = 0; $size = 0; ?>
											<?php foreach ($uom_has_item as $data): ?>
												<?= $seq += 1 ?>
												<?php if ($seq == 3) {
													$x_uom = $data->name;
													$size = $data->size;
													break;
												}?>
											<?php endforeach; ?>

											<option value="">--Select UOM--</option>
											<?php foreach ($uom as $row): ?>
												<option value="<?=$row->name?>" <?= strtolower($row->name) == strtolower($x_uom) ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
									<th>Size</th>
									<td>
										<input type="number" any="step" class="form-control" id="size_big" name="size_big" placeholder="Size Big" style="text-align: right;"value="<?= number_format($size, 2) ?>">
									</td>
								</tr>
								
								<tr>
									<th>Category</th>
									<td>:</td>
									<td colspan="4">
										<select name="Kategori" id="Kategori" class="form-control select2 input-sm" required>
										<option value="">--Select Category--</option>
											<?php foreach ($category as $row): ?>
												<option value="<?=$row->name?>" <?= $row->name == $item->Kategori ? 'selected' : '' ?>><?=$row->note?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Area / Cabang</th>
									<td>:</td>
									<td colspan="4">
										<select name="Area" id="Area" class="form-control select2 input-sm" required>
										<option value="">--Select Area / Cabang--</option>
											<?php foreach ($building as $row): ?>
												<option value="<?=$row->code?>" <?= $row->code == $item->Area ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Departement</th>
									<td>:</td>
									<td colspan="4">
										<select name="Departement" id="Departement" class="form-control select2 input-sm" required>
										<option value="">--Select Departement--</option>
											<?php foreach ($departement as $row): ?>
												<option value="<?=$row->code?>" <?= $row->code == $item->Departement ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Supplier/Vendor</th>
									<td>:</td>
									<td colspan="4">
										<select name="Vendor" id="Vendor" class="form-control select2 input-sm">
										<option value="">--Select Vendor--</option>
											<?php foreach ($supplier as $row): ?>
												<option value="<?=$row->code?>" <?= $row->code == $item->Vendor ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>
						</div>

						<div class="col-md-6">
							<table class="table table-bordered" style="width: 100%;">
								<tr>
									<th>Buying</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control" id="Harga" name="Harga" placeholder="Price Buying" value="<?= number_format($item->Harga) ?>" style="text-align: right;" required>
									</td>
								</tr>
								<tr>
									<th>Selling</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Price Selling" value="<?= number_format($item->harga_jual) ?>" style="text-align: right;" required>
									</td>
								</tr>
								<tr>
									<th>Date Buying</th>
									<td>:</td>
									<td><input type="date" class="form-control" id="TglBeli" name="TglBeli" placeholder="Date Buying" value="<?= date('Y-m-d', strtotime($item->TglBeli)) ?>" required></td>
								</tr>
								<tr>
									<th>Date Expired</th>
									<td>:</td>
									<td><input type="date" class="form-control" id="TglKadaluarsa" name="TglKadaluarsa" placeholder="Date Expired" value="<?= date('Y-m-d', strtotime($item->TglKadaluarsa)) ?>" required></td>
								</tr>
								<tr>
									<th>Status</th>
									<td>:</td>
									<td>
										<select name="Status" id="Status" class="form-control select2 input-sm" required>
											<option value="">--Select Status--</option>
											<?php foreach ($status as $row): ?>
												<option value="<?=$row->name?>" <?= $row->name == $item->Status ? 'selected' : '' ?>><?=$row->name?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Minimum Stock</th>
									<td>:</td>
									<td>
										<div class="input-group">
											<input type="number" any="step" class="form-control" id="MinStock" name="MinStock" placeholder="Minimum Stock"
												style="text-align: right;"
												required value="<?= $item->MinStock ?>"
											>
											<span class="input-group-addon uom">UOM</span>
										</div>
									</td>
								</tr>
								<tr>
									<th>Maximum Stock</th>
									<td>:</td>
									<td>
										<div class="input-group">
											<input type="number" any="step" class="form-control" id="MaxStock" name="MaxStock" placeholder="Maximum Stock"
												style="text-align: right;"
												required value="<?= $item->MaxStock ?>"
											>
											<span class="input-group-addon uom">UOM</span>
										</div>
									</td>
								</tr>
								<tr>
									<th>Note</th>
									<td>:</td>
									<td><input type="text" class="form-control" id="Catatan" name="Catatan" placeholder="Note" value="<?= $item->Catatan ?>" required></td>
								</tr>
							</table>
							
							<a href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory/index') ?>" class="btn pull-right" style="margin-right: 7px; background-color: black; color: white;"><i class="fa fa-undo" style="color: white; margin-right: 5px;"></i>Cancel</a>
							<button type="submit" class="btn btn-warning pull-right" style="margin-right: 5px;"><i class="fa fa-save" style="margin-right: 5px;"></i>Update</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<style>
		.table.table-bordered,
		.table.table-bordered th,
		.table.table-bordered td {
			border: none !important;
		}
	</style>

	<script>
		// document.querySelector('form').addEventListener('submit', function() {
		// 	let hargaInput = document.getElementById('Harga');
		// 	// Hilangkan semua karakter non-angka
		// 	hargaInput.value = hargaInput.value.replace(/\D/g, '');
		// });

		select_uom();
		function select_uom() {
			const elements = document.getElementsByClassName("uom");
			for (let i = 0; i < elements.length; i++) {
				elements[i].innerHTML = document.getElementById("uom_small").value == '' ? 'UOM' : document.getElementById("uom_small").value;
			}
		}

		// $(document).idle({
		// 	onIdle: function() {
		// 		window.location = "/logout.php";
		// 	},
		// 	idle: 10000
		// });
	</script>
</div>

