
<?php
    error_reporting(E_ALL);
?>

<style type="text/css">
	.marginatas5{
		margin-top: 5px;
	}
</style>

<script src="<?php echo base_url() ?>assets/external/format-number.js"></script>
<script src="<?php echo base_url() ?>assets/external/format-decimal.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="post" action="<?= site_url('Tenancy/MasterData/Property/Unit/update/'.$result->id) ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box-body">
                                    <h4 class="text-muted" style="font-weight: bold;">Basic Data</h4>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="building_id" class="col-sm-2 control-label text-capitalize">building</label>

                                                <div class="col-sm-10">
                                                    <select name="building_id" id="building_id" class="form-control input-sm">
                                                        <option value="">-- Select this --</option>
                                                        <?php foreach ($buildings as $row): ?>
                                                            <option value="<?= $row->id ?>" <?= $row->id == $result->building_id ? 'selected' : ''; ?>><?= $row->name ?></option>

                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="code" class="col-sm-2 control-label text-capitalize">code</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="code" name="code" placeholder="Unit Code"
                                                        value="<?php echo $result->code; ?>" required
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label text-capitalize">unit</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Unit Name"
                                                    value="<?php echo $result->name; ?>" required
                                                >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit_type" class="col-sm-2 control-label text-capitalize">type</label>

                                                <div class="col-sm-10">
                                                    <select name="unit_type" id="unit_type" class="form-control input-sm">
                                                        <option value="">-- Select this --</option>
                                                        <option value="Indoor" <?php echo $result->unit_type == 'Indoor' ? 'selected' : ''; ?>>Indoor</option>
                                                        <option value="Outdoor" <?php echo $result->unit_type == 'Outdoor' ? 'selected' : ''; ?>>Outdoor</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="unit_size" class="col-sm-2 control-label text-capitalize">size</label>

                                                <div class="col-sm-10">
                                                    <input type="number" step="any" class="form-control" id="unit_size" name="unit_size" placeholder="Size"
                                                        value="<?php echo number_format((float)$result->unit_size, 2, '.', ''); ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="basic_price" class="col-sm-2 control-label text-capitalize">Basic Price</label>

                                                <div class="col-sm-10">
                                                    <input type="number" step="any" class="form-control" id="basic_price" name="basic_price" placeholder="Basic Price"
                                                        value="<?php echo number_format((float)$result->basic_price, 2, '.', ''); ?>"
                                                        required
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="note" class="col-sm-2 control-label text-capitalize">note</label>

                                                <div class="col-sm-10">
                                                    <textarea id="note" name="note" class="form-control" rows="3" placeholder="Note">
                                                        <?php echo $result->note; ?>
                                                    </textarea>
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
                                                            <input type="checkbox" id="is_active" name="is_active" checked
                                                                <?php echo $result->is_active == 1 ? 'checked' : ''; ?>
                                                            > Is Active
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>

                            <div class="col-md-6">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#facility">Facility
                                            <span class="label label-success" id="total-facility"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#owner">Owner Property
                                            <span class="label label-success" id="total-owner"></span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div id="facility" class="tab-pane fade in active">
                                        <?php $this->view('Tenancy/MasterData/Property/Unit/partials/facility'); ?>
                                    </div>

                                    <div id="owner" class="tab-pane fade">
                                        <?php $this->view('Tenancy/MasterData/Property/Unit/partials/owner'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="button" class="btn pull-right" style="background-color:black; color:white;"
                            onclick="location.href='<?=site_url('Tenancy/MasterData/Property/Unit/index')?>'"
                        >
                            <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
                        </button>

                        <button type="submit" class="btn btn-warning pull-right" style="margin-right: 10px;">
                            <i class="fa fa-save"></i><span style="margin-left: 5px;">Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <?php $this->view('Tenancy/MasterData/Property/Unit/partials/modal/unit'); ?>
        <?php $this->view('Tenancy/MasterData/Property/Unit/partials/modal/unit-facility'); ?>
        <?php $this->view('Tenancy/MasterData/Property/Unit/partials/modal/owner'); ?>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#tbl').DataTable();
    });
</script>



