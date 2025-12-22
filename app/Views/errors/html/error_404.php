<!doctype html>
<html lang="id">
<?php
$current_url = current_url();
$title = urlencode("Lihat halaman menarik ini!");
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../../../../favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?= $meta->title ?? 'jariklurik' ?></title>
    <meta name="robots" content="index, follow">
    <meta name="description" content="<?= $meta->description ?? 'Jariklurik - Job info menarik luar negeri sekali klik' ?>">
    <meta name="keywords" content="<?= $meta->keywords ?? 'Jariklurik - Job info menarik luar negeri sekali klik' ?>">
    
    <meta property="og:title" content="<?= $meta->title ?? 'jariklurik' ?>">
    <meta property="og:description" content="<?= $meta->description ?? 'Jariklurik - Job info menarik luar negeri sekali klik' ?>">
    <meta property="og:image" content="https://staging.jariklurik.id/image/jariklurik-share.png">
    <meta property="og:url" content="<?= $current_url ?? 'https://staging.jariklurik.id' ?>">
    <meta property="og:site_name" content="Jariklurik">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $meta->title ?? 'jariklurik' ?>">
    <meta name="twitter:description" content="<?= $meta->description ?? 'Jariklurik - Job info menarik luar negeri sekali klik' ?>">
    <meta name="twitter:image" content="https://staging.jariklurik.id/image/jariklurik-share.png">

    <link href="<?= base_url('/css/parsley.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/css/materialize.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/css/flatpickr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/css/app.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/css/style.css') ?>" rel="stylesheet">
    <script src="<?= base_url('/assets/js/lib/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('/js/materialize.min.js') ?>"></script>
    <script src="<?= base_url('/js/admin.js') ?>"></script>
    <script src="<?= base_url('js/parsleyjs.js') ?>"></script>
    <script src="<?= base_url('js/flatpickr.js') ?>"></script>
    <script src="<?= base_url('js/flowbite.min.js') ?>"></script>

</head>

<body>
    <!-- <div class="preloaderpage w-full h-full flex justify-center items-center fixed top-0 left-0 bg-[#EBC470] z-[9999]">
        <div class="loader w-[19.375rem] h-[19.375rem]"></div>
    </div> -->
    <?= view('Sections/header') ?>
    <main role="main relative z-10 ">
        <?= view('Sections/second-banner') ?>
        <?= view('Sections/not-found') ?>
    </main>
    <?= view('Sections/footer') ?>
</body>
<script>

</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/68edc6c2115d471943f50d3c/1j7gd0qbp';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->