<?php
    if (isset($_GET['data'])) {
        $data = json_decode($_GET['data'], true);
    } else {
        $data = null;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">
        <title>Payment Gate</title>

        <?php include_view('templates/boot5/sections/style'); ?>
        <?php yield_section('page-style'); ?>

        <style>
            body { font-family: Arial; background:#f8f8f8; text-align:center; padding:40px; }
            .container { background:white; padding:30px; border-radius:16px; box-shadow:0 4px 8px rgba(0,0,0,0.1); display:inline-block; }
            img.qr { width:280px; height:280px; border:4px solid #ccc; border-radius:12px; }
            .status-box { margin-top:20px; font-size:18px; }
            .pending { color:orange; }
            .success { color:green; font-weight:bold; }
            .failed { color:red; font-weight:bold; }
            button { margin-top:20px; padding:12px 22px; font-size:16px; background:#007bff; border:none; border-radius:8px; color:white; cursor:pointer; }
            button:hover { background:#0056b3; }
            .timer { font-size:20px; font-weight:bold; margin-top:10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2 id="x_header" class="text-uppercase fw-bold mb-4">💳 Payment</h2>
            <div id="x_qris"></div>
            <div id="x_status"></div>
            <div id="x_timer"></div>
        </div>

        <?php include_view('templates/boot5/sections/script'); ?>
        <?php yield_section('page-script'); ?>
        <script src="<?php echo base_url() ?>assets/external/format-number.js"></script>

        <script>
            var data = <?= json_encode($data); ?>;
            var party_id = <?= json_encode($party_id); ?>;
            var html = "";

            if (data) {
                document.getElementById("x_header").innerHTML = '💳 ' + data.qris.payment_type + ' Payment';

                html = "";
                html += '<img src="' + data.qris_code + '" alt="QRIS Code" class="qr mb-2">';
                html += '<p class="text-capitalize fw-bold">amount: ' + data.qris.currency + ' ' + formatNumber(data.qris.gross_amount, 2) + '</p>';
                html += '<p>Scan with e-wallet (GoPay/OVO/DANA/LinkAja)</p>';
                document.getElementById("x_qris").innerHTML = html;

                html = "";
                html += '<div class="status-box">';
                    html += '<span>Transaction Status: <span id="status" class="pending">Payment Waiting...</span>';
                html += '</div">';
                document.getElementById("x_status").innerHTML = html;

                html = "";
                html += '<div class="timer">';
                    html += '<span>Time remaining : <span id="countdown">countdown...</span>';
                html += '</div">';
                document.getElementById("x_timer").innerHTML = html;
            } else {
                document.getElementById("x_header").innerHTML = '💳 Unfortunately there was an error';
                
                html = "";
                html += '<p style="color:red;">❌ Failed create QR code.</p>';
                document.getElementById("x_qris").innerHTML = html;
            }

            let timeLeft = 15 * 60; // second
            const countdownEl = document.getElementById('countdown');
            const orderId = data ? data.qris.order_id : 0;

            function updateTimer() {
                if (orderId != 0) {
                    if (timeLeft <= 0) {
                        countdownEl.textContent = "Time has run out!";
                        document.getElementById("status").textContent = "❌ Expired Transaction";
                        document.getElementById("status").className = "failed";
                        clearInterval(timerInterval);
                        clearInterval(statusInterval);
                        return;
                    }
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    countdownEl.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
                    timeLeft--;
                }
            }

            function refreshStatus(orderId) {
                if (orderId != 0) {
                        fetch("<?= site_url('Tenancy/API/GlobalAPI_Payment_Gate/payment_gate_qris_midtrans_status/') ?>" + orderId + "/" + party_id, {
                        headers:{'Accept':'application/json'}
                    })
                    .then(res=>{
                        if(!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data=>{
                        const el=document.getElementById("status");
                        if(!data.transaction_status) return;

                        switch(data.transaction_status){
                            case "pending":
                                el.textContent="Payment Waiting...";
                                el.className="pending";
                                break;
                            case "settlement":
                                el.textContent="✅ Payment Success!";
                                el.className="success";
                                clearInterval(timerInterval);
                                clearInterval(statusInterval);
                                break;
                            case "expire":
                            case "cancel":
                                el.textContent="❌ Payment Failed / Canceled.";
                                el.className="failed";
                                clearInterval(timerInterval);
                                clearInterval(statusInterval);
                                document.getElementById('countdown').innerHTML="Time has run out!";
                                break;
                        }
                    }).catch(err=>console.error("Fetch Error:",err));
                }
            }

            // Start interval
            const timerInterval = setInterval(updateTimer, 1000);
            const statusInterval = setInterval(refreshStatus(orderId), 5000);
        </script>
    </body>
</html>