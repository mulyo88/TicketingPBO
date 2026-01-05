<?php extend('templates/boot5/ticketing/app'); ?>

<?php start_section('title'); ?>
    <?=($title) ? $title : 'Header not set'  ?>
<?php end_section('title'); ?>

<?php start_section('content'); ?>
    <?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/fn_input'); ?>
    <?php include_view('component/flash', ['flash_type' => 'danger', 'flash_title' => 'data not found!']); ?>

    <?php $flash = $this->session->flashdata('flash'); ?>
    <?php $print = $this->session->flashdata('print'); ?>
    <?php $old = $this->session->flashdata('_old_input'); ?>

    <form method="post" action="<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/store/'.$building_code.'/'.$venue_code.'/'.$gate_code) ?>">
        <input type="hidden" name="building_id" id="building_id" value="<?php echo $building ? $building->id : null; ?>" required>
        <input type="hidden" name="venue_id" id="venue_id" value="<?php echo $venue ? $venue->id : null; ?>" required>
        <input type="hidden" name="gate_id" id="gate_id" value="<?php echo $gate ? $gate->id : null; ?>" required>

        <div class="p-2">
            <div class="row">
                <div class="col-md-8">
                    <div class="m-2 mb-2">
                        <?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/search'); ?>
                    </div>

                    <div class="m-2 mb-2">
                        <?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/information'); ?>

                        <h3>Log:</h3>
                        <div id="log" class="rounded" style="background-color: rgba(255, 255, 255, 0.5);"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/card-statistic'); ?>
                </div>
            </div>

            <div class="m-2 mb-2">
                <?php include_view('Tenancy/Ticketing/Trans/Checkin_Scan/partials/comunication'); ?>
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
    <?php yield_section('page-style-partials-search'); ?>
    <?php yield_section('page-style-partials-information'); ?>
    <?php yield_section('page-style-partials-comunication'); ?>

    <style>
        #log { margin-top: 20px; white-space: pre-line; background: #f3f3f3; padding: 10px; height: 200px; overflow-y: auto; }
    </style>
<?php end_section('page-style'); ?>

<?php start_section('page-script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

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
    <?php yield_section('page-script-partials-card-statistic'); ?>
    <?php yield_section('page-script-partials-search'); ?>
    <?php yield_section('page-script-partials-information'); ?>
    <?php yield_section('page-script-partials-comunication'); ?>

    <script>
        const flashData = <?= json_encode($flash ?? null); ?>;
        const printData = <?= json_encode($print ?? null); ?>;

        if (flashData) {
            showAlert(flashData.type, flashData.message);
        }

        function reload_data() {
            if (confirm("Are you sure?")) {
                if (fn_input_type == 'create') {
                    location.href = "<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/create/') ?>" + fn_building.code + '/' + fn_venue.code + '/' + fn_gate.code;
                } else if (fn_input_type == 'edit') {
                    location.href = "<?= site_url('Tenancy/Ticketing/Trans/Checkin_Scan/edit/') ?>" + fn_result.id;
                }
            }
        }
    </script>
<?php end_section('page-script'); ?>

<?php render_template(); ?>
