
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

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="<?= site_url('Tenancy/MasterData/Bank/update/'.$result->id) ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="account_code" class="col-sm-2 control-label text-capitalize">code</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="account_code" name="account_code" placeholder="Account Code"
                                                    value="<?php echo old('account_code') ? old('account_code') : $result->account_code; ?>" required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="account_name" class="col-sm-2 control-label text-capitalize">name</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Account Name"
                                                    value="<?php echo old('account_name') ? old('account_name') : $result->account_name; ?>" required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="owner_name" class="col-sm-2 control-label text-capitalize">owner</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Owner Name"
                                                    value="<?php echo old('owner_name') ? old('owner_name') : $result->owner_name; ?>" required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="office_name" class="col-sm-2 control-label text-capitalize">office</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="office_name" name="office_name" placeholder="Office Name"
                                                    value="<?php echo old('office_name') ? old('office_name') : $result->office_name; ?>" required
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
                                                    <?php echo old('note') ? old('note') : $result->note; ?>
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
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" class="btn pull-right" style="background-color:black; color:white; margin-right: 15px;"
                                    onclick="location.href='<?=site_url('Tenancy/MasterData/Bank/index')?>'"
                                >
                                    <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
                                </button>

                                <button type="submit" class="btn btn-warning pull-right" style="margin-right: 10px;">
                                    <i class="fa fa-save"></i><span style="margin-left: 5px;">Update</span>
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
        $('#tbl').DataTable();
    });
</script>



