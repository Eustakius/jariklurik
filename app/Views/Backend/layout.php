<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>jariklurik</title>
    <script src="<?= base_url('/assets/js/lib/tinymce/tinymce.min.js') ?>"></script>
    <link href="<?= base_url('/assets/css/remixicon.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/apexcharts.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/editor-katex.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/editor.atom-one-dark.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/editor.quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/flatpickr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/full-calendar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/jquery-jvectormap-2.0.5.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/magnific-popup.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/slick.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/prism.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/file-upload.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/audioplayer.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/datatables.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/dataTables.dataTables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/responsive.dataTables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/select2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/lib/parsley.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/css/app.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
    <script src="<?= base_url('/assets/js/lib/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/lib/select2.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/lib/datatables.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/lib/dataTables.responsive.js') ?>"></script>
    <script src="<?= base_url('/assets/js/lib/responsive.dataTables.js') ?>"></script>

    <?= $this->renderSection('pageStyles') ?>
</head>
<?php   
$auth = service('authentication');
?>
<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white text-sm sm:text-base md:text-lg">
    <?= $this->include('Backend/Partial/layout-sidebar') ?>
    <main role="main" class="dashboard-main">
        <?= view('Backend/Partial/layout-header', ['auth' => $auth]) ?>
        <?= $this->renderSection('main') ?>
        <?= $this->include('Backend/Partial/layout-footer') ?>
    </main>
    <script src="<?= base_url('assets/js/lib/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/iconify-icon.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/jquery-jvectormap-2.0.5.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/jquery-jvectormap-world-mill-en.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/magnifc-popup.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/slick.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/prism.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/file-upload.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/audioplayer.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/flowbite.min.js') ?>"></script>
    <script src="<?= base_url('js/flatpickr.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lib/parsleyjs.js') ?>"></script>
    <script>
    </script>
    <script>
        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle('.toggle-password');
        // ========================= Password Show Hide Js End ===========================
    </script>
    <?= $this->renderSection('pageScripts') ?>
    <svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
        <defs id="SvgjsDefs1002"></defs>
        <polyline id="SvgjsPolyline1003" points="0,0"></polyline>
        <path id="SvgjsPath1004" d="M0 0 "></path>
    </svg>
</body>

</html>