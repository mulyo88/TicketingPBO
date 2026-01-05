<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QRIS Payment</title>
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
    <h2>💳 QRIS Payment</h2>
    <p>Order ID: <strong><?= $qris['transaction_details']['order_id'] ?? '' ?></strong></p>

    <?php if (!empty($qris_code)): ?>
        <img src="<?= $qris_code ?>" alt="QRIS Code" class="qr">
        <p>Scan dengan e-wallet (GoPay/OVO/DANA/LinkAja)</p>
    <?php else: ?>
        <p style="color:red;">❌ Gagal memuat QR code.</p>
        <pre><?php print_r($qris); ?></pre>
    <?php endif; ?>

    http code: <?= $http_code ?? ' not found http code' ?>
</div>

</body>
</html>
