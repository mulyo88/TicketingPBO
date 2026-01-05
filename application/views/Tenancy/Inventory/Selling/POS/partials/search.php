<div class="input-group mx-1">
    <input type="text" id="search" class="form-control" placeholder="Search data" aria-label="Search data" aria-describedby="basic-addon2" onkeypress="show_data_cart_press(event)">
    <span type="button" class="input-group-text" id="clear_search" onclick="clear_search()" hidden><i class="fa-solid fa-times text-danger"></i></span>
    <select id="search_type" class="form-select border-start">
        <option value="">Search By Scan</option>
        <option value="search_code">Search By Code</option>
        <option value="search_name">Search By Name</option>
    </select>
    <select id="sort" class="form-select border-start">
        <option value="">Sort by</option>
        <option value="name_asc">Name (A-Z)</option>
        <option value="name_desc">Name (Z-A)</option>
    </select>
    <span type="button" class="input-group-text" id="btn-search" onclick="search_item()"><i class="fa-solid fa-search"></i></span>
</div>

<?php start_section('page-script-partials-search'); ?>
    <script>
        function clear_search() {
            document.getElementById("search").value = "";
            document.getElementById("clear_search").hidden = true;
        }

        function mode_btn_search(loading = false) {
            if (!loading) {
                document.getElementById("btn-search").innerHTML = '<i class="fa-solid fa-search"></i>';
            } else {
                document.getElementById("btn-search").innerHTML = '<div class="spinner-border spinner-border-sm"></div>';
            }
        }

        function show_data_cart_press(e) {
            if (e.keyCode === 13) { // where 13 is the enter button
                e.preventDefault(); //ignore submit

                search_item();
            }
        }
    </script>
<?php end_section('page-script-partials-search'); ?>