<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $mos->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?php echo site_url('job/FormKO/' . $KO->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box box-solid">
							<div class="box-header with-border">
								<div class="box-header bg-blue-gradient">
									<h3 class="box-title">KETERANGAN PO</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<div class="row">
									<div class="col-md-4">
										<div class="box-header bg-blue-gradient">
											<h3 class="box-title">PENERIMA PO</h3>
											<div class="box-tools pull-right">
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="form-group row">
													<input type="text" name="JobNo" id="JobNo" value="<?php echo $KO->JobNo ?>" hidden>
													<label class="col-sm-3 col-form-label">Id</label>
													<div class="col-sm-8">
														<select name="VendorId" id="VendorId" class="form-control">
															<option value="">--Pilih Vendor</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Nama</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="NamaVendor" id="NamaVendor">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Alamat</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Alamat" id="Alamat">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Telp</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Telp" id="Telp">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">NPWP</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="NPWP" id="NPWP">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">UP</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Up" id="Up">
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="box-header bg-blue-gradient">
											<h3 class="box-title">INFORMASI TENTANG PO</h3>
											<div class="box-tools pull-right">
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Tgl PO</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="TglPO" id="TglPO">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">NoPO</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="NoPO" id="NoPO">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Job No</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Alamat" id="Alamat">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">NO SPR</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<select name="NoSPR" id="NoSPR" class="form-control">
															<option value="">--- Pilih SPR ---</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Kategori</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Kategori" id="Kategori">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Bidang Usaha</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Alamat" id="Alamat">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="box-header bg-blue-gradient">
											<h3 class="box-title">LOKASI PENGIRIMAN</h3>
											<div class="box-tools pull-right">
											</div>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Nama</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="TglKontrak" id="Alamat">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Alamat</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<textarea name="" id="" cols="30" rows="5" class="form-control"></textarea>
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Telepon</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Alamat" id="Alamat">
													</div>
												</div>
												<div class="form-group row">
													<label for="" class="col-sm-3 col-form-label">Tgl Pengiriman</label>
													<label for="" class="col-sm-1 col-form-label">:</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="Kategori" id="Kategori">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group-row">
										<div class="checkbox col-sm-2">
											<label>
												<input type="checkbox" value="MA">
												Material Approval
											</label>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<label>
												<input type="checkbox" value="RAP">
												RAP
											</label>
										</div>
									</div>

									<div>
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
										<h3 class="box-title">ORDER LIST</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
										</div>
									</div>
								</div>

								<div class="box-body">
									<a data-toggle="modal" data-target="#ModalRincianKerja" type="button" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp; TAMBAH</a>
									<div class="box-body table-responsive no-no-padding">
										<table class="table table-hover" border="1" cellspacing="2" width="100%"">
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
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>
													<a data-target="" data-toggle=" modal" class="btn btn-primary btn-xs">SELECT</a>
											<a data-target="" data-toggle="modal" class="btn btn-warning btn-xs">PRINT</a>
											<a data-target="" data-toggle="modal" class="btn btn-success btn-xs">PDF</a>
											</td>
											</tr>
											</tbody>
										</table>
										<div class="form-group row">
											<label for="" class="col-sm-1 col-form-label" style="margin-left: 40%;">Disc (%)</label>
											<div class="col-sm-1">
												<input type="text" class="form-control pull-right" name="DiscPercentage" id="DiscPercentage">
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control pull-right" name="DiscAmount" id="DiscAmount">
											</div>
										</div>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 40%;">PPN</label>
											<div class="col-sm-2">
												<input type="text" class="form-control pull-right" name="PPN" id="PPN">
											</div>
										</div>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 40%;">Grand Total</label>
											<div class="col-sm-2">
												<input type="text" class="form-control pull-right" name="Alamat" id="Alamat">
											</div>
										</div>
										<div class="form-group row">
											<label for="" class="col-sm-2 col-form-label" style="margin-left: 40%;">Mengurangi KO</label>
											<div class="col-sm-2">
												<select name="NoKO" id="NoKO" class="form-control">
													<option value="">--- Pilih No KO --- </option>
												</select>
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
											<label for="" class="col-sm-12 col-form-label">1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3)&nbsp<input type="checkbox" name="cb1" id="cb1"></label>
										</div>
										<div class="form-group">
											<label for="">2. Syarat Teknis</label>
											<textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control"></textarea>
										</div>
										<div class="form-group">
											<label for="">3. Jadwal Pengiriman</label>
											<textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control"></textarea>
										</div>
										<div class="form-group">
											<label for="">4. Jadwal Pembayaran</label>
											<textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control"></textarea>
										</div>
									</div>
									<br>
									<br>

									<div class="col-md-6">
										<div class="form-group row">
											<label for="">5. Syarat Pembayaran</label>
										</div>
										<div class="col-md-6">
											<div class="col-sm-12">
												<input type="checkbox" name="INV" id="INV">&nbsp<label for="">Invoice/Kwitansi</label>
											</div>
											<div class="col-sm-12">
												<input type="checkbox" name="SJ" id="SJ">&nbsp<label for="">Surat Jalan/Tanda Terima Lapangan</label>
											</div>
											<div class="col-sm-12">
												<input type="checkbox" name="PO" id="PO">&nbsp<label for="">Copy PO</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-sm-12">
												<input type="checkbox" name="FP" id="FP">&nbsp<label for="">Faktur Pajak</label>
											</div>
											<div class="col-sm-12">
												<input type="checkbox" name="BAP" id="BAP">&nbsp<label for="">Berita Acara Pembayaran</label>
											</div>
											<div class="col-sm-12">
												<input type="checkbox" name="BAOP" id="BAOP">&nbsp<label for="">Berita Acara Opname Pekerjaan</label>
											</div>
										</div>
										<div class="form-group row"></div>
										<div class="form-group row"></div>
										<div class="form-group">
											<label for="">6. Sanksi</label>
											<textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control"></textarea>
										</div>
										<div class="form-group">
											<label for="">7. Keterangan Lain</label>
											<textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control"></textarea>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-warning">SIMPAN</button>
										<button type="button" class="btn btn-google" data-dismiss="modal">BATAL</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="modal fade" id="ModalRincianKerja">
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
										<input type="text" name="NoUrut" id="NoUrut" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label">Kode RAP</label>
									<div class="col-sm-8">
										<select name="KodeRAP" id="KodeRAP" class="form-control">
											<option value="">---Pilih Kode RAP---</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label">Uraian</label>
									<div class="col-sm-8">
										<textarea name="Uraian" id="" cols="50" rows="10" class="form-control"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label">Volume</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="Vol" id="Vol">
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label">Satuan</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="Sat" id="Sat">
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label">Harga Satuan</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="HrgSatuan" id="HrgSatuan">
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



			</div>
	</section>



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
