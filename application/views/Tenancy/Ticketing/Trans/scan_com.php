<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>COM Serial Scanner Barcode</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        #barcode { font-size: 22px; padding: 10px; border: 1px solid #000; width: 300px; }
        #log { margin-top: 20px; white-space: pre-line; background: #f3f3f3; padding: 10px; height: 200px; overflow-y: auto; }
        button { padding: 10px 20px; font-size: 16px; }
    </style>
</head>
<body>

<h2>Scanner Barcode via COM Port (Web Serial API)</h2>

<button onclick="connectSerial()">CONNECT COM</button>

<h3>Hasil Scan:</h3>
<div id="barcode">Belum ada data</div>

<h3>Log:</h3>
<div id="log"></div>



<script>

// =====================================================================




// =======================================================================

    let port;
    let reader;

    async function connectSerial() {
        addLog("Meminta akses COM...");
        try {
            port = await navigator.serial.requestPort();
            await port.open({ baudRate: 9600 });

            addLog("Port terbuka. Menunggu data...");
            listenToPort();
        } catch (e) {
            addLog("ERROR: " + e);
        }
    }

    // async function listenToPort() {
    //     const decoder = new TextDecoderStream();
    //     const inputDone = port.readable.pipeTo(decoder.writable);
    //     const inputStream = decoder.readable;

    //     reader = inputStream.getReader();

    //     let buffer = "";

    //     try {
    //         while (true) {
    //             const { value, done } = await reader.read();
    //             if (done) break;

    //             buffer += value;

    //             // Barcode biasanya diakhiri ENTER (\n)
    //             if (buffer.includes("\n")) {
    //                 const barcode = buffer.trim();
    //                 document.getElementById("barcode").innerText = barcode;
    //                 addLog("SCAN: " + barcode);
    //                 buffer = "";
    //             }
    //         }
    //     } catch (e) {
    //         addLog("ERROR membaca: " + e);
    //     } finally {
    //         reader.releaseLock();
    //     }
    // }

    async function listenToPort() {
        try {
            const textDecoder = new TextDecoder();
            const readerStream = port.readable.getReader();
            let buffer = "";

            addLog("Mendengarkan data COM...");

            while (true) {
                const { value, done } = await readerStream.read();
                if (done) break;

                const chunk = textDecoder.decode(value);
                buffer += chunk;

                if (buffer.includes("\n") || buffer.includes("\r")) {
                    const barcode = buffer.trim();
                    document.getElementById("barcode").innerText = barcode;
                    addLog("SCAN: " + barcode);
                    buffer = "";
                }
            }

        } catch (e) {
            addLog("ERROR: " + e);
        }
    }


    function addLog(msg) {
        const log = document.getElementById("log");
        log.innerText += msg + "\n";
        log.scrollTop = log.scrollHeight;
    }
</script>

</body>
</html>
