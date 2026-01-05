<?php

use phpDocumentor\Reflection\DocBlock\Tags\Link;

error_reporting(0);
// error_reporting(E_ALL);

?>
<link rel="stylesheet" href="<?= site_url('css/forum-diskus.css') ?>">
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small>Aplikasi Monitoring Procurement & Evaluasi ( AMPEL )</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>


	<!-- Main content -->
	<section class="content">

		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<!-- <div class="row">
			<div class="col-sm-12">
				<table border="0" width="100%" style="margin-bottom: 10px;" class="bg-silver">
					<tr>
						<th>
							<marquee direction="Left" style="font-size: 26px;color:" class="text-primary"><?= strtoupper($this->config->item('Nama_Sign')) ?></marquee>
						</th>
					</tr>
				</table>

			</div>
		</div> -->

		<!-- Small boxes (Stat box) -->
		 <!-- <div class="box-body"> -->
		
		<div class="col-lg-9">
			<div class="col-lg-3">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h4><strong><?= number_format($TOTAL->TOTAL) ?></strong></h4>

						<p>TOTAL NILAI DRP </p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>

				<div class="small-box bg-blue">
					<div class="inner">
						<h4><?= number_format($TOTAL->TOTAL - $Penyusutan) ?></sup></h4>

						<p>TOTAL NILAI SP2BJ </p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>

				<div class="small-box bg-purple">
					<div class="inner">
						<?php $Penyusutan= '66485452556' ?>
						<h4><?= number_format($Penyusutan) ?></h4>

						<p>TOTAL NILAI PEMBELIAN</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>

				<div class="small-box bg-green">
					<div class="inner">
						<h4><strong><?= number_format($TOTAL->TOTAL - $Penyusutan) ?></strong></sup></h4>

						<p>TOTAL NILAI EFISIENSI</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>

				<div class="small-box bg-blue">
					<div class="inner">
						<h4>200</sup></h4>

						<p>TOTAL JUMLAH SP2BJ </p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>


			</div>
			<!-- ./col -->

			<!-- <div class="col-lg-3 col-xs-3">
				<!-- small box --
				<div class="small-box bg-blue">
					<div class="inner">
						<h3><?= number_format($TOTAL->TOTAL - $Penyusutan) ?></sup></h4>

						<p>TOTAL NILAI SP2BJ </p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div> -->


			<!-- <div class="col-lg-3 col-xs-3">
				<!-- small box --
				
			</div>			 -->

			<!-- <div class="col-lg-3 col-xs-3">
				<!-- small box --
				
			</div> -->

			<div class="col-sm-4">
				<div class="box">
					<!-- <div class="box-header bg-green">
						<h5>Data Asset Berdasarkan Area</h5>
					</div> -->
					<div class="box-body">
						<div id="chartContainer" style="height: 200px; width: 100%;"></div>
					</div>
				</div>				
			</div>

			<div class="col-sm-5">
				<div class="box">
					<!-- <div class="box-header bg-green">
						<h5>Data Asset Berdasarkan Area</h5>
					</div> -->
					<div class="box-body">
						<div id="chartContainer2" style="height: 200px; width: 100%;"></div>
					</div>
				</div>

				
			</div>
			
			<div class="col-sm-9">

			<div class="box">
            <div class="box-header">
              <h3 class="box-title">Status SP2BJ</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
			  	<thead>
					<tr>
						<th>No</th>
						<th>No SP2BJ</th>
						<th>Tgl Sp2BJ</th>
						<th>No PO </th>
						<th>Tgl PO</th>
						<Th>Status</Th>
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
					</tr>
				</tbody>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->



				<!-- <div class="box">
					<!-- <div class="box-header bg-green">
						<h5>Data Asset Berdasarkan Area</h5>
					</div> --
					<div class="box-body">
						<label for="">Daftar Permintaan</label>
						<div id="table Permintaan" style="height: 300px; width: 100%;">
							<table  class="table table-striped table-bordered" border="1">
								
								
							</table>

						</div>
					</div>
				</div> -->

				
			</div>

			
		</div>
		<div class="col-sm-3">
			<div class="box">					
				<div class="box-body">						
					<div id="chartContainer3" style="height: 620px; width: 100%;">
						<!-- <table class="table-resposive">

						</table> -->
					</div>	
				</div>
			</div>				
		</div>


		<div class="col-lg-12">
			<div class="box-header box-solid bg-aqua-gradient"></div>
		</div>
		


		
		


	<div class="row">
		<div class="col-lg-12 connectedSortable">
			<P></P>
			<div class="col-lg-4">
				<table width="100%" border="0" class="marginatas5 huruf13">
					<tr>
						<td width="100px">AREA</td>
						<td width="10px" class="text-center">:</td>
						<td width="300px">
							<select id="Alokasi" name="Alokasi" width="100px" class="form-control input-sm huruf13">
								<option value="">:: Pilih Area ::</option>
								<?php foreach ($Area as $row) : ?>
									<option value="<?= $row->NamaArea ?>" <?= ($NamaArea == $row->NamaArea) ? 'selected' : '' ?>><?= $row->KdArea . ' - ' . $row->NamaArea ?></option>
								<?php endforeach ?>
							</select>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
	</div>


	<div class="col-sm-12">
					<div class="box">
						<div class="box-header bg-green">
							<h5>Data Komparasi </h5>
						</div>
						<div class="box-body">
							<!-- <canvas id="myChart" style="height: 370px; width: 100%;"></canvas> -->
							<div id="myChart"></div>
						</div>
					</div>				
				</div>


		
		<div class="row" <?= (($this->session->userdata('PecahToken')->DataTerlambat == NULL) || ($this->session->userdata('PecahToken')->DataTerlambat == 0)) ? 'hidden' : '' ?>>
			
		<!-- <div class="row" id="MOD-IN"> -->
		<div class="col-md-12" >
			<div class="box box-warning">
				<div class="box-header with-border">
					<div class="box box-solid">
						<!-- <div class="box-header with-border"> -->
							<div class="box-header bg-blue-gradient">
								<h3 class="box-title">Data Asset Area Berdasarkan Kategori</h3>
								<div class="box-tools pull-right">
								<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
							    <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
						<!-- </div> -->
							<div class="box-body">
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-aqua">
											<h5><strong>Monitoring Asset Kategori Kendaraan</strong></h5>
										</div>
										<div class="box-body">
											<div id="chartKendaraan2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-aqua-gradient">
											<h5><strong>Monitoring Asset Kategori FFE </strong></h5>
										</div>
										<div class="box-body">
											<div id="chartffe2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-aqua-gradient">
											<h5><strong>Monitoring Asset Kategori Elektronik</strong></h5>
										</div>
										<div class="box-body">
											<div id="chartElektronik2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-purple-gradient">
											<h5><strong>Monitoring Asset Kategori Landscape</strong></h5>
										</div>
										<div class="box-body">
											<div id="chartLandscape2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-purple-gradient">
											<h5><strong>Monitoring Asset Kategori Tanah</strong></h5>
										</div>
										<div class="box-body">
											<div id="chartTanah2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="box">
										<div class="box-header bg-purple-gradient">
											<h5><strong>Monitoring Asset Kategori Bangunan</strong></h5>
										</div>
										<div class="box-body">
											<div id="chartBangunan2" style="height: 250px; width: 100%;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	<!-- </div> -->




				
			<!-- </div> -->
		<!-- </div> -->


				</div>
			</div>

		</div>
</div>
</div>

<!-- /.content -->
</section>
</div>
<!-- /.content-wrapper -->

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<!-- <link rel="stylesheet" href="https://www.canvasxpress.org/dist/canvasXpress.css" type="text/css"/> -->
  <script src="https://www.canvasxpress.org/dist/canvasXpress.min.js"></script>


<?php
$tahun ="";
$Pembelian = null;
$Total = "";

foreach ($PertumbuhanAsset as $item) {

	$thn = $item->Tahun;
	$tahun .="'$thn'" . ",";

	$pemb = ($item->Pembelian == 0) ? null : $item->Pembelian;
	$Pembelian .= "'$pemb'" . ", ";

	$val = ($item->Nilai == 0) ? null : $item->Nilai;
	$Total .= "'$val'" . ", ";


}

// print_rr($item);
// exit;
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>



<script>
window.onload = function () {

	var chart = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Efisiensi Tahun 2025"
	},
	axisY: {
		title: "Amount"
	},
	data: [{        
		type: "column",  
		showInLegend: true, 
		legendMarkerColor: "grey",
		legendText: "Bulan",
		dataPoints: [      
			{ y: 300878, label: "Januari" },
			{ y: 266455,  label: "Februari" },
			{ y: 169709,  label: "Maret" },
			{ y: 158400,  label: "April" },
			{ y: 142503,  label: "Mei" },
			{ y: 101500, label: "Juni" },
			{ y: 97800,  label: "Juli" },
			{ y: 0,  label: "Agustus" },
			{ y: 0,  label: "September" },
			{ y: 0,  label: "Oktober" },
			{ y: 0,  label: "November" },
			{ y: 0,  label: "Desember" },
		]
	}]
});
chart.render();



// ==========================================================
var chart = new CanvasJS.Chart("chartContainer", {
	exportEnabled: true,
	animationEnabled: true,
	title:{
		text: "Status Pelaksanaan SP2BJ"
	},
	legend:{
		cursor: "pointer",
		itemclick: explodePie
	},
	data: [{
		type: "pie",
		showInLegend: true,
		toolTipContent: "{name}: <strong>{y}%</strong>",
		indexLabel: "{name} - {y}",
		dataPoints: [
			{ y: 200, name: "Jumlah SP2BJ", exploded: true },
			{ y: 20, name: "SP2BJ Pending" },
			{ y: 5, name: "PO" }
		]
	}]
});
chart.render();
}

function explodePie (e) {
	if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
		e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
	} else {
		e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
	}
	e.chart.render();

}
</script>





<script>


var chart = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	title: {
		text: "Daftar Permintaan Area"
	},
	axisX: {
		interval: 1
	},
	axisY: {
		title: "Amount",
		includeZero: true,
		scaleBreaks: {
			type: "wavy",
			customBreaks: [{
				startValue: 80,
				endValue: 210
				},
				{
					startValue: 230,
					endValue: 600
				}
		]}
	},
	data: [{
		type: "bar",
		toolTipContent: "<img src=\"https://canvasjs.com/wp-content/uploads/images/gallery/javascript-column-bar-charts/\"{url}\"\" style=\"width:40px; height:20px;\"> <b>{label}</b><br>Budget: ${y}bn<br>{gdp}% of GDP",
		dataPoints: [
			{ label: "Meruorah", y: 17.8, gdp: 5.8, url: "israel.png" },
			{ label: "Merak", y: 22.8, gdp: 5.7, url: "uae.png" },
			{ label: "Bakauheni", y: 22.8, gdp: 1.3, url: "brazil.png"},
			{ label: "Labuan Bajo", y: 24.3, gdp: 2.0, url: "australia.png" },
			{ label: "Pusat", y: 36.8, gdp: 2.7, url: "skorea.png" }
			
		]
	}]
});
chart.render();


// =============================================================================================================

			Highcharts.chart('myChart', {

			title: {
				text: 'Data Komparasi '
			},

			subtitle: {
				text: 'Data pertumbuhan asset dengan 2 variabel data yaitu tahunan dan akumulasi'
			},

			xAxis: {
				categories:<?= $ArrayTahun ?>
				// categories: [
				// 	'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
				// 	'Oct', 'Nov', 'Dec'
				// ]
			},

			yAxis: {
				title: {
					text: 'IDR',
					fontSize: '1.5rem'
				},
				labels: {
					format: '{value}',
					yValueFormatString: "#,##0",
					style: {
						color: 'black',
						fontSize: '1rem',
					}

				}


			},

			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						borderRadius: 5,
						backgroundColor: 'rgba(252, 255, 197, 0.7)',
						borderWidth: 1,
						borderColor: '#AAA',
						y: -6
					}
				}
			},

			series: [
				{ 
					name:'Akumulasi Pembelian',
					data: <?= $ArrayTotal ?> 
				},
				{ 
					name:'Nilai Pembelian',
					data: <?= $ArrayPembelian ?> },
			
			]



			// series: [{

			// 	data: [29.9, 71.5, 106.4, 129.2, 144.0, 178.0, 135.6, 148.5, {
			// 		// y: 216.4,
			// 		dataLabels: {
			// 			borderColor: 'red',
			// 			borderWidth: 2,
			// 			padding: 5,
			// 			shadow: true,
			// 			style: {
			// 				fontWeight: 'bold'
			// 			}
			// 		}
			// 	},194.1, 95.6, 54.4]
			// }]

			});




// ===============================================================================================
	// var ctx = document.getElementById("myChart").getContext('2d');
	// var myChart = new Chart(ctx, {
	// // 	graphOrientation:"vertical",
    // //    graphType:"AreaLine",
    // //    lineThickness:"3",
    // //    lineType:"spline",
	// 	type: 'line',
	// 	data: {
	// 		labels: [<?= $tahun ?>],			
			
			
	// 		datasets: [{
	// 			label: 'Pembelian Asset',
	// 			data: [<?= $Pembelian ?>],
	// 			backgroundColor: [
	// 				'rgba(255, 99, 132, 0.2)',
	// 					// 'rgba(54, 162, 235, 0.2)',
	// 					// 'rgba(255, 206, 86, 0.2)',
	// 					// 'rgba(75, 192, 192, 0.2)'
	// 				],
	// 			borderColor: [
	// 				'rgba(255,99,132,1)',
	// 					// 'rgba(54, 162, 235, 1)',
	// 					// 'rgba(255, 206, 86, 1)',
	// 					// 'rgba(75, 192, 192, 1)'
	// 				],
	// 			// borderWidth: 1
	// 			tension : 0.4
	// 		},
	// 		{
	// 			label: 'Akumulasi Pembelian Asset',
	// 			data: [<?= $Total ?>],
	// 			backgroundColor: [
	// 					// 'rgba(255, 99, 132, 0.2)',
	// 				'rgba(54, 162, 235, 0.2)',
	// 					// 'rgba(255, 206, 86, 0.2)',
	// 					// 'rgba(75, 192, 192, 0.2)'
	// 				],
	// 			borderColor: [
	// 					// 'rgba(255,99,132,1)',
	// 				'rgba(54, 162, 235, 1)',
	// 					// 'rgba(255, 206, 86, 1)',
	// 					// 'rgba(75, 192, 192, 1)'
	// 				],
	// 			// borderWidth: 1
	// 			tension : 0.4
	// 		}
	// 		]
	// 	},
		
	// 	options: {
	// 		scales: {
	// 			yAxes: [{
	// 				beginAtZero: true,
	// 				scaleLabel:{
	// 					display:true,
	// 					labelString:'Persentage (%)'
	// 				}
					
	// 			}
	// 		],
	// 		XAxes:[{
	// 				beginAtZero:true,
	// 				scaleLabel:{
	// 					display:true,
	// 					labelString:'Bulan'
	// 				}
	// 			}]
	// 	}
	// 	}
	// });
</script>

<script>
  $(function () {
    $('#example1').DataTable(
		{
		'lengthMenu': [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, 'All']],
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    }
	)
    // $('#example2').DataTable()
  })
</script>




<script>
	$(document).ready(function() {
		$('#JobNoChart').select2();

		$('#tbl_terlambat').DataTable({
			'ordering': false
		});

		$('.frm-container .content').scroll(function() {
			$('.btn-scroll-bottom').show();
			var st = $(this).scrollTop();
			var sh = $(this).prop("scrollHeight");
			var ch = $(this).height();
			if (st + ch >= sh - 100) {
				$('.btn-scroll-bottom').hide();
			} else {
				$('.btn-scroll-bottom').show();
			}
		})

		$('.inp-search').keyup(function(e) {
			var inputSearch = e.target.value
			$('.list-container .content .list .item').each(function() {
				var txt = $(this).find('.isi').text().toLowerCase();
				var authorAndDate = $(this).find('.date').text().toLowerCase();
				if (txt.indexOf(inputSearch.toLowerCase()) > -1 || authorAndDate.indexOf(inputSearch.toLowerCase()) > -1) {
					$(this).show();
				} else {
					$(this).hide();
				}
			})
		})

		$('.btn-scroll-bottom').click(function() {
			scrollToBottom()
		})
	});

	function scrollToBottom() {
		$('.frm-container .content').animate({
			scrollTop: $(".frm-container .content").offset().top + $(".frm-container .content")[0].scrollHeight
		}, 2000);
	}
</script>



