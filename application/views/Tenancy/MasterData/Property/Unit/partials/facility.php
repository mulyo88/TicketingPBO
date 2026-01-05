<section class="content-facility">
    <table class="table table-bordered table-sm" style="margin-top: 20px;">
        <thead style="background-color:black; color:white;">
            <tr>
                <th class="text-center text-capitalize" style="padding:4px;">facility name</th>
                <th class="text-center text-capitalize" style="padding:4px; width:90px;">qty</th>
                <th class="text-center text-capitalize" style="padding:4px; width:90px;"></th>
            </tr>
        </thead>
        <tbody id="tbody-facility"></tbody>
    </table>

    <div style="display: flex; justify-content: center; align-items: center;">
        <button type="button" class="btn btn-sm align-middle" style="background-color:gray; color:white; margin-right: 5px;"
            data-toggle="modal" data-target="#modal-unit"
            onclick="openUnitCopy('facility')"
        >
            <i class="fa fa-copy"></i><span class = "text-capitalize" style="margin-left: 5px;">copy</span>
        </button>

        <button type="button" class="btn btn-sm align-middle" style="background-color:black; color:white; margin-right: 5px;"
            data-toggle="modal" data-target="#modal-unit-facility"
        >
            <i class="fa fa-plus"></i><span class = "text-capitalize" style="margin-left: 5px;">add new</span>
        </button>

        <button type="button" class="btn btn-danger btn-sm align-middle"
            onclick="clearFacility()"
        >
            <i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">clear</span>
        </button>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        var temp_unit_facility = <?php echo json_encode($tnc_mp_unit_has_unit_facility); ?>;
        if (temp_unit_facility) {
            if (temp_unit_facility.length == 0) {
                clearFacility();
            } else {
                loadData_unit_facility(temp_unit_facility);
            }
        } else {
            clearFacility();
        }
    });

    function clearFacility() {
        document.getElementById("tbody-facility").innerHTML="";

        var html="";
        html +='<tr>';
            html +='<td colspan="3" class="text-center text-capitalize text-muted" style="padding:3px;">No matching records found</td>';
        html +='</tr>';

        $("#tbody-facility").append(html);
        calculateFacility();
    }

    function removeFacility(rows) {
        if (document.getElementById("rowCount-facility_" + rows)) {
            document.getElementById("rowCount-facility_" + rows).remove();
        }

        var total_element = $(".rowCount-facility").length;
        if (total_element == 0) {
            clearFacility();
        }

        calculateFacility();
    }

    function calculateFacility() {
        var qty = 0;
        $('.facility_qty').each(function() {
            qty += parseFloat($(this).val());
        });

        document.getElementById("total-facility").innerHTML = qty == 0 ? '' : formatNumber(qty, 0);
    }

    function checkExistingFacility(id) {
        var exists = false;

        $(".rowCount-facility").each(function() {
            var split_id = $(this).attr("id").split("_");
            var rowIndex = split_id[1];
            var facility_id = document.getElementById("facility[" + rowIndex + "][facility_id]").value;

            if (facility_id == id) {
                exists = true;
                return false;
            }
        });

        return exists;
    }

    function selectFacility(id, name, qty = 1) {
        if (checkExistingFacility(id)) {
            alert('Facility already exists!');
            return;
        }

        var total_element = $(".rowCount-facility").length;
        if (total_element == 0) {
            document.getElementById("tbody-facility").innerHTML = "";
        }

        var total_element = $(".rowCount-facility").length;
        var rows = 1;

        if (total_element > 0) {
            var lastid = $(".rowCount-facility:last").attr("id");
            var split_id = lastid.split("_");
            rows = Number(split_id[1]) + 1;
        }

        var html="";
        html +='<tr class="rowCount-facility" id="rowCount-facility_' + rows + '">';
            html +='<td>';
                html +='<input type="hidden" class="form-control" id="facility[' + rows + '][facility_id]" name="facility[' + rows + '][facility_id]" value="' + id + '" />';

                html +='<input type="text" class="form-control" id="facility[' + rows + '][facility_name]" name="facility[' + rows + '][facility_name]" value="' + name + '" readonly />';
            html +='</td">';

            html +='<td>';
                html +='<input type="number" step="any" class="form-control facility_qty" id="facility[' + rows + '][facility_qty]" name="facility[' + rows + '][facility_qty]" value="' + qty + '" onkeyup="calculateFacility()" required />';
            html +='</td">';

            html +='<td>';
                html +='<button type="button" class="text-capitalize btn btn-danger btn-sm" onclick="removeFacility(' + rows + ')"><i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">remove</span></button>';
            html +='</td">';
        html +='</tr">';

        $("#tbody-facility").append(html);
        calculateFacility();
    }

    function loadData_unit_facility(data) {
        clearFacility();

        data.forEach(function(item) {
            selectFacility(item.unit_facility_id, item.name, formatDecimal(item.qty, 1));
        });
    }
</script>