
<?php
    error_reporting(E_ALL);
?>

<style type="text/css">
	.marginatas5{
		margin-top: 5px;
	}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="post" action="<?= site_url('Tenancy/Inventory/Buying/Supplier/update/'.$result->id) ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="code" class="col-sm-3 control-label text-capitalize">code</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="code" name="code" placeholder="Code"
                                                    value="<?php echo old('code') ? old('code') : $result->code; ?>" required
                                                    <?= invalid('code') ?>
                                                >
                                                <?= error('code') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-3 control-label text-capitalize">company name</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Company Name"
                                                    value="<?php echo old('name') ? old('name') : $result->name; ?>" required
                                                    <?= invalid('name') ?>
                                                >
                                                <?= error('name') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="telp" class="col-sm-3 control-label text-capitalize">telp</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="telp" name="telp" placeholder="Phone Number"
                                                    value="<?php echo old('telp') ? old('telp') : $result->telp; ?>" required
                                                    <?= invalid('telp') ?>
                                                >
                                                <?= error('telp') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="email" class="col-sm-3 control-label text-capitalize">email</label>

                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                                    value="<?php echo old('email') ? old('email') : $result->email; ?>" required
                                                    <?= invalid('email') ?>
                                                >
                                                <?= error('email') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address" class="col-sm-3 control-label text-capitalize">address</label>

                                            <div class="col-sm-9">
                                                <textarea name="address" id="address" name="address" class="form-control" rows="3" placeholder="Address">
                                                    <?php echo old('address') ? old('address') : $result->address; ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="note" class="col-sm-3 control-label text-capitalize">note</label>

                                            <div class="col-sm-9">
                                                <textarea id="note" name="note" class="form-control" rows="3" placeholder="Note">
                                                    <?php echo old('note') ? old('note') : $result->note; ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="is_active" name="is_active"
                                                            <?php echo $result->is_active == 1 ? 'checked' : ''; ?>
                                                        > Is Active
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" class="btn pull-right" style="background-color:black; color:white; margin-right: 15px;"
                            onclick="location.href='<?=site_url('Tenancy/Inventory/Buying/Supplier/index')?>'"
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
    </section>
</div>



