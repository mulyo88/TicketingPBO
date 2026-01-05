
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
                <button type="button" class="btn" style="background-color:black; color:white; margin-bottom: 10px;"
                    onclick="location.href='<?=site_url('Tenancy/MasterData/Property/Unit/create')?>'">
                    <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                </button>

                <div class="table-responsive">
                    <table id="tbl" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center text-capitalize">building</th>
                                <th class="text-center text-capitalize">code</th>
                                <th class="text-center text-capitalize">name</th>
                                <th class="text-center text-capitalize">type</th>
                                <th class="text-center text-capitalize">size</th>
                                <th class="text-center text-capitalize">basic price</th>
                                <th class="text-center text-capitalize">status</th>
                                <th class="text-center text-capitalize">active</th>
                                <th class="text-center text-capitalize">updated</th>
                                <th class="text-center text-capitalize"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td class="text-capitalize"><?= $row->building_name ?></td>
                                    <td class="text-capitalize text-center"><?= $row->code ?></td>
                                    <td class="text-capitalize"><?= $row->name ?></td>
                                    <td class="text-capitalize"><?= $row->unit_type ?></td>
                                    <td class="text-capitalize"><?= number_format($row->unit_size, 2, '.', ',') ?> m<sup>2</sup></td>
                                    <td class="text-capitalize text-right"><?= number_format($row->basic_price, 2, '.', ',') ?></td>
                                    <td class="text-center">
                                        <span class="label" style="margin-right: 2px; background-color: 
                                            <?php if ($row->contract) { ?>
                                                <?php if ($row->contract->tenant) { ?>
                                                    #FF0000
                                                <?php } else { ?>
                                                    #1AAB00
                                                <?php } ?>
                                            <?php } else { ?>
                                                #1AAB00
                                            <?php } ?>
                                        ;">
                                            <?= $row->contract ? ($row->contract->tenant ? $row->contract->tenant->code : 'Vacant') : 'Vacant' ?>
                                        </span>
                                    </td>
                                    <td class="text-capitalize text-center">
                                        <input type="checkbox"
                                            <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                        disabled>
                                    </td>

                                    <td class="text-capitalize"><?= date("d-M-Y H:i:s", strtotime($row->updated_at)) ?></td>

                                    <td class="text-center">
                                        <a href="<?= site_url('Tenancy/MasterData/Property/Unit/edit/'.$row->id); ?>" class="label label-warning">
                                            <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                        </a>

                                        <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/MasterData/Property/Unit/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
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



