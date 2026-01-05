<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('Welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>


	<section class="content-header">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box-body bg-info">
							<section class="content-header">
								<div class="container-fluid">
									<div class="row mb-2">

									</div>
								</div>
							</section>

							<section class="content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-3">

											<div class="card card-primary card-outline">
												<div class="card-body box-profile">
													<div class="text-center">
														<img class="profile-user-img img-fluid img-circle" src="<?php echo base_url() ?>assets/dist/img/default_foto.jpg" alt="User profile picture">
													</div>

													<h3 class="profile-username text-center"><label name="Username"><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></label></h3>


													<p class="text-muted text-center">IT MANAGER</p>


													<a href="#" class="btn btn-primary btn-block"><b>DATA PRIBADI</b></a>
												</div>

											</div>

											<form class="form-horizontal">
												<div class="form-group row">
													<label class="col-sm-5 col-form-label">NIK</label>
													<div class="col-sm-7">
														<label>: <?= $this->session->userdata('MIS_LOGGED_NIK'); ?></label>
														
													</div>
												</div>


												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Jenis Kelamin</label>
													<div class="col-sm-7">
														<label>: Jenis Kelamin</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Tempat Lahir</label>
													<div class="col-sm-7">
														<label>: Jakarta</label>
													</div>
													<label class="col-sm-5 col-form-label">Tanggal Lahir</label>
													<div class="col-sm-7">
														<label name="TglLahir">: Tanggal Lahir</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Warga Negara</label>
													<div class="col-sm-7">
														<label>: Indonesia</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Status Pernikahan</label>
													<div class="col-sm-7">
														<label name="NIK">: Menikah</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Agama</label>
													<div class="col-sm-7">
														<label name="NIK">: Islam</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Alamat KTP</label>
													<div class="col-sm-7">
														<label name="NIK">: Pondok Ungu Permai Sektor V Blok N.13 No.12A </label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Provinsi</label>
													<div class="col-sm-7">
														<label name="NIK">: Jawa Barat</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Kota</label>
													<div class="col-sm-7">
														<label name="NIK">: Bekasi</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">Alamat Email</label>
													<div class="col-sm-7">
														<label name="NIK">: mulyo.saputra@gmail.com</label>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-sm-5 col-form-label">No Telp </label>
													<div class="col-sm-7">
														<label name="NIK">: 081384677227</label>
													</div>
												</div>

											</form>
											<!-- /.card -->

											<!-- About Me Box -->
											<!-- <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header --
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
                  B.S. in Computer Science from the University of Tennessee at Knoxville
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">Malibu, California</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                <p class="text-muted">
                  <span class="tag tag-danger">UI Design</span>
                  <span class="tag tag-success">Coding</span>
                  <span class="tag tag-info">Javascript</span>
                  <span class="tag tag-warning">PHP</span>
                  <span class="tag tag-primary">Node.js</span>
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
              </div>
              <!-- /.card-body --
            </div> -->
											<!-- /.card -->
										</div>
										<!-- /.col -->
										<div class="col-md-9">
											<div class="card">
												<div class="card-header p-2">
													<ul class="nav nav-pills">
														<!-- <li class="nav-item"><a class="nav-link active" href="#DataPribadi" data-toggle="tab">Data Pribadi</a></li> -->
														<li class="nav-item"><a class="nav-link active" href="#IdentitasPersonal" data-toggle="tab">Identitas Personal</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Keluarga</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Emergency Contact</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Pendidikan</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Keterampilan</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Riwayat Pekerjaan</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Kehadiran</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Slip Gaji</a></li>
														<li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Data Request Cuti</a></li>
													</ul>
												</div><!-- /.card-header -->
												<div class="card-body">
													<div class="tab-content">
														<div class="active tab-pane" id="IdentitasPersonal">
															<div class="body">
																<section class="content-header">
																	<button type="button" data-target="#ModalAddProfile" class="btn btn-primary" data-toggle="modal">Tambah Data</button>
																	</p>
																	</p>
																</section>

																<div class="modal fade" id="ModalAddProfile">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-header">
																				<h1 class="box-title" style="font-size: larger;">Tambah Data Identitas Pribadi</h1>
																			</div>
																			<div class="modal-body">
																				<input type="text" name="NIK" class="form-control" value="<?= $this->session->userdata('MIS_LOGGED_NIK'); ?>">
																				<label>Jenis Data Identitas</label>
																				<select class="form-control" name="JenisIdentitas">
																					<option value="">--Pilih Data Identitas--</option>
																					<option value="KTP">KTP</option>
																					<option value="PASSPORT">PASSPORT</option>
																					<option value="NPWP">NPWP</option>
																					<option value="KARTU KELUARGA">KARTU KELUARGA</option>
																					<option value="SIM A">SIM A</option>
																					<option value="SIM B">SIM B</option>
																					<option value="SIM C">SIM C</option>
																				</select>
																				<label>No Identitas</label>
																				<input type="text" name="NoIdentitas" id="NoIdentitas" class="form-control">
																				<label>Berlaku Mulai</label>
																				<input type="date" name="Berlaku1" class="form-control">
																				<label>Berlaku Sampai</label>
																				<input type="date" name="Berlaku2" class="form-control">
																				<label>Diterbitkan Oleh</label>
																				<input type="text" name="Penerbit" class="form-control">
																				<br>
																				<div class="modal-header">
																					<label>Informasi Data Rekening</label>
																				</div>
																				<label>Nama Bank</label>
																				<input type="text" name="NamaBank" class="form-control">
																				<label>Atas Nama</label>
																				<input type="text" name="AtasNama" class="form-control">
																				<label>No Rekening</label>
																				<input type="text" name="NoRek" class="form-control">

																				<div class="modal-footer">
																					<button type="submit" class="btn btn-success">SIMPAN</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="box-body table-responsive no-padding">
																	<table class="table table-hover">
																		<tr style="background-color: deepskyblue;">
																			<th>Jenis Identitas</th>
																			<th>Berlaku Mulai</th>
																			<th>Berlaku Sampai Dengan</th>
																			<th>Diterbitkan Oleh</th>
																		</tr>

																		<tr>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td></td>
																		</tr>
																	</table>
																</div>
															</div>
															<div class="row mb-3">

															</div>
														</div>
														<!-- /.tab-pane -->
														<div class="tab-pane" id="#">
															<!-- The timeline -->
															<div class="timeline timeline-inverse">
																<!-- timeline time label -->
																<div class="time-label">
																	<span class="bg-danger">
																		10 Feb. 2014
																	</span>
																</div>
																<!-- /.timeline-label -->
																<!-- timeline item -->
																<div>
																	<i class="fas fa-envelope bg-primary"></i>

																	<div class="timeline-item">
																		<span class="time"><i class="far fa-clock"></i> 12:05</span>

																		<h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

																		<div class="timeline-body">
																			Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
																			weebly ning heekya handango imeem plugg dopplr jibjab, movity
																			jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
																			quora plaxo ideeli hulu weebly balihoo...
																		</div>
																		<div class="timeline-footer">
																			<a href="#" class="btn btn-primary btn-sm">Read more</a>
																			<a href="#" class="btn btn-danger btn-sm">Delete</a>
																		</div>
																	</div>
																</div>
																<!-- END timeline item -->
																<!-- timeline item -->
																<div>
																	<i class="fas fa-user bg-info"></i>

																	<div class="timeline-item">
																		<span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

																		<h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
																		</h3>
																	</div>
																</div>
																<!-- END timeline item -->
																<!-- timeline item -->
																<div>
																	<i class="fas fa-comments bg-warning"></i>

																	<div class="timeline-item">
																		<span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

																		<h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

																		<div class="timeline-body">
																			Take me to your leader!
																			Switzerland is small and neutral!
																			We are more like Germany, ambitious and misunderstood!
																		</div>
																		<div class="timeline-footer">
																			<a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
																		</div>
																	</div>
																</div>
																<!-- END timeline item -->
																<!-- timeline time label -->
																<div class="time-label">
																	<span class="bg-success">
																		3 Jan. 2014
																	</span>
																</div>
																<!-- /.timeline-label -->
																<!-- timeline item -->
																<div>
																	<i class="fas fa-camera bg-purple"></i>

																	<div class="timeline-item">
																		<span class="time"><i class="far fa-clock"></i> 2 days ago</span>

																		<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

																		<div class="timeline-body">
																			<img src="https://placehold.it/150x100" alt="...">
																			<img src="https://placehold.it/150x100" alt="...">
																			<img src="https://placehold.it/150x100" alt="...">
																			<img src="https://placehold.it/150x100" alt="...">
																		</div>
																	</div>
																</div>
																<!-- END timeline item -->
																<div>
																	<i class="far fa-clock bg-gray"></i>
																</div>
															</div>
														</div>
														<!-- /.tab-pane -->

														<div class="tab-pane" id="IdentitasPersonal">
															<form class="form-horizontal">
																<div class="form-group row">
																	<label for="inputName" class="col-sm-2 col-form-label">Name</label>
																	<div class="col-sm-10">
																		<input type="email" class="form-control" id="inputName" placeholder="Name">
																	</div>
																</div>
																<div class="form-group row">
																	<label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
																	<div class="col-sm-10">
																		<input type="email" class="form-control" id="inputEmail" placeholder="Email">
																	</div>
																</div>
																<div class="form-group row">
																	<label for="inputName2" class="col-sm-2 col-form-label">Name</label>
																	<div class="col-sm-10">
																		<input type="text" class="form-control" id="inputName2" placeholder="Name">
																	</div>
																</div>
																<div class="form-group row">
																	<label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
																	<div class="col-sm-10">
																		<textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
																	</div>
																</div>
																<div class="form-group row">
																	<label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
																	<div class="col-sm-10">
																		<input type="text" class="form-control" id="inputSkills" placeholder="Skills">
																	</div>
																</div>
																<div class="form-group row">
																	<div class="offset-sm-2 col-sm-10">
																		<div class="checkbox">
																			<label>
																				<input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
																			</label>
																		</div>
																	</div>
																</div>
																<div class="form-group row">
																	<div class="offset-sm-2 col-sm-10">
																		<button type="submit" class="btn btn-danger">Submit</button>
																	</div>
																</div>
															</form>
														</div>
														<!-- /.tab-pane -->
													</div>
													<!-- /.tab-content -->
												</div><!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
								</div><!-- /.container-fluid -->
							</section>
							<!-- /.content -->
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