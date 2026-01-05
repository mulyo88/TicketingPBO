<div id="struk" class="body2" style="padding:10px;">
    <?php if ($results): ?>
        <div class="text-center">
            <strong class="text-capitalize">summary settlement</strong><br>
        </div>

        <?php foreach ($results->summary as $row): ?>
            <div class="line2"></div>

            <div class="flex-between">
                <div class="text-capitalize" style="margin-top: 5px;">area</div>
                <div class="text-capitalize" style="margin-top: 5px;"><?= $row->building ? $row->building->code : 'none' ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">date</div>
                <div class="text-capitalize"><?= date('d-M-Y', strtotime($row->date_trans)) ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">qty</div>
                <div class="text-capitalize"><?= number_format($row->qty, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">total</div>
                <div class="text-capitalize"><?= number_format($row->total, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">discount</div>
                <div class="text-capitalize"><?= number_format($row->discount, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">tax</div>
                <div class="text-capitalize"><?= number_format(($row->total - $row->discount) * $row->tax / 100, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">grandtotal</div>
                <div class="text-capitalize"><?= number_format($row->grandtotal, 2, '.', ',') ?></div>
            </div>
        <?php endforeach ?>

        <?php if (count($results->summary) > 0): ?>
            <div class="line2"></div>
            <div class="text-center">
                <strong class="text-capitalize text-center" style="margin-top: 5px;">grandtotal</strong><br>
            </div>
            
            <div class="row line2">
                <?php
                    $total_qty = 0;
                    $total_total = 0;
                    $total_discount = 0;
                    $total_tax = 0;
                    $total_grand = 0;

                    foreach ($results->summary as $row) {
                        $total_qty      += $row->qty;
                        $total_total    += $row->total;
                        $total_discount += $row->discount;

                        $tax_value = ($row->total - $row->discount) * $row->tax / 100;
                        $total_tax += $tax_value;

                        $total_grand += $row->grandtotal;
                    }
                ?>
                
                <div class="col-xs-4 text-capitalize" style="margin-top: 5px;">qty</div>
                <div class="col-xs-8 text-capitalize text-right" style="margin-top: 5px;"><?= number_format($total_qty, 2, '.', ',') ?></div>
                <div class="col-xs-4 text-capitalize">total</div>
                <div class="col-xs-8 text-capitalize text-right"><?= number_format($total_total, 2, '.', ',') ?></div>
                <div class="col-xs-4 text-capitalize">discount</div>
                <div class="col-xs-8 text-capitalize text-right"><?= number_format($total_discount, 2, '.', ',') ?></div>
                <div class="col-xs-4 text-capitalize">tax</div>
                <div class="col-xs-8 text-capitalize text-right"><?= number_format($total_tax, 2, '.', ',') ?></div>
                <div class="col-xs-4 text-capitalize">grandtotal</div>
                <div class="col-xs-8 text-capitalize text-right"><?= number_format($total_grand, 2, '.', ',') ?></div>
            </div>
        <?php endif ?>






        <div class="text-center" style="margin-top: 50px;">
            <strong class="text-capitalize">settlement details</strong><br>
        </div>

        <div class="line2"></div>
        <?php foreach ($results->transaction as $row): ?>
            <div class="text-capitalize text-center" style="margin-top: 5px;"><?= ($row->building ? $row->building->code : 'none') . ' - ' . ($row->building ? $row->building->name : 'none') ?></div>
            <div class="line2"></div>

            <div><?= $row->series ?></div>
            <div class="flex-between">
                <div class="text-capitalize">date</div>
                <div class="text-capitalize"><?= date('d-M-Y H:i:s', strtotime($row->date_trans)) ?></div>
            </div>

            <div class="flex-between" style="margin-bottom:5px;">
                <div class="text-capitalize">cashier</div>
                <div class="text-capitalize"><?= $row->username ?></div>
            </div>

            <table class="table">
                <tbody>
                    <?php foreach ($row->detail as $detail): ?>
                        <tr>
                            <td><?= $detail->item ? $detail->item->NmBarang : 'none' ?></td>
                            <td class="text-right"><?= (number_format($detail->qty, 0, '.', ',')) . ($detail->item ? $detail->item->Satuan : 'none') ?></td>
                            <td class="text-right"><?= ('Rp.') . (number_format($detail->total, 2, '.', ',')) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <div class="line2"></div>

            <div class="flex-between">
                <div class="text-capitalize">qty</div>
                <div class="text-capitalize"><?= number_format($row->qty, 0, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">total</div>
                <div class="text-capitalize"><?= number_format($row->total, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">discount</div>
                <div class="text-capitalize"><?= number_format($row->discount, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">tax</div>
                <div class="text-capitalize"><?= (number_format($row->tax, 0, '.', ',')) . '%' ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">grandtotal</div>
                <div class="text-capitalize"><?= number_format($row->grandtotal, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">payment</div>
                <div class="text-capitalize"><?= number_format($row->payment, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between">
                <div class="text-capitalize">return</div>
                <div class="text-capitalize"><?= number_format($row->return, 2, '.', ',') ?></div>
            </div>

            <div class="flex-between" style="margin-top: 50px;">
                <div class="text-capitalize">method</div>
                <div class="text-capitalize"><?= $row->methode ?></div>
            </div>
        <?php endforeach ?>



        <!-- <p class="text-center" style="margin-top:10px;">Thanks for coming</p> -->
    <?php else: ?>
        <div class="text-center">No data available</div>
    <?php endif ?>
</div>

<?php start_section('page-style-partials-struk'); ?>
    <style>
        #struk {
            display: none;
        }

        .font-bold {
            font-weight: bold;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #struk, #struk * {
                visibility: visible;
            }
            #struk {
                display: block;
                position: absolute;
                top: 0;
                left: 0;
                width: 58mm;
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            table, tbody, tr, th, td {
                padding: 0 !important;
                margin: 0 !important;
                border: 0 !important;
                border-collapse: collapse !important;
            }
            .line2 { border-top: 1px dashed #000; margin: 6px 0; }
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center; /* opsional */
        }
    </style>
<?php end_section('page-style-partials-struk'); ?>

<?php start_section('page-script-partials-struk'); ?>
    <script>
        var results = <?php echo json_encode($results); ?>;
        console.log(results);
        

        function printStruk() {
            window.print();
        }
    </script>
<?php end_section('page-script-partials-struk'); ?>