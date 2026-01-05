<section class="content-owner">
    <table class="table table-bordered table-sm" style="margin-top: 20px;">
        <thead style="background-color:black; color:white;">
            <tr>
                <th class="text-center text-capitalize" style="padding:4px;">owner name</th>
                <th class="text-center text-capitalize" style="padding:4px; width:90px;"></th>
            </tr>
        </thead>
        <tbody id="tbody-owner"></tbody>
    </table>

    <div style="display: flex; justify-content: center; align-items: center;">
        <button type="button" class="btn btn-sm align-middle" style="background-color:gray; color:white; margin-right: 5px;"
            data-toggle="modal" data-target="#modal-unit"
            onclick="openUnitCopy('owner')"
        >
            <i class="fa fa-copy"></i><span class = "text-capitalize" style="margin-left: 5px;">copy</span>
        </button>

        <button type="button" class="btn btn-sm align-middle" style="background-color:black; color:white; margin-right: 5px;"
            data-toggle="modal" data-target="#modal-owner"
        >
            <i class="fa fa-plus"></i><span class = "text-capitalize" style="margin-left: 5px;">add new</span>
        </button>

        <button type="button" class="btn btn-danger btn-sm align-middle"
            onclick="clearOwner()"
        >
            <i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">clear</span>
        </button>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){
        var temp_owner = <?php echo json_encode($tnc_mp_unit_has_owner_property); ?>;
        if (temp_owner) {
            if (temp_owner.length == 0) {
                clearOwner();
            } else {
                loadData_owner(temp_owner);
            }
        } else {
            clearOwner();
        }
    });

    function clearOwner() {
        document.getElementById("tbody-owner").innerHTML="";

        var html="";
        html +='<tr>';
            html +='<td colspan="2" class="text-center text-capitalize text-muted" style="padding:3px;">No matching records found</td>';
        html +='</tr>';

        $("#tbody-owner").append(html);
        calculateOwner();
    }

    function removeOwner(rows) {
        if (document.getElementById("rowCount-owner_" + rows)) {
            document.getElementById("rowCount-owner_" + rows).remove();
        }

        var total_element = $(".rowCount-owner").length;
        if (total_element == 0) {
            clearOwner();
        }

        calculateOwner();
    }

    function calculateOwner() {
        document.getElementById("total-owner").innerHTML = $(".rowCount-owner").length == 0 ? '' : formatNumber($(".rowCount-owner").length, 0);
    }

    function checkExistingOwner(id) {
        var exists = false;

        $(".rowCount-owner").each(function() {
            var split_id = $(this).attr("id").split("_");
            var rowIndex = split_id[1];
            var owner_id = document.getElementById("owner[" + rowIndex + "][owner_id]").value;

            if (owner_id == id) {
                exists = true;
                return false;
            }
        });

        return exists;
    }

    function selectOwner(id, name) {
        if (checkExistingOwner(id)) {
            alert('Owner already exists!');
            return;
        }

        var total_element = $(".rowCount-owner").length;
        if (total_element == 0) {
            document.getElementById("tbody-owner").innerHTML = "";
        }

        var total_element = $(".rowCount-owner").length;
        var rows = 1;

        if (total_element > 0) {
            var lastid = $(".rowCount-owner:last").attr("id");
            var split_id = lastid.split("_");
            rows = Number(split_id[1]) + 1;
        }

        var html="";
        html +='<tr class="rowCount-owner" id="rowCount-owner_' + rows + '">';
            html +='<td>';
                html +='<input type="hidden" class="form-control" id="owner[' + rows + '][owner_id]" name="owner[' + rows + '][owner_id]" value="' + id + '" />';

                html +='<input type="text" class="form-control" id="owner[' + rows + '][owner_name]" name="owner[' + rows + '][owner_name]" value="' + name + '" readonly />';
            html +='</td">';

            html +='<td>';
                html +='<button type="button" class="text-capitalize btn btn-danger btn-sm" onclick="removeOwner(' + rows + ')"><i class="fa fa-trash"></i><span class = "text-capitalize" style="margin-left: 5px;">remove</span></button>';
            html +='</td">';
        html +='</tr">';

        $("#tbody-owner").append(html);
        calculateOwner();
    }

    function loadData_owner(data) {
        clearOwner();

        data.forEach(function(item) {
            selectOwner(item.owner_property_id, item.name);
        });
    }
</script>