<!-- this component using bootstrapt 5 (ario elvanda, 2025-10-24, poland) -->

<div id="flash-container">
    <div class="alert alert-danger alert-dismissible fade show shadow" role="alert" id="flash-alert">
        <i id="flash-icon" class="fa-solid fa-thumbs-up me-2"></i><span id="flash-information">information</span>
        <button type="button" class="btn-close" id="manualClose" aria-label="Close"></button>
    </div>
</div>

<?php start_section('style-flash'); ?>
    <style>
        #flash-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            display: none;
        }
    </style>
<?php end_section('style-flash'); ?>

<?php start_section('script-flash'); ?>
    <script>
        const flashContainer = document.getElementById('flash-container');
        const flashAlert = document.getElementById('flash-alert');
        const showBtn = document.getElementById('showBtn');
        const closeBtn = document.getElementById('closeBtn');
        const manualClose = document.getElementById('manualClose');

        function showAlert(type, information) {
            document.getElementById("flash-alert").classList.remove('alert-danger');
            document.getElementById("flash-alert").classList.remove('alert-warning');
            document.getElementById("flash-alert").classList.remove('alert-success');
            document.getElementById("flash-alert").classList.add('alert-' + type);

            document.getElementById("flash-icon").classList.remove('fa-exclamation-circle');
            document.getElementById("flash-icon").classList.remove('fa-exclamation-triangle');
            document.getElementById("flash-icon").classList.remove('fa-thumbs-up');

            if (type == 'danger') {
                document.getElementById("flash-alert").classList.add('fa-exclamation-circle');
            } else if (type == 'warning') {
                document.getElementById("flash-alert").classList.add('fa-exclamation-triangle');
            } else if (type == 'success') {
                document.getElementById("flash-alert").classList.add('fa-thumbs-up');
            }

            document.getElementById("flash-information").innerHTML = information;

            flashContainer.style.display = 'block';
            flashAlert.classList.add('show');
            flashAlert.classList.remove('d-none');

            setTimeout(hideAlert, 3000);
        }

        function hideAlert() {
            flashAlert.classList.remove('show');
            flashAlert.classList.add('d-none');
            flashContainer.style.display = 'none';
        }
    </script>
<?php end_section('script-flash'); ?>