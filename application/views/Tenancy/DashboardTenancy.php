<style>
  #map {
    height: 350px;
    width: 100%;
  }

  /* Style untuk zoom control */
  .leaflet-control-zoom {
    border: 2px solid rgba(0, 0, 0, 0.2);
    border-radius: 4px;
  }

  .leaflet-control-zoom a {
    background-color: #fff;
    color: #333;
    font-weight: bold;
  }

  .leaflet-control-zoom a:hover {
    background-color: #f4f4f4;
  }

  /* Custom control style */
  .leaflet-control-custom {
    border: 2px solid rgba(0, 0, 0, 0.2);
    border-radius: 4px;
    margin-top: 40px;
    /* Jarak dari zoom control */
  }

  .leaflet-control-custom a {
    text-decoration: none;
    color: #333;
    display: block;
    width: 100%;
    height: 100%;
  }

  .leaflet-control-custom a:hover {
    background-color: #f4f4f4;
  }



  /* batas */
  .invoice-table {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, sans-serif;
    font-size: 30px;
  }

  .invoice-table th,
  .invoice-table td {
    border: 1px solid #000;
    padding: 5px;
    text-align: left;
  }

  .invoice-table th {
    background-color: #f2f2f2;
    text-align: center;
  }

  .invoice-table .text-right {
    text-align: right;
  }

  .invoice-table .total-row {
    font-weight: bold;
  }

  /* Warna tambahan sesuai gambar */
  .invoice-table .bg-red {
    background-color: #FF0000;
  }

  .invoice-table .bg-krem {
    background-color: #FFF2CC;
  }

  .invoice-table .bg-hijau {
    background-color: #D9EAD3;
  }

  .invoice-table .bg-biru {
    background-color: #A4C2F4;
  }

  .invoice-table .text-white {
    color: #f2f2f2;
  }

  .invoice-table .bg-dark {
    background-color: #000;
  }

  .invoice-table .realisasi-panjang1 {
    width: 20%;
  }

  .invoice-table .realisasi-panjang2 {
    width: 10%;
  }

  .bg-red {
    background-color: #FF0000;
  }

  .bg-krem {
    background-color: #FFF2CC;
  }

  .bg-hijau {
    background-color: #D9EAD3;
  }

  .bg-biru {
    background-color: #A4C2F4;
  }

  .atur_kolom_kiri {
    width: 300px;
  }

  .atur_kolom_tengah {
    width: 300px;
    text-align: center;
  }

  .table_kedua {

    font-family: Arial, sans-serif;
    font-size: 10px;
    border-collapse: collapse;
    width: 70%;

  }

  .table_kedua tr td {
    padding-top: 5px;
    padding-bottom: 5px;
  }

  .outstanding-table {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, sans-serif;
    text-align: center;
    margin-top: 30px;
  }

  .outstanding-table th,
  .outstanding-table td {
    border: 1px solid #aaa;
    padding: 6px 10px;
  }

  .outstanding-table th {
    background-color: #f4f4f4;
    font-weight: bold;
  }

  .highlight {
    background-color: yellow;
    font-weight: bold;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
  }

  .legend-box {
    width: 14px;
    height: 14px;
    border-radius: 3px;
  }

  .jan {
    background-color: #3b6eea;
  }

  .feb {
    background-color: #f28424;
  }

  .mar {
    background-color: gray;
  }

  .apr {
    background-color: #f1b11a;
  }

  .mei {
    background-color: #2da84a;
  }

  .jun {
    background-color: #2da8a8;
  }


  /* CSSS Outstanding Payments */
  .outstanding-table {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, sans-serif;
    text-align: center;
    margin-top: 30px;
  }

  .outstanding-table th,
  .outstanding-table td {
    border: 1px solid #aaa;
    padding: 6px 10px;
  }

  .outstanding-table th {
    background-color: #f4f4f4;
    font-weight: bold;
  }

  .highlight {
    background-color: yellow;
    font-weight: bold;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
  }

  .legend-box {
    width: 14px;
    height: 14px;
    border-radius: 3px;
  }

  .nav-tabs {
    border-bottom: 2px solid #ddd;
    margin-bottom: 15px;
    overflow: hidden;
  }

  .nav-tabs>li {
    margin-bottom: -2px;
  }

  .nav-tabs>li>a {
    color: #444;
    padding: 10px 20px;
    font-weight: 600;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    transition: all 0.3s ease;
  }

  .nav-tabs>li>a:hover {
    background-color: #f4f4f4;
    border-color: #ddd #ddd #ccc;
    color: #000;
  }

  .nav-tabs>li.active>a,
  .nav-tabs>li.active>a:hover,
  .nav-tabs>li.active>a:focus {
    color: #fff;
    background-color: #3c8dbc;
    /* AdminLTE primary color */
    border: 1px solid #3c8dbc;
    border-bottom-color: transparent;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  }

  /* Smooth tab content transition */
  .tab-content>.tab-pane {
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;

  }

  .tab-content>.active {
    display: block;
    opacity: 1;
  }

  /* Optional: Add hover effect to box inside tabs */
  .tab-pane .box {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .tab-pane .box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  }

  /* Small boxes hover effect */
  .small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .small-box {
    height: 200px;
    /* atur tinggi box sesuai kebutuhan */
    display: flex;
    align-items: center;
    /* center secara vertikal */
    justify-content: center;
    /* center secara horizontal */
    text-align: center;
    position: relative;
  }

  .small-box .inner {
    margin: 0;
    /* hapus margin default */
  }

  .small-box .icon {
    position: static;
    /* hilangkan absolute agar ikut center */
    font-size: 60px;
    /* perbesar icon kalau perlu */
    margin-left: 15px;
    /* beri jarak antara teks dan icon */
  }

  /* Optional: Scrollable tabs if many */
  .nav-tabs {
    overflow-x: auto;
    white-space: nowrap;
  }

  .nav-tabs>li {
    display: inline-block;
    float: none;
  }


  /* CSS khusus untuk grafik dan komponennya */
  #chart-wrapper {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 100%;
    margin: 0 auto;
    padding: 25px;
    background: linear-gradient(to bottom, #ffffff, #f8f9fa);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  }

  #chart-header h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 600;
    font-size: 1.2em;
  }

  #chart-header .subtitle {
    text-align: center;
    color: #7f8c8d;
    margin-bottom: 30px;
    font-size: 1.1em;
  }

  #chart-container {
    height: 250px;
    width: 100%;
    margin-bottom: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    /* padding: 10px; */
    /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
  }

  #data-table {
    width: 100%;
    border-collapse: collapse;
    /* margin-top: 30px; */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
    font-size: 10px;
  }

  #data-table th,
  #data-table td {
    /* padding: 12px 15px; */
    padding: 3px 5px;
    text-align: center;
    border: 1px solid #e0e0e0;
  }

  #data-table th {
    background-color: #f2f6fc;
    font-weight: 600;
    color: #2c3e50;
  }

  #data-table tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  #data-table tr:hover {
    background-color: #f1f7ff;
  }

  #chart-footer {
    text-align: center;
    margin-top: 25px;
    color: #7f8c8d;
    font-size: 0.9em;
  }

  @media (max-width: 768px) {
    #chart-wrapper {
      padding: 15px;
    }

    #chart-container {
      height: 400px;
    }
  }

  #table_buatan {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
  }

  #table_buatan thead tr {
    padding-top: 5px;
    padding-bottom: 5px;
    background-color: rgb(204, 214, 235);
    text-align: center;
    /* color: whitesmoke; */

  }

  .tableProyeksiCashOut {
    width: 100%;
    border-collapse: collapse;
    /* margin-top: 15px; */
    font-family: Arial, sans-serif;
    font-size: 25px;
    text-align: center;
  }

  .tableProyeksiCashOut thead tr {
    background-color: #2c3e50;
    color: #fff;
    /* font-weight: bold; */
  }

  .tableProyeksiCashOut th,
  .tableProyeksiCashOut td {
    border: 1px solid #ccc;
    padding: 3px 5px;
  }

  .tableProyeksiCashOut tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .tableProyeksiCashOut tbody tr:hover {
    background-color: #f1f1f1;
  }

  .tableProyeksiCashOut td:first-child {
    font-weight: bold;
    text-align: left;
    background-color: #ecf0f1;
  }

  .no-gutters {
    margin-right: 0;
    margin-left: 0;
  }

  .no-gutters>[class*='col-'] {
    padding-right: 0;
    padding-left: 0;
  }


  /* css untuk Sales & PnL */
  /* Chart khusus */
  .sales-chart-container {
    width: 90%;
    height: 500px;
    margin: 0 auto 30px auto;

  }

  /* Table khusus */
  .sales-table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
    font-size: 10px;
  }

  .sales-table,
  .sales-table th,
  .sales-table td {
    border: 1px solid #000;
  }

  .sales-table th,
  .sales-table td {
    padding: 3px;
    text-align: center;
  }

  .sales-table th {
    background-color: #f0fff0;
  }

  .variance-table td {
    background-color: #e7f0d7;
  }

  .variance-header {
    font-weight: bold;
    background-color: #fff;
  }
</style>


<!-- // css khusus loading ajax chart -->
<style>
  /* Loading Styles */
  .chart-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
  }

  .loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .chart-loading p {
    margin: 0;
    font-size: 16px;
    color: #666;
    font-weight: 500;
  }

  /* No Data Styles */
  .chart-no-data {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    color: #666;
  }

  .no-data-icon {
    font-size: 48px;
    margin-bottom: 15px;
  }

  .chart-no-data h3 {
    margin: 0 0 10px 0;
    color: #666;
  }

  .chart-no-data p {
    margin: 0;
    color: #888;
  }

  /* Error Styles */
  .chart-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    color: #e74c3c;
  }

  .error-icon {
    font-size: 48px;
    margin-bottom: 15px;
  }

  .chart-error h3 {
    margin: 0 0 10px 0;
    color: #e74c3c;
  }

  .chart-error p {
    margin: 0;
    color: #666;
  }
</style>

<style>
  .chart-loading {
    text-align: center;
    padding: 40px;
    color: #666;
  }

  .loading-spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    margin: 0 auto 20px;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .chart-error,
  .chart-no-data {
    text-align: center;
    padding: 40px;
    color: #e74c3c;
  }

  .chart-no-data {
    color: #7f8c8d;
  }

  .error-icon,
  .no-data-icon {
    font-size: 48px;
    margin-bottom: 20px;
  }

  .panel {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .panel-heading {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #eee;
  }

  .panel-title {
    font-weight: bold;
    color: #333;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Dashboard
      <!-- <small>Aplikasi Monitoring Procurement & Evaluasi ( AMPEL )</small> -->
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>


  <section class="content">
    <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab-bulan_berjalan" role="tab" data-toggle="tab">Bulan Berjalan</a>
          </li>
          <li>
            <a href="#tab-sampai_dengan_bulan_berjalan" role="tab" data-toggle="tab">Sampai Dengan Bulan Sekarang (YTD)</a>
          </li>

        </ul>
      </div>
      <div class="panel-body tab-content">
        <!-- Tab Bulan Berjalan -->
        <div class="tab-pane fade in active" id="tab-bulan_berjalan">
          <form id="formFilterTenancy_BulanBerjalan" class="form-inline mb-3">
            <div class="form-group">
              <label for="bulan">Bulan:</label>
              <select id="bulan" name="bulan" class="form-control mx-2">
                <?php foreach ($bulan_list as $num => $nama): ?>
                  <option value="<?= $num ?>" <?= ($num == $bulan_selected ? 'selected' : '') ?>>
                    <?= $nama ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>


            <div class="form-group">
              <label for="tahun">Tahun:</label>
              <select id="tahun" name="tahun" class="form-control mx-2">
                <?php
                $tahunSekarang = date('Y');
                for ($i = $tahunSekarang - 3; $i <= $tahunSekarang; $i++) {
                  $selected = $i == $tahunSekarang ? 'selected' : '';
                  echo "<option value='$i' $selected>$i</option>";
                }
                ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fa fa-filter"></i> Tampilkan
            </button>
          </form>

          <div id="container-tenancy-bulan_berjalan" style="height:600px;"></div>
        </div>

        <!-- Tab YTD -->
        <div class="tab-pane fade" id="tab-sampai_dengan_bulan_berjalan">
          <form id="formFilterTenancy_YTD" class="form-inline mb-3">
            <div class="form-group">
              <label for="tahun_ytd">Tahun:</label>
              <select id="tahun_ytd" name="tahun_ytd" class="form-control mx-2">
                <?php
                $tahunSekarang = date('Y');
                for ($i = $tahunSekarang - 3; $i <= $tahunSekarang; $i++) {
                  $selected = $i == $tahunSekarang ? 'selected' : '';
                  echo "<option value='$i' $selected>$i</option>";
                }
                ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fa fa-filter"></i> Tampilkan
            </button>
          </form>

          <div id="container-tenancy-ytd" style="height:600px;"></div>
        </div>



      </div>
    </div>

  </section>
</div>

<script>
  $(document).ready(function() {
    // Chart Status SP2BJ (Pie Chart)
    Highcharts.chart('chartStatusSP2BJ', {
      chart: {
        type: 'pie',
        height: 200
      },
      title: {
        text: 'Status Pelaksanaan SP2BJ'
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
          }
        }
      },
      series: [{
        name: 'Persentase',
        colorByPoint: true,
        data: [{
          name: 'Jumlah SP2BJ',
          y: 200,
          sliced: true,
          selected: true
        }, {
          name: 'SP2BJ Pending',
          y: 20
        }, {
          name: 'PO',
          y: 5
        }]
      }]
    });

    // Chart Efisiensi (Column Chart)
    Highcharts.chart('chartEfisiensi', {
      chart: {
        type: 'column',
        height: 200
      },
      title: {
        text: 'Efisiensi Tahun 2025'
      },
      xAxis: {
        categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ]
      },
      yAxis: {
        title: {
          text: 'Amount'
        }
      },
      series: [{
        name: 'Efisiensi',
        data: [300878, 266455, 169709, 158400, 142503, 101500,
          97800, 0, 0, 0, 0, 0
        ]
      }]
    });

    // Chart Permintaan Area (Bar Chart)
    Highcharts.chart('chartPermintaanArea', {
      chart: {
        type: 'bar',
        height: 620
      },
      title: {
        text: 'Daftar Permintaan Area'
      },
      xAxis: {
        categories: ['Meruorah', 'Merak', 'Bakauheni', 'Labuan Bajo', 'Pusat']
      },
      yAxis: {
        title: {
          text: 'Amount'
        }
      },
      series: [{
        name: 'Permintaan',
        data: [17.8, 22.8, 22.8, 24.3, 36.8]
      }]
    });



    // Inisialisasi DataTable
    $('#example1').DataTable({
      'lengthMenu': [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, 'All']
      ],
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
  });
</script>

<!-- === start wilayah tenancy, bulan berjalan, ytd, tampil tenant === -->
<script>
  $(document).ready(function() {
    autoRotateTabsWithPause({
      navSelector: '.nav-tabs li',
      interval: 10000,
      pauseAfterActivity: 3000
    });

    loadTenancyChartBulanBerjalan();
    loadTenancyChartYTD(); // Tambahkan ini

    // Event handlers untuk Bulan Berjalan
    $('#formFilterTenancy_BulanBerjalan').on('submit', function(e) {
      e.preventDefault();
      loadTenancyChartBulanBerjalan();
    });

    $('#formFilterTenancy_BulanBerjalan').find('#bulan').on('change', function(e) {
      loadTenancyChartBulanBerjalan();
    });

    $('#formFilterTenancy_BulanBerjalan').find('#tahun').on('change', function(e) {
      loadTenancyChartBulanBerjalan();
    });

    // Event handlers untuk YTD
    $('#formFilterTenancy_YTD').on('submit', function(e) {
      e.preventDefault();
      loadTenancyChartYTD();
    });

    $('#formFilterTenancy_YTD').find('#tahun_ytd').on('change', function(e) {
      loadTenancyChartYTD();
    });

    $('#formFilterTenancy_YTD').find('#tahun_ytd').on('change', function(e) {
      loadTenancyChartYTD();
    });



  });

  // Fungsi autoRotateTabsWithPause tetap sama
  function autoRotateTabsWithPause(options) {
    var settings = $.extend({
      navSelector: '.nav-tabs li',
      interval: 10000,
      pauseAfterActivity: 3000
    }, options);

    var tabItems = $(settings.navSelector);
    var totalTabs = tabItems.length;
    var currentIndex = 0;
    var rotateTimer;
    var activityTimer;

    function activateTab(index) {
      var tabLink = tabItems.eq(index).find('a');
      tabLink.tab('show');
      currentIndex = index;
    }

    function scheduleNextRotate() {
      clearTimeout(rotateTimer);
      rotateTimer = setTimeout(function() {
        var nextIndex = (currentIndex + 1) % totalTabs;
        activateTab(nextIndex);
        scheduleNextRotate();
      }, settings.interval);
    }

    function pauseAutoRotate() {
      clearTimeout(rotateTimer);
      clearTimeout(activityTimer);
      activityTimer = setTimeout(function() {
        scheduleNextRotate();
      }, settings.pauseAfterActivity);
    }

    activateTab(0);
    scheduleNextRotate();

    tabItems.find('a').on('click', function(e) {
      e.preventDefault();
      var clickedIndex = $(this).parent().index();
      if (clickedIndex !== currentIndex) {
        activateTab(clickedIndex);
        pauseAutoRotate();
      }
    });

    $(document).on('mousemove keydown scroll click', function() {
      pauseAutoRotate();
    });
  }

  // Fungsi untuk Bulan Berjalan (tetap sama)
  function loadTenancyChartBulanBerjalan() {
    const bulan = $('#formFilterTenancy_BulanBerjalan').find('#bulan').val();
    const tahun = $('#formFilterTenancy_BulanBerjalan').find('#tahun').val();

    $.ajax({
      url: '<?= base_url("ControlTenancyData/getFilteredBulanBerjalan") ?>',
      type: 'POST',
      data: {
        bulan,
        tahun
      },
      dataType: 'json',
      beforeSend: function() {
        $('#container-tenancy-bulan_berjalan').html(`
                <div class="chart-loading">
                    <div class="loading-spinner"></div>
                    <p>Memuat data chart...</p>
                </div>
            `);
      },
      success: function(response) {
        if (response.revenue && response.revenue.length > 0) {
          // console.log(response.revenue);
          tenancy_ChartBulanBerjalan(response.revenue, bulan, tahun);
        } else {
          $('#container-tenancy-bulan_berjalan').html(`
                    <div class="chart-no-data">
                        <i class="no-data-icon">📊</i>
                        <h3>Tidak ada data tersedia</h3>
                        <p>Silakan pilih periode lain</p>
                    </div>
                `);
        }
      },
      error: function(xhr, status, error) {
        console.error(error);
        $('#container-tenancy-bulan_berjalan').html(`
                <div class="chart-error">
                    <i class="error-icon">⚠️</i>
                    <h3>Gagal memuat data chart</h3>
                    <p>Terjadi kesalahan saat mengambil data</p>
                </div>
            `);
      }
    });
  }


  // Fungsi chart untuk Bulan Berjalan
  function tenancy_ChartBulanBerjalan(revenue, bulan, tahun) {
    // Konversi angka bulan ke nama bulan Indonesia
    const namaBulanIndonesia = {
      1: 'Januari',
      2: 'Februari',
      3: 'Maret',
      4: 'April',
      5: 'Mei',
      6: 'Juni',
      7: 'Juli',
      8: 'Agustus',
      9: 'September',
      10: 'Oktober',
      11: 'November',
      12: 'Desember'
    };

    // Konversi bulan ke nama Indonesia
    const bulanIndonesia = namaBulanIndonesia[bulan] || bulan;

    const areas = revenue.map(r => r.Area);
    const targetRetail = revenue.map(r => r.RKAP_Retail / 100000);
    const targetUtility = revenue.map(r => r.RKAP_Utility / 100000);
    const achRetail = revenue.map(r => r.Ach_Retail / 100000);
    const achUtility = revenue.map(r => r.Ach_Utility / 100000);
    const persenTRA = revenue.map(r => parseFloat(r.Persen_TRA) / 100);

    Highcharts.chart('container-tenancy-bulan_berjalan', {
      chart: {
        type: 'column'
      },
      title: {
        text: `Revenue Per ${bulanIndonesia} ${tahun} (Stacked Column + % Achievement)`,
        style: {
          fontSize: '24px',
          fontWeight: 'bold'
        }
      },
      legend: {
        itemStyle: {
          fontSize: '24px',
          fontWeight: 'bold'
        }
      },
      xAxis: {
        categories: areas,
        labels: {
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        }
      },
      yAxis: [{
        min: 0,
        title: {
          text: 'Nilai (Miliar Rupiah)',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        labels: {
          format: '{value:,.0f}',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        stackLabels: {
          enabled: true,
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          },
          formatter: function() {
            return Highcharts.numberFormat(this.total, 0, ',', '.');
          }
        }
      }, {
        title: {
          text: '% Achievement',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        labels: {
          format: '{value} %',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        opposite: true
      }],
      tooltip: {
        shared: true,
        formatter: function() {
          if (!this.points) return 'Tidak ada data';
          let s = '<b>' + this.x + '</b><br/>';
          this.points.forEach(function(point) {
            if (point.series.name.includes('%')) {
              s += point.series.name + ': ' + (point.y * 100).toFixed(2) + '%<br/>';
            } else {
              s += point.series.name + ': ' + Highcharts.numberFormat(point.y, 0, ',', '.') + '<br/>';
            }
          });
          return s;
        }
      },
      plotOptions: {
        column: {
          stacking: 'normal',
          dataLabels: {
            enabled: true,
            style: {
              fontSize: '24px',
              fontWeight: 'bold'
            },
            formatter: function() {
              return Highcharts.numberFormat(this.y, 0, ',', '.');
            }
          }
        }
      },
      series: [{
          name: 'Target - Retail',
          data: targetRetail,
          stack: 'Target',
          color: '#4E79A7'
        },
        {
          name: 'Target - Utility',
          data: targetUtility,
          stack: 'Target',
          color: '#59A14F'
        },
        {
          name: 'Achievement - Retail',
          data: achRetail,
          stack: 'Achievement',
          color: '#F28E2B'
        },
        {
          name: 'Achievement - Utility',
          data: achUtility,
          stack: 'Achievement',
          color: '#E15759'
        },
        {
          name: '% Achievement',
          type: 'spline',
          yAxis: 1,
          data: persenTRA,
          lineWidth: 5,
          marker: {
            lineWidth: 3,
            lineColor: '#76B7B2',
            fillColor: '#fff'
          },
          tooltip: {
            valueSuffix: '%'
          },
          dataLabels: {
            enabled: true,
            style: {
              fontSize: '24px',
              fontWeight: 'bold',
              color: '#333'
            },
            formatter: function() {
              return (this.y * 100).toFixed(2) + '%';
            }
          }
        }
      ]
    });
  }

  function loadTenancyChartYTD() {
    const tahun = $('#formFilterTenancy_YTD').find('#tahun_ytd').val();

    $.ajax({
      url: '<?= base_url("ControlTenancyData/getFilteredYTD") ?>',
      type: 'POST',
      data: {
        tahun
      },
      dataType: 'json',
      beforeSend: function() {
        $('#container-tenancy-ytd').html(`
                <div class="chart-loading">
                    <div class="loading-spinner"></div>
                    <p>Memuat data chart YTD...</p>
                </div>
            `);
      },
      success: function(response) {
        if (response.revenue && response.revenue.length > 0) {
          tenancy_ChartYTD(response.revenue, response.filter_info);
        } else {
          $('#container-tenancy-ytd').html(`
                    <div class="chart-no-data">
                        <i class="no-data-icon">📊</i>
                        <h3>Tidak ada data tersedia</h3>
                        <p>Silakan pilih tahun lain</p>
                    </div>
                `);
        }
      },
      error: function(xhr, status, error) {
        console.error(error);
        $('#container-tenancy-ytd').html(`
                <div class="chart-error">
                    <i class="error-icon">⚠️</i>
                    <h3>Gagal memuat data chart</h3>
                    <p>Terjadi kesalahan saat mengambil data</p>
                </div>
            `);
      }
    });
  }

  function tenancy_ChartYTD(revenue, filterInfo) {
    const areas = revenue.map(r => r.Area);
    const targetRetail = revenue.map(r => r.RKAP_Retail / 100000);
    const targetUtility = revenue.map(r => r.RKAP_Utility / 100000);
    const achRetail = revenue.map(r => r.Ach_Retail / 100000);
    const achUtility = revenue.map(r => r.Ach_Utility / 100000);
    const persenTRA = revenue.map(r => parseFloat(r.Persen_TRA) / 100);

    // Buat judul dinamis berdasarkan filter bulan
    const bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    let titleText;
    if (filterInfo.bulan_akhir === 12) {
      titleText = `Revenue Year To Date ${filterInfo.tahun} (Full Year)`;
    } else {
      const bulanAkhirName = bulanNames[filterInfo.bulan_akhir - 1];
      titleText = `Revenue Year To Date ${filterInfo.tahun} (Januari - ${bulanAkhirName})`;
    }

    Highcharts.chart('container-tenancy-ytd', {
      chart: {
        type: 'column'
      },
      title: {
        text: titleText,
        style: {
          fontSize: '24px',
          fontWeight: 'bold'
        }
      },
      subtitle: {
        text: `Data hingga ${bulanNames[filterInfo.bulan_akhir - 1]} ${filterInfo.tahun}`,
        style: {
          fontSize: '24px'
        }
      },
      legend: {
        itemStyle: {
          fontSize: '24px',
          fontWeight: 'bold'
        }
      },
      xAxis: {
        categories: areas,
        labels: {
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        }
      },
      yAxis: [{
        min: 0,
        title: {
          text: 'Nilai (Miliar Rupiah)',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        labels: {
          format: '{value:,.0f}',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        stackLabels: {
          enabled: true,
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          },
          formatter: function() {
            return Highcharts.numberFormat(this.total, 0, ',', '.');
          }
        }
      }, {
        title: {
          text: '% Achievement',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        labels: {
          format: '{value} %',
          style: {
            fontSize: '24px',
            fontWeight: 'bold'
          }
        },
        opposite: true
      }],
      tooltip: {
        shared: true,
        formatter: function() {
          if (!this.points) return 'Tidak ada data';
          let s = '<b>' + this.x + '</b><br/>';
          s += `<small>Periode: Jan-${bulanNames[filterInfo.bulan_akhir - 1].substring(0,3)} ${filterInfo.tahun}</small><br/>`;

          this.points.forEach(function(point) {
            if (point.series.name.includes('%')) {
              s += point.series.name + ': ' + (point.y * 100).toFixed(2) + '%<br/>';
            } else {
              s += point.series.name + ': ' + Highcharts.numberFormat(point.y, 0, ',', '.') + '<br/>';
            }
          });
          return s;
        }
      },
      plotOptions: {
        column: {
          stacking: 'normal',
          dataLabels: {
            enabled: true,
            style: {
              fontSize: '24px',
              fontWeight: 'bold'
            },
            formatter: function() {
              return Highcharts.numberFormat(this.y, 0, ',', '.');
            }
          }
        }
      },
      series: [{
        name: 'Target - Retail',
        data: targetRetail,
        stack: 'Target',
        color: '#4E79A7'
      }, {
        name: 'Target - Utility',
        data: targetUtility,
        stack: 'Target',
        color: '#59A14F'
      }, {
        name: 'Achievement - Retail',
        data: achRetail,
        stack: 'Achievement',
        color: '#F28E2B'
      }, {
        name: 'Achievement - Utility',
        data: achUtility,
        stack: 'Achievement',
        color: '#E15759'
      }, {
        name: '% Achievement',
        type: 'spline',
        yAxis: 1,
        data: persenTRA,
        lineWidth: 5,
        marker: {
          lineWidth: 3,
          lineColor: '#76B7B2',
          fillColor: '#fff'
        },
        tooltip: {
          valueSuffix: '%'
        },
        dataLabels: {
          enabled: true,
          style: {
            fontSize: '24px',
            fontWeight: 'bold',
            color: '#333'
          },
          formatter: function() {
            return (this.y * 100).toFixed(2) + '%';
          }
        }
      }]
    });
  }
</script>