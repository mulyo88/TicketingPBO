
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
                        <form method="post" action="<?= site_url('Tenancy/MasterData/Letter_Line/store') ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="party_type" class="col-sm-2 control-label text-capitalize">type</label>

                                            <div class="col-sm-10">
                                                <select name="party_type" id="party_type" class="form-control input-sm" onchange="selectParty()" required>
                                                    <option value="">-- Select this --</option>
                                                    <?php foreach ($common_code as $row): ?>
                                                        <option value="<?=$row->id?>"><?=$row->name?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="party_id" class="col-sm-2 control-label text-capitalize">company</label>

                                            <div class="col-sm-10">
                                                <select name="party_id" id="party_id" class="form-control input-sm" required>
                                                    <option value="">-- Select this --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label text-capitalize">name</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="position" class="col-sm-2 control-label text-capitalize">position</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="position" name="position" placeholder="Position" required>
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
                                    onclick="location.href='<?=site_url('Tenancy/MasterData/Letter_Line/index')?>'"
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
    function selectParty() {
        resetParty();
        var type = $("#party_type option:selected").text();
        if (document.getElementById("party_type").value == '') {
            return;
        }

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/get_party_by_type') ?>/" + type,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                if (data) {
                    var html = '<option value="">-- Select this --</option>';
                    for (let index = 0; index < data.length; index++) {
                        if (type == "TENANT") {
                            html += '<option value="' + data[index].id + '">' + data[index].brand + '</option>';
                        } else {
                            html += '<option value="' + data[index].id + '">' + data[index].name + '</option>';
                        }
                    }

                    document.getElementById("party_id").innerHTML = html;
                }
            },
            error: function(xhr, status, error) {
                alert(error);
                return;
            }
        });
    }

    function resetParty() {
        document.getElementById("party_id").innerHTML = '<option value="">-- Select this --</option>';
    }
</script>

