<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QRIS Payment - <?= ucfirst($mode) ?></title>
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
    <h2>💳 QRIS Payment (<?= ucfirst($mode) ?> Mode)</h2>
    <p>Order ID: <strong><?= $qris['transaction_details']['order_id'] ?? '' ?></strong></p>

    <?php if (!empty($qris_code)): ?>
        <img src="<?= $qris_code ?>" alt="QRIS Code" class="qr">
        <p>Scan dengan e-wallet (GoPay/OVO/DANA/LinkAja)</p>
    <?php else: ?>
        <p style="color:red;">❌ Gagal memuat QR code.</p>
        <pre><?php print_r($qris); ?></pre>
    <?php endif; ?>

    <div class="status-box">
        Status Transaksi: <span id="status" class="pending">Menunggu Pembayaran...</span>
    </div>

    <div class="timer">
        Waktu tersisa: <span id="countdown">15:00</span>
    </div>

    <?php if($mode==='sandbox'): ?>
        <button id="simulate">🧪 Simulate Payment</button>
    <?php endif; ?>
</div>

<script>
const orderId = "<?= $qris['transaction_details']['order_id'] ?? '' ?>";
const mode = "<?= $mode ?>";

// Timer setup (15 menit)
let timeLeft = 15 * 60; // detik
const countdownEl = document.getElementById('countdown');

function updateTimer() {
    if (timeLeft <= 0) {
        countdownEl.textContent = "Waktu Habis!";
        document.getElementById("status").textContent = "❌ Transaksi Expired";
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

// Refresh status setiap 5 detik
function refreshStatus() {
    fetch("<?= site_url('Tenancy/PaymentGate/Midtrans_Qris/check_status/') ?>" + orderId, {
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
                el.textContent="Menunggu Pembayaran...";
                el.className="pending";
                break;
            case "settlement":
                el.textContent="✅ Pembayaran Berhasil!";
                el.className="success";
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                break;
            case "expire":
            case "cancel":
                el.textContent="❌ Pembayaran Gagal / Dibatalkan.";
                el.className="failed";
                clearInterval(timerInterval);
                clearInterval(statusInterval);
                break;
        }
    }).catch(err=>console.error("Fetch Error:",err));
}

// Start interval
const timerInterval = setInterval(updateTimer, 1000);
const statusInterval = setInterval(refreshStatus, 5000);

// Tombol simulate hanya di sandbox
if(mode==='sandbox'){
    const btn=document.getElementById("simulate");
    if(btn){
        btn.onclick=function(){
            alert("Simulasi pembayaran sandbox. Production scan nyata diperlukan.");
        };
    }
}
</script>

</body>
</html>