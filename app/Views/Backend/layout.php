<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>jariklurik</title>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
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

    <style>
        @media (min-width: 1024px) {
            /* Smooth Transition */
            .sidebar, .dashboard-main {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Default (Sidebar OPEN) */
            body:not(.sidebar-toggled) .sidebar {
                left: 0 !important;
                width: 270px !important;
            }
            body:not(.sidebar-toggled) .dashboard-main {
                margin-left: 270px !important;
                width: calc(100% - 270px) !important;
            }

            /* Toggled (Sidebar CLOSED / MINI) */
            body.sidebar-toggled .sidebar {
                left: 0 !important;
                width: 80px !important; /* Mini width */
            }
            body.sidebar-toggled .dashboard-main {
                margin-left: 80px !important; /* Match mini width */
                width: calc(100% - 80px) !important;
            }

            /* Mini Sidebar Contents */
            body.sidebar-toggled .sidebar .sidebar-menuSpan, 
            body.sidebar-toggled .sidebar .sidebar-menu-group-title,
            body.sidebar-toggled .sidebar .sidebar-logo img:not(.logo-icon),
            body.sidebar-toggled .sidebar .sidebar-close-btn {
                display: none !important;
            }
            
            body.sidebar-toggled .sidebar span {
                display: none !important;
            }

            body.sidebar-toggled .sidebar .logo-icon {
                display: block !important;
                margin: 0 auto;
            }
            
            body.sidebar-toggled .sidebar > div:first-child {
                padding-inline: 0;
                display: flex;
                justify-content: center;
            }
            
            body.sidebar-toggled .sidebar .sidebar-menu {
                padding-inline: 0.5rem; /* Reduce padding for mini */
            }
            
            body.sidebar-toggled .sidebar .sidebar-menu li a {
                justify-content: center;
                padding-inline: 0;
            }
        }
        
        
        /* FLUENT DESIGN SIDEBAR OVERRIDES (THEME AWARE) */
        /* Default: LIGHT MODE */
        .sidebar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95); /* Light glass */
            border-inline-end: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: none !important;
        }

        /* DARK MODE */
        :is(.dark .sidebar) {
            background-color: rgba(31, 41, 55, 0.95) !important; /* Dark glass */
            border-inline-end: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Header Section */
        .sidebar > div:first-child {
            margin-block-end: 2rem;
            padding-top: 1rem;
        }

        /* Menu Container */
        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem; 
            padding-inline: 1.25rem;
        }

        /* Menu Items */
        .sidebar-menu li {
            margin: 0 !important;
            width: 100%;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Light Mode Text */
            color: #374151; /* Gray-700 */
        }

        /* Dark Mode Text */
        :is(.dark .sidebar-menu li a) {
            color: #d1d5db; /* Gray-300 */
        }

        /* Hover State (Light Mode) */
        .sidebar-menu li a:hover, .sidebar-menu li.active > a {
            background-color: rgba(0, 0, 0, 0.05);
            color: #111827; /* Gray-900 */
        }

        /* Hover State (Dark Mode) */
        :is(.dark .sidebar-menu li a:hover), :is(.dark .sidebar-menu li.active > a) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        /* Active Indicator (Left Bar) */
        .sidebar-menu li a:hover::before, .sidebar-menu li.active > a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 1.5rem;
            width: 3px;
            background-color: var(--primary);
            border-radius: 0 4px 4px 0;
            opacity: 1;
        }

        /* Icon Centering & Container */
        .sidebar-menu .menu-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px; 
            height: 40px;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        /* Typography Hierarchy (Group Titles) */
        .sidebar-menu-group-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            
            /* Light Mode Title */
            color: #6b7280; /* Gray-500 */
            
            margin-top: 1.5rem !important;
            margin-bottom: 0.5rem !important;
            padding-left: 1rem;
            font-weight: 600;
        }
        
        /* Dark Mode Title */
        :is(.dark .sidebar-menu-group-title) {
            color: #9ca3af; /* Gray-400 */
        }
    </style></head>
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
    <!-- <script src="<?= base_url('assets/js/lib/jquery-ui.min.js') ?>"></script> -->
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
        $(document).ready(function() {
            // FIX: Sidebar Toggle Logic
            // The user reported "wont shrink back", suggesting the toggle isn't removing the active class.
            
            // Toggle Button (Header)
            $('.sidebar-toggle').off('click').on('click', function(e) {
                e.preventDefault();
                // Toggle sidebar state on body or sidebar itself
                // Usually themes use a class on the body to control sidebar state
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('active'); 
                
                // Toggle Icons
                $(this).find('.icon').toggleClass('active non-active');
            });

            // Close Button (Sidebar X)
            $('.sidebar-close-btn').off('click').on('click', function(e) {
                e.preventDefault();
                $('body').removeClass('sidebar-toggled');
                $('.sidebar').removeClass('active');
                
                // Reset Header Toggle Icons
                $('.sidebar-toggle .icon[icon="heroicons:bars-3-solid"]').removeClass('non-active').addClass('active');
                $('.sidebar-toggle .icon[icon="iconoir:arrow-right"]').removeClass('active').addClass('non-active');
            });
        });
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