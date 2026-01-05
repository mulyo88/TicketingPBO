<div class="col mb-2">
    <input type="radio" class="form-check-input btn-check" name="rb_category" id="category_0" autocomplete="off"
        onclick='select_category("ALL")'
        checked
        value="ALL"
    >
    <label class="btn btn-primary w-100 me-1 py-3" for="category_0">
        <i class="fa-solid fa-bookmark me-1"></i>
        ALL
    </label>
</div>

<?php foreach ($category as $row): ?>
    <div class="col mb-2">
        <input type="radio" class="form-check-input btn-check" name="rb_category" id="category_<?= $row->id; ?>" autocomplete="off"
            onclick='select_category("<?= $row->name; ?>")'
            value="<?= $row->name; ?>"
        >
        <label class="btn btn-primary w-100 me-1 py-3" for="category_<?= $row->id; ?>">
            <i class="fa-solid fa-bookmark me-1"></i>
            <?= $row->name; ?>
        </label>
    </div>
<?php endforeach ?>

<?php start_section('page-style-partials-category'); ?>
    <style>
        .form-check-input:checked + label {
            color: white !important;
            background-color: #BC00D4 !important;
            border-color: #BC00D4 !important;
        }
    </style>
<?php end_section('page-style-partials-category'); ?>

<?php start_section('page-script-partials-category'); ?>
    <script>
        function select_category(data) {
            var search_by = document.getElementById("search_type").value;
            var sort_by = document.getElementById("sort").value;
            var category = get_category_selected();

            list_item('', search_by, sort_by, category);
        }

        function get_category_selected() {
            const selected = document.querySelector('input[name="rb_category"]:checked');
            if (selected) {
                return selected.value;
            }
        }
    </script>
<?php end_section('page-script-partials-category'); ?>