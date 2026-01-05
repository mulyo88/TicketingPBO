<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Master/Ticket/index') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <button type="button" class="btn" style="background-color:black; color:white; margin-bottom: 10px;"
                    onclick="location.href='<?=site_url('Tenancy/Ticketing/Master/Ticket/create')?>'">
                    <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                </button>

                <div class="table-responsive">
                    <table id="tbl" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center text-capitalize" rowspan="2">area</th>
                                <th class="text-center text-capitalize" rowspan="2">location</th>
                                <th class="text-center text-capitalize" rowspan="2">category</th>
                                <th class="text-center text-capitalize" rowspan="2">type</th>
                                <th class="text-center text-capitalize" rowspan="2">party</th>
                                <th class="text-center text-capitalize" rowspan="2">name</th>
                                
                                <th class="text-center text-capitalize" colspan="4">monday</th>
                                <th class="text-center text-capitalize" colspan="4">tuesday</th>
                                <th class="text-center text-capitalize" colspan="4">wednesday</th>
                                <th class="text-center text-capitalize" colspan="4">thursday</th>
                                <th class="text-center text-capitalize" colspan="4">friday</th>
                                <th class="text-center text-capitalize" colspan="4">saturday</th>
                                <th class="text-center text-capitalize" colspan="4">sunday</th>

                                <th class="text-center text-capitalize" rowspan="2">tax</th>
                                <th class="text-center text-capitalize" rowspan="2">active</th>
                                <th class="text-center text-capitalize" rowspan="2">updated</th>
                                <th class="text-center text-capitalize" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>

                                <th class="text-center text-capitalize">price (COGS)</th>
                                <th class="text-center text-capitalize">disc</th>
                                <th class="text-center text-capitalize">amount</th>
                                <th class="text-center text-capitalize">promote</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td class="text-capitalize"><?= $row->area ?></td>
                                    <td class="text-capitalize"><?= $row->location_code ?></td>
                                    <td class="text-capitalize"><?= $row->category ?></td>
                                    <td class="text-capitalize"><?= $row->type ?></td>
                                    <td class="text-capitalize"><?= $row->party ?></td>
                                    <td class="text-capitalize"><?= $row->name ?></td>

                                    <?php
                                        $color_active_monday = '';
                                        $color_active_tuesday = '';
                                        $color_active_wednesday = '';
                                        $color_active_thursday = '';
                                        $color_active_friday = '';
                                        $color_active_saturday = '';
                                        $color_active_sunday = '';

                                        if ($row->is_active_monday == 0) { $color_active_monday = 'bg-danger'; }
                                        if ($row->is_active_tuesday == 0) { $color_active_tuesday = 'bg-danger'; }
                                        if ($row->is_active_wednesday == 0) { $color_active_wednesday = 'bg-danger'; }
                                        if ($row->is_active_thursday == 0) { $color_active_thursday = 'bg-danger'; }
                                        if ($row->is_active_friday == 0) { $color_active_friday = 'bg-danger'; }
                                        if ($row->is_active_saturday == 0) { $color_active_saturday = 'bg-danger'; }
                                        if ($row->is_active_sunday == 0) { $color_active_sunday = 'bg-danger'; }
                                    ?>

                                    <td class="text-capitalize text-right <?= $color_active_monday ?>"><?= number_format($row->amount_monday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_monday ?>"><?= number_format($row->discount_monday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_monday ?>"><?= number_format($row->price_monday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_monday ?>">
                                        <?php if ($row->buy_ticket_monday != 0 && $row->free_ticket_monday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_monday, 0) ?> free: <?= number_format($row->free_ticket_monday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_tuesday ?>"><?= number_format($row->amount_tuesday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_tuesday ?>"><?= number_format($row->discount_tuesday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_tuesday ?>"><?= number_format($row->price_tuesday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_tuesday ?>">
                                        <?php if ($row->buy_ticket_tuesday != 0 && $row->free_ticket_tuesday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_tuesday, 0) ?> free: <?= number_format($row->free_ticket_tuesday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_wednesday ?>"><?= number_format($row->amount_wednesday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_wednesday ?>"><?= number_format($row->discount_wednesday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_wednesday ?>"><?= number_format($row->price_wednesday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_wednesday ?>">
                                        <?php if ($row->buy_ticket_wednesday != 0 && $row->free_ticket_wednesday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_wednesday, 0) ?> free: <?= number_format($row->free_ticket_wednesday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_thursday ?>"><?= number_format($row->amount_thursday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_thursday ?>"><?= number_format($row->discount_thursday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_thursday ?>"><?= number_format($row->price_thursday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_thursday ?>">
                                        <?php if ($row->buy_ticket_thursday != 0 && $row->free_ticket_thursday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_thursday, 0) ?> free: <?= number_format($row->free_ticket_thursday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_friday ?>"><?= number_format($row->amount_friday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_friday ?>"><?= number_format($row->discount_friday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_friday ?>"><?= number_format($row->price_friday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_friday ?>">
                                        <?php if ($row->buy_ticket_friday != 0 && $row->free_ticket_friday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_friday, 0) ?> free: <?= number_format($row->free_ticket_friday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_saturday ?>"><?= number_format($row->amount_saturday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_saturday ?>"><?= number_format($row->discount_saturday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_saturday ?>"><?= number_format($row->price_saturday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_saturday ?>">
                                        <?php if ($row->buy_ticket_saturday != 0 && $row->free_ticket_saturday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_saturday, 0) ?> free: <?= number_format($row->free_ticket_saturday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-right <?= $color_active_sunday ?>"><?= number_format($row->amount_sunday, 2) ?></td>
                                    <td class="text-capitalize text-right <?= $color_active_sunday ?>"><?= number_format($row->discount_sunday, 2) ?>%</td>
                                    <td class="text-capitalize text-right <?= $color_active_sunday ?>"><?= number_format($row->price_sunday, 2) ?></td>
                                    <td class="text-capitalize <?= $color_active_sunday ?>">
                                        <?php if ($row->buy_ticket_sunday != 0 && $row->free_ticket_sunday != 0) : ?>
                                            buy: <?= number_format($row->buy_ticket_sunday, 0) ?> free: <?= number_format($row->free_ticket_sunday, 0) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-capitalize text-center">
                                        <input type="checkbox"
                                            <?php echo $row->tax == 1 ? 'checked' : ''; ?>
                                        disabled>
                                    </td>
                                    <td class="text-capitalize text-center">
                                        <input type="checkbox"
                                            <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                        disabled>
                                    </td>

                                    <td class="text-capitalize"><?= time_ago($row->updated_at) ?></td>

                                    <td class="text-center">
                                        <a href="<?= site_url('Tenancy/Ticketing/Master/Ticket/edit/'.$row->id); ?>" class="label label-warning">
                                            <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                        </a>

                                        <a onclick="return confirm('Are you sure?')" href="<?= site_url('Tenancy/Ticketing/Master/Ticket/destroy/'.$row->id); ?>" class="label label-danger" style="margin-left: 5px;">
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
