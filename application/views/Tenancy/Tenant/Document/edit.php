
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

        <form method="post" action="<?= site_url('Tenancy/Tenant/Document/update/'.$result->id) ?>" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-capitalize text-muted">tenant</h4>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="tenant_code" class="col-sm-3 control-label text-capitalize">code</label>

                                            <div class="col-sm-9">
                                                <input type="hidden" class="form-control" id="tenant_id" name="tenant_id" value="<?php echo $result->id; ?>">

                                                <input type="text" class="form-control" id="tenant_code" name="tenant_code" placeholder="Owner Code"
                                                value="<?php echo $result->code; ?>" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="owner" class="col-sm-3 control-label text-capitalize">owner</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="owner" name="owner" placeholder="Owner Name"
                                                value="<?php echo $result->owner; ?>" readonly required
                                            >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="brand" class="col-sm-3 control-label text-capitalize">brand</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand Name"
                                                value="<?php echo $result->brand; ?>" readonly required
                                            >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="product" class="col-sm-3 control-label text-capitalize">product</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="product" name="product" placeholder="Product Name"  value="<?php echo $result->product; ?>" readonly required
                                            >
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

                        <button type="submit" class="btn btn-warning pull-right" style="margin-right: 10px;">
                            <i class="fa fa-save"></i><span style="margin-left: 5px;">Update</span>
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
        var document_result = <?php echo json_encode($document_result); ?>;
        $tenant_result = <?php echo json_encode($result); ?>;
        $config = <?php echo json_encode($this->config->item("tnc_tenant")); ?>;
        $base_url = <?php echo json_encode($this->config->item("base_url")); ?>;
        
        var html = '';

        for (let index = 0; index < data.length; index++) {
            var x_file = "";
            for (let ii = 0; ii < document_result.length; ii++) {
                if (data[index].id == document_result[ii].common_id) {
                    x_file = document_result[ii].file;

                    break;
                }
            }

            html +='<div class="row" style="margin-left: 1px;">';
                html +='<div class="col">';
                    html +='<div class="form-group">';
                        html +='<label for="file[' + data[index].id + '][id]" class="col control-label text-capitalize">' + data[index].name + '</label>';

                        html +='<div class="col">';
                            html +='<input type="file" id="file[' + data[index].id + '][file]" name="file[]" class="form-control file-input" style="display:none;" />';

                            html +='<input type="text" id="file[' + data[index].id + '][document]" name="document[]" class="form-control" value="' + data[index].id + '" style="display:none;" />';
                            
                            html +='<button class="btn btn-sm btn-warning" type="button" style="margin-right: 5px;" onclick="clickFile(' + data[index].id + ')"><i class="fa fa-folder-open"></i></button>';

                            html +='<button class="btn btn-danger btn-sm" type="button" style="margin-right: 10px;" onclick="removeFile(' + data[index].id + ', `' + x_file + '`)"><i class="fa fa-trash"></i></button>';
                            
                            html +='<label id="file[' + data[index].id + '][filename]">';
                                if (x_file == "") {
                                    html +='<span class="text-danger">none</span>';
                                } else {
                                    const url = '<?php echo base_url() ?>' + '/' + $config + '/' + $tenant_result.id + '/' + x_file;
                                    html +='<a href="' + url + '" target="_blank">';
                                        html +='<span class="text-primary">' + x_file + '</span>';
                                    html +='</a>';
                                }
                            html +='</label>';
                        html +='</div>';
                    html +='</div>';
                html +='</div>';
            html +='</div>';
        }
        
        document.getElementById("document-rows").innerHTML = html;

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

    function removeFile(id, filename) {
        if (document.getElementById("file[" + id + "][file]").value == "" && document.getElementById("file[" + id + "][filename]").innerHTML != '<span class="text-danger">none</span>') {
            if (confirm("Are you sure ?, youre missing this file, we are delete to database!") == true) {
                var tenant_id = document.getElementById("tenant_id").value;

                $.ajax({
                    dataType: "json",
                    type: "GET",
                    url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/delete_tenant_document_file') ?>/" + tenant_id + "/" + id + "/" + filename,

                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(data) {
                        if (data == 'File deleted.') {
                            document.getElementById("file[" + id + "][file]").value = "";
                            document.getElementById("file[" + id + "][filename]").innerHTML = '<span class="text-danger">none</span>';
                        }

                        alert(data);
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        return;
                    }
                });
            }
        } else {
            document.getElementById("file[" + id + "][file]").value = "";
            document.getElementById("file[" + id + "][filename]").innerHTML = '<span class="text-danger">none</span>';
        }
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
