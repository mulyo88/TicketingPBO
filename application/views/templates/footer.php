<style>
	.notify {
		font-size: 14px;
		border-radius: 10px;
		animation: gerak 4s;
	}

	/* notifikasi */
	.notif-forum {
		position: fixed;
		bottom: 0;
		right: 0;
		margin: 0 40px 20px 0;
		width: 400px;
		height: 100px;
		padding: 5px;
		background-color: #fff;
		border-radius: 10px;
		border: 2px solid #12b886;
		display: none;
		animation: notif 0.5s ease-in-out;
	}

	.notif-forum .cont-logo {
		width: 20%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.notif-forum .logo {
		width: 50px;
		height: 50px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #12b886;
		border-radius: 50%;
		color: #ECDB54;
		flex-wrap: wrap;
	}

	.notif-forum .message {
		width: 80%;
		text-wrap: wrap;
		overflow: hidden;
	}

	.notif-forum .message .from {
		font-size: 12px;
	}

	.notif-forum .title {
		font-size: 14;
		font-weight: bold;
		width: 90%;
		/* Tentukan lebar elemen */
		white-space: nowrap;
		/* Mencegah teks untuk membungkus ke baris berikutnya */
		overflow: hidden;
		/* Sembunyikan teks yang melampaui lebar elemen */
		text-overflow: ellipsis;
		/* Gantikan teks yang terpotong dengan ellipsis (...) */
	}

	.notif-forum .from {
		font-size: 12px;
		font-weight: 600;
	}

	.notif-forum .value {
		font-size: 11px;
		font-weight: 600;
		width: 90%;
		/* Tentukan lebar elemen */
		white-space: nowrap;
		/* Mencegah teks untuk membungkus ke baris berikutnya */
		overflow: hidden;
		/* Sembunyikan teks yang melampaui lebar elemen */
		text-overflow: ellipsis;
		/* Gantikan teks yang terpotong dengan ellipsis (...) */
	}

	@keyframes gerak {
		0% {
			transform: translateX(-20px);
		}

		25% {
			transform: translateX(20px);
		}

		50% {
			transform: translateX(-20px);
		}

		75% {
			transform: translateX(20px);
		}

		100% {
			transform: translateX(-20px);
		}
	}

	@keyframes notif {
		0% {
			transform: translateX(500px);
		}

		100% {
			transform: translateX(0px);
		}
	}
</style>
<footer class="main-footer" data-useridlogin="<?= $this->session->userdata('MIS_LOGGED_ID') ?>">
	<div class="notif-forum">
		<div class="cont-logo">
			<div class="logo">
				<i class="fa fa-magic" aria-hidden="true"></i>
			</div>
		</div>
		<div class="message">
			<div class="title"></div>
			<p> <span class="">From :</span>
				<span class="from"></span> <br>
			<div class="value"></div>
			</p>
		</div>
	</div>
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2025 <a href="#">MULYO SAPUTRA</a>.</strong> All rights
	reserved.
</footer>


<aside class="control-sidebar control-sidebar-dark">

	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane" id="control-sidebar-home-tab">
			<h3 class="control-sidebar-heading">Recent Activity</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-birthday-cake bg-red"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

							<p>Will be 23 on April 24th</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-user bg-yellow"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

							<p>New phone +1(800)555-1234</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

							<p>nora@example.com</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-file-code-o bg-green"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

							<p>Execution time 5 seconds</p>
						</div>
					</a>
				</li>
			</ul>


			<h3 class="control-sidebar-heading">Tasks Progress</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Custom Template Design
							<span class="label label-danger pull-right">70%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Update Resume
							<span class="label label-success pull-right">95%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-success" style="width: 95%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Laravel Integration
							<span class="label label-warning pull-right">50%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Back End Framework
							<span class="label label-primary pull-right">68%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
						</div>
					</a>
				</li>
			</ul>


		</div>

		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>

		<div class="tab-pane" id="control-sidebar-settings-tab">
			<form method="post">
				<h3 class="control-sidebar-heading">General Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Report panel usage
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Some information about this general settings option
					</p>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Allow mail redirect
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Other sets of options are available
					</p>
				</div>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Expose author name in posts
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Allow the user to show his name in blog posts
					</p>
				</div>


				<h3 class="control-sidebar-heading">Chat Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Show me as online
						<input type="checkbox" class="pull-right" checked>
					</label>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Turn off notifications
						<input type="checkbox" class="pull-right">
					</label>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Delete chat history
						<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
					</label>
				</div>

			</form>
		</div>

	</div>
</aside>

<div class="control-sidebar-bg"></div>
</div>


<script src="<?php echo base_url() ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>

<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script> -->

<script src="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/fastclick/lib/fastclick.js"></script>

<script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

<!-- <script src="<?= base_url(); ?>assets/bower_components/select2/dist/js/select2.min.js"></script> -->
<script src="<?= base_url('assets/select2_baru.js') ?>"></script>


<script src="<?= base_url(); ?>assets/bower_components/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url(); ?>assets/bower_components/datatables/dataTables.bootstrap.js"></script>
<script src="<?= base_url(); ?>assets/bower_components/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?= base_url() ?>assets/toast/js/jquery.toast.js"></script>
<script src="<?= base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url() ?>assets/jquery.mask.min.js"></script>
<script src="<?= base_url() ?>assets/toastr.js"></script>
<script type="<?= base_url('assets/mycanvas.js') ?>"></script>
<!-- toastify -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- socket.io -->
<script src="https://cdn.socket.io/4.7.4/socket.io.min.js" integrity="sha384-Gr6Lu2Ajx28mzwyVR8CFkULdCU7kMlZ9UthllibdOSo6qAiN+yXNHqtgdTvFXMT4" crossorigin="anonymous"></script>


<!-- Tambahan 30 Sept 2024 Datatable Baru -->
<!-- <script src="<?= base_url('assets/datatables.js') ?>"></script>
<script src="<?= base_url('assets/datatables.min.js') ?>"></script>

<script src="<?= base_url('assets/css/datatables.css') ?>"></script>
<script src="<?= base_url('assets/css/datatables.min.css') ?>"></script>
<script src="<?= base_url('assets/css/style.css') ?>"></script> -->

<!-- Akhir Tambahan 30 Sept 2024  -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<?php (isset($js_saya)) ? $this->load->view($js_saya) : '' ?>
<script>
	function hanyaAngka(event) {
		var angka = (event.which) ? event.which : event.keyCode
		if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
			return false;
		return true;
	}

	function ppn_baru_decimal_js() {
		var PPN_baru = parseFloat(1.11);
		return PPN_baru;
	}

	function ppn_baru_angka_js() {
		var PPN_baru = parseFloat(0.11);
		return PPN_baru;
	}

	function ubah_uang_js(number, decimals, dec_point, thousands_sep) {
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}
	var UbahKeUang = function(num) {
		var str = num.toString().replace("", ""),
			parts = false,
			output = [],
			i = 1,
			formatted = null;
		if (str.indexOf(".") > 0) {
			parts = str.split(".");
			str = parts[0];
		}
		str = str.split("").reverse();
		for (var j = 0, len = str.length; j < len; j++) {
			if (str[j] != ",") {
				output.push(str[j]);
				if (i % 3 == 0 && j < (len - 1)) {
					output.push(",");
				}
				i++;
			}
		}
		formatted = output.reverse().join("");
		return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
	};
</script>
<!-- notifikasi -->
<script>
	$(function() {
		var userid = $('.main-footer').data('useridlogin');
		var totalNotifHeeader = $('#totalNotifHeader')
		var total = 0;
		// cekSudahVotePelamar harus last function 
		cekSudahVotePelamar(userid);


		// cekSudahVotePelamar harus last function 
		function cekSudahVotePelamar(userid) {
			$.ajax({
				url: "<?= site_url('FrmPelamarPosting/cekHasVote') ?>",
				method: "POST",
				data: {
					userid
				},
				success: function(result) {
					if (result < 1) {
						total = total + 1;
						$('#menu-notif').append(`
							<li>
								<a href="<?= site_url('FrmPelamarPosting') ?>">
									<h3 class="text-blue">
										• Menu Pilih Pelamar
									</h3>
								</a>
							</li>
						`);
						var url = "<?= base_url('assets/dist/img/MDH.png') ?>"
						Toastify({
							className: "notify",
							avatar: url,
							text: "Anda belum melakukan Voting pada menu Pilih Pelamar",
							duration: 5000,
							destination: "<?= site_url('FrmPelamarPosting') ?>",
							newWindow: false,
							close: true,
							gravity: "bottom", // `top` or `bottom`
							position: "right", // `left`, `center` or `right`
							stopOnFocus: true, // Prevents dismissing of toast on hover
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)",
							},
							onClick: function() {} // Callback after click
						}).showToast();
					}
					totalNotifHeeader.html(total)
				}
			})
		}



	})
</script>

<!-- socket.io -->
<script>
	// $(function() {
	// 	const iduserlogin = $('.frm-container').data('iduserlogin');
	// 	const socket = io('http://localhost:5000/');
	// 	var idthread = '';
	// 	var idcomment = '';
	// 	var content = $('.frm-container .content')
	// 	socket.on('connection', function() {
	// 		console.log('hello');
	// 	});

	// 	socket.on('cbAllComment', function(data) {
	// 		if (data.length > 0) {
	// 			if (data[0].IDThread == idthread) {
	// 				content.empty()
	// 				data.map(val => {
	// 					const hash = hashCode(val.IdUser)
	// 					const color = hashToHSL(hash)
	// 					if (val.ReplyOn == '') {
	// 						content.append(templateComment(val, color))
	// 					} else {
	// 						const hash2 = hashCode(val.UserName2)
	// 						const color2 = hashToHSL(hash2)
	// 						content.append(templateCommentWithReply(val, color, color2))
	// 					}
	// 				})
	// 				scrollToBottom()
	// 			}
	// 		} else {
	// 			content.empty()
	// 		}
	// 	})

	// 	socket.on('cbNotification', function(data) {
	// 		$('.notif-forum .message .title').html(data.title)
	// 		$('.notif-forum .message .from').html(data.username)
	// 		$('.notif-forum .message .value').html(data.value)
	// 		$('.notif-forum').css('display', 'flex')
	// 		setTimeout(() => {
	// 			$('.notif-forum').css('display', 'none')
	// 		}, 8000);
	// 	})

	// 	$('.item-diskusi').click(function(e) {
	// 		e.preventDefault()
	// 		var titleForum = $(this).find('.isi').text()
	// 		$('.frm-container .header .title p').text(titleForum)
	// 		idthread = $(this).data('idthread')
	// 		getAllComment(idthread)
	// 		$('.frm-container .footer .input').removeAttr('disabled')
	// 	})

	// 	$('.frm-container .footer .input').on('input', function(e) {
	// 		e.preventDefault()
	// 		if ($(this).val().length > 0) {
	// 			$('.frm-container .footer .btn-send').removeAttr('disabled')
	// 		} else {
	// 			$('.frm-container .footer .btn-send').attr('disabled', true)
	// 		}
	// 	})

	// 	$('.frm-container .footer .input').keydown(function(e) {
	// 		if (e.keyCode == 13) {
	// 			sendComment();
	// 		}
	// 	})

	// 	$('.frm-container .footer .input-container .btn-send').click(function(e) {
	// 		e.preventDefault();
	// 		sendComment();
	// 	})

	// 	$('.frm-container .content').click(function(e) {
	// 		if (!$(event.target).closest('.pop-up-chat').length && !$(event.target).is('.pop-up-chat')) {
	// 			$('.pop-up-chat').remove();
	// 		}
	// 	})

	// 	$(document).on('contextmenu', '.frm-container .content .comment .text-container .txtmessage', function(e) {
	// 		e.preventDefault()
	// 		$('.frm-container .content .comment').find('.pop-up-chat').remove()
	// 		$(this).append(templatePopUpChat())
	// 	})

	// 	$(document).on('click', '.frm-container .content .comment .text-container .txtmessage .pop-up-chat .replay', function(e) {
	// 		e.preventDefault()
	// 		var value = $(this).closest('.txtmessage').find('.value-comment').html()
	// 		idcomment = $(this).closest('.txtmessage').data('idcomment')
	// 		$('.frm-container .content .comment').find('.pop-up-chat').remove()
	// 		$('.frm-container .footer').find('.reply-message').remove()
	// 		$('.frm-container .footer .input-container').before(templateReplyChat(value))
	// 		$('.frm-container .footer .input').focus()
	// 	})

	// 	$(document).on('click', '.frm-container .footer .reply-message .close', function(e) {
	// 		$(this).closest('.reply-message').remove()
	// 		idcomment = '';
	// 	})

	// 	function getAllComment(idthread) {
	// 		socket.emit('joinRoom', idthread)
	// 	}

	// 	function sendComment() {
	// 		var value = $('.frm-container .footer .input-container .input-message').val()
	// 		var replyOn = idcomment != '' ? idcomment : null;
	// 		socket.emit('sendComment', {
	// 			iduserlogin,
	// 			idthread,
	// 			value,
	// 			replyOn
	// 		})
	// 		$('.frm-container .footer .input-container .input-message').val('')
	// 		$('.frm-container .footer .btn-send').attr('disabled', true)
	// 		$('.frm-container .footer .reply-message').remove()
	// 		idcomment = '';
	// 	}

	// 	function templateComment(data, color) {
	// 		return (`
	// 			<div class="comment">
	// 				<div class="profile">
	// 					<div class="img">
	// 						<img src="<?= site_url('images/icon_people.png') ?>" alt="">
	// 					</div>
	// 				</div>
	// 				<div class="text-container">
	// 					<div class="name-time">
	// 						<p class="name" style="color: ${color};">${data.UserName}</p>
	// 						<p class="time">${formatDateAndTime(data.DateCreated)}</p>
	// 					</div>
	// 					<div class="txtmessage" data-idcomment="${data.IDComment}">
	// 						<p class="value-comment">${data.ValueComment}</p>
	// 					</div>
	// 				</div>
	// 			</div>
	// 		`)
	// 	}

	// 	function templateCommentWithReply(data, color, color2) {
	// 		return (`<div class="comment">
	// 					<div class="profile">
	// 						<div class="img">
	// 							<img src="<?= site_url('images/icon_people.png') ?>" alt="">
	// 						</div>
	// 					</div>
	// 					<div class="text-container">
	// 						<div class="name-time">
	// 							<p class="name" style="color: ${color};">${data.UserName}</p>
	// 							<p class="time">${formatDateAndTime(data.DateCreated)}</p>
	// 						</div>
	// 						<div class="txtmessage" data-idcomment="${data.IDComment}">
	// 							<div class="replyFrom">
	// 								<p class="name" style="color: ${color2}">${data.UserName2}</p>
	// 								<p class="value">${data.ValueComment2}</p>
	// 							</div>
	// 							<p class="value-comment">${data.ValueComment}</p>
	// 						</div>
	// 					</div>
	// 				</div>`);
	// 	}

	// 	function templatePopUpChat(id) {
	// 		return (`<div class="pop-up-chat">
	// 					<div class="list replay">
	// 						<div><i class="fa fa-reply-all"></i></div>
	// 						<div>Reply</div>
	// 					</div>
	// 				</div>`);
	// 	}

	// 	function templateReplyChat(value, idComment) {
	// 		return (`<div class="reply-message" data-id="${idComment}">
	// 					<div class="isi">
	// 						<p>${value}</p>
	// 					</div>
	// 					<div class="close">
	// 						<i class="fa fa-times-circle"></i>
	// 					</div>
	// 				</div>
	// 			`);
	// 	}

	// 	function scrollToBottom() {
	// 		$('.frm-container .content').animate({
	// 			scrollTop: $(".frm-container .content").offset().top + $(".frm-container .content")[0].scrollHeight
	// 		}, 2000);
	// 	}

	// 	function hashCode(str) {
	// 		let hash = 0;
	// 		for (let i = 0; i < str.length; i++) {
	// 			const char = str.charCodeAt(i);
	// 			hash = ((hash << 5) - hash) + char;
	// 			hash |= 0;
	// 		}
	// 		return hash;
	// 	}

	// 	function hashToHSL(hash) {
	// 		const hue = hash % 360; // Value between 0 and 359
	// 		const saturation = 50 + (hash % 50); // Value between 50 and 99
	// 		const lightness = 50 + (hash % 10); // Value between 50 and 59
	// 		return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
	// 	}

	// 	function formatDateAndTime(dateTime) {
	// 		return dateTime.slice(0, -3)
	// 	}

	// })
</script>