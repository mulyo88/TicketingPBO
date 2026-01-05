<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $view_datako->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $view_datako->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $view_datako->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<!-- <a href="<?php echo site_url('Job/formKO/' . $FormKO->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->
	<?php foreach ($view_datako as $vd) : ?>
		<section class="content-header">
			<div class="row" id="MOD-IN">
				<div class="col-md-12">
					<div class="box box-warning">
						<div class="box-header with-border">
							<div class="box box-solid">
								<div class="box-header with-border">
									<div class="box-header bg-blue-gradient">
										<h3 class="box-title">PENERIMA KONTRAK</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
										</div>
									</div>
								</div>

								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<input type="text" name="JobNo" id="JobNo" value="<?php echo $vd->JobNo ?>" hidden>
												<label class="col-sm-3 col-form-label">Id</label>
												<div class="col-sm-8">
													<select name="VendorId" id="VendorId" class="form-control">
														<option value=""><?php echo $vd->VendorId ?></option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Nama</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="NamaVendor" id="NamaVendor" value="<?php echo $vd->VendorNm ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Alamat</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="Alamat" id="Alamat" value="<?php echo $vd->Alamat ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Telp</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="Telp" id="Telp" value="<?php echo $vd->Telepon1 ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">NPWP</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="NPWP" id="NPWP" value="<?php echo $vd->NPWP ?>">
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Tgl Kontrak</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="TglKontrak" id="TglKontrak" value="<?php echo $vd->TglKO ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">NoKontrak</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="NoKontrak" id="NoKontrak" value="<?php echo $vd->NoKO ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Job No</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="JobNo" id="JobNO" value="<?php echo $vd->JobNo ?> - <?php echo $vd->JobNm ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Kategori</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="Kategori" id="Kategori" value="<?php echo $vd->KategoriId ?>">
												</div>
											</div>
											<div class="form-group row">
												<label for="" class="col-sm-3 col-form-label">Bidang Usaha</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="Bidus" id="Bidus" value="<?php echo $vd->BidangUsaha ?>">
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="box box-warning">
						<div class="box-header with-border">
							<div class="box box-solid">
								<div class="box-header with-border">
									<div class="box-header bg-green-active">
										<h3 class="box-title">RINCIAN PEKERJAAN</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
										</div>
									</div>
								</div>

								<div class="box-body">
									<a data-toggle="modal" data-target="#ModalRincianKerja" type="button" class="btn btn-dropbox"><i class="fa fa-plus"></i> &nbsp; TAMBAH</a>
									<div class="box-body table-responsive no-no-padding">
										<table id="RincianKO" class="table table-hover" border="1" cellspacing="2" width="100%"">
										<thead>
											<tr>
												<th>No</th>
												<th>Kode RAP</th>
												<th>Uraian</th>
												<th>Vol</th>
												<th>Sat</th>
												<th>Harga Satuan (Rp)</th>
												<th>Jumlah Harga (Rp)</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($v_KoDtl as $vk) :
												$Jumlah_Harga[] = $vk->Vol * $vk->HrgSatuan;
												$grandtotal = array_sum($Jumlah_Harga);
											?>
												
											<tr>
												<td><?php echo $vk->NoUrut ?></td>
												<td><?php echo $vk->KdRAP ?></td>
												<td><?php echo $vk->Uraian ?></td>
												<td><?php echo $vk->Vol ?></td>
												<td><?php echo $vk->Uom ?></td>
												<td><?php echo number_format($vk->HrgSatuan) ?></td>
												<td><?php echo number_format($vk->Vol * $vk->HrgSatuan) ?></td>
												<td>
													<a data-target=" #ModalRincianKerja<?php echo $vk->NoUrut ?>" data-toggle="modal" class="btn btn-primary btn-xs">SELECT</a>
													<a data-target="" data-toggle="modal" class="btn btn-warning btn-xs">PRINT</a>
													<a data-target="" data-toggle="modal" class="btn btn-success btn-xs">PDF</a>
											</td>
											</tr>
										<?php endforeach; ?>
										</tbody>
										</table>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 50%;">Dikurangi KO</label>
											<div class="col-sm-2">
												<input type="text" class="form-control pull-right" name="KurangKO" id="KurangKO">
											</div>
										</div>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 50%;">Total Pengurangan</label>
											<div class="col-sm-2">
												<input type="text" class="form-control pull-right" name="TtlPengurang" id="TtlPengurang">
											</div>
										</div>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 50%;">Grand Total</label>
											<d class="col-sm-2">
												<input type="text" class="form-control pull-right" name="GrandTotal" id="GrandTotal" value="<?php echo number_format($grandtotal) ?>">
											</d value="php" iv>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="box box-warning">
						<div class="box-header with-border">
							<div class="box box-solid">
								<div class="box-header with-border">
									<div class="box-header bg-orange-active">
										<h3 class="box-title">PERSYARATAN DAN SANKSI</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
										</div>
									</div>
								</div>

								<div class="box-body">
									<div class="col-md-6">
										<div class="form-group row">
											<label for="" class="col-sm-12 col-form-label">1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3)&nbsp
												<input type="checkbox" name="cb1[]" value="1" id="cb1" <?php echo ($vd === '1') ? 'checked' : ''; ?>></label>
										</div>
										<div class="form-group">
											<label for="">2. Syarat Teknis</label>
											<textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control"><?php echo $vd->SyaratTeknis ?></textarea>
										</div>
										<div class="form-group">
											<label for="">3. Jadwal Pengiriman</label>
											<textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control"><?php echo $vd->JadwalPengiriman ?></textarea>
										</div>
										<div class="form-group">
											<label for="">4. Jadwal Pembayaran</label>
											<textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control"><?php echo $vd->JadwalPembayaran ?></textarea>
										</div>
									</div>
									<br>
									<br>
									<div class="col-md-6">
										<div class="form-group row">
											<label for="">5. Syarat Pembayaran</label>
										</div>

										<div class="control-group after-add-more3">
											<ul class="todo-list">
												<?php
												$DatasyaratPemb = [
													'Invoice/Kwitansi',
													'Surat Jalan/Tanda Terima Lapangan',
													'Copy PO', 'Faktur Pajak',
													'Berita Acara Pembayaran',
													'Berita Acara Opname Pekerjaan'
												];
												?>
												<div class="control-group after-add-more3">
													<?php foreach ($DatasyaratPemb as $row => $value) : ?>
														<?php $check = ''; ?>
														<?php
														foreach ($checkSP as $r => $v) {
															if ($v->item == $value) $check = 'checked';
														}
														?>

														<li>
															<span class="handle">
																<i class="fa fa-ellipsis-v"></i>
																<i class="fa fa-ellipsis-v"></i>
															</span>
															<input type="checkbox" <?= $check ?> name="SyaratPembayaran[]" value="<?= $value ?>">
															<span class="text"><?= $value ?></span>
														</li>
													<?php endforeach; ?>
												</div>
											</ul>

										</div>
										<div class="form-group">
											<label for="">6. Sanksi</label>
											<textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control"><?php echo $vd->Sanksi ?></textarea>
										</div>
										<div class="form-group">
											<label for="">7. Keterangan Lain</label>
											<textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control"><?php echo $vd->Keterangan ?></textarea>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">SIMPAN</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>

			<?php foreach($v_KoDtl as $dtl) : ?>
			<div class="modal fade" id="ModalRincianKerja<?php echo $dtl->NoUrut ?>">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">DATA ENTRY</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">No.</label>
								<div class="col-sm-3">
									<input type="text" name="NoUrut" id="NoUrut" class="form-control" value="<?php echo $dtl->NoUrut ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">Kode RAP</label>
								<div class="col-sm-8">
									<select name="KodeRAP" id="KodeRAP" class="form-control">
										<option value=""><?php echo $dtl->NoUrut ?></option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">Uraian</label>
								<div class="col-sm-8">
									<textarea name="Uraian" id="" cols="50" rows="10" class="form-control"><?php echo $dtl->Uraian ?></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">Volume</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="Vol" id="Vol" value="<?php echo $dtl->Vol ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">Satuan</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="Sat" id="Sat" value="<?php echo $dtl->Uom ?>">
								</div>
							</div>
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label">Harga Satuan</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="HrgSatuan" id="HrgSatuan" value="<?php echo $dtl->NoUrut ?>">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary">Tambah</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
</div>
</section>
<?php endforeach; ?>

<script>
	$(document).ready(function() {
		$('#RincianKO').DataTable();
	})
</script>




<script>
	$(document).idle({
		onIdle: function() {
			window.location = "/logout.php";
		},
		idle: 10000
	});
</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->
