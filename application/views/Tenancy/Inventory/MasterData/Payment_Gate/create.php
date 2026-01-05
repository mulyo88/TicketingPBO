<?php
    error_reporting(E_ALL);
?>

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
                        <form method="post" action="<?= site_url('Tenancy/Inventory/MasterData/Payment_Gate/store') ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="code" class="col-sm-2 control-label text-capitalize">payment gate</label>

                                            <div class="col-sm-10">
                                                <select name="payment_gate_id" id="payment_gate_id" class="form-control input-sm"
                                                    <?= invalid('payment_gate_id') ?>
                                                >
                                                    <?php foreach ($payment_gate_name as $row): ?>
                                                        <option value="<?= $row->code ?>" <?= old('payment_gate_id') == $row->id ? 'selected' : '' ?>><?=$row->name?></option>
                                                    <?php endforeach ?>
                                                </select>

                                                <?= error('payment_gate_id') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="key_id" class="col-sm-2 control-label text-capitalize">type</label>

                                            <div class="col-sm-10">
                                                <select name="key_id" id="key_id" class="form-control input-sm"
                                                    <?= invalid('key_id') ?>
                                                >
                                                    <?php foreach ($payment_gate_type as $row): ?>
                                                        <option value="<?=$row->id?>" <?= old('key_id') == $row->id ? 'selected' : '' ?>><?=$row->name?></option>
                                                    <?php endforeach ?>
                                                </select>

                                                <?= error('key_id') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="key" class="col-sm-2 control-label text-capitalize">key</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="key" name="key" placeholder="Key" required
                                                    <?= invalid('key') ?>
                                                    value="<?php echo old('key'); ?>"
                                                >
                                                <?= error('key') ?>
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
                                    onclick="location.href='<?=site_url('Tenancy/Inventory/MasterData/Payment_Gate/index')?>'"
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
