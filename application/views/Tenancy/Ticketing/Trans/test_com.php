<!-- --------------------------------------------- -->

<button onclick="sendCommand('OPEN')">OPEN</button>
<button onclick="sendCommand('CLOSE')">CLOSE</button>

 <!-- --------------------------------------------- -->

<script>
    function sendCommand(cmd, tcp = "127.0.0.1:8868") {
        fetch("<?= site_url('Tenancy/Ticketing/Testcom/send_plc') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ cmd: cmd, tcp: tcp })
        })
        .then(async res => {
            const text = await res.text();
            console.log(res.status);
            
            // alert("HTTP " + res.status + " | " + text);
        })
        .catch(err => {
            // alert("FETCH ERROR: " + err);
            console.log("FETCH ERROR: " + err);
        });
    }
</script>
