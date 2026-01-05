<?php
$printed_non_entrance = 0;
?>

<style>
/* FORCE BORDER UNTUK KOTAK */
.box-table {
    margin: 10px auto;
    border-collapse: collapse;
    margin-left: 40px !important;
    
}

.box-table td {
    width: 2cm;
    height: 1.2cm;
    border: 6px solid #000 !important;
}
</style>


<?php $this->load->helper('qr'); ?>

<?php extend('component/struk_ticketing'); ?>
    <?php start_section('title'); ?>
        <?=($judul) ? $judul : 'Header not set'  ?>
    <?php end_section('title'); ?>

    <?php start_section('content'); ?>
        <div id="bill_page" class="page-break-after">
            <div class="text-center">
                <span style="font-size: 12px;">Ticketing</span><br>
                <span style="font-size: 12px;"><strong>SUNSET MALL</strong></span><br>
                <small>Sosoro Mall & Hotel Lt.1</small>
                <span style="font-size: 12px;" hidden><?= $building ? $building->name : 'Store Name' ?></span><br>
                <small hidden><?= $building ? $building->address : 'Store Address' ?></small><br>
            </div>

            <div class="line"></div>

            <div class="row">
                <div class="col-xs-12 text-center" style="font-size: 10px; margin-bottom:10px;"><?= $checkin->series ?></div>
            </div>
            
            <div class="row">
                <div class="col-xs-4">Date</div>
                <div class="col-xs-8 text-right"><?= date("d-M-Y H:m:i", strtotime($checkin->updated_at)) ?></div>
            </div>

            <div class="row" style="margin-bottom:10px;">
                <div class="col-xs-4">Cashier</div>
                <div class="col-xs-8 text-right"><?= $counter->code ?></div>
            </div>

            <table class="table">
                <tbody>
                    <?php foreach ($detail as $row): ?>
                        <tr>
                            <!-- <td style="font-size:x-small ;"><?= $row->ticket ? $row->ticket->name : 'None' ?></td> -->
                            <td style="font-size:x-small ;">
                                <?php if ($row->ticket->type) { ?>
                                    <?= $row->ticket ? $row->ticket->name . ' ' . $row->ticket->type : 'None' ?>
                                <?php } else { ?>
                                    <?= $row->ticket ? $row->ticket->name : 'None' ?>
                                <?php } ?>
                            </td>
                            <td class="text-right" style="font-size:x-small ;"><?= number_format($row->qty, 0, '.', ',') ?>x</td>
                            <td class="text-right" style="font-size:x-small ;">Rp.<?= number_format($row->price, 2, '.', ',') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <div class="line"></div>

            <div class="row">
                <div class="col-xs-6">Qty</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->qty, 2, '.', ',') ?></div>
            </div>

            <div class="row">
                <div class="col-xs-6">Total</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->total, 2, '.', ',') ?></div>
            </div>

            <div class="row">
                <div class="col-xs-6">Discount</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->discount, 2, '.', ',') ?></div>
            </div>

            <div class="row">
                <div class="col-xs-6">Tax</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->tax, 0, '.', ',') ?></div>
            </div>

            <div class="row font-bold">
                <div class="col-xs-6">Grand Total</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->subtotal, 2, '.', ',') ?></div>
            </div>

            <div class="row font-bold">
                <div class="col-xs-6">Payment</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->payment, 2, '.', ',') ?></div>
            </div>

            <div class="row font-bold">
                <div class="col-xs-6">Return</div>
                <div class="col-xs-6 text-right"><?= number_format($checkin->balance, 2, '.', ',') ?></div>
            </div>

            <div class="row">
                <div class="col-xs-6">Method</div>
                <div class="col-xs-6 text-right"><?= $checkin->methode ?></div>
            </div>

            <p class="text-center" style="margin-top:10px;">Terima Kasih atas Kunjungan Anda!</p>
            <table style="width:100%;">
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                       &nbsp; Dilarang membawa makanan dan minuman dari luar.
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                       &nbsp; Tiket tidak dapat ditukarkan atau dikembalikan.
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                       &nbsp; Tiket berlaku pada hari yang sama dengan pembelian.
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                       &nbsp; Pengunjung wajib mematuhi peraturan wahana & arahan petugas.
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">* </td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                        &nbsp;Orang tua wajib mendampingi anak selama berada di area bermain.
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                    <td style="margin-top:10px; text-align:justify; font-size:7px;">
                        &nbsp;Kehilangan barang pribadi bukan tanggung jawab kami.
                    </td>
                </tr>
            </table>

        </div>

        <div id="barcode_page">
            <?php
                $printed_non_entrance = 0;
            ?>

            <?php $no = 1; ?>
            <?php foreach ($barcode as $row): ?>
                <?php if ($row->ticket->category == 'Ticket'): ?>
                    <div class="page-break">
                        <div class="text-center" style="margin-bottom:20px;">
                            <span style="font-size: 12px;">Ticketing</span><br>
                            <span style="font-size: 12px;"><strong>SUNSET MALL</strong></span><br>
                            <small>Sosoro Mall & Hotel Lt.1</small>
                            <span style="font-size: 12px;" hidden><?= $building ? $building->name : 'Store Name' ?></span><br>
                            <small hidden><?= $building ? $building->address : 'Store Address' ?></small><br>
                        </div>

                        <?php
                            // $qrText = urlencode($checkin->series . ' - ' . $row->seq);
                            // $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$qrText}";

                            // $this->load->helper('qr');
                            // $rawText = $checkin->series . '|' . $row->seq;
                            // $encrypted = qr_encrypt($rawText);

                            // $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($encrypted);

                            $data = $checkin->series . '-' . $row->seq;
                            $encrypted = encryptData($data);
                            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($encrypted);
                        ?>

                        <div class="qrcode-wrapper text-center" style="margin-top:10px; margin-bottom:10px;">
                            <img src="<?= $qrUrl ?>" width="100" height="100">
                        </div>

                        <!-- <div class="text-center" style="font-size:8px;">
                            <?= $encrypted ?>
                        </div> -->

                        <p class="text-center">Terima Kasih atas Kunjungan Anda!</p>
                    </div>
                    <table style="width:100%;">
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                            &nbsp; Dilarang membawa makanan dan minuman dari luar.
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                            &nbsp; Tiket tidak dapat ditukarkan atau dikembalikan.
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                            &nbsp; Tiket berlaku pada hari yang sama dengan pembelian.
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                            &nbsp; Pengunjung wajib mematuhi peraturan wahana & arahan petugas.
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">* </td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                                &nbsp;Orang tua wajib mendampingi anak selama berada di area bermain.
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-top:10px; width:2px; text-align:justify; font-size:xx-small;">*</td>
                            <td style="margin-top:10px; text-align:justify; font-size:7px;">
                                &nbsp;Kehilangan barang pribadi bukan tanggung jawab kami.
                            </td>
                        </tr>
                    </table>
            <!--?php if (!$has_entrance_ticket): ?-->
            
            <!-- ============================= -->
            <!-- KOTAK SUNSET (GLOBAL LIMIT) -->
            <!-- ============================= -->
            <?php if (
                strtolower($row->ticket->type) !== 'entrance'
                && $printed_non_entrance < $non_entrance_qty
            ): ?>

                <p style="font-size:xx-small; text-align:center; margin-top:12px;">
                    Penggunaan Fasilitas Sunset
                </p>

                <table class="box-table" align="center">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <?php $printed_non_entrance++; ?>

            <?php endif; ?>


                <?php endif; ?>
            <?php $no++; endforeach ?>
        </div>
    <?php end_section('content'); ?>
<?php render_template(); ?>