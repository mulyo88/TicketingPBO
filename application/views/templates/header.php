<?php
$NIK_yang_login = $this->session->userdata('MIS_LOGGED_NIK');

// JIKA BUKAN DI HRIS MAKA BACA NYA NIK ATASAN UNTUK MENGECEK APAKAH ADA PERMINTAAN





// array_push($array_notif,$array_memo);

// print_rr($array_notif);
// exit;

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="icon" type="image" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">

	<title><?= $this->config->item('Nama_Company') ?> | <?php echo $judul	?></title>
	<style type="text/css">
		.text_button_mati {
			pointer-events: none;
			opacity: 0.4;
		}
	</style>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<script src="<?php echo base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

	<script src="<?= base_url() ?>assets/chart.min.js"></script>
	<script src="<?= base_url() ?>assets/jquery-3.6.0.js"></script>
	<script src="<?= base_url() ?>assets/jquery-ui.js"></script>

	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/morris.js/morris.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/bower_components/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/bower_components/datatables/extensions/Responsive/css/dataTables.responsive.css">



	<link href="<?= base_url() ?>assets/toast/css/jquery.toast.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>assets/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<style type="text/css">
		.modal-body {
			max-height: calc(100vh - 210px);
			overflow-y: auto;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url('assets/toastr.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('fixed_header/fixed_columns.css') ?>">
	<!-- toastify -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />


</head>

<body class="hold-transition sidebar-mini <?= (!isset($bodyclass)) ? 'skin-red' : $bodyclass ?> ">
	<div class="wrapper">

		<header class="main-header ">
			<a href="#" class="logo" style="height: 85px;">
				<span class="logo-mini"><b>
						<!-- <?= $this->config->item('Company') ?> -->
						<img src="<?= site_url('assets\dist\img\LOGO_IFPROIconOnly.png') ?>" class="img-circle" alt="User Image" style="width:30px;">
					</b></span>
				<span class="logo-xs"><b>
					<!--?xml version="1.0" encoding="UTF-8"?-->
					<!--svg version="1.1" viewBox="0 0 2048 987" width="251" height="85" xmlns="http://www.w3.org/2000/svg"-->
					<svg version="1.1" viewBox="0 0 2050 900" width="250" height="70" xmlns="http://www.w3.org/2000/svg">
					<path transform="translate(268,95)" d="m0 0 5 2 7 10 6 15 6 22 5 30 2 22v35l-3 23-5 24-8 27-10 25-12 25-12 22-13 21-21 32-11 16-19 29-14 22-13 21-13 23-10 18-14 29-11 28-6 19-6 24-3 20-1 16v14l2 24 5 24 7 22 10 24 10 19 11 16 13 18 3 9-1 4-6-7v-2l-4-2-13-12-10-9-10-11-11-14-11-16-11-19-9-19-8-22-6-22-4-27-1-10-1-26 2-36 4-28 7-30 8-24 11-26 11-23 12-21 15-25 9-14 11-17 22-33 17-25 26-39 7-11 17-28 12-22 11-22 10-25 7-23 4-23 1-9v-22l-4-23z" fill="#029DDE"/>
					<path transform="translate(184,116)" d="m0 0 3 1 4 10 5 28 2 25-1 23-4 24-7 24-9 21-10 17-12 17-13 16-7 8-12 14-9 10-11 14-14 18-12 17-9 14-11 18-12 26-7 19-6 23-2 16v27l2 16 4 13v5l-3 2-7-8-10-25-8-28-5-26-2-16v-19l4-24 6-22 6-17 12-25 11-18 13-18 11-13 8-11 14-17 11-14 13-16 10-13 11-14 15-20 10-15 9-16 6-13 6-19 3-19 1-17z" fill="#F89227"/>
					<path transform="translate(290,383)" d="m0 0 4 1 5 10 5 17 3 17 1 10v44l-3 19-6 22-7 19-10 20-8 12-9 12-9 11-11 13-13 17-10 14-8 12-10 16-12 20-13 24-8 19-7 21-4 19-2 21v23l3 18 3 12v8l-7-6-10-19-8-20-8-28-4-23v-32l3-20 6-24 8-22 8-17 16-27 8-12 11-15 14-19 16-21 14-19 13-18 16-24 9-15 12-25 5-16 4-20v-14l-1-4v-10z" fill="#08A6CB"/>
					<path transform="translate(163,632)" d="m0 0 1 2-6 11 2 4-2 4-4 3-3 6-4 4 4-1h21l9-4 4-5 13-7 13-4 4 2-2 7-21 35-13 24-8 19-7 21-4 19-2 21v23l3 18 3 12v8l-7-6-10-19-8-20-8-28-4-23v-32l3-20 6-24 8-22 8-17 5-9 5-1z" fill="#07A4B4"/>
					<path transform="translate(905,452)" d="m0 0 11 1 8 5 8 11 13 22 9 14 5 7 2 1-1-11v-49h17l3 1v99h-15l-6-4-14-22-14-23-9-12h-1l2 17v43l-1 1h-18l-1-1-1-15 1-83z" fill="#213594"/>
					<path transform="translate(531,453)" d="m0 0h12l6 3 7 8 12 20 9 14 6 9 5 6v-60h18l2 2-1 98-3 1h-9l-5-2-8-10-13-20-10-17-10-13 1 16v45l-4 1-15-1-1-1v-98z" fill="#213594"/>
					<path transform="translate(1737,453)" d="m0 0h32l19 1 8 3 9 8 4 8 1 5v13l-3 7-5 6-8 5h-2l4 10 7 11 10 18 1 5-2 1h-13l-7-2-6-9-12-24-4-4-6-2h-7v39l-1 1h-18l-1-1zm25 16-5 3-1 4v16l4 4 6 1h11l10-3 3-4 1-9-4-8-5-3-5-1z" fill="#213594"/>
					<path transform="translate(1859,453)" d="m0 0h43l12 2 9 6 6 8 3 7v16l-4 7-5 6-8 5 1 5 9 15 12 22-2 2h-14l-6-2-5-6-14-27-5-5-11-2v36l-1 4-2 1h-17l-1-2-1-94zm25 16-5 3-1 2v18l4 4 5 1h13l9-3 4-5v-9l-4-6-5-4-4-1z" fill="#213594"/>
					<path transform="translate(661,453)" d="m0 0h34l18 2 9 5 5 6 4 9 2 10 1 15v11l-2 18-4 9-5 7-8 5-7 2-9 1-35 1-4-2v-98zm24 16-5 1-1 8 1 57 7 2 16-2 6-4 3-5 1-10v-31l-3-9-5-5-10-2z" fill="#213594"/>
					<path transform="translate(807,452)" d="m0 0h17l11 3 9 7 6 9 3 8 1 5 1 15v12l-2 17-4 10-8 9-8 5-14 3h-9l-9-2-9-5-8-9-5-11-2-11v-29l3-12 5-10 9-9 9-4zm8 16-9 3-6 7-2 5-1 10v17l2 15 3 6 6 5 2 1h12l7-4 4-6 1-5v-38l-4-10-5-4-5-2z" fill="#213594"/>
					<path transform="translate(1031,453)" d="m0 0h68l1 1v13l-1 2-3 1h-45l1 19 1 5h38l1 1 1 14-2 2h-40l1 25h47l1 16-1 1-65 1-3-3z" fill="#213694"/>
					<path transform="translate(1624,453)" d="m0 0h64l2 2v14l-47 1v24l40 1 1 1 1 13-2 2h-41l1 24 46 1 1 1 1 15-2 1h-66v-99z" fill="#213594"/>
					<path transform="translate(1164,452)" d="m0 0h22l16 4 4 4v8l-5 5h-8l-13-4h-10l-8 4-2 3v9l5 5 18 4 13 4 7 4 5 6 4 11v8l-4 11-9 10-10 5-11 2h-13l-12-2-10-4-5-4v-6l6-9 28 7h9l5-3h2l3-9-1-7-5-3-12-3-16-3-8-5-7-8-2-6v-13l3-8 4-6 8-7z" fill="#213594"/>
					<path transform="translate(1361,451)" d="m0 0h6l6 7 10 25 15 41 9 26v4h-14l-5-2-6-14-4-2h-29l-3 2-6 12-3 3h-17l1-7 11-30 13-37 8-18 6-9zm1 32-9 27-1 9 17 1 7-1-2-10-6-20-4-6z" fill="#213594"/>
					<path transform="translate(1511,453)" d="m0 0h23l43 1 2 1v10l-1 4-48 1v26h41l1 2v8l-2 6h-40v41l-6 1h-11l-2-1-1-23v-76z" fill="#213695"/>
					<path transform="translate(86,721)" d="m0 0 8 1 1 2 3 20 6 23 5 15 10 23 9 17 11 16 13 18 3 9-1 4-6-7v-2l-4-2-13-12-10-9-10-11-11-14-11-16-11-19-9-19-8-22v-6l2-4 2-1 13-1z" fill="#213796"/>
					<path transform="translate(1061,622)" d="m0 0h39l9 3 6 4 6 9 3 6 1 9-3 9-4 6-8 7-7 3 9 19 12 21 2 5-1 1h-9l-7-6-18-36-1-1-24-2v44l-2 1h-8l-2-1-1-7v-91l1-2zm17 10-11 1-1 1v34l4 1h27l7-2 6-5 2-5v-10l-3-7-5-5-5-2-7-1z" fill="#F87C1E"/>
					<path transform="translate(586,622)" d="m0 0h38l10 3 8 6 6 10 2 6-1 12-6 9-9 7-6 3 9 19 14 24-1 3-5 1-7-3-7-10-15-30-9-2-16-1v43l-3 2h-7l-2-2-1-14v-80l3-5zm13 10-7 1-1 1-1 10v18l1 6 4 1h24l9-2 8-6 2-9-2-9-3-5-10-5-6-1z" fill="#F87C1E"/>
					<path transform="translate(726,621)" d="m0 0h15l11 4 7 6 7 11 3 12v37l-2 10-4 8-9 10-10 5-4 1h-17l-10-4-5-4-8-11-4-12-2-19 1-16 4-16 6-10 10-8zm1 10-8 4-7 8-3 8-1 12v28l3 10 4 6 4 4 11 4h7l9-4 5-5 4-7 1-5 1-38-2-10-7-9-7-5-3-1z" fill="#F87B1E"/>
					<path transform="translate(2040,451)" d="m0 0h7l1 1v7l-8 11-17 33-4 11-1 8v27l-3 4-8 1-7-2-1-1-1-36-5-14-17-34-8-13v-2h12l10 3 6 9 11 27h3l5-11 8-18 4-6 8-4z" fill="#213594"/>
					<path transform="translate(942,622)" d="m0 0h61l1 4-2 7h-50v34h41l3 1 1 2v6l-1 2h-45l1 34 50 1 2 5v5l-1 1h-60l-3-3-1-22v-74z" fill="#F87C1E"/>
					<path transform="translate(469,622)" d="m0 0h34l12 3 6 4 6 7 4 9v15l-4 8-4 5-9 6-11 3h-31v41l-3 1-8-1-1-9-1-63v-24l2-4zm5 10-2 2v34l4 3 4 1h21l9-3 6-5 4-7v-9l-3-6-8-7-5-2-8-1z" fill="#F87B1E"/>
					<path transform="translate(830,622)" d="m0 0h34l12 3 8 6 6 9 2 5v12l-3 8-6 8-9 6-11 3h-30l1 19v21l-2 2h-7l-3-2-1-3v-94l3-2zm4 10-1 4v29l2 6 4 1h24l9-4 6-5 3-6v-7l-3-8-5-5-9-4-9-1z" fill="#F87B1E"/>
					<path transform="translate(459,453)" d="m0 0h19l1 1v62l-1 37-1 1h-16l-3-2v-98z" fill="#213594"/>
					<path transform="translate(1270,452)" d="m0 0h7l2 1v26l-1 73-4 2h-14l-2-2v-99z" fill="#213594"/>
					<path transform="translate(1272,620)" d="m0 0 6 1 7 8 8 15 8 19 3 4v4h2l1-5 7-14 10-21 7-9 2-2h6l1 4-23 46-4 10-1 16-1 25-2 3h-7l-3-2-1-3v-21l-1-15-3-10-16-33-8-14-1-5z" fill="#F87C1E"/>
					<path transform="translate(1205,622)" d="m0 0h28l2 3-1 6-2 2h-26l-1 90h-12l-1-21v-56l-1-12-24-1-5-2-1-5 3-3z" fill="#F87C1F"/>
					<path transform="translate(290,383)" d="m0 0 4 1 4 8v4l-8 1-1-3v-10z" fill="#039EDB"/>
					<path transform="translate(163,632)" d="m0 0 1 2-7 13-5 2h-2l2-6 5-9 5-1z" fill="#07A4B9"/>
					</svg>
						<!-- <?= $this->config->item('NamaLogoCompany_besar') ?> -->
						<!-- <img src="<?= site_url('assets\dist\img\LOGO-IFPRO-1.png') ?>" class="img-circle" alt="User Image" style="width:100%;"> -->
					</b></span>
			</a>
			<nav class="navbar navbar-static-top" style="background-color:deepskyblue; ">
				<marquee direction="Left" style="font-size: 20px;color:yellow" class="text-primary">
					<strong><?= strtoupper($this->config->item('Nama_Sign')) ?></strong>
				</marquee>
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>


				<div class="navbar-custom-menu" style="padding-top: 2;">

					<ul class="nav navbar-nav" style="background-color: grey;">
						<!-- Tasks: style can be found in dropdown.less -->
						<li class="dropdown tasks-menu" style="background-color: yellowgreen;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="label label-danger" id="totalNotifHeader">0</span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<ul class="menu" id="menu-notif">
									</ul>
								</li>
								<li class="footer">
									<a href="#">View all tasks</a>
								</li>
							</ul>
						</li>
						<li style="background-color:blueviolet;"><a href="<?php echo base_url('Auth/logout') ?>"><i class="fa fa-sign-out"></i> LOG OUT</a></li>
						<!-- <li style="background-color: #32a852;">
							<a href="#scanQr" style="color: white;" data-toggle="modal">
								<i class="fa fa-qrcode" style="color: white;"></i> SCAN QR
							</a>
						</li> -->
						<li class="nav-item d-none d-sm-inline-block" style="background-color: cornflowerblue;">
							<a href="#changePassword" data-toggle="modal" class="nav-link" style="display:<?= ($this->session->userdata('PecahToken')->ChangePasswd == 0) ? 'none' : '' ?>;">GANTI PASSWORD</a>
							<!-- User Account: style can be found in dropdown.less -->
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<!-- <img src="<?= file_get_contents(alamat_server_dan_local_dan_folder('server') . 'Akses_get_file/dapat_foto/' . $this->session->userdata('MIS_LOGGED_NIK') . '/1') ?>" class="user-image" alt="User Image"> -->
								<img src="<?= site_url() ?>assets/foto_karyawan/user.jpg" class="user-image" alt="User Image">
								<span class="hidden-xs"><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">

									<img src="<?= file_get_contents(alamat_server_dan_local_dan_folder('server') . 'Akses_get_file/dapat_foto/' . $this->session->userdata('MIS_LOGGED_NIK') . '/1') ?>" class="user-image" alt="User Image">

									<!-- <img src="<?= site_url() ?>assets/foto_karyawan/user.jpg" class="user-image" alt="User Image"> -->
									<p><?= $this->session->userdata('MIS_LOGGED_NAME'); ?>
									<p>
									<h5 style="color:white;"><?= $this->session->userdata('MIS_LOGGED_NIK'); ?></h5>

								</li>
								<!-- Menu Body -->

								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= site_url('Kepegawaian/view_profile') ?>" class="btn btn-default btn-flat">Profile</a>
									</div>
									<div class="pull-right">
										<a href="<?= base_url('Auth/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
									</div>
								</li>
							</ul>
						</li>
						<!-- Control Sidebar Toggle Button -->
						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<div class="modal fade" id="changePassword">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Ubah Password Anda</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="<?= site_url('Auth/updatePassword') ?>" method="POST">
							<div class="form-group">
								<label>Password*</label>
								<input type="password" class="form-control" name="Password" placeholder="Masukan password baru">
							</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Simpan!</button>
					</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

		<!-- Modal Scan QR Code -->
		<div class="modal fade" id="scanQr">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Scan Here!</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
					</div>
					<div class="modal-body">
						<div id="my-qr-reader">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" style="width:100%" data-dismiss="modal">Cancel</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		<!-- Modal Result Scan QR Code -->
		<div class="modal fade" id="scanQrResult">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Result</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
					</div>
					<div class="modal-body">
						<table class="table" id="txtResult">
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" style="width:100%" data-dismiss="modal">Close</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

		<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
		<script>
			function domReady(fn) {
				if (
					document.readyState === "complete" ||
					document.readyState === "interactive"
				) {
					setTimeout(fn, 1000);
				} else {
					document.addEventListener("DOMContentLoaded", fn);
				}
			}

			domReady(function () {
				function onScanSuccess(decodeText, decodeResult) {
					showResultScan(decodeText)
					$("#scanQr").modal('hide')
				}
				let htmlscanner = new Html5QrcodeScanner(
					"my-qr-reader",
					{ fps: 10, qrbos: 250 }
				);
				htmlscanner.render(onScanSuccess);
			});

			function showResultScan(data){
				var modal_container = $("#scanQrResult")
				var table = modal_container.find("#txtResult")
				var explode = data.split("➡️")
				explode.map((val) => {
					table.append(`<tr><td>${val}</td></tr>`)
					// table.append(`<p>Hai</p>`)
				})
				modal_container.modal("show")

			}
		</script>