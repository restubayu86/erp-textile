<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="slim">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">

  <!-- ===============================================-->
  <!--    Document Title-->
  <!-- ===============================================-->
  <title><?= esc((string)$title) ?></title>

  <!-- ===============================================-->
  <!--    Favicons-->
  <!-- ===============================================-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/favicons/apple-touch-icon.png') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/favicons/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicons/favicon-16x16.png') ?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/img/android-chrome-192x192.png') ?>">
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/img/favicons/favicon.ico') ?>">
  <link rel="manifest" href="<?= base_url('assets/img/favicons/manifest.json') ?>">
  <meta name="msapplication-TileImage" content="<?= base_url('assets/img/favicons/mstile-150x150.png') ?>">
  <meta name="theme-color" content="#ffffff">
  <meta name="viewport" content="width=device-width" />
  <script src="<?= base_url('vendors/simplebar/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/config.js') ?>"></script>
  <link href="<?= base_url('vendors/sweetalert2/themes/bootstrap-5.css') ?>" rel="stylesheet">
  <script>
    window.config.set({
      phoenixNavbarPosition: 'vertical',
      phoenixNavbarTopShape: 'slim',
      phoenixNavbarTopStyle: 'default'
    });
  </script>

  <!-- ===============================================-->
  <!--    Stylesheets-->
  <!-- ===============================================-->

  <link href="<?= base_url('vendors/choices/choices.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('vendors/prism/prism-okaidia.css') ?>" rel="stylesheet">
  <link href="<?= base_url('vendors/jquery-toast/jquery.toast.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('vendors/dropzone/dropzone.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('vendors/keith-wood-calc/jquery.calculator.css') ?>" rel="stylesheet" />

  <link rel="stylesheet" href="<?= base_url('vendors/select2-4.1.0/dist/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('vendors/select2-4.1.0/dist/css/select2-bootstrap-5-theme.min.css') ?>">

  <!-- ==================== DATATABLES CSS ==================== -->
  <link rel="stylesheet" href="<?= base_url('vendors/DataTables/css/dataTables.bootstrap5.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('vendors/DataTables/css/responsive.bootstrap5.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('vendors/DataTables/ext/Buttons-3.2.5/css/buttons.bootstrap5.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('vendors/DataTables/ext/RowGroup-1.6.0/css/rowGroup.bootstrap5.min.css') ?>">

  <link rel="stylesheet" href="<?= base_url('vendors/flatpickr/flatpickr.min.css') ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
  <link href="<?= base_url('vendors/simplebar/simplebar.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/theme-rtl.min.css') ?>" type="text/css" rel="stylesheet" id="style-rtl">
  <link href="<?= base_url('assets/css/theme.min.css') ?>" type="text/css" rel="stylesheet" id="style-default">
  <link href="<?= base_url('assets/css/user-rtl.min.css') ?>" type="text/css" rel="stylesheet" id="user-style-rtl">
  <link href="<?= base_url('assets/css/user.min.css') ?>" type="text/css" rel="stylesheet" id="user-style-default">
  <?= $this->renderSection('styles') ?>
  <script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
      var linkDefault = document.getElementById('style-default');
      var userLinkDefault = document.getElementById('user-style-default');
      linkDefault.setAttribute('disabled', true);
      userLinkDefault.setAttribute('disabled', true);
      document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
      var linkRTL = document.getElementById('style-rtl');
      var userLinkRTL = document.getElementById('user-style-rtl');
      linkRTL.setAttribute('disabled', true);
      userLinkRTL.setAttribute('disabled', true);
    }
  </script>
  <style>
    /* HEADER BULAN */
    .flatpickr-months {
      background: var(--phoenix-bg) !important;
    }

    .flatpickr-month {
      background: var(--phoenix-bg) !important;
    }

    .flatpickr-current-month {
      background: var(--phoenix-bg) !important;
      color: var(--phoenix-text) !important;
    }

    /* TEXT BULAN & TAHUN */
    .flatpickr-current-month .cur-month,
    .flatpickr-current-month input.cur-year {
      color: var(--phoenix-text) !important;
    }

    /* DROPDOWN BULAN */
    .flatpickr-monthDropdown-months {
      background: var(--phoenix-bg) !important;
      color: var(--phoenix-text) !important;
    }

    /* NAVIGATION ARROW AREA */
    .flatpickr-prev-month,
    .flatpickr-next-month {
      background: transparent !important;
    }

    /* WEEKDAY (SUN MON DST) */
    .flatpickr-weekdays {
      background: var(--phoenix-bg) !important;
    }

    /* =========================
   FLATPICKR - PHOENIX THEME
========================= */

    /* Container */
    .flatpickr-calendar {
      background: var(--phoenix-body-bg);
      color: var(--phoenix-body-color);
      border: 1px solid var(--phoenix-border-color);
      box-shadow: var(--phoenix-box-shadow);
    }

    /* Header (bulan + tahun) */
    .flatpickr-months .flatpickr-month {
      color: var(--phoenix-body-color);
    }

    .flatpickr-current-month {
      color: var(--phoenix-body-color);
    }

    .flatpickr-current-month .cur-month {
      color: var(--phoenix-body-color) !important;
    }

    .flatpickr-current-month input.cur-year {
      color: var(--phoenix-body-color) !important;
    }

    /* Prev / Next arrow */
    .flatpickr-prev-month,
    .flatpickr-next-month {
      color: var(--phoenix-body-color);
      fill: var(--phoenix-body-color);
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
      color: var(--phoenix-primary);
    }

    .flatpickr-prev-month:hover svg,
    .flatpickr-next-month:hover svg {
      fill: var(--phoenix-primary);
    }

    /* Weekdays (Sen, Sel, dst) */
    .flatpickr-weekday {
      color: var(--phoenix-secondary-color);
    }

    /* Day default */
    .flatpickr-day {
      color: var(--phoenix-body-color);
    }

    /* Hover */
    .flatpickr-day:hover {
      background: var(--phoenix-secondary-bg);
      border-color: var(--phoenix-border-color);
    }

    /* Today */
    .flatpickr-day.today {
      border-color: var(--phoenix-primary);
    }

    .flatpickr-day.today:hover {
      background: var(--phoenix-primary);
      color: #fff;
    }

    /* Selected */
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
      background: var(--phoenix-primary);
      border-color: var(--phoenix-primary);
      color: #fff;
    }

    /* Range highlight */
    .flatpickr-day.inRange {
      background: var(--phoenix-secondary-bg);
      box-shadow: none;
    }

    /* Disabled (FIX UTAMA kamu) */
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
      color: var(--phoenix-secondary-color) !important;
      opacity: 0.5;
    }

    /* Disabled hover (biar tidak berubah) */
    .flatpickr-day.flatpickr-disabled:hover {
      background: transparent;
      cursor: not-allowed;
    }

    /* Week number */
    .flatpickr-weekwrapper .flatpickr-day {
      color: var(--phoenix-secondary-color);
    }

    /* Time picker */
    .flatpickr-time input,
    .flatpickr-time .flatpickr-time-separator,
    .flatpickr-time .flatpickr-am-pm {
      color: var(--phoenix-body-color);
    }

    .flatpickr-time input:hover {
      background: var(--phoenix-secondary-bg);
    }

    /* Arrow dropdown month */
    .flatpickr-monthDropdown-months {
      color: var(--phoenix-body-color);
    }

    /* Fix border antar bulan */
    .dayContainer+.dayContainer {
      box-shadow: -1px 0 0 var(--phoenix-border-color);
    }

    .flatpickr * {
      color: inherit;
    }
  </style>
</head>

<body class="nav-slim">
  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top">
    <?= $this->include('templates/verticalbar') ?>
    <?= $this->include('templates/horizontalbar') ?>
    <script>
      var navbarVertical = document.querySelector('.navbar-vertical');
      navbarVertical.removeAttribute('style');
    </script>
    <div class="content">
      <?php $user = auth()->getUser(); ?>
      <?= $this->renderSection('content') ?>
    </div>
    <?= $this->include('templates/footer') ?>

  </main>

  <!-- ===============================================-->
  <!--    End of Main Content-->
  <!-- ===============================================-->

  <!-- ===============================================-->
  <!--    JavaScripts-->
  <!-- ===============================================-->
  <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
  <script>
    if (!jQuery.fn.andSelf) {
      jQuery.fn.andSelf = jQuery.fn.addBack;
    }
  </script>
  <script src="<?= base_url('vendors/popper/popper.min.js') ?>"></script>
  <script src="<?= base_url('vendors/bootstrap/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('vendors/anchorjs/anchor.min.js') ?>"></script>
  <script src="<?= base_url('vendors/is/is.min.js') ?>"></script>
  <script src="<?= base_url('vendors/fontawesome/all.min.js') ?>"></script>
  <script src="<?= base_url('vendors/lodash/lodash.min.js') ?>"></script>
  <script src="<?= base_url('vendors/list.js/list.min.js') ?>"></script>
  <script src="<?= base_url('vendors/feather-icons/feather.min.js') ?>"></script>
  <script src="<?= base_url('vendors/choices/choices.min.js') ?>"></script>
  <script src="<?= base_url('vendors/prism/prism.js') ?>"></script>
  <script src="<?= base_url('vendors/jquery-toast/jquery.toast.min.js') ?>"></script>
  <script src="<?= base_url('vendors/dropzone/dropzone-min.js') ?>"></script>
  <script src="<?= base_url('vendors/keith-wood-calc/jquery.plugin.js') ?>"></script>
  <script src="<?= base_url('vendors/keith-wood-calc/jquery.calculator.js') ?>"></script>
  <script src="<?= base_url('assets/js/phoenix.js') ?>"></script>

  <!-- DataTables -->
  <script src="<?= base_url('vendors/DataTables/js/dataTables.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/js/dataTables.bootstrap5.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/js/dataTables.responsive.min.js') ?>"></script>

  <!-- Export -->
  <script src="<?= base_url('vendors/DataTables/ext/JSZip/jszip.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/pdfmake/pdfmake.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/pdfmake/vfs_fonts.js') ?>"></script>

  <!-- Buttons -->
  <script src="<?= base_url('vendors/DataTables/ext/Buttons-3.2.5/js/dataTables.buttons.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/Buttons-3.2.5/js/buttons.bootstrap5.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/Buttons-3.2.5/js/buttons.html5.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/RowGroup-1.6.0/js/rowGroup.bootstrap5.min.js') ?>"></script>
  <script src="<?= base_url('vendors/DataTables/ext/Buttons-3.2.5/js/buttons.print.min.js') ?>"></script>

  <script src=" <?= base_url('vendors/flatpickr/flatpickr.min.js') ?>"></script>
  <script src="<?= base_url('vendors/apexcharts/dist/apexcharts.min.js'); ?>"></script>
  <script src="<?= base_url('vendors/jquery.repeater/jquery.repeater.min.js') ?>"></script>
  <script src="<?= base_url('vendors/select2-4.1.0/dist/js/select2.min.js') ?>"></script>
  <script src="<?= base_url('vendors/sweetalert2/dist/sweetalert2.all.min.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
</body>

</html>