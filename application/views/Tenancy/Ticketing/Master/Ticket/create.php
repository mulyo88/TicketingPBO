<?php
    error_reporting(E_ALL);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">
            <li class="text-capitalize"><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i>dashboard</a></li>
            <li class="text-capitalize active"><a href="<?= site_url('Tenancy/Ticketing/Master/Ticket/create') ?>"><?php echo $judul ?></a></li>
        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <form method="post" action="<?= site_url('Tenancy/Ticketing/Master/Ticket/store') ?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
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
                                            <label for="category" class="col-sm-2 control-label text-capitalize">category</label>

                                            <div class="col-sm-10">
                                                <select name="category" id="category" class="form-control"
                                                    <?= invalid('category') ?>
                                                >
                                                    <?php foreach ($category as $row): ?>
                                                        <option value="<?= $row->name ?>"<?= old('category') == $row->name ? 'selected' : '' ?>><?= $row->name ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                                <?= error('category') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="type" class="col-sm-2 control-label text-capitalize">type</label>

                                            <div class="col-sm-10">
                                                <select name="type" id="type" class="form-control"
                                                    <?= invalid('type') ?>
                                                >
                                                    <option value=""<?= old('type') == '' ? 'selected' : '' ?>>None</option>

                                                    <?php foreach ($type as $row): ?>
                                                        <option value="<?= $row->name ?>"<?= old('type') == $row->name ? 'selected' : '' ?>><?= $row->name ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                                <?= error('type') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="party" class="col-sm-2 control-label text-capitalize">party</label>

                                            <div class="col-sm-10">
                                                <select name="party" id="party" class="form-control"
                                                    <?= invalid('party') ?>
                                                >
                                                    <?php foreach ($party as $row): ?>
                                                        <option value="<?= $row->name ?>"<?= old('party') == $row->name ? 'selected' : '' ?>><?= $row->name ?></option>
                                                    <?php endforeach ?>
                                                </select>

                                                <?= error('party') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label text-capitalize">name</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                                    value="<?php echo old('name'); ?>" required
                                                    <?= invalid('name') ?>
                                                >

                                                <?= error('name') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="price" class="col-sm-2 control-label text-capitalize">price (COGS)</label>

                                            <div class="col-sm-10">
                                                <input type="number" any="step" class="form-control" id="price" name="price" placeholder="Price (Cost of Goods Sold/HPP)"
                                                    value="<?php echo old('price'); ?>" style="text-align: right;" required
                                                    <?= invalid('price') ?>
                                                >

                                                <?= error('price') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

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
                                                        <input type="checkbox" id="tax" name="tax"> Is Tax
                                                    </label>
                                                </div>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="is_active" name="is_active" checked> Is Active All
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>

                        <div class="col-md-6">

                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right">
                                    <li><a href="#sunday" data-toggle="tab" class="text-capitalize">sunday</a></li>
                                    <li><a href="#saturday" data-toggle="tab" class="text-capitalize">saturday</a></li>
                                    <li><a href="#friday" data-toggle="tab" class="text-capitalize">friday</a></li>
                                    <li><a href="#thursday" data-toggle="tab" class="text-capitalize">thursday</a></li>
                                    <li><a href="#wednesday" data-toggle="tab" class="text-capitalize">wednesday</a></li>
                                    <li><a href="#tuesday" data-toggle="tab" class="text-capitalize">tuesday</a></li>
                                    <li class="active"><a href="#monday" data-toggle="tab" class="text-capitalize">monday</a></li>
                                    <li class="pull-left header text-capitalize"><i class="fa fa-inbox"></i>price list</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <div class="chart tab-pane active" id="monday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[monday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[monday][amount]" name="xprice[monday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[monday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[monday][amount]') ?>
                                                        >

                                                        <?= error('xprice[monday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[monday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[monday][discount]" name="xprice[monday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[monday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[monday][discount]') ?>
                                                        >

                                                        <?= error('xprice[monday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[monday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_monday" name="xprice[monday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[monday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[monday][buy]') ?>
                                                        >

                                                        <?= error('xprice[monday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[monday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[monday][free]" name="xprice[monday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[monday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[monday][free]') ?>
                                                        >

                                                        <?= error('xprice[monday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[monday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[monday][is_active]" name="xprice[monday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="chart tab-pane" id="tuesday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[tuesday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[tuesday][amount]" name="xprice[tuesday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[tuesday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[tuesday][amount]') ?>
                                                        >

                                                        <?= error('xprice[tuesday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[tuesday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[tuesday][discount]" name="xprice[tuesday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[tuesday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[tuesday][discount]') ?>
                                                        >

                                                        <?= error('xprice[tuesday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[tuesday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_tuesday" name="xprice[tuesday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[tuesday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[tuesday][buy]') ?>
                                                        >

                                                        <?= error('xprice[tuesday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[tuesday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[tuesday][free]" name="xprice[tuesday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[tuesday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[tuesday][free]') ?>
                                                        >

                                                        <?= error('xprice[tuesday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[tuesday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[tuesday][is_active]" name="xprice[tuesday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart tab-pane" id="wednesday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[wednesday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[wednesday][amount]" name="xprice[wednesday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[wednesday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[wednesday][amount]') ?>
                                                        >

                                                        <?= error('xprice[wednesday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[wednesday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[wednesday][discount]" name="xprice[wednesday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[wednesday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[wednesday][discount]') ?>
                                                        >

                                                        <?= error('xprice[wednesday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[wednesday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_wednesday" name="xprice[wednesday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[wednesday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[wednesday][buy]') ?>
                                                        >

                                                        <?= error('xprice[wednesday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[wednesday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[wednesday][free]" name="xprice[wednesday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[wednesday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[wednesday][free]') ?>
                                                        >

                                                        <?= error('xprice[wednesday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[wednesday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[wednesday][is_active]" name="xprice[wednesday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart tab-pane" id="thursday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[thursday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[thursday][amount]" name="xprice[thursday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[thursday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[thursday][amount]') ?>
                                                        >

                                                        <?= error('xprice[thursday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[thursday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[thursday][discount]" name="xprice[thursday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[thursday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[thursday][discount]') ?>
                                                        >

                                                        <?= error('xprice[thursday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[thursday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_thursday" name="xprice[thursday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[thursday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[thursday][buy]') ?>
                                                        >

                                                        <?= error('xprice[thursday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[thursday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[thursday][free]" name="xprice[thursday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[thursday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[thursday][free]') ?>
                                                        >

                                                        <?= error('xprice[thursday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[thursday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[thursday][is_active]" name="xprice[thursday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart tab-pane" id="friday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[friday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[friday][amount]" name="xprice[friday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[friday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[friday][amount]') ?>
                                                        >

                                                        <?= error('xprice[friday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[friday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[friday][discount]" name="xprice[friday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[friday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[friday][discount]') ?>
                                                        >

                                                        <?= error('xprice[friday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[friday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_friday" name="xprice[friday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[friday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[friday][buy]') ?>
                                                        >

                                                        <?= error('xprice[friday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[friday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[friday][free]" name="xprice[friday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[friday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[friday][free]') ?>
                                                        >

                                                        <?= error('xprice[friday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[friday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[friday][is_active]" name="xprice[friday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart tab-pane" id="saturday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[saturday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[saturday][amount]" name="xprice[saturday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[saturday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[saturday][amount]') ?>
                                                        >

                                                        <?= error('xprice[saturday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[saturday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[saturday][discount]" name="xprice[saturday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[saturday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[saturday][discount]') ?>
                                                        >

                                                        <?= error('xprice[saturday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[saturday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_saturday" name="xprice[saturday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[saturday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[saturday][buy]') ?>
                                                        >

                                                        <?= error('xprice[saturday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[saturday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[saturday][free]" name="xprice[saturday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[saturday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[saturday][free]') ?>
                                                        >

                                                        <?= error('xprice[saturday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[saturday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[saturday][is_active]" name="xprice[saturday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart tab-pane" id="sunday" style="position: relative;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[sunday][amount]" class="col-sm-4 control-label text-capitalize">price (COGS)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[sunday][amount]" name="xprice[sunday][amount]" placeholder="price (Cost of Goods Sold/HPP)"
                                                            value="<?php echo old('xprice[sunday][amount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[sunday][amount]') ?>
                                                        >

                                                        <?= error('xprice[sunday][amount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[sunday][discount]" class="col-sm-4 control-label text-capitalize">Discount (%)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[sunday][discount]" name="xprice[sunday][discount]" placeholder="Discount %"
                                                            value="<?php echo old('xprice[sunday][discount]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[sunday][discount]') ?>
                                                        >

                                                        <?= error('xprice[sunday][discount]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[sunday][buy]" class="col-sm-4 control-label text-capitalize">Buy Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="buy_sunday" name="xprice[sunday][buy]" placeholder="Buy Ticket (Promotion)"
                                                            value="<?php echo old('xprice[sunday][buy]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[sunday][buy]') ?>
                                                        >

                                                        <?= error('xprice[sunday][buy]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[sunday][free]" class="col-sm-4 control-label text-capitalize">Free Ticket (Promotion)</label>

                                                    <div class="col-sm-8">
                                                        <input type="number" any="step" class="form-control" id="xprice[sunday][free]" name="xprice[sunday][free]" placeholder="Free Ticket (Promotion)"
                                                            value="<?php echo old('xprice[sunday][free]'); ?>" style="text-align: right;"
                                                            <?= invalid('xprice[sunday][free]') ?>
                                                        >

                                                        <?= error('xprice[sunday][free]') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="price[sunday][is_active]" class="col-sm-4 control-label text-capitalize"></label>

                                                    <div class="col-sm-8">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" id="xprice[sunday][is_active]" name="xprice[sunday][is_active]" checked> Is Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn pull-right" style="background-color:black; color:white; margin-right: 15px;"
                                onclick="location.href='<?=site_url('Tenancy/Ticketing/Master/Ticket/index')?>'"
                            >
                                <i class="fa fa-undo"></i><span style="margin-left: 5px;">Back to List</span>
                            </button>
                        
                            <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">
                                <i class="fa fa-save"></i><span style="margin-left: 5px;">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
