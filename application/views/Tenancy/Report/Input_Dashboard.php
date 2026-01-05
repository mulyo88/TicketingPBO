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
		<a href="<?= site_url('Tenancy/Report/Dashboard/TambahData') ?>" class="btn btn-warning"><i class="fa fa-plus"></i> Tambah Data</a>
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
									<h3 class="box-title"></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
								<table class="table table table-bordered table-striped table-responsive">
									<thead>
										<tr class="bg-aqua-gradient">
											<th></th>
											<th style="font-size: 18px;">Area</th>
											<th style="font-size: 18px;">Periode</th>
											<th colspan="2" style="text-align: center; font-size: 18px;">Target RKAP</th>
											<th colspan="2" style="text-align: center; font-size: 18px;">Achievement</th>
										</tr>
										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th style="text-align: center; ">Retail</th>
											<th style="text-align: center; ">Utility</th>
											<th style="text-align: center; ">Retail</th>
											<th style="text-align: center; ">Utility</th>
										</tr>
										<tr>
											<th></th>
											<th><input type="text" name="" id="" class="form-control"></th>
											<th><input type="text" name="" id="" class="form-control"></th>
											<th><input type="text" name="" id="" class="form-control"></th>
											<th><input type="text" name="" id="" class="form-control"></th>
											<th><input type="text" name="" id="" class="form-control"></th>
											<th><input type="text" name="" id="" class="form-control"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($Revenue as $bld): ?>
										<tr>
											<td><button class="btn btn-info btn-xs" data-toggle="modal" data-target=".modal-edit-<?= $bld->LedgerNo ?>" data-id="<?= $bld->LedgerNo ?>">Edit</button></td>
											<td><?= $bld->Area ?></td>
											<td><?= TglIndonesia($bld->Periode) ?></td>
											<td><?= number_format($bld->RKAP_Retail) ?></td>
											<td><?= number_format($bld->RKAP_Utility) ?></td>
											<td><?= number_format($bld->Ach_Retail) ?></td>
											<td><?= number_format($bld->Ach_Utility) ?></td>
										</tr>


										<div class="modal fade modal-edit-<?= $bld->LedgerNo ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
												 	<div class="modal-header bg-blue-gradient">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														<h4 class="modal-title" id="myLargeModalLabel">Edit Data Report Dashboard</h4>
													</div>
													<div class="modal-body">
														<form action="<?= site_url('Tenancy/Report/Dashboard/UpdateData') ?>" method="POST" enctype="multipart/form-data">
															<input type="hidden" name="LedgerNo" value="<?= $bld->LedgerNo ?>">
															<div class="form-group">
																<label for="inputName" class="col-sm col-form-label">Area</label>
																<input type="text" name="Area" class="form-control" value="<?= $bld->Area ?>" required>
																<label for="inputName" class="col-sm col-form-label">Periode</label>
																<input type="text" name="Periode" class="form-control" value="<?= $bld->Periode ?>" required>
																<label for="inputName" class="col-sm col-form-label">Target RKAP Retail</label>
																<input type="text" name="RKAP_Retail" class="form-control" value="<?= number_format($bld->RKAP_Retail) ?>" required>
																<label for="inputName" class="col-sm col-form-label">Target RKAP Utility</label>
																<input type="text" name="RKAP_Utility" class="form-control" value="<?= number_format($bld->RKAP_Utility) ?>" required>
																<label for="inputName" class="col-sm col-form-label">Achievement Retail</label>
																<input type="text" name="Ach_Retail" class="form-control" value="<?= number_format($bld->Ach_Retail) ?>" required>
																<label for="inputName" class="col-sm col-form-label">Achievement Utility</label>
																<input type="text" name="Ach_Utility" class="form-control" value="<?= number_format($bld->Ach_Utility) ?>" required>
															</div>
													</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															<button type="submit" class="btn btn-primary">Save changes</button>
														</div>
												</div>
												</form>
											</div>
										</div>
										<?php endforeach; ?>
								</table>
								
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

