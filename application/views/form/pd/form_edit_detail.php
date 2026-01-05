<form id="myform">
    <div class="form-group row">
        <div class="col-sm-2">
            <label for="">No Urut</label>
            <input type="text" class="form-control form-control-sm" name="NoUrut" value="<?= $row->NoUrut ?>" readonly <?= $disab ?>>
        </div>

        <div class="col-sm-10">
            <label for="">Kode RAP</label>
            <input type="text" class="form-control form-control-sm" name="KdRAP" value="<?= $row->KdRAP ?>" readonly <?= $disab ?>>
        </div>

    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <label for="">Uraian</label>
            <textarea name="Uraian" id="Uraian" class="form-control form-control-sm" <?= $disab ?>><?= $row->Uraian ?></textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-4">
            <label for="">Volume</label>
            <input type="text" class="form-control form-control-sm" name="Vol" value="<?= PecahAngka($row->Vol) ?>" onkeypress="return hanyaAngka(event)" required <?= $disab ?>>
        </div>

        <div class="col-sm-4">
            <label for="">Satuan</label>
            <input type="text" class="form-control form-control-sm" name="Uom" value="<?= $row->Uom ?>" required <?= $disab ?>>
        </div>

        <div class="col-sm-4">
            <label for="">Harga</label>
            <input type="text" class="form-control form-control-sm uang" name="HrgSatuan" value="<?= UbahGabunganUang($row->HrgSatuan) ?>" onkeypress="return hanyaAngka(event)" required <?= $disab ?>>
        </div>

    </div>

    <div class="form-group row">
        <div class="col-sm-12">
            <input type="hidden" name="NoPD" value="<?= $row->NoPD ?>">
            <button type="submit" class="btn btn-primary btn-md pull-right" <?= $disab ?>>Simpan</button>
        </div>

    </div>
</form>
<script>
    $(function() {
        var JobNo = '<?= $JobNo ?>';
        $('.uang').mask('000,000,000,000,000', {
            reverse: true
        });

        $('#myform').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "<?= site_url('Pd/do_edit_detail') ?>",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() {
                    $('.my-loading').show();
                },
                success: function(response) {
                    $('.my-loading').hide();

                    // if (response.status == 'success') {
                    //     $('#my-modal').modal('hide');
                    //     toastr.success('Berhasil Diubah');
                    //     detailtable(JobNo, response.NoPD);
                    // } else {
                    //     toastr.error('Gagal Dihapus');
                    // }
                    $('#my-modal').modal('hide');
                    detailtable(JobNo, response.NoPD);

                    // $('#my-modal').modal('hide');
                }
            });
        })
    })
</script>