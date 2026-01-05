<h1 id="information-text" class="text-center fw-bold text-muted p-2 rounded"></h1>

<?php start_section('page-style-partials-information'); ?>
    <style>
        
    </style>
<?php end_section('page-style-partials-information'); ?>

<?php start_section('page-script-partials-information'); ?>
    <script>
        clear_information();
        function clear_information() {
            document.getElementById('information-text').innerHTML = '';
        }

        function add_information(message, type='bg-dark') {
            clear_information();
            document.getElementById('information-text').innerHTML = message;

            const elemen = document.getElementById('information-text');
            if (elemen) {
                elemen.classList.remove('bg-dark');
                elemen.classList.remove('bg-danger');
                elemen.classList.remove('bg-light');
                elemen.classList.remove('bg-warning');
                elemen.classList.remove('bg-success');
                elemen.classList.remove('bg-primary');
                elemen.classList.remove('bg-info');

                elemen.classList.add(type);
            }
        }
    </script>
<?php end_section('page-script-partials-information'); ?>