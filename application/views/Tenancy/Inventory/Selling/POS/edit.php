<?php extend('templates/boot5/pos/app'); ?>

<?php start_section('title'); ?>
    <?=($title) ? $title : 'Header not set'  ?>
<?php end_section('title'); ?>

<?php start_section('content'); ?>
    <?php include_view('Tenancy/Inventory/Selling/POS/partials/fn_input'); ?>
    <?php include_view('component/flash', ['flash_type' => 'danger', 'flash_title' => 'data not found!']); ?>

    <?php $flash = $this->session->flashdata('flash'); ?>
    <?php $print = $this->session->flashdata('print'); ?>
    <?php $old = $this->session->flashdata('_old_input'); ?>
    
    <form method="post" action="<?= site_url('Tenancy/Inventory/Selling/POS/update/'.$result->id) ?>">
        <input type="hidden" name="building_id" id="building_id" value="<?php echo $building ? $building->id : null; ?>" required>
        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer ? $customer->id : null; ?>" required>

        <div class="row mt-2">
            <div class="col-lg-8">
                <div class="flex-grow-1 d-flex flex-column" id="main-content2">
                    <div class="card border-0 m-2" id="row-1" style="background-color: transparent;">
                        <?php include_view('Tenancy/Inventory/Selling/POS/partials/search'); ?>
                    </div>

                    <div class="card border-0 m-2" id="row-2" style="background-color: transparent;">
                        <span class="text-danger text-capitalize fw-bold" style="font-size: 25px;" id="item_data_info" hidden>data not found</span>

                        <div class="flex-fill card border-0 rounded-2 m-1" style="overflow: auto; overflow-x: hidden; background-color: transparent;">
                            <?php include_view('Tenancy/Inventory/Selling/POS/partials/item'); ?>
                        </div>
                    </div>

                    <div class="card border-0 m-2" id="row-3" style="background-color: transparent;">
                        <div class="flex-fill card border-0 rounded-2 m-1" style="overflow: auto; overflow-x: hidden; background-color: transparent;">
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-2 row-cols-xl-5" style="background-color: transparent;">
                                <?php include_view('Tenancy/Inventory/Selling/POS/partials/category'); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="flex-grow-1 d-flex flex-column" id="main-content">
                    <div class="flex-fill card border-0 rounded-2 m-2" id="cart-row-1" style="background-color: #C100FF;">
                        <div class="d-flex flex-column overflow-auto m-1" id="cart-container">
                            <?php include_view('Tenancy/Inventory/Selling/POS/partials/cart'); ?>
                        </div>
                    </div>

                    <div class="flex-fill card border-0 rounded-2 mx-2" id="cart-row-2" style="background-color: #C100FF;">
                        <div class="d-flex flex-column m-4 text-light">
                            <?php include_view('Tenancy/Inventory/Selling/POS/partials/sum'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php end_section('content'); ?>

<?php start_section('page-style'); ?>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/bootstrap5/DataTables/datatables.css">

    <?php yield_section('style-flash'); ?>
    <?php yield_section('page-style-partials-item'); ?>
    <?php yield_section('page-style-partials-category'); ?>
    <?php yield_section('page-style-partials-cart'); ?>

    <style>
        #main-content {
            height: calc(100vh - 70px);
        }

        #cart-row-1 {
            height: 35%;
        }

        #cart-row-2 {
            height: 55%;
        }

        #main-content2 {
            height: calc(100vh - 70px);
        }

        #row-1 {
            height: 5%;
        }

        #row-2 {
            height: 65%;
        }

        #row-3 {
            height: 25%;
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
    <?php yield_section('page-script-partials-search'); ?>
    <?php yield_section('page-script-partials-category'); ?>
    <?php yield_section('page-script-partials-item'); ?>
    <?php yield_section('page-script-partials-cart'); ?>

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
                "<?= site_url('Tenancy/Inventory/Selling/POS/print_struk/') ?>" + id,
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
                    location.href = "<?= site_url('Tenancy/Inventory/Selling/POS/create/') ?>" + fn_building.code;
                } else if (fn_input_type == 'edit') {
                    location.href = "<?= site_url('Tenancy/Inventory/Selling/POS/edit/') ?>" + fn_result.id;
                }
            }
        }
    </script>
<?php end_section('page-script'); ?>

<?php render_template(); ?>
