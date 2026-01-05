function export_data(tbl) {
    let table = document.getElementById(tbl);

    let exportTable = document.createElement("table");
    exportTable.border = "1";

    Array.from(table.rows).forEach(row => {

        if (row.offsetParent === null) return;

        let newRow = document.createElement("tr");

        Array.from(row.cells).forEach(cell => {

            // 🔥 SKIP cell dengan export=false
            if (cell.getAttribute("export") === "false") return;

            // skip cell hidden by CSS
            let style = window.getComputedStyle(cell);

            let hidden = (
                style.display === "none" ||
                style.visibility === "hidden" ||
                cell.offsetWidth === 0 ||
                cell.offsetHeight === 0
            );
            if (hidden) return;

            // clone visible cell
            let newCell = document.createElement(cell.tagName);

            // remove hidden span inside
            let cloneHTML = cell.cloneNode(true);
            cloneHTML.querySelectorAll(
                "span[hidden], span[hidden='true'], span[style*='display:none'], span[style*='visibility:hidden']"
            ).forEach(sp => sp.remove());

            newCell.innerHTML = cloneHTML.innerHTML;

            if (cell.colSpan > 1) newCell.colSpan = cell.colSpan;
            if (cell.rowSpan > 1) newCell.rowSpan = cell.rowSpan;

            newRow.appendChild(newCell);
        });

        if (newRow.cells.length > 0) exportTable.appendChild(newRow);
    });

    // Create Excel file
    let html = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">
        <head><meta charset="UTF-8"></head>
        <body>${exportTable.outerHTML}</body></html>
    `;

    let blob = new Blob([html], { type: "application/vnd.ms-excel" });
    let a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = tbl + "_" + moment(new Date()).format('DD_MM_YYYY_HH_mm_ss') + ".xls";
    a.click();
}