<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-20">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Cashier</span>
                <span class="info-box-number">
                    <span id='checkin_statistic'></span>
                    <small id='person_checkin_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-20">
        <div class="info-box">
            <span class="info-box-icon text-bg-warning">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Checkin</span>
                <span class="info-box-number">
                    <span id='checkin_scan_statistic'></span>
                    <small id='person_checkin_scan_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-20">
        <div class="info-box">
            <span class="info-box-icon text-bg-danger">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Checkout</span>
                <span class="info-box-number">
                    <span id='checkout_statistic'></span>
                    <small id='person_checkout_statistic'></small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-20">
        <div class="info-box">
            <span class="info-box-icon text-bg-success">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Current</span>
                <span class="info-box-number">
                    <span id='current_statistic'></span>
                    <small id='person_current_statistic'></small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-xs-6 col-sm-6 col-md-20">
        <div class="info-box">
            <span class="info-box-icon text-bg-info">
                <i class="bi bi-people-fill"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text"><?= $counter_code ?></span>
                <span class="info-box-number">
                    <span id='counter_statistic'></span>
                    <small id='person_counter_statistic'></small>
                </span>
            </div>
        </div>
    </div>
</div>

<?php start_section('page-style-partials-card-statistic'); ?>
    <style>
        @media (min-width: 992px) {
            .col-md-20 {
                width: 20%;
                float: left;
            }
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
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/load_scan_statistic') ?>/" + date_from + "/" + date_to + "/" + fn_building_code + "/" + fn_venue_code + "/" + 'null' + "/" + fn_counter_code + "/" + 'cashier',

                beforeSend: function() {
                    document.getElementById("checkin_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkin_statistic").innerHTML = "";

                    document.getElementById("checkin_scan_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkin_scan_statistic").innerHTML = "";

                    document.getElementById("checkout_statistic").innerHTML = "loading data...";
                    document.getElementById("person_checkout_statistic").innerHTML = "";

                    document.getElementById("current_statistic").innerHTML = "loading data...";
                    document.getElementById("person_current_statistic").innerHTML = "";

                    document.getElementById("counter_statistic").innerHTML = "loading data...";
                    document.getElementById("person_counter_statistic").innerHTML = "";
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

                    document.getElementById("counter_statistic").innerHTML = formatNumber(data.gate_statistic, 0);
                    document.getElementById("person_counter_statistic").innerHTML = "person";
                },
                error: function(xhr, status, error) {
                    showAlert('danger', error);
                    document.getElementById("checkin_statistic").innerHTML = error;
                    document.getElementById("checkin_scan_statistic").innerHTML = error;
                    document.getElementById("checkout_statistic").innerHTML = error;
                    document.getElementById("current_statistic").innerHTML = error;
                    document.getElementById("counter_statistic").innerHTML = error;

                    return;
                }
            });
        }
    </script>
<?php end_section('page-script-partials-card-statistic'); ?>