<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test QRCode Encrypt Descrypt</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        #barcode { margin-top: 20px; white-space: pre-line; background: #f3f3f3; padding: 10px; height: 200px; overflow-y: auto; }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<h2>Test QRCode Encrypt & Descrypt</h2>

<select name="type" id="type" class="form-control" style="padding: 5px;">
    <option value="Encrypt">Encrypt</option>
    <option value="Descrypt">Descrypt</option>
</select>

<input type="text" class="form-control" id="keyword" name="keyword" placeholder="Scan Barcode" style="padding: 5px;">

<button onclick="execute()" style="padding: 5px;">Execute</button>

<h3>Hasil Scan:</h3>
<div id="barcode">Belum ada data</div>

<script>
    function execute() {
        if (document.getElementById("type").value == 'Encrypt') {
            encrypt_scan();
        } else if (document.getElementById("type").value == 'Descrypt') {
            descript_scan();
        }
    }

    async function encrypt_scan() {
        document.getElementById("keyword").focus();

        let keyword = document.getElementById('keyword').value;
        if (!keyword || keyword === '') {
            alert('Please enter a keyword to search.');
            return;
        }

        try {
            const res = await encrypt(keyword);
            if (res.status === 'success') {
                document.getElementById("barcode").innerText = res.data;
            } else {
                alert(res.data);
                document.getElementById("barcode").innerText = "";
            }
        } catch (err) {
            console.log(err);
            alert(err);
            document.getElementById("barcode").innerText = "";
        }
    }

    function encrypt(keyword) {
        return new Promise((resolve, reject) => {
            var encryptedKeyword = encodeURIComponent(keyword);

            $.ajax({
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/encrypt_qr') ?>",
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

    async function descript_scan() {
        document.getElementById("keyword").focus();

        let keyword = document.getElementById('keyword').value;
        if (!keyword || keyword === '') {
            alert('Please enter a keyword to search.');
            return;
        }

        try {
            const res = await descript(keyword);
            if (res.status === 'success') {
                document.getElementById("barcode").innerText = res.data;
            } else {
                alert(res.data);
                document.getElementById("barcode").innerText = "";
            }
        } catch (err) {
            console.log(err);
            alert(err);
            document.getElementById("barcode").innerText = "";
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

</body>
</html>
