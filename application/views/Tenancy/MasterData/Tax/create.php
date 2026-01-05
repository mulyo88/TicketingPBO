
<?php
    error_reporting(E_ALL);
?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="<?= site_url('Tenancy/MasterData/Tax/store') ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="tax" class="col-sm-2 control-label text-capitalize">tax (%)</label>

                                            <div class="col-sm-10">
                                                <input type="number" any="step" class="form-control" id="tax" name="tax" placeholder="Tax" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="date_register" class="col-sm-2 control-label text-capitalize">register</label>

                                            <div class="col-sm-10">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right" id="date_register" name="date_register" placeholder="dd-MMM-yyyy" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="note" class="col-sm-2 control-label text-capitalize">note</label>

                                            <div class="col-sm-10">
                                                <textarea id="note" name="note" class="form-control" rows="3" placeholder="Note"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="is_active" name="is_active" checked> Is Active
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" class="btn pull-right" style="background-color:black; color:white; margin-right: 15px;"
                                    onclick="location.href='<?=site_url('Tenancy/MasterData/Tax/index')?>'"
                                >
                                    <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
                                </button>

                                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">
                                    <i class="fa fa-save"></i><span style="margin-left: 5px;">Save</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        document.getElementById("date_register").value = moment(new Date()).format('DD-MMM-YYYY');

        $('#date_register').datepicker({
            autoclose: true,
            format: 'dd-M-yyyy'
        });
    });
</script>



