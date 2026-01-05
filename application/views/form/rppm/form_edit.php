<form id="MyForm" enctype="multipart/form-data" action="<?= site_url("Rppm/editEmon") ?>">

	<div class="form-group">
		<form class="form-horizontal">

			<div class="form-group row">
				<label class="col-sm-4 col-form-label">Tahun</label>
				<div class="col-sm-8">
					<select class="form-control" name="Tahun" id="Tahun" required>
						<option value="<?= $data->Tahun ?>"><?= $data->Tahun ?></option>
						<?php $tahun = date('Y');
						for ($i = '2020'; $i < $tahun + 5; $i++) { ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 col-form-label">Bulan</label>
				<div class="col-sm-8">
					<select class="form-control" name="Bulan" id="Bulan" required>
						<option value="<?= $data->Bulan ?>"><?= $data->Bulan ?></option>
						<option value="01">Januari</option>
						<option value="02">Februari</option>
						<option value="03">Maret</option>
						<option value="04">April</option>
						<option value="05">Mei</option>
						<option value="06">Juni</option>
						<option value="07">Juli</option>
						<option value="08">Agustus</option>
						<option value="09">September</option>
						<option value="10">Oktober</option>
						<option value="11">November</option>
						<option value="12">Desember</option>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-lg-4 col-form-label">Rencana Fisik Tahun Berjalan (%)</label>
				<div class="col-sm-8">
					<input type="text" name="RencanaFisTB" id="RencanaFisTB" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" autocomplete="off" value="<?= $data->RencanaTB ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-4 col-form-label">Realisasi Fisik Tahun Berjalan (%)</label>
				<div class="col-sm-8">
					<input type="text" name="RealisasiFisTB" id="RealisasiFisTB" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" autocomplete="off" value="<?= $data->RealisasiTB ?>">
				</div>
			</div>
			<div class=" form-group row">
					<label class="col-lg-4 col-form-label">Realisasi Keuangan Tahun Berjalan (%)</label>
					<div class="col-sm-8">
						<input type="text" name="RealisasiKeuTB" id="RealisasiKeuTB" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" autocomplete="off" value="<?= $data->RealisasiKeuTB ?>">
					</div>
				</div>

			</div>
			<div class="form-group row">
						<label class="col-lg-4 col-form-label">Rencana Fisik Komulatif (%)</label>
						<div class="col-sm-8">
							<input type="text" name="RencanaFiskom" id="RencanaFiskom" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" value="<?= $data->RencanaK ?>" >
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-4 col-form-label">Realisasi Fisik Komulatif (%)</label>
						<div class="col-sm-8">
							<input type="text" name="RealisasiFiskom" id="RealisasiFiskom" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" value="<?= $data->RealisasiK ?>" >
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-4 col-form-label">Realisasi Keuangan Komulatif (%)</label>
						<div class="col-sm-8">
							<input type="text" name="RealisasiKeuKom" id="RealisasiKeuKom" class="form-control" placeholder="0.00" onkeypress="return hanyaAngka(event)" value="<?= $data->RealisasiKeuK ?>" >
						</div>
					</div>

					<hr>
					<input type="hidden" name="JobNo" id="JobNo" class="form-control" value="<?= $data->JobNo ?>">
					<input type="hidden" name="LedgerNo" class="form-control" value="<?= $data->LedgerNo ?>">
					<button type="submit" name="simpan" id="simpan" class="btn btn-facebook pull-right">SIMPAN</button>
						</div>



		</form>

		<script type="text/javascript">
			$(function() {
				var RencanaFiskom = $('#RencanaFiskom').val();
				var RealisasiFiskom = $('#RealisasiFiskom').val();
				var RealisasiKeuKom = $('#RealisasiKeuKom').val();

				// $('#MyForm').submit(function(e) {
				//     alert('ok');
				// })

				$('#Tahun,#Bulan').select2({
					width: "100%"
				});

				$('#RencanaFisTB').keyup(function(e) {
					var hitung;

					// console.log("Jumlah Awal RencanaFiskom :" + RencanaFiskom);
					if (RencanaFiskom != 0 || RencanaFiskom != '') {

						if (this.value == '') {
							$('#RencanaFiskom').val(RencanaFiskom);
							return false;
						}

						hitung = parseInt(RencanaFiskom) + parseInt(this.value);
						$('#RencanaFiskom').val((isNaN(hitung) ? 0 : hitung));
						// console.log("fiskom : " + parseInt(RencanaFiskom));
						// console.log("nilai : " + parseInt(this.value));
						// console.log("jumlah : " + (isNaN(hitung) ? 0 : hitung));
						return false;
					}

					$('#RencanaFiskom').val(this.value);

				});

				$('#RealisasiFisTB').keyup(function(e) {
					var hitung;
					if (RealisasiFiskom != 0 || RealisasiFiskom != '') {

						if (this.value == '') {
							$('#RealisasiFiskom').val(RealisasiFiskom);
							return false;
						}

						hitung = parseInt(RealisasiFiskom) + parseInt(this.value);
						$('#RealisasiFiskom').val((isNaN(hitung) ? 0 : hitung));
						return false;
					}

					$('#RealisasiFiskom').val(this.value);
				});


				$('#RealisasiKeuTB').keyup(function(e) {

					var hitung;
					if (RealisasiKeuKom != 0 || RealisasiKeuKom != '') {

						if (this.value == '') {
							$('#RealisasiKeuKom').val(RealisasiKeuKom);
							return false;
						}

						hitung = parseInt(RealisasiKeuKom) + parseInt(this.value);
						$('#RealisasiKeuKom').val((isNaN(hitung) ? 0 : hitung));
						return false;
					}
					$('#RealisasiKeuKom').val(this.value);
				});

				$('#MyForm').submit(function(e) {
					e.preventDefault();
					var formData = new FormData(this);
					$.ajax({
						url: "<?= base_url('Rppm/editEmon') ?>",
						type: "POST",
						dataType: "JSON",
						data: formData,
						cache: false,
						contentType: false,
						processData: false,
						success: function(response) {

							// alert(response);
							// return false;

							if (response.status == 'success') {
								$('#MyModal').modal('hide');
								$.toast({
									heading: 'Berhasil',
									text: 'Data Telah Ditambahkan',
									position: 'top-right',
									showHideTransition: 'slide',
									icon: 'success',
									afterHidden : function(){
										location.reload();
									}
								});

							} else {
								$('#MyModal').modal('hide');
								$.toast({
									heading: 'Terjadi Kesalahan',
									text: 'Gagal Menambahkan Data',
									position: 'top-right',
									showHideTransition: 'slide',
									icon: 'error'
								})
							}
							// reload_table();



						},
						error: function(jqXHR, exception) {
							var msg = '';
							if (jqXHR.status === 0) {
								msg = 'Not connect.\n Verify Network.';
							} else if (jqXHR.status == 404) {
								msg = 'Requested page not found. [404]';
							} else if (jqXHR.status == 500) {
								msg = 'Internal Server Error [500].';
							} else if (exception === 'parsererror') {
								msg = 'Requested JSON parse failed.';
							} else if (exception === 'timeout') {
								msg = 'Time out error.';
							} else if (exception === 'abort') {
								msg = 'Ajax request aborted.';
							} else {
								msg = 'Uncaught Error.\n' + jqXHR.responseText;
							}
							console.log(msg);
						},
					})
				})

			})
		</script>
