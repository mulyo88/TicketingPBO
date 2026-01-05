<form id="MyForm" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-6">
            <label for="">
                <h6>PERMINTAAN DANA</h6>
            </label>
            <div class="form-group row">
                <label for="inputNo" class="col-sm-2 col-form-label">No. </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputName" value="<?= $mydata->NoUrut ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputalokasi" class="col-sm-2 col-form-label">Kode RAP</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="KodeRap" value="<?= $mydata->KdRAP ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputalokasi" class="col-sm-2 col-form-label">Uraian</label>
                <div class="col-sm-10">
                    <textarea name="uraianPDentry" id="uraianPDentry" cols="58" rows="5" readonly="readonly" <?=$disab?>><?= $mydata->Uraian ?></textarea>
                </div>
            </div>
            <div class=" form-group row">
                <label for="inputvolume" class="col-sm-2 col-form-label">Volume</label>
                <div class="col-sm-10">
                    <input type="text" name="VolPDentry" id="VolPDentry" class="form-control" value="<?= PecahAngka($mydata->Vol) ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputSatuan" class="col-sm-2 col-form-label">Satuan</label>
                <div class="col-sm-10">
                    <input type="text" name="SatPDentry" id="SatPDentry" class="form-control" value="<?= $mydata->Uom ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="HargaSatuan" class="col-sm-2 col-form-label">Harga Satuan</label>
                <div class="col-sm-10">
                    <input type="text" name="HarSatPDentry" id="HarSatPDentry" class="form-control" value="<?= UbahGabunganUang($mydata->HrgSatuan) ?>" readonly <?=$disab?>>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <label for="">
                <h6>PERTANGGUNGJAWABAN DANA</h6>
            </label>
            <div class="form-group row">
                <label for="inputNo" class="col-sm-2 col-form-label">No. </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="inputName" name="NoUrut" value="<?= $mydata->NoUrut ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputalokasi" class="col-sm-2 col-form-label">Kode RAP</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="PjKodeRap" value="<?= $mydata->KdRAP ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputalokasi" class="col-sm-2 col-form-label">Uraian</label>
                <div class="col-sm-10">
                    <textarea name="PjuraianPDentry" id="uraianPDentry" cols="58" rows="5" required <?=$disab?>><?= ($mydata->PjUraian == '') ? $mydata->Uraian : $mydata->PjUraian ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputvolume" class="col-sm-2 col-form-label">Volume</label>
                <div class="col-sm-10">
                    <input type="text" name="PjVolPDentry" id="VolPDentry" class="form-control" onkeypress="return hanyaAngka(event)" value="<?= ($mydata->PjVol != ''  || $mydata->PjVol != '') ? PecahAngka($mydata->PjVol) : '' ?>" required <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputSatuan" class="col-sm-2 col-form-label">Satuan</label>
                <div class="col-sm-10">
                    <input type="text" name="PjSatPDentry" id="SatPDentry" class="form-control" value="<?= $mydata->Uom ?>" readonly <?=$disab?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="HargaSatuan" class="col-sm-2 col-form-label">Harga Satuan</label>
                <div class="col-sm-10">
                    <input type="text" name="PjHarSatPDentry" id="HarSatPDentry" class="form-control uang" onkeypress="return hanyaAngka(event)" value="<?= ($mydata->PjHrgSatuan != ''  || $mydata->PjHrgSatuan != '') ? UbahGabunganUang($mydata->PjHrgSatuan) : '' ?>" required <?=$disab?>>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <input type="hidden" name="PjNoPD" value="<?= $this->secure->encrypt_url($mydata->NoPD) ?>">
    <input type="hidden" name="JobNo" value="<?= $JobNo ?>">
    <button type="submit" name="simpan" id="simpan" class="btn btn-facebook pull-right" <?=$disab?>>SIMPAN</button>



</form>

<script type="text/javascript">
    $(function() {
        $('.uang').mask('000,000,000,000,000', {
            reverse: true
        });


        jQuery('#MyForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?= base_url('Pj/SimpanDetailPj') ?>",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {



                    if (response.status == 'success') {

                        $.toast({
                            heading: 'Berhasil',
                            text: 'Data Telah Ubah',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            hideAfter: 1000,
                            afterHidden: function() {
                                $('#MyModal').modal('hide');
                                // detailtable(response.JobNo, response.NoPD);
                                location.reload();
                            }
                        })
                    } else {
                        $('#MyModal').modal('hide');
                        $.toast({
                            heading: 'Terjadi Kesalahan',
                            text: 'Gagal Mengubah Data',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            icon: 'error'
                        })
                    }


                },
                error: function(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg);
                },
            })
        })

    })
</script>