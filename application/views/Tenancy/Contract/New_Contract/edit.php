
<?php
    error_reporting(E_ALL);
?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>
<script src="<?php echo base_url() ?>assets/external/remove-format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/set-string-hour-to-datetime.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<?php $this->load->view('Tenancy/Contract/New_Contract/partials/fn_input'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/modal/building'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/modal/tenant'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/modal/bank'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/modal/unit'); ?>

<?php $this->load->view('Tenancy/Contract/New_Contract/partials/header'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/detail'); ?>
<?php $this->load->view('Tenancy/Contract/New_Contract/partials/summary'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="post" action="<?= site_url('Tenancy/Contract/New_Contract/update/'.$result->id) ?>">
            <input type="hidden" name="idx_token" value="idx_token">
            <?php yield_section('view_header'); ?>

            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <?php yield_section('view_detail'); ?>
                        </div>

                        <div class="col-md-3">
                            <?php yield_section('view_summary'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php yield_section('view_modal_building'); ?>
        <?php yield_section('view_modal_tenant'); ?>
        <?php yield_section('view_modal_bank'); ?>
        <?php yield_section('view_modal_unit'); ?>
    </section>
</div>

<?php yield_section('script_fn_input'); ?>
<?php yield_section('script_modal_building'); ?>
<?php yield_section('script_modal_tenant'); ?>
<?php yield_section('script_modal_bank'); ?>
<?php yield_section('script_modal_unit'); ?>

<?php yield_section('script_header'); ?>
<?php yield_section('script_summary'); ?>
<?php yield_section('script_detail'); ?>
