<style type="text/css">
    .myFont {
        font-size: 11px;
    }
</style>

<section class="content-header overlay">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h6><?= strtoupper($title) ?></h6>
            </div>
        </div>
    </div>
</section>

<section class="content-header">

    <div class="row">

        <div class="col-sm-12">
            <?php
            if (!empty($this->session->flashdata('message'))) {
                echo $this->session->flashdata('message');
                $flag_abis_redirect = 1;
            }
            ?>

            <div class="card">

                <div class="card-header bg-info">
                    <div class="card-title">...</div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">

                            <form id="myformpd">



                                <!-- <div class="table-responsive"> -->
                                <table class="table table-striped table hover table-borderless table-condensed" width="100%">
                                    <tr>
                                        <th style="font-size: 11px;">Job.No</th>
                                        <td style="font-size: 11px;">:</td>
                                        <td style="font-size: 11px;">
                                            <select style="font-size: 11px !important;" class="form-control form-control-sm" name="JobNo" id="JobNo" required>
                                                <!-- <option value="">-- Pilih Job.No --</option> -->
                                                <?php
                                                foreach ($job as $job) { ?>
                                                    <option value="<?= $job['JobNo'] ?>" <?= ($jobno_sesi == $job['JobNo']) ? 'selected' : '' ?>><?= $job['JobNo'] . ' - ' . $job['NamaPaket'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="font-size: 11px;">Alokasi</th>
                                        <td style="font-size: 11px;">:</td>
                                        <td style="font-size: 11px;">
                                            <select style="font-size: 11px !important;" class="form-control form-control-sm" name="Alokasi" id="Alokasi" required>
                                                <!-- <option value="">-- Pilih Alokasi --</option> -->
                                                <?php
                                                foreach ($alokasi as $alokasi) { ?>
                                                    <option value="<?= $alokasi['Alokasi'] ?>" <?= ($alokasi_sesi ==  $alokasi['Alokasi']) ? 'selected' : '' ?>><?= $alokasi['AlokasiGabung'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="font-size: 11px;"></th>
                                        <td style="font-size: 11px;"></td>
                                        <td style="font-size: 11px;">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label style="font-size:10px !important;">Dari Tgl</label>
                                                    <input type="date" style="font-size:10px !important;" name="DariTgl" id="DariTgl" value="<?= $DariTgl ?>" class="form-control datepicker" required>
                                                </div>
                                                <div class="col-6">
                                                    <label style="font-size:10px !important;">Sampai Tgl</label>
                                                    <input style="font-size:10px !important;" type="date" name="SampaiTgl" id="SampaiTgl" value="<?= $SampaiTgl ?>" class="form-control" required>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <th style="font-size: 11px;"></th>
                                        <td style="font-size: 11px;"></td>
                                        <td style="font-size: 11px;">
                                            <!-- untuk pengaturan redirect -->
                                            <input type="hidden" id="flag_abis_redirect" value="<?= $flag_abis_redirect ?>">
                                            <button type="submit" id="btn-cari" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>

                                        </td>
                                    </tr>
                                </table>
                                <!-- </div> -->
                            </form>


                        </div>

                        <div class="col-sm-6 bg-white">
                            <!-- <h1>ss</h1> -->
                        </div>
                    </div>




                </div>

                <div class="card-footer">

                    <div class="spinner-grow" role="status" id="pd-view-loading" style="display: none;">
                        <span class="sr-only">Loading...</span>
                    </div>

                </div>


            </div>


        </div>

    </div>

    <div id="pd-view-table" style="display:none;">

    </div>



</section>

<script type="text/javascript">
    function awalPage() {

        $('#pd-view-loading').hide();
        $('#pd-view-table').html('');
        $('#pd-view-table').hide();

        if ($('#flag_abis_redirect').val() == 1) {
            untuk_flag_redirect();
        } else {
            $('#Alokasi').val('');
            $('#JobNo').val('');
        }

    }

    function untuk_flag_redirect() {
        $.ajax({
                url: '<?= site_url("PD_keuangan/tampilTable_PD") ?>',
                type: 'POST',
                dataType: 'HTML',
                data: $('#myformpd').serialize(),
                beforeSend: function() {
                    $('#pd-view-loading').show();
                }
            })
            .done(function(response) {

                $('#pd-view-loading').hide();
                $('#pd-view-table').show().html(response);


            })
            .fail(function() {

            })
            .always(function() {

            });
    }

    function lakukan_app(LedgerNo) {

        Swal.fire({
            title: 'Anda Yakin ?',
            // text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Yakin!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('PD_keuangan/lakukan_approve_atau_unapprove') ?>",
                    data: {
                        'LedgerNo': LedgerNo
                    },
                    dataType: "HTML",
                    beforeSend: function() {
                        $('#pd-view-loading').show();
                    },
                    success: function(response) {
                        $('#pd-view-loading').hide();

                        if (response == 'berhasil') {
                            toastr.success('Berhasil');
                        }

                        if (response == 'gagal') {
                            toastr.success('Gagal,silahkan ulangi');
                        }

                        untuk_flag_redirect();

                    },
                    error: function() {
                        toastr.info('Terjadi Kesalahan');
                        $('#pd-view-loading').hide();
                    }
                });


            }
        })




    }




    $(function() {

        awalPage();


        $('#JobNo').select2({
            'width': '100%',
            'dropdownCssClass': "myFont",
            'placeholder': '-- Pilih Job No --'

        })

        $('#Alokasi').select2({
            'width': '100%',
            'dropdownCssClass': "myFont",
            'placeholder': '-- Pilih Alokasi --'

        })

        $('#myformpd').submit(function(e) {
            e.preventDefault();

            $.ajax({
                    url: '<?= site_url("PD_keuangan/tampilTable_PD") ?>',
                    type: 'POST',
                    dataType: 'HTML',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#pd-view-loading').show();
                    }
                })
                .done(function(response) {

                    $('#pd-view-loading').hide();
                    $('#pd-view-table').show().html(response);


                })
                .fail(function() {

                })
                .always(function() {

                });


        })




    })
</script>