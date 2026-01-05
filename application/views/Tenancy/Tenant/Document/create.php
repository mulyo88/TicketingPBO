
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

        <form method="post" action="<?= site_url('Tenancy/Tenant/Document/store') ?>" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-capitalize text-muted">tenant</h4>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="code" class="col-sm-3 control-label text-capitalize">code</label>

                                            <div class="col-sm-9">
                                                <select name="tenant_id" id="tenant_id" class="form-control input-sm" onchange="select_tenant()" required>
                                                    <option value="">-- Select this --</option>
                                                    <?php foreach ($tenant as $row): ?>
                                                        <option value="<?=$row->id?>"><?=$row->code?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="owner" class="col-sm-3 control-label text-capitalize">owner</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="owner" name="owner" placeholder="Owner Name" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="brand" class="col-sm-3 control-label text-capitalize">brand</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand Name" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="product" class="col-sm-3 control-label text-capitalize">product</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="product" name="product" placeholder="Product Name"  readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="text-capitalize text-muted">document</h4>
                                <div id="document-rows"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" class="btn pull-right" style="background-color:black; color:white; margin-right: 15px;"
                            onclick="location.href='<?=site_url('Tenancy/Tenant/Document/index')?>'"
                        >
                            <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
                        </button>

                        <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">
                            <i class="fa fa-save"></i><span style="margin-left: 5px;">Save</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var data = <?php echo json_encode($common_code); ?>;
        var html = '';
        for (let index = 0; index < data.length; index++) {
            html +='<div class="row" style="margin-left: 1px;">';
                html +='<div class="col">';
                    html +='<div class="form-group">';
                        html +='<label for="file[' + data[index].id + '][id]" class="col control-label text-capitalize">' + data[index].name + '</label>';

                        html +='<div class="col">';
                            html +='<input type="file" id="file[' + data[index].id + '][file]" name="file[]" class="form-control file-input" style="display:none;" />';

                            html +='<input type="text" id="file[' + data[index].id + '][document]" name="document[]" class="form-control" value="' + data[index].id + '" style="display:none;" />';
                            
                            html +='<button class="btn btn-sm btn-warning" type="button" style="margin-right: 5px;" onclick="clickFile(' + data[index].id + ')"><i class="fa fa-folder-open"></i></button>';

                            html +='<button class="btn btn-danger btn-sm" type="button" style="margin-right: 10px;" onclick="removeFile(' + data[index].id + ')"><i class="fa fa-trash"></i></button>';
                            
                            html +='<label id="file[' + data[index].id + '][filename]"><span class="text-danger">none</span></label>';
                        html +='</div>';
                    html +='</div>';
                html +='</div>';
            html +='</div>';
        }
        
        document.getElementById("document-rows").innerHTML=html;

        $('.file-input').on('change', function(){
            const file = this.files[0];
            var id = this.id.replace(/[^0-9]/g, '');

            const fileURL = URL.createObjectURL(file);
            var html = "";
            html +='<a href="' + fileURL + '" target="_blank">';
                html +='<span class="text-primary">' + file.name + '</span>';
            html +='</a>';

            document.getElementById("file[" + id + "][filename]").innerHTML = html;
        });
    });

    function clickFile(id) {
        document.getElementById("file[" + id + "][file]").click();
    }

    function removeFile(id) {
        document.getElementById("file[" + id + "][file]").value = "";
        document.getElementById("file[" + id + "][filename]").innerHTML = '<span class="text-danger">none</span>';
    }

    function select_tenant() {
        document.getElementById("owner").value = "";
        document.getElementById("brand").value = "";
        document.getElementById("product").value = "";

        if (document.getElementById("document-rows").value != "") {
            var data = <?php echo json_encode($tenant); ?>;
            for (let index = 0; index < data.length; index++) {
                if (data[index].id == document.getElementById("tenant_id").value) {
                    document.getElementById("owner").value = data[index].owner;
                    document.getElementById("brand").value = data[index].brand;
                    document.getElementById("product").value = data[index].product;

                    return;
                }
            }
        }
    }
</script>
