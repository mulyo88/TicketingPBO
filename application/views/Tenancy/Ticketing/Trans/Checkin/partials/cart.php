<table class="table align-middle mb-0">
    <thead class="cart-header">
        <tr>
            <th style="background-color: #C100FF;" class="text-light text-end text-capitalize fw-bold">no.</th>
            <th style="background-color: #C100FF;" class="text-light text-start text-capitalize fw-bold">item</th>
            <th style="background-color: #C100FF;" class="text-light text-center text-capitalize fw-bold">qty</th>
            <th style="background-color: #C100FF;" class="text-light text-end text-capitalize fw-bold">subtotal</th>
            <th style="background-color: #C100FF;" class="text-light text-center text-capitalize fw-bold">
                <button type="button" class="btn btn-outline-light btn-remove" onclick="remove_all_card()"><i class="fa-solid fa-trash"></i></button>
            </th>
        </tr>
    </thead>
    <tbody id="cart-body"></tbody>
</table>

<?php start_section('page-style-partials-cart'); ?>
    <style>
        .cart-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.05);
            max-height: 420px; /* scroll area */
            overflow-y: auto;
        }

        .cart-header {
            background-color: #C100FF;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .cart-footer {
            background-color: #fff;
            position: sticky;
            bottom: 0;
            z-index: 9;
            box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
        }

        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.2rem;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            font-size: 0.9rem;
            padding: 0.25rem;
        }

        .btn-qty {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.9rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .btn-qty:hover {
            transform: scale(1.05);
        }

        .btn-remove {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.9rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .btn-remove:hover {
            transform: scale(1.05);
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transition: 0.2s;
        }

        .cart-table::-webkit-scrollbar {
            width: 8px;
        }

        .cart-table::-webkit-scrollbar-thumb {
            background-color: rgba(13, 110, 253, 0.3);
            border-radius: 10px;
        }
    </style>
<?php end_section('page-style-partials-cart'); ?>

<?php start_section('page-script-partials-cart'); ?>
    <script>
        init_cart(fn_detail);
        function init_cart(data) {
            reset_cart();

            if (old) {

            } else if (data) {
                for (let index = 0; index < data.length; index++) {
                    add_cart(data[index], "edit")
                }
            }
        }

        function reset_cart() {
            document.getElementById("cart-body").innerHTML = '<tr><td class="text-center text-capitalize text-muted" colspan="5">no data record</td></tr>';

            calculateQty();
        }

        function add_cart(data, type="select") {
            var total_element = $(".rowCount-cart").length;
            if (total_element > 0) {
                if (!check_validation(data)) {
                    return;
                }
            }

            if (total_element == 0) {
                document.getElementById("cart-body").innerHTML = "";
            }

            var total_element = $(".rowCount-cart").length;
            var rows = 1;

            if (total_element > 0) {
                var lastid = $(".rowCount-cart:last").attr("id");
                var split_id = lastid.split("_");
                rows = Number(split_id[1]) + 1;
            }

            if (type == "select") {
                var id = data.id;
                var name = '';
                if (data.type != '' && data.type != null) {
                    name = data.category + ' ' + data.type + ' - ' + data.name;
                } else {
                    var name = data.category + ' - ' + data.name;
                }

                var buy_promotion = data.buy_ticket;
                var free_promotion = data.free_ticket;

                var rate = data.amount;
                var discount = data.discount;
                var tax = 0;
                if (data.tax == 1) {
                    tax = fn_i_tax;
                }

                var qty = 1;
                var discount_amount = data.discount_amount;
                var tax_amount = data.tax_value;
                var total = rate;
            } else if (type == "edit") {
                var id = data.ticket_id;
                var name = '';
                if (data.ticket.type != '' && data.ticket.type != null) {
                    name = data.ticket ? data.ticket.category + ' ' + data.ticket.type + ' - ' + data.ticket.name : 'not found';
                } else {
                    name = data.ticket ? data.ticket.category + ' - ' + data.ticket.name : 'not found';
                }

                var buy_promotion = data.buy_ticket;
                var free_promotion = data.free_ticket;
                
                var rate = data.amount;
                var discount = data.discount;
                var tax = data.tax;
                var qty = formatDecimal(data.qty, 0);
                var discount_amount = data.discount_amount;
                var tax_amount = data.tax_value;
                var total = parseFloat(rate) * parseFloat(qty);
            }

            var html="";
            html +='<tr class="rowCount-cart" id="rowCount-cart_' + rows + '">';
                html +='<td class="text-end">';
                  html +='<input type="hidden" class="form-control" id="cart[' + rows + '][id]" name="cart[' + rows + '][id]" value="' + id + '" />';
                  html +='<input type="hidden" class="form-control" id="cart[' + rows + '][category]" name="cart[' + rows + '][category]" value="' + name + '" />';
                  html +='<input type="hidden" class="form-control" id="cart[' + rows + '][type]" name="cart[' + rows + '][type]" value="' + type + '" />';
                  html +='<input type="hidden" class="form-control rate" id="cart[' + rows + '][price]" name="cart[' + rows + '][price]" value="' + rate + '" />';
                  html +='<input type="hidden" class="form-control discount" id="cart[' + rows + '][discount]" name="cart[' + rows + '][discount]" value="' + discount + '" />';
                  html +='<input type="hidden" class="form-control tax" id="cart[' + rows + '][tax]" name="cart[' + rows + '][tax]" value="' + tax + '" />';
                  html +='<input type="hidden" class="form-control discount_amount" id="cart[' + rows + '][discount_amount]" name="cart[' + rows + '][discount_amount]" value="' + discount_amount + '" />';
                  html +='<input type="hidden" class="form-control tax_amount" id="cart[' + rows + '][tax_amount]" name="cart[' + rows + '][tax_amount]" value="' + tax_amount + '" />';
                  html +='<input type="hidden" class="form-control" id="cart[' + rows + '][buy_promotion]" name="cart[' + rows + '][buy_promotion]" value="' + buy_promotion + '" />';
                  html +='<input type="hidden" class="form-control" id="cart[' + rows + '][free_promotion]" name="cart[' + rows + '][free_promotion]" value="' + free_promotion + '" />';

                  html +=rows;
                html +='</td>';

                html +='<td class="fw-semibold">' + name + '</td>';
                html +='<td class="text-center">';
                    html +='<div class="qty-control">';
                        html +='<button type="button" class="btn btn-outline-primary btn-qty"><i class="fa-solid fa-minus" onclick="minus_card(' + rows + ')"></i></button>';
                        html +='<input type="number" any="step" id="cart[' + rows + '][qty]" name="cart[' + rows + '][qty]" class="form-control form-control-sm qty-input qty" min="1" value="' + qty + '" style="text-align: right;" onkeyup="calculateQty()">';
                        html +='<button type="button" class="btn btn-outline-primary btn-qty"><i class="fa-solid fa-plus" onclick="plus_card(' + rows + ')"></i></button>';
                    html +='</div>';
                html +='</td>';
                html +='<td class="text-end fw-semibold text-success">';
                    html +='<span id="cart[' + rows + '][subtotal]">' + formatNumber(total, 2) + '</span>';
                html +='</td>';
                html +='<td class="text-center">';
                    html +='<button type="button" class="btn btn-outline-danger btn-remove" onclick="remove_card(' + rows + ')"><i class="fa-solid fa-trash"></i></button>';
                html +='</td>';
            html +='</tr>';

            $("#cart-body").append(html);

            // auto bottom scroll
            let tableContainer = $('#cart-container');
            tableContainer.scrollTop(tableContainer[0].scrollHeight);

            calculateQty();
        }

      function remove_card(rows) {
        if (document.getElementById("rowCount-cart_" + rows)) {
            document.getElementById("rowCount-cart_" + rows).remove();
        }

        var total_element = $(".rowCount-cart").length;
        if (total_element == 0) {
          reset_cart();
        }

        calculateQty();
      }

      function remove_all_card() {
        reset_cart();
        calculateQty();
      }

      function plus_card(rows) {
        var qty = parseFloat(document.getElementById("cart[" + rows + "][qty]").value);
        qty +=1;
        document.getElementById("cart[" + rows + "][qty]").value = qty;
        calculateQty();
      }

      function minus_card(rows) {
        var qty = parseFloat(document.getElementById("cart[" + rows + "][qty]").value);
        if (qty > 0) {
          qty -=1;
        }

        document.getElementById("cart[" + rows + "][qty]").value = qty;
        calculateQty();
      }

      function check_validation(data) {
        const rowCount = document.getElementsByClassName('rowCount-cart');
        if (rowCount) {
          for (let index = 0; index < rowCount.length; index++) {
            var rows = 0;
            var lastid = rowCount[index].id;
            var split_id = lastid.split("_");
            rows = Number(split_id[1]);
            
            if (document.getElementById("cart[" + rows + "][id]").value == data.id) {
              plus_card(rows);
              return false;
            }
          }
        }

        return true;
      }
    </script>
<?php end_section('page-script-partials-cart'); ?>