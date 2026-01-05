<div class="container-fluid h-100">
    <div class="row full-row d-flex align-items-center justify-content-center">
        <div class="info-box p-3">
            <span class="info-box-icon text-bg-primary" style="width: 100px; height: 100px; font-size: 50px;">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content ml-4">
                <span class="info-box-text fw-bold text-muted" style="font-size: 20px;">Cashier</span>
                <span class="info-box-number">
                    <span id='checkin_statistic' style="font-size: 40px;"></span>
                    <small id='person_checkin_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="row full-row d-flex align-items-center justify-content-center">
        <div class="info-box p-3">
            <span class="info-box-icon text-bg-warning" style="width: 100px; height: 100px; font-size: 50px;">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content ml-4">
                <span class="info-box-text fw-bold text-muted" style="font-size: 20px;">Checkin</span>
                <span class="info-box-number">
                    <span id='checkin_scan_statistic' style="font-size: 40px;"></span>
                    <small id='person_checkin_scan_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="row full-row d-flex align-items-center justify-content-center">
        <div class="info-box p-3">
            <span class="info-box-icon text-bg-danger" style="width: 100px; height: 100px; font-size: 50px;">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content ml-4">
                <span class="info-box-text fw-bold text-muted" style="font-size: 20px;">Checkout</span>
                <span class="info-box-number">
                    <span id='checkout_statistic' style="font-size: 40px;"></span>
                    <small id='person_checkout_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="row full-row d-flex align-items-center justify-content-center">
        <div class="info-box p-3">
            <span class="info-box-icon text-bg-success" style="width: 100px; height: 100px; font-size: 50px;">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content ml-4">
                <span class="info-box-text fw-bold text-muted" style="font-size: 20px;">Current</span>
                <span class="info-box-number">
                    <span id='current_statistic' style="font-size: 40px;"></span>
                    <small id='person_current_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="row full-row d-flex align-items-center justify-content-center">
        <div class="info-box p-3">
            <span class="info-box-icon text-bg-info" style="width: 100px; height: 100px; font-size: 50px;">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content ml-4">
                <span class="info-box-text fw-bold text-muted" style="font-size: 20px;"><?= $gate_code ?></span>
                <span class="info-box-number">
                    <span id='gate_statistic' style="font-size: 40px;"></span>
                    <small id='person_gate_statistic'></small>
                </span>
            </div>
        </div>
    </div>
</div>

<?php start_section('page-style-partials-card-statistic'); ?>
    <style>
        html, body {
            height: 100%;
        }

        .full-row {
            height: 17vh;
        }
    </style>
<?php end_section('page-style-partials-card-statistic'); ?>

<?php start_section('page-script-partials-card-statistic'); ?>
    <script>
        load_statistic();
        function load_statistic() {
            var now = moment(new Date()).format('YYYY-MM-DD');
            var date_from = now;
            var date_to = now;
            
            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/load_scan_statistic') ?>/" + date_from + "/" + date_to + "/" + fn_building_code + "/" + fn_venue_code + "/" + fn_gate_code + "/" + 'null' + "/" + 'checkout',

                beforeSend: function() {
                    document.getElementById("checkin_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkin_statistic").innerHTML = "";

                    document.getElementById("checkin_scan_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkin_scan_statistic").innerHTML = "";

                    document.getElementById("checkout_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkout_statistic").innerHTML = "";

                    document.getElementById("current_statistic").innerHTML = "loading data...";
                    document.getElementById("person_current_statistic").innerHTML = "";

                    document.getElementById("gate_statistic").innerHTML = "loading data...";
                    document.getElementById("person_gate_statistic").innerHTML = "";
                },
                complete: function() {

                },
                success: function(data) {
                    document.getElementById("checkin_statistic").innerHTML = formatNumber(data.checkin_statistic, 0);
                    document.getElementById("person_checkin_statistic").innerHTML = "person";

                    document.getElementById("checkin_scan_statistic").innerHTML = formatNumber(data.checkin_scan_statistic, 0);
                    document.getElementById("person_checkin_scan_statistic").innerHTML = "person";

                    document.getElementById("checkout_statistic").innerHTML = formatNumber(data.checkout_statistic, 0);
                    document.getElementById("person_checkout_statistic").innerHTML = "person";

                    document.getElementById("current_statistic").innerHTML = formatNumber(data.current_statistic, 0);
                    document.getElementById("person_current_statistic").innerHTML = "person";

                    document.getElementById("gate_statistic").innerHTML = formatNumber(data.gate_statistic, 0);
                    document.getElementById("person_gate_statistic").innerHTML = "person";
                },
                error: function(xhr, status, error) {
                    showAlert('danger', error);
                    document.getElementById("checkin_statistic").innerHTML = error;
                    document.getElementById("checkin_scan_statistic").innerHTML = error;
                    document.getElementById("checkout_statistic").innerHTML = error;
                    document.getElementById("current_statistic").innerHTML = error;
                    document.getElementById("gate_statistic").innerHTML = error;

                    return;
                }
            });
        }
    </script>
<?php end_section('page-script-partials-card-statistic'); ?>