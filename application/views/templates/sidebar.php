 <?php
	$NIKnew = $this->session->userdata('MIS_LOGGED_NIK');
	$querynew = $this->db->where('NIK', $NIKnew)->get('Karyawan')->row_array();

	error_reporting(0);
	$fotonew = '';
	if (isset($querynew['Foto'])) {

		if ($fotonew !== NULL || $fotonew !== '') {
			$fotonew = 'assets/foto_karyawan/' . $querynew['Foto'];
		} else {
			$fotonew = 'assets/dist/img/default_foto.jpg';
		}
	} else {
		$fotonew = 'assets/dist/img/default_foto.jpg';
	}


	$NIK_header = $this->session->userdata('MIS_LOGGED_NIK');

	?>


 <aside class="main-sidebar">

 	<section class="sidebar">
 		<div class="user-panel" style="padding-top: 50px;">
 			<div class="pull-left image">
 				<img src="<?= site_url('assets/foto_karyawan/user.jpg') ?>" class="img-circle" alt="User Image">
 				<p></p>
 			</div>
 			<div class="pull-left info" style="height: 100px;">
 				<p><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
 				<a href="#"><i class="fa fa-circle text-success"></i></a>
 			</div>
 		</div>

 		<ul class="sidebar-menu" data-widget="tree">
 			<li class="header">MAIN NAVIGATION</li>
 			<li class="active treeview">
 			<li>
 				<a href="<?= site_url('welcome/index') ?>"><i class="fa fa-home"></i>
 					<span>Dashboard</span>
 					<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
 				</a>
 			</li>
 			</li>

 			



		<!-- === START TICKETING === -->
		<li class="header">TICKETING IFPRO</li>
		<li class="treeview <?= ($this->uri->segment(2) == 'Ticketing') ? 'active menu-open' : ''  ?>">
			<li class="treeview <?= ($this->uri->segment(2) == 'Ticketing') ? 'active menu-open' : ''  ?>">
				<a href="#"><i class="fa fa-file-word-o"></i>
					<span>Ticketing</span>
					<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>

				<ul class="treeview-menu">
					<li class="treeview <?= ($this->uri->segment(3) == 'Master') ? 'active menu-open' : ''  ?>">
						<a href="#"><i class="fa fa-folder-open"></i>
							<span>Master</span>
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>

						<ul class="treeview-menu">
							<li class="treeview">
								<li class="<?= ($this->uri->segment(4) == 'Common_Code') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Master/Common_Code') ?>"><i class="fa fa-circle-o"></i>
										Common Codes
									</a>
								</li>

								<li class="<?= ($this->uri->segment(4) == 'Venue') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Master/Venue') ?>"><i class="fa fa-circle-o"></i>
										Locations
									</a>
								</li>

								<li class="<?= ($this->uri->segment(4) == 'Counter') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Master/Counter') ?>"><i class="fa fa-circle-o"></i>
										Cashier's
									</a>
								</li>

								<li class="<?= ($this->uri->segment(4) == 'Gate') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Master/Gate') ?>"><i class="fa fa-circle-o"></i>
										Gate's
									</a>
								</li>

								<li class="<?= ($this->uri->segment(4) == 'Ticket') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Master/Ticket') ?>"><i class="fa fa-circle-o"></i>
										Tickets
									</a>
								</li>
							</li>
						</ul>
					</li>

					<li class="<?= ($this->uri->segment(4) == 'Checkin') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Trans/Checkin') ?>"><i class="fa fa-circle-o"></i>
							Cashier's
						</a>
					</li>

					<li class="<?= ($this->uri->segment(4) == 'Checkin_Scan') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan') ?>"><i class="fa fa-circle-o"></i>
							Checkin
						</a>
					</li>

					<li class="<?= ($this->uri->segment(4) == 'Checkout') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Trans/Checkout') ?>"><i class="fa fa-circle-o"></i>
							Checkout
						</a>
					</li>

					<li class="treeview <?= ($this->uri->segment(2) == 'Ticketing') && ($this->uri->segment(3) == 'Report') ? 'active menu-open' : ''  ?>">
						<a href="#"><i class="fa fa-bar-chart"></i>
							<span>Report</span>
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>

						<ul class="treeview-menu">
							<li class="treeview">
								<li class="<?= ($this->uri->segment(4) == 'Ticket_Ledger') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Report/Ticket_Ledger') ?>"><i class="fa fa-circle-o"></i>
										Ticket Ledger
									</a>
								</li>
							</li>

							<li class="treeview">
								<li class="<?= ($this->uri->segment(4) == 'Daily_Report') ? 'active' : ''  ?>">
									<a href="<?= site_url('Tenancy/Ticketing/Report/Daily_Report') ?>"><i class="fa fa-circle-o"></i>
										Daily Report
									</a>
								</li>
							</li>
						</ul>
					</li>
				</ul>
			</li>
		</li>
		<!-- === END TICKETING === -->







		<!-- === START TESTING === -->
		<li class="header">TESTING</li>
		<li class="treeview">
			<li class="treeview">
				<a href="#"><i class="fa fa-gear"></i>
					<span>Testing</span>
					<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>

				<ul class="treeview-menu">
					<li class="<?= ($this->uri->segment(4) == 'Scan_Com') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Trans/Scan_Com') ?>"><i class="fa fa-circle-o"></i>
							Scan Com
						</a>
					</li>

					<li class="<?= ($this->uri->segment(4) == 'Test_Com') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Testcom') ?>"><i class="fa fa-circle-o"></i>
							Test Com
						</a>
					</li>

					<li class="<?= ($this->uri->segment(4) == 'Test_QRCode_Encrypt_Descrypt') ? 'active' : ''  ?>">
						<a href="<?= site_url('Tenancy/Ticketing/Trans/Test_QRCode_Encrypt_Descrypt') ?>"><i class="fa fa-circle-o"></i>
							QRCode Enc & Desc
						</a>
					</li>
				</ul>
			</li>
		</li>
		<!-- === END TICKETING === -->











 
 		

 		

 		



 		

 		

 		



 	

 	

 		

 		

 		<li style="display:none;">
 			<a href="<?= site_url('LogoList') ?>">
 				<i class="fa fa-wrench"></i> <span>Setting Logo</span>
 				<span class="pull-right-container">
 					<!-- <small class="label pull-right bg-green">new</small> -->
 				</span>
 			</a>
 		</li>

 		<li><a href="<?php echo base_url('Auth/logout') ?>"><i class="fa fa-sign-out"></i><span>LOG OUT</span> </a></li>
 		</ul>
 	</section>
 	<!-- /.sidebar -->
 </aside>
