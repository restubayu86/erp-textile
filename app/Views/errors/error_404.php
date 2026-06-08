<!DOCTYPE html>
<html lang="id" dir="ltr" data-navigation-type="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? '404 — Halaman Tidak Ditemukan') ?> — ERP Textile</title>
    <script src="<?= base_url('vendors/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/config.js') ?>"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="<?= base_url('vendors/simplebar/simplebar.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/theme.min.css') ?>" rel="stylesheet" id="style-default">
    <link href="<?= base_url('assets/css/user.min.css') ?>" rel="stylesheet">
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (!phoenixIsRTL) {
            document.getElementById('style-default').removeAttribute('disabled');
        }
    </script>
</head>

<body>
    <main class="main" id="top">
        <div class="px-3">
            <div class="row min-vh-100 flex-center text-center">
                <div class="col-12 col-xl-10">

                    <!-- Ilustrasi / kode error besar -->
                    <div class="d-flex flex-center mb-4">
                        <h1 class="display-1 fw-bold text-primary" style="font-size: 8rem; line-height: 1;">
                            404
                        </h1>
                    </div>

                    <h2 class="text-body-emphasis fw-bold mb-2">
                        Halaman Tidak Ditemukan
                    </h2>
                    <p class="text-body-tertiary mb-5">
                        <?= esc($message ?? 'Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.') ?>
                    </p>

                    <!-- Aksi -->
                    <div class="d-flex flex-center flex-wrap gap-3">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary px-5">
                            <span data-feather="home" class="me-2"></span>
                            Kembali ke Dashboard
                        </a>
                        <a href="javascript:history.back()" class="btn btn-phoenix-secondary px-5">
                            <span data-feather="arrow-left" class="me-2"></span>
                            Halaman Sebelumnya
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendors/bootstrap/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('vendors/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/phoenix.js') ?>"></script>
</body>

</html>