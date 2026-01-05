<!DOCTYPE html>
<html>
<head>
    <title>Test QRIS Xendit (CI3)</title>
</head>
<body style="font-family: Arial; margin: 30px;">

<h2>Test QRIS Xendit - CodeIgniter 3</h2>

<form action="<?= base_url('Tenancy/PaymentGate/Xendit_Qris/create_qris') ?>" method="post">
    <button type="submit">Buat QRIS</button>
</form>

<?php if (isset($qris)) : ?>
    <hr>
    <h3>QRIS Created</h3>
    <pre><?php print_r($qris); ?></pre>

    <?php if (isset($qris['qr_string'])) : ?>
        <h4>QR Code</h4>
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($qris['qr_string']); ?>&size=200x200" alt="QRIS QR Code">
    <?php endif; ?>

    <?php if (isset($qris['id'])) : ?>
        <h4>Simulate Payment</h4>
        <a href="<?= base_url('Tenancy/PaymentGate/Xendit_Qris/simulate_payment/' . $qris['id']); ?>" target="_blank">Klik untuk Simulate Pembayaran</a>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
