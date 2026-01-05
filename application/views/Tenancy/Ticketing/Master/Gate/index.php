<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Master/Gate/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <button type="button" class="btn" style="background-color:black; color:white; margin-bottom: 10px;"
                    onclick="location.href='<?=site_url('Tenancy/Ticketing/Master/Gate/create')?>'">
                    <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                </button>

                <div class="table-responsive">
                    <table id="tbl" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center text-capitalize">area</th>
                                <th class="text-center text-capitalize">location</th>
                                <th class="text-center text-capitalize">gate</th>
                                <th class="text-center text-capitalize">active</th>
                                <th class="text-center text-capitalize">updated</th>
                                <th class="text-center text-capitalize"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td class="text-capitalize"><?= ($row->venue ? ($row->venue->building ? $row->venue->building->code : '') : '') . ' - ' . ($row->venue ? ($row->venue->building ? $row->venue->building->name : '') : '') ?></td>
                                    <td class="text-capitalize"><?= ($row->venue ? $row->venue->code : '') . ' - ' .  ($row->venue ? $row->venue->name : '') ?></td>
                                    <td class="text-capitalize"><?= $row->code ?></td>
                                    <td class="text-capitalize text-center">
                                        <input type="checkbox"
                                            <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                        disabled>
                                    </td>

                                    <td class="text-capitalize"><?= time_ago($row->updated_at) ?></td>

                                    <td class="text-center">
                                        <a href="<?= site_url('Tenancy/Ticketing/Master/Gate/edit/'.$row->id); ?>" class="label label-warning">
                                            <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                        </a>

                                        <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Ticketing/Master/Gate/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
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
