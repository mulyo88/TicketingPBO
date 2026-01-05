
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
                <!-- <button type="button" class="btn" style="background-color:black; color:white; margin-bottom: 10px;"
                    onclick="location.href='<?=site_url('Tenancy/Tenant/Document/create')?>'">
                    <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                </button> -->

                <div class="table-responsive">
                    <table id="tbl" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center text-capitalize">code</th>
                                <th class="text-center text-capitalize">owner</th>
                                <th class="text-center text-capitalize">brand</th>
                                <th class="text-center text-capitalize">product</th>
                                <th class="text-center text-capitalize">document</th>
                                <th class="text-center text-capitalize">created</th>
                                <th class="text-center text-capitalize">updated</th>
                                <th class="text-center text-capitalize"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td class="text-capitalize text-center"><?= $row->code ?></td>
                                    <td class="text-capitalize"><?= $row->owner ?></td>
                                    <td class="text-capitalize"><?= $row->brand ?></td>
                                    <td class="text-capitalize"><?= $row->product ?></td>
                                    <td class="text-capitalize text-right
                                        <?= $row->document_progress >= 0 && $row->document_progress <= 50 ? 'text-danger' : ($row->document_progress > 50 && $row->document_progress < 100 ? 'text-warning' : 'text-success') ?>
                                    " style="font-weight: bold;">
                                        <?= number_format($row->document_progress, 2, '.', ',') ?> %
                                    </td>
                                    <td class="text-capitalize">
                                        <?= $row->updated_at ? date("d-M-Y H:i:s", strtotime($row->created_at)) : '' ?>
                                    </td>

                                    <td class="text-capitalize">
                                        <?= $row->updated_at ? date("d-M-Y H:i:s", strtotime($row->updated_at)) : '' ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= site_url('Tenancy/Tenant/Document/edit/'.$row->id); ?>" class="label label-warning">
                                            <i class="fa fa-edit"></i><span style="margin-left: 5px;">Update</span>
                                        </a>

                                        <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Tenant/Document/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
                                            <i class="fa fa-trash"></i><span style="margin-left: 5px;">Delete</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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



