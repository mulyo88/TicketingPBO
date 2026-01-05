<div class="row row-cols-2 row-cols-md-3 row-cols-lg-2 row-cols-xl-5" id="row_item"></div>

<?php start_section('page-style-partials-item'); ?>
    <style>
        .click-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .click-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .click-card:active {
            transform: scale(0.97);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .click-card.selected {
            border: 2px solid #0d6efd;
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.4);
        }
    </style>
<?php end_section('page-style-partials-item'); ?>

<?php start_section('page-script-partials-item'); ?>
    <script>
        list_item('', '', '', get_category_selected());
        function init_item(data) {
            document.getElementById("row_item").innerHTML = '';
            var html ='';

            if (data.length == 0) {
                document.getElementById("item_data_info").hidden = false;
                document.getElementById("item_data_info").innerHTML = "data not found";
            } else {
                document.getElementById("item_data_info").hidden = true;
                for (let index = 0; index < data.length; index++) {
                    html +='<div class="col mb-3" type="button" onclick="select_item(`' + encodeURIComponent(JSON.stringify(data[index])) + '`)">';
                        html +='<div class="card click-card" id="card_' + data[index].LedgerNo + '">';
                            html +='<img src="<?= site_url('images/image_not_found.jpg') ?>" class="card-img-top" alt="img" style="height: 130px;">';
                            html +='<div class="card-body" style="background-color:';
                                if (data[index].Jumlah < data[index].MinStock) {
                                    html +='#FFC0B3';
                                } else if (data[index].Jumlah == data[index].MinStock) {
                                    html +='#FFFFB0';
                                } else {
                                    html +='#E3E9FF';
                                }

                                html +='; height: 129px; overflow: auto; overflow-x: hidden;"';
                            html +='>';
                                html +='<div class="d-flex justify-content-center">';
                                    html +='<span class="text-center badge bg-primary me-1">' + data[index].Kategori + '</span>';
                                    html +='<span class="text-center badge bg-dark">' + data[index].Departement + '</span>';
                                html +='</div>';

                                html +='<div class="text-muted text-center text-capitalize">';
                                    html +=limit_string(data[index].NmBarang, 30);
                                html +='</div>';

                                html +='<div class="d-flex justify-content-between mb-2" style="position: absolute; bottom: 0; left: 10px; right: 10px;">';
                                    html +='<span class="text-capitalize fw-bold">';
                                        html +='Rp.' + formatFixed(data[index].harga_jual);
                                    html +='</span>';

                                    html +='<span class="text-capitalize fw-bold';
                                    if (data[index].Jumlah < data[index].MinStock) {
                                        html +='text-danger';
                                    } else if (data[index].Jumlah == data[index].MinStock) {
                                        html +='text-warning';
                                    } else {
                                        html +='text-dark';
                                    }
                                    
                                    html +='">' + formatNumber(data[index].Jumlah) + ' ' + data[index].Satuan + '</span>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';
                }
            }
            
            document.getElementById("row_item").innerHTML = html;
        }

        function search_item() {
            document.getElementById("search").classList.remove('is-invalid');

            var search = document.getElementById("search").value;
            var search_by = document.getElementById("search_type").value;
            var sort_by = document.getElementById("sort").value;
            var category = get_category_selected();

            if (search != '' && search_by == '') {
                scan_barcode(search, fn_building_code)
                clear_search();
                return
            }

            if (search != '') {
                document.getElementById("clear_search").hidden = false;
            }

            list_item(search, search_by, sort_by, category);
        }

        function list_item(search, search_by, sort_by, category) {
            search = search == '' ? null : search;
            search_by = search_by == '' ? null : search_by;
            sort_by = sort_by == '' ? null : sort_by;
            category = category == '' ? null : category;

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/search_item') ?>/" + search + "/" + search_by + "/" + sort_by + "/" + category,

                beforeSend: function() {
                    mode_btn_search(true);
                },
                complete: function() {
                    mode_btn_search(false);
                },
                success: function(data) {
                    init_item(data);
                },
                error: function(xhr, status, error) {
                    alert(error);
                    document.getElementById("item_data_info").hidden = false;
                    document.getElementById("item_data_info").innerHTML = error;
                    mode_btn_search(false);
                    return;
                }
            });
        }

        function scan_barcode(code, area) {
            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/scan_barcode') ?>/" + code + "/" + area,

                beforeSend: function() {
                    mode_btn_search(true);
                },
                complete: function() {
                    mode_btn_search(false);
                },
                success: function(data) {
                    if (!data) {
                        var snd = new Audio(beep_warning());
                        snd.play();

                        showAlert('danger', 'data not found!');
                        document.getElementById("search").classList.add('is-invalid');
                    } else {
                        add_cart(data);
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    document.getElementById("item_data_info").hidden = false;
                    document.getElementById("item_data_info").innerHTML = error;
                    mode_btn_search(false);
                    return;
                }
            });
        }

        function select_item(data) {
            data = JSON.parse(decodeURIComponent(data));

            add_cart(data);
            selectCard(data);
        }

        function selectCard(data) {
            document.querySelectorAll('.click-card').forEach(c => c.classList.remove('selected'));
            var card = document.getElementById("card_" + data.LedgerNo);
            card.classList.add('selected');
        }
    </script>
<?php end_section('page-script-partials-item'); ?>