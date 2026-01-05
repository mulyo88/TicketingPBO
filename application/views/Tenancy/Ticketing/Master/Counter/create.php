<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Master/Counter/create') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="<?= site_url('Tenancy/Ticketing/Master/Counter/store') ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="area" class="col-sm-2 control-label text-capitalize">area</label>
                                            <div class="col-sm-10">
                                                <select name="area" id="area" class="form-control"
                                                    onchange="load_vanue(this.value);"
                                                    <?= invalid('area') ?>
                                                >
                                                    <?php foreach ($building as $row): ?>
                                                        <option value="<?= $row->code ?>"<?= old('area') == $row->code ? 'selected' : '' ?>><?= $row->code . ' - ' . $row->name ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <?= error('area') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="vanue_id" class="col-sm-2 control-label text-capitalize">location</label>
                                            <div class="col-sm-10">
                                                <select name="vanue_id" id="vanue_id" class="form-control"
                                                    <?= invalid('vanue_id') ?>
                                                >
                                                </select>
                                                <?= error('vanue_id') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="code" class="col-sm-2 control-label text-capitalize">code</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="code" name="code" placeholder="Code"
                                                    value="<?php echo old('code'); ?>" required
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
                                    onclick="location.href='<?=site_url('Tenancy/Ticketing/Master/Counter/index')?>'"
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

<script>
    load_vanue(document.getElementById("area").value);

    function load_vanue(value, id = <?php echo json_encode(old('vanue_id')); ?>) {
        document.getElementById("vanue_id").innerHTML = '';

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/load_venue') ?>/" + value,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                if (data) {
                    data.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id;
                        option.text = item.code + ' - ' + item.name;
                        if (id == item.id) { option.selected = true; }
                        document.getElementById("vanue_id").appendChild(option);
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(error);
                return;
            }
        });
    }
</script>
