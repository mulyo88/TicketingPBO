<?php extend('component/struk'); ?>
    <?php start_section('title'); ?>
        <?=($judul) ? $judul : 'Header not set'  ?>
    <?php end_section('title'); ?>

    <?php start_section('content'); ?>
        <div class="text-center">
            <strong><?= $building ? $building->name : 'Store Name' ?></strong><br>
            <small><?= $building ? $building->address : 'Store Address' ?></small><br>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="col-xs-12 text-center" style="font-size: 10px; margin-bottom:10px;"><?= $pos->series ?></div>
        </div>
        
        <div class="row">
            <div class="col-xs-4">Date</div>
            <div class="col-xs-8 text-right"><?= date("d-M-Y H:m:i", strtotime($pos->updated_at)) ?></div>
        </div>

        <div class="row" style="margin-bottom:10px;">
            <div class="col-xs-4">Cashier</div>
            <div class="col-xs-8 text-right"><?= $pos->username ?></div>
        </div>

        <table class="table">
            <tbody>
                <?php foreach ($detail as $row): ?>
                    <tr>
                        <td><?= $row->item ? $row->item->NmBarang : 'None' ?></td>
                        <td class="text-right"><?= number_format($row->qty, 0, '.', ',') ?><?= $row->item ? $row->item->Satuan : 'None' ?></td>
                        <td class="text-right"><?= number_format($row->total, 2, '.', ',') ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>

        <div class="line"></div>

        <div class="row">
            <div class="col-xs-6">Qty</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->qty, 2, '.', ',') ?></div>
        </div>

        <div class="row">
            <div class="col-xs-6">Total</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->total, 2, '.', ',') ?></div>
        </div>

        <div class="row">
            <div class="col-xs-6">Discount</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->discount, 2, '.', ',') ?></div>
        </div>

        <div class="row">
            <div class="col-xs-6">Tax</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->tax, 0, '.', ',') ?>%</div>
        </div>

        <div class="row font-bold">
            <div class="col-xs-6">Grand Total</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->grandtotal, 2, '.', ',') ?></div>
        </div>

        <div class="row font-bold">
            <div class="col-xs-6">Payment</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->payment, 2, '.', ',') ?></div>
        </div>

        <div class="row font-bold">
            <div class="col-xs-6">Return</div>
            <div class="col-xs-6 text-right"><?= number_format($pos->balance, 2, '.', ',') ?></div>
        </div>

        <div class="row">
            <div class="col-xs-6">Method</div>
            <div class="col-xs-6 text-right"><?= $pos->methode ?></div>
        </div>

        <p class="text-center" style="margin-top:10px;">Thanks for coming</p>
    <?php end_section('content'); ?>
<?php render_template(); ?>