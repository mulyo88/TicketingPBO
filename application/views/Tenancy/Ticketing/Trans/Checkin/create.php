<?php extend('templates/boot5/ticketing/app'); ?>

<?php start_section('title'); ?>
    <?=($title) ? $title : 'Header not set'  ?>
<?php end_section('title'); ?>

<?php start_section('content'); ?>
    <?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/fn_input'); ?>
    <?php include_view('component/flash', ['flash_type' => 'danger', 'flash_title' => 'data not found!']); ?>

    <?php $flash = $this->session->flashdata('flash'); ?>
    <?php $print = $this->session->flashdata('print'); ?>
    <?php $old = $this->session->flashdata('_old_input'); ?>

    <form method="post" action="<?= site_url('Tenancy/Ticketing/Trans/Checkin/store/'.$building_code.'/'.$venue_code.'/'.$counter_code) ?>">
        <input type="hidden" name="building_id" id="building_id" value="<?php echo $building ? $building->id : null; ?>" required>
        <input type="hidden" name="venue_id" id="venue_id" value="<?php echo $venue ? $venue->id : null; ?>" required>
        <input type="hidden" name="counter_id" id="counter_id" value="<?php echo $counter ? $counter->id : null; ?>" required>

        <div class="row mt-2">
            <div class="col-lg-8">
                <div class="flex-grow-1 d-flex flex-column" id="main-content2">
                    <div class="m-2 mb-3" id="row-3" style="background-color: transparent;">
                        <?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/card-statistic'); ?>
                    </div>
                    
                    <div class="card border-0 m-2" id="row-2" style="background-color: #8800AD;">
                        <div class="flex-fill border-0 rounded-2 m-3" style="overflow: auto; overflow-x: hidden; background-color: transparent;">
                            <span class="text-light text-capitalize fw-bold" style="font-size: 25px;" id="item_data_info" hidden>data not found</span>
                            <?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/item'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="flex-grow-1 d-flex flex-column" id="main-content">
                    <div class="flex-fill card border-0 rounded-2 m-2" id="cart-row-1" style="background-color: #C100FF;">
                        <div class="d-flex flex-column overflow-auto m-1" id="cart-container">
                            <?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/cart'); ?>
                        </div>
                    </div>

                    <div class="flex-fill card border-0 rounded-2 mx-2" id="cart-row-2" style="background-color: #C100FF;">
                        <div class="d-flex flex-column m-4 text-light">
                            <?php include_view('Tenancy/Ticketing/Trans/Checkin/partials/sum'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php end_section('content'); ?>

<?php start_section('page-style'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/bootstrap5/DataTables/datatables.css">

    <?php yield_section('style-flash'); ?>
    <?php yield_section('page-style-partials-card-statistic'); ?>
    <?php yield_section('page-style-partials-item'); ?>
    <?php yield_section('page-style-partials-cart'); ?>
    <?php yield_section('page-style-partials-history'); ?>
    <?php yield_section('page-style-partials-sum'); ?>

    <style>
        #main-content {
            height: calc(100vh - 75px);
        }

        #cart-row-1 {
            height: 35%;
        }

        #cart-row-2 {
            height: 65%;
        }

        #main-content2 {
            height: calc(100vh - 75px);
        }

        #row-2 {
            height: 86%;
        }

        #row-3 {
            height: 8.5%;
        }

        .input-group .form-select {
            flex: 0 0 200px;
            max-width: 200px;
        }
    </style>
<?php end_section('page-style'); ?>

<?php start_section('page-script'); ?>
    <script src="<?php echo base_url() ?>assets/bootstrap5/DataTables/datatables.js"></script>
    <script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
    <script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
    <script src="<?php echo base_url() ?>assets/external/limit-string.js"></script>
    <script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
    <script src="<?php echo base_url() ?>assets/external/format-fixed.js"></script>
    <script src="<?php echo base_url() ?>assets/external/wav/beep-warning.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>

    <?php yield_section('script-flash'); ?>
    <?php yield_section('page-script-fn-input'); ?>
    <?php yield_section('page-script-partials-sum'); ?>
    <?php yield_section('page-script-partials-cart'); ?>
    <?php yield_section('page-script-partials-history'); ?>
    <?php yield_section('page-script-partials-item'); ?>
    <?php yield_section('page-script-partials-card-statistic'); ?>

    <script>
        const flashData = <?= json_encode($flash ?? null); ?>;
        const printData = <?= json_encode($print ?? null); ?>;

        if (flashData) {
            showAlert(flashData.type, flashData.message);
        }
        
        if (printData) {
            if (printData.is_print) {
                printStruk(printData.id)
            }
        }

        function printStruk(id) {
            const w = 750;
            const h = 600;

            const left = (screen.width / 2) - (w / 2);
            const top  = (screen.height / 2) - (h / 2);

            const win = window.open(
                "<?= site_url('Tenancy/Ticketing/Trans/Checkin/print_struk/') ?>" + id,
                "_blank",
                `width=${w},height=${h},top=${top},left=${left}`
            );

            if (win) {
                win.focus();
            } else {
                alert('Popups are blocked by the browser. Allow popups to print receipts.');
            }
        }

        function reload_data() {
            if (confirm("Are you sure?")) {
                if (fn_input_type == 'create') {
                    location.href = "<?= site_url('Tenancy/Ticketing/Trans/Checkin/create/') ?>" + fn_building.code + '/' + fn_venue.code + '/' + fn_counter.code;
                } else if (fn_input_type == 'edit') {
                    location.href = "<?= site_url('Tenancy/Ticketing/Trans/Checkin/edit/') ?>" + fn_result.id;
                }
            }
        }
    </script>
<?php end_section('page-script'); ?>

<?php render_template(); ?>
