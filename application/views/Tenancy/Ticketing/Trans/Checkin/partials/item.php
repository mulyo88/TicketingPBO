<div class="row row-cols-2 row-cols-md-3 row-cols-lg-2 row-cols-xl-5 g-3" id="row_item"></div>


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
        list_item();
        function list_item() {
            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/load_ticket') ?>/" + fn_i_venue_id,

                beforeSend: function() {
                    document.getElementById("item_data_info").hidden = false;
                    document.getElementById("item_data_info").innerHTML = "loading data...";
                },
                complete: function() {

                },
                success: function(data) {
                    init_item(data);
                },
                error: function(xhr, status, error) {
                    showAlert('danger', error);
                    document.getElementById("item_data_info").hidden = false;
                    document.getElementById("item_data_info").innerHTML = error;
                    return;
                }
            });
        }

        function init_item(data) {
            document.getElementById("row_item").innerHTML = '';
            var html ='';

            if (data.length == 0) {
                document.getElementById("item_data_info").hidden = false;
                document.getElementById("item_data_info").innerHTML = "data not found";
            } else {
                document.getElementById("item_data_info").hidden = true;
                for (let index = 0; index < data.length; index++) {
                    html +='<div class="col" id="card_' + data[index].id + '" onclick="select_item(`' + encodeURIComponent(JSON.stringify(data[index])) + '`)">';
                        html +='<div class="card click-card h-100" style="background-color: #F2BFFF;">';
                            html +='<div class="card-body text-center text-capitalize">';
                                html +='<div class="d-flex flex-column bd-highlight">';
                                    html +='<div class="p-0 bd-highlight"><h5 class="fw-bold">' + data[index].category + '</h5></div>';
                                    if (data[index].type) {
                                        html +='<div class="p-0 bd-highlight"><h5 class="fw-bold">' + data[index].type + '</h5></div>';
                                    }
                                    html +='<div class="p-0 bd-highlight"><h5 class="fw-bold">' + data[index].name + '</h5></div>';

                                    if (data[index].buy_ticket != 0 && data[index].free_ticket != 0) {
                                        html +='<div class="p-0 bd-highlight"><h5 class="fw-bold">Buy ' + formatNumber(data[index].buy_ticket, 0) + ' Free ' + formatNumber(data[index].free_ticket, 0) + '</h5></div>';
                                    }

                                    html +='<div class="p-2 bd-highlight"><h5>' + formatNumber(data[index].price, 0) + '</h5></div>';
                                html +='</div>';
                            html +='</div>';
                        html +='</div>';
                    html +='</div>';
                }
            }
            
            document.getElementById("row_item").innerHTML = html;
        }

        function select_item(data) {
            data = JSON.parse(decodeURIComponent(data));

            add_cart(data);
            selectCard(data);
        }

        function selectCard(data) {
            document.querySelectorAll('.click-card').forEach(c => c.classList.remove('selected'));
            var card = document.getElementById("card_" + data.id);
            card.classList.add('selected');
        }
    </script>
<?php end_section('page-script-partials-item'); ?>