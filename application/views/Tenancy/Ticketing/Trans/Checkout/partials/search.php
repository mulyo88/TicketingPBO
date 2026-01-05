<?php $this->load->helper('qr'); ?>

<!-- example descript -->
<!-- <?php
    $data = 'ZRNvLRePhhH4s/idtd4nyDYYihIPb0r/8jSjkvmAD2zsqWwT1Larg0FKhmemApjw7MDWpvHatxthzNJ8kS1+FQCmCqXefB4Y+RvcesnRkVYt0mjZhcwGWs0U6BL9x0QR';
    $decrypted = decryptData($data);
?>

<div class="text-center" style="font-size:8px;">
    <?= $decrypted ?>
</div> -->

<div class="google-search">
    <div class="input-group position-relative">
        <i class="bi bi-search google-icon-left"></i>
        <input type="text" id="keyword" name="keyword" class="form-control google-input" placeholder="Scan here" onkeypress="search_data_press(event)">

        <button class="btn google-btn" type="button" onclick="checkout_scan()">
            <i class="bi bi-search"></i>
        </button>
    </div>
</div>

<?php start_section('page-style-partials-search'); ?>
    <style>
        .google-search {
            max-width: 700px;
            margin: 30px auto;
        }

        .google-input {
            border-radius: 50px 0 0 50px !important;
            padding-left: 50px;
            height: 55px;
            font-size: 18px;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            border-right: 0;
        }

        .google-input:focus {
            box-shadow: 0 0 6px rgba(66,133,244,0.6);
            border-color: #4285F4;
            outline: none;
        }

        .google-icon-left {
            position: absolute;
            top: 15px;
            left: 20px;
            color: #777;
            font-size: 22px;
        }

        .google-btn {
            border-radius: 0 50px 50px 0 !important;
            padding: 0 25px;
            height: 55px;
            font-size: 18px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-left: 0;
            transition: 0.2s;
        }

        .google-btn:hover {
            background-color: #e9ecef;
        }

        .google-btn i {
            color: #555;
            font-size: 22px;
        }
    </style>
<?php end_section('page-style-partials-search'); ?>

<?php start_section('page-script-partials-search'); ?>
    <script>
        document.getElementById("keyword").focus();
        
        function search_data_press(e) {
            if (e.keyCode === 13) { // where 13 is the enter button
                e.preventDefault(); //ignore submit

                checkout_scan();
            }
        }

        async function checkout_scan() {
            document.getElementById("keyword").focus();

            let keyword = document.getElementById('keyword').value;
            if (!keyword || keyword === '') {
                add_information('Please enter a keyword to search.', 'bg-warning');
                showAlert('warning', document.getElementById('information-text').innerHTML);

                document.getElementById('keyword').value = '';
                document.getElementById("keyword").focus();
                return;
            }

            try {
                const res = await descript(keyword);
                if (res.status === 'success') {
                    keyword = res.data;
                } else {
                    add_information(res.data, 'bg-danger');
                    showAlert('danger', 'descript failed');

                    document.getElementById('keyword').value = '';
                    document.getElementById("keyword").focus();
                    return;
                }

                if (!keyword || keyword === '') {
                    add_information('Please enter a keyword to search.', 'bg-warning');
                    showAlert('warning', document.getElementById('information-text').innerHTML);

                    document.getElementById('keyword').value = '';
                    document.getElementById("keyword").focus();
                    return;
                }
                                
                $.ajax({
                    url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/ticketing_checkout_scan_search_barcode') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        barcode: keyword,
                        gate: fn_i_gate_id,
                        "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
                    },
                    beforeSend: function() {
                        add_information('waiting...', type='bg-info');
                    },
                    complete: function() {

                    },
                    success: function(data) {
                        if (data.msg == 'already checkout') {
                            add_information(keyword.trim() + ' ' + data.msg, 'bg-warning');
                        } else if (data.msg == 'not yet checkin') {
                            add_information(keyword.trim() + ' ' + data.msg, 'bg-warning');
                        } else if (data.msg == 'not found') {
                            add_information(keyword.trim() + ' ' + data.msg, 'bg-danger');
                        } else if (data.msg == 'scan success') {
                            add_information(keyword.trim() + ' ' + data.msg, 'bg-success');
                            load_statistic();
                            access_gate("OPEN", document.getElementById('address-json').innerHTML.trim());
                        }

                        document.getElementById('keyword').value = '';
                        document.getElementById("keyword").focus();
                    },
                    error: function(xhr, status, error) {
                        add_information(error, type='bg-danger');
                        showAlert('danger', error);

                        document.getElementById('keyword').value = '';
                        document.getElementById("keyword").focus();
                        return;
                    }
                });
            } catch (err) {
                add_information(err.data, 'bg-danger');
                showAlert('danger', 'AJAX error');

                document.getElementById('keyword').value = '';
                document.getElementById("keyword").focus();
            }
        }

        function descript(keyword) {
            return new Promise((resolve, reject) => {
                var encryptedKeyword = encodeURIComponent(keyword);

                $.ajax({
                    url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/descript_qr') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        qr: encryptedKeyword,
                        "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
                    },
                    success: function(res) {
                        resolve(res);
                    },
                    error: function(xhr, status, error) {
                        reject({
                            status: "failed",
                            data: error
                        });
                    }
                });
            });
        }
    </script>
<?php end_section('page-script-partials-search'); ?>