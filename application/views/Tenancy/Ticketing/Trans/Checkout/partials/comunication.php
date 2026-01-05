<div class="p-2 rounded" style="background-color: rgba(255, 255, 255, 0.3); width: fit-content;">
    <span class="fw-bold me-3">JSON: <span id="address-json"><?= $json->name; ?></span></span>

    <span class="fw-bold text-danger" id="com-serial">
        COM: <?= $com->name; ?> (Disconnected)
    </span>


    <span
        id="connect-com-btn"
        class="px-2 py-1 rounded bg-danger text-white ms-2"
        style="cursor:pointer;"
    >
        Connect COM
    </span>
</div>





<?php start_section('page-style-partials-comunication'); ?>
    <style>
        #com-serial {
            transition: color 0.3s;
            font-weight: bold;
        }

        #connect-com-btn {
            transition: background-color 0.3s;
            user-select: none;
        }
    </style>
<?php end_section('page-style-partials-comunication'); ?>



<?php start_section('page-script-partials-comunication'); ?>
    <script>
        const comSpan = document.getElementById('com-serial');
        const connectBtn = document.getElementById('connect-com-btn');

        let port = null;

        function setComStatus(connected) {
            if (connected) {
                // TEXT COM
                comSpan.classList.remove('text-danger');
                comSpan.classList.add('text-success');
                comSpan.textContent = `COM: <?= $com->name; ?> (Connected)`;

                // BUTTON
                connectBtn.classList.remove('bg-danger');
                connectBtn.classList.add('bg-success');
                connectBtn.textContent = 'Connected';
            } else {
                comSpan.classList.remove('text-success');
                comSpan.classList.add('text-danger');
                comSpan.textContent = `COM: <?= $com->name; ?> (Disconnected)`;

                connectBtn.classList.remove('bg-success');
                connectBtn.classList.add('bg-danger');
                connectBtn.textContent = 'Connect COM';
            }
        }


        if (!('serial' in navigator)) {
            alert('Browser not support Web Serial API (Chrome / Edge)');
        }

        connectBtn.addEventListener('click', async () => {
            try {
                port = await navigator.serial.requestPort();
                await port.open({ baudRate: 9600 });

                setComStatus(true);
                listenToPort();
                add_information('Port open, waiting data...', 'bg-info');
            } catch (err) {
                console.error(err);
                setComStatus(false);
            }
        });

        navigator.serial.addEventListener('disconnect', e => {
            if (port === e.target) {
                port = null;
                setComStatus(false);
            }
        });

        async function listenToPort() {
            try {
                const textDecoder = new TextDecoder();
                const readerStream = port.readable.getReader();
                let buffer = "";

                add_information('Listening data COM...', 'bg-info');

                while (true) {
                    const { value, done } = await readerStream.read();
                    if (done) break;

                    const chunk = textDecoder.decode(value);
                    buffer += chunk;

                    if (buffer.includes("\n") || buffer.includes("\r")) {
                        const barcode = buffer.trim();

                        document.getElementById('keyword').value = barcode;
                        search_data_press({ keyCode: 13, preventDefault: () => {} });

                        // const input = document.getElementById("keyword");
                        // if (document.activeElement != input) {
                        //     search_data_press({ keyCode: 13, preventDefault: () => {} });
                        // }

                        buffer = "";
                    }
                }

            } catch (e) {
                add_information(e, 'bg-danger');
            }
        }

        function access_gate(cmd = "OPEN", tcp = "127.0.0.1:8868") {
            fetch("<?= site_url('Tenancy/Ticketing/Testcom/send_plc') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ cmd: cmd, tcp: tcp })
            })
            .then(async res => {
                const text = await res.text();
                addLog("HTTP " + res.status + " | " + text);
                
                // alert("HTTP " + res.status + " | " + text);
            })
            .catch(err => {
                // alert("FETCH ERROR: " + err);
                // console.log("FETCH ERROR: " + err);
                addLog("FETCH ERROR: " + err);
            });
        }

        function addLog(msg) {
            const log = document.getElementById("log");
            log.innerText += moment(new Date()).format('YYYY-MM-DD HH:mm:ss') + ' ' + msg + "\n";
            log.scrollTop = log.scrollHeight;
        }
    </script>
<?php end_section('page-script-partials-comunication'); ?>
