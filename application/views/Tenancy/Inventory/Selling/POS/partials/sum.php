<div class="row">
    <label for="qty" class="col-sm-3 col-form-label fw-semibold text-capitalize">qty</label>
    <div class="col-sm-9">
        <input type="number" any="step" name="qty" id="qty" class="form-control <?= invalid5('qty') ?>" style="text-align: right;" placeholder="Qty" required readonly
        >
    </div>
</div>

<div class="row mt-2">
    <label for="total" class="col-sm-3 col-form-label fw-semibold text-capitalize">total</label>
    <div class="col-sm-9">
        <input type="text" name="total" id="total" class="form-control" style="text-align: right;" placeholder="Total" required readonly>
    </div>
</div>

<div class="row mt-2">
    <label for="discount" class="col-sm-3 col-form-label fw-semibold text-capitalize">discount</label>
    <div class="col-sm-9">
        <input type="number" any="step" name="discount" id="discount" class="form-control <?= invalid5('discount') ?>" style="text-align: right;" placeholder="Discount" required onkeyup="calculateQty()">
    </div>
</div>

<div class="row mt-2">
    <label for="tax" class="col-sm-3 col-form-label fw-semibold text-capitalize">tax</label>
    <div class="col-sm-9">
        <div class="input-group">
            <input type="number" any="step" name="tax" id="tax" class="form-control <?= invalid5('tax') ?>" style="text-align: right;" placeholder="Tax" aria-label="tax" aria-describedby="basic-addon2" required onkeyup="calculateQty()">
            <span class="input-group-text" id="basic-addon2">%</span>
        </div>
    </div>
</div>

<div class="row mt-2">
    <label for="grandtotal" class="col-sm-3 col-form-label fw-semibold text-capitalize">grandtotal</label>
    <div class="col-sm-9">
        <input type="text" name="grandtotal" id="grandtotal" class="form-control" style="text-align: right;" placeholder="Grandtotal" required readonly>
    </div>
</div>

<div class="row mt-2">
    <label for="payment" class="col-sm-3 col-form-label fw-semibold text-capitalize">payment</label>
    <div class="col-sm-9">
        <div class="input-group">
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="checkbox" id="is_payment" name="is_payment" onclick="calculateQty()">
            </div>
            <input type="number" any="step" name="payment" id="payment" class="form-control <?= invalid5('payment') ?>" style="text-align: right;" placeholder="Payment" required onkeyup="calculateQty()">
        </div>
    </div>
</div>

<div class="row mt-2">
    <label for="balance" class="col-sm-3 col-form-label fw-semibold text-capitalize">balance</label>
    <div class="col-sm-9">
        <input type="text" name="balance" id="balance" class="form-control" style="text-align: right;" placeholder="Balance" required readonly>
    </div>
</div>

<div class="row mt-2">
    <label for="method" class="col-sm-3 col-form-label fw-semibold text-capitalize">method</label>
    <div class="col-sm-9">
        <select name="method" id="method" class="form-select <?= invalid5('method') ?>" onchange="load_party(this)">
            <?php foreach ($method as $row): ?>
                <option value="<?=$row->name?>" data-id="<?=$row->id?>" data-party-type="<?=$row->note?>"><?=$row->name?></option>
            <?php endforeach ?>
        </select>

        <input type="hidden" name="party_type" id="party_type" class="form-control">
    </div>
</div>

<div class="row mt-2">
    <label for="method" class="col-sm-3 col-form-label fw-semibold text-capitalize">party</label>
    <div class="col-sm-9">
        <div style="display: flex; flex-direction: row;">
            <input type="hidden" name="order_id" id="order_id">
            <select name="party_id" id="party_id" class="form-select <?= invalid5('party_id') ?>"></select>
            <button id="btn_generate" type="button" class="btn text-capitalize" style="margin-left: 5px; background-color:black; color:white;" onclick="generate_party()">
                generate
            </button>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col">
        <div class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-danger"
                onclick="reload_data()"
            >
                <i class="fa-solid fa-undo me-1"></i>Cancel</button>
            <button type="submit" class="btn btn-success me-1"><i class="fa-solid fa-cart-shopping me-1"></i>Checkout</button>

            <div class="form-check me-4">
                <input type="checkbox" id="is_print" name="is_print" checked>
                <label class="form-check-label" for="is_print">
                    Print
                </label>
            </div>
        </div>
    </div>
</div>

<?php start_section('page-script-partials-sum'); ?>
    <script>
        init_sum();
        function init_sum() {
            document.getElementById("discount").value = formatDecimal(fn_i_discount);
            document.getElementById("tax").value = formatDecimal(fn_i_tax);
            document.getElementById("payment").value = fn_i_payment == null ? null : formatDecimal(fn_i_payment);
            document.getElementById("is_payment").value = fn_i_is_payment;
            document.getElementById("method").value = fn_i_method;
            document.getElementById("order_id").value = fn_i_order_id;

            load_party(document.getElementById("method"));
        }

        function calculateQty() {
            var qty = 0;
            var total = 0;
            const rowCount = document.getElementsByClassName('rowCount-cart');
            if (rowCount) {
                for (let index = 0; index < rowCount.length; index++) {
                    var rows = 0;
                    var lastid = rowCount[index].id;
                    var split_id = lastid.split("_");
                    rows = Number(split_id[1]);
                    
                    qty += parseFloat(xnumber(document.getElementById("cart[" + rows + "][qty]").value));
                    document.getElementById("cart[" + rows + "][subtotal]").innerHTML = formatNumber(parseFloat(xnumber(document.getElementById("cart[" + rows + "][qty]").value)) * parseFloat(xnumber(document.getElementById("cart[" + rows + "][harga_jual]").value)), 2);

                    total += parseFloat(xnumber(document.getElementById("cart[" + rows + "][subtotal]").innerHTML));
                }
            }

            document.getElementById("qty").value = qty;
            document.getElementById("total").value = formatNumber(total, 2);
            var discount = parseFloat(xnumber(document.getElementById("total").value) - xnumber(document.getElementById("discount").value));
            
            var tax = parseFloat(discount) + (parseFloat(discount) * parseFloat(xnumber(document.getElementById("tax").value)) / 100);
            document.getElementById("grandtotal").value = formatNumber(tax, 2);

            if (document.getElementById("is_payment").checked) {
                document.getElementById("payment").value = tax;
            }

            document.getElementById("balance").value = formatNumber(parseFloat(xnumber(document.getElementById("payment").value)) - parseFloat(xnumber(document.getElementById("grandtotal").value)), 2);
        }

        function load_party(select) {
            var selectedOption = select.options[select.selectedIndex];
            var id = selectedOption.getAttribute('data-id');
            var party_type = selectedOption.getAttribute('data-party-type');
            document.getElementById("party_type").value = party_type;
            document.getElementById("party_id").innerHTML = '';

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/get_party') ?>/" + id,

                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(data) {
                    if (data) {
                        if (data.model == "BANK") {
                            set_party_bank(data.party);
                        } else if (data.model == "PAYMENT_GATE") {
                            set_party_payment_gate(data.party);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }

        function set_party_bank(data) {
            const select = document.getElementById("party_id");
            for (let index = 0; index < data.length; index++) {
                const opt = document.createElement("option");
                opt.value = data[index].id;
                opt.textContent = data[index].account_name;

                if (data[index].id == fn_i_party_id) {
                    opt.selected = true;
                }
                
                select.appendChild(opt);
            }
        }

        function set_party_payment_gate(data) {
            const select = document.getElementById("party_id");
            for (let index = 0; index < data.length; index++) {
                const opt = document.createElement("option");
                opt.value = data[index].id;
                opt.textContent = data[index].name;

                if (data[index].id == fn_i_party_id) {
                    opt.selected = true;
                }

                select.appendChild(opt);
            }
        }

        let tab_generate = null;
        function generate_party() {
            var method = document.getElementById("method");
            var selectedOption = method.options[method.selectedIndex];
            var method_id = selectedOption.getAttribute('data-id');
            var method_type = selectedOption.getAttribute('data-party-type');
            var method_text = selectedOption.text;
            var party_id = document.getElementById("party_id").value;
            var building_id = fn_building.id;
            var value = xnumber(document.getElementById("grandtotal").value);

            if (value == 0 || value == null || value == '') {
                alert('Grandtotal cannot be 0');
                return;
            }

            if (method_type == null || method_type == '') {
                alert('Payment method not registered party type');
                return;
            }

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Payment_Gate/setup_payment_gate') ?>/" + method_id + "/" + party_id + "/" + building_id + "/" + value,

                beforeSend: function() {
                    const button = document.getElementById('btn_generate');
                    button.textContent = 'Wait!';
                },
                complete: function() {
                    const button = document.getElementById('btn_generate');
                    button.textContent = 'Generate';
                },
                success: function(data) {
                    if (data) {
                        if (data.msg == 'failed') {
                            alert('failed generate, please check your payment gate configuration');
                        } else {
                            document.getElementById("order_id").value = data.qris.order_id;

                            const x_data = encodeURIComponent(JSON.stringify(data));
                            const url = "<?= site_url('Tenancy/Inventory/Selling/POS/payment_gate?data=') ?>" + x_data + "&party_id=" + party_id;
                            openPaymentTab(url);
                        }
                    } else {
                        alert('no data result');
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }

        function openPaymentTab(url) {
            if (tab_generate && !tab_generate.closed) {
                tab_generate.close();
            }

            tab_generate = window.open(url, '_blank');

            if (tab_generate) {
                tab_generate.focus();
            }
        }
    </script>
<?php end_section('page-script-partials-sum'); ?>