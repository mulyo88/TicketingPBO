<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<!-- <p></p> -->
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label> -->
            <!-- <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('Tenancy/Report/Dashboard') ?>" class="btn btn-success"><i class="fa fa-arrow-left"></i> Kembali</a>
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
									<h3 class="box-title"></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<div class="col-md-12">
									<form action="<?= site_url('Tenancy/Report/Dashboard/saveData') ?>" method="post">
								<table class="table table-bordered table-responsive" style="width: 50%;" >
									<tr>
										<th>Pilih Periode</th>
										<td>:</td>
										<td>
											<input type="month" name="periode" id="periode" class="form-control">
										</td>
									</tr>
									<tr>
										<th>Pilih Area</th>
										<td>:</td>
										<td>
											<select name="area" id="area" class="form-control select2">
												<option value="">Pilih Area</option>
												<?php foreach($building as $bld): ?>
													<option value="<?= $bld->name ?>"><?= $bld->name ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
								</table>
								</div>
								<div class="col-md-6">
									<table class="table table-bordered table-responsive">
									<tr>
										<th>Target RKAP Retail</th>
										<td>:</td>
										<td>
											<input type="number" name="target_rkap_retail" id="target_rkap_retail" class="form-control">
										</td>
									</tr>
									<tr>
										<th>Target RKAP Utility</th>
										<td>:</td>
										<td>
											<input type="number" name="target_rkap_utility" id="target_rkap_utility" class="form-control">
										</td>
									</tr>
									<tr>
										<th>Jumlah RKAP  </th>
										<td>:</td>
										<td>
											<input type="number" name="jumlah_rkap" id="jumlah_rkap" class="form-control" readonly>
										</td>
									</tr>
									<tr>
										<th>% Achievement Retail</th>
										<td>:</td>
										<td><input type="text" name="persentase_achievement_retail" id="persentase_achievement_retail" class="form-control" readonly></td>
									</tr>
									<tr>
										<th>% Target RKAP VS Achievement</th>
										<td>:</td>
										<td><input type="text" name="persentase_target_vs_achievement" id="persentase_target_vs_achievement" class="form-control" readonly></td>
									</tr>
								</table>
								</div>


								<div class="col-md-6">
									<table class="table table-bordered table-responsive">
									<tr>
										<th>Achievement Retail</th>
										<td>:</td>
										<td>
											<input type="number" name="achievement_retail" id="achievement_retail" class="form-control">
										</td>
									</tr>
									<tr>
										<th>Achievement Utility</th>
										<td>:</td>
										<td>
											<input type="number" name="achievement_utility" id="achievement_utility" class="form-control">
										</td>
									</tr>
									<tr>
										<th>Jumlah Achievement</th>
										<td>:</td>
										<td>
											<input type="number" name="jumlah_achievement" id="jumlah_achievement" class="form-control" readonly>
										</td>
									</tr>
									<tr>
										<th>% Achievement Utility</th>
										<td>:</td>
										<td><input type="text" name="persentase_achievement_utility" id="persentase_achievement_utility" class="form-control" readonly></td>
									</tr>
									<tr>
										<th></th>
										<td></td>
										<td>
											<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
										</td>
									</tr>
								</table>

								</form>
								
							</div>





								</div>
								

						</div>
					</div>
				</div>
			</div>
	</section>



	<script>
		$(document).ready(function() {
			$('.select2').select2();
		});

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

