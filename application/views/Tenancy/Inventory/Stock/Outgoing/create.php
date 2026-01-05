<?php include_view('Tenancy/Inventory/Stock/Outgoing/partials/fn_input'); ?>
<?php include_view('Tenancy/Inventory/Stock/Outgoing/partials/header'); ?>
<?php include_view('Tenancy/Inventory/Stock/Outgoing/partials/detail'); ?>
<?php include_view('Tenancy/Inventory/Stock/Outgoing/partials/modal/item'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="post" action="<?= site_url('Tenancy/Inventory/Stock/Outgoing/store') ?>">
            <?php yield_section('page-content-header'); ?>
            <?php yield_section('page-content-detail'); ?>
        </form>
    </section>

    <?php yield_section('page-content-modal-item'); ?>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/set-string-hour-to-datetime.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/external/wav/beep-warning.js"></script>

<?php yield_section('page-script-fn-input'); ?>
<?php yield_section('page-script-content-modal-item'); ?>

<?php yield_section('page-script-header'); ?>
<?php yield_section('page-script-detail'); ?>
<script></script>