<!DOCTYPE html>
<html lang="id" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="slim">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>Lupa Password — ERP Textile</title>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/favicons/favicon-32x32.png') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/img/favicons/favicon.ico') ?>">

    <script src="<?= base_url('vendors/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/config.js') ?>"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <link href="<?= base_url('vendors/simplebar/simplebar.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/theme-rtl.min.css') ?>" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="<?= base_url('assets/css/theme.min.css') ?>" type="text/css" rel="stylesheet" id="style-default">
    <link href="<?= base_url('assets/css/user.min.css') ?>" type="text/css" rel="stylesheet">

    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            document.getElementById('style-default').setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            document.getElementById('style-rtl').setAttribute('disabled', true);
        }
    </script>
</head>

<body>
    <main class="main" id="top">
        <div class="container-fluid">
            <div class="row min-vh-100 flex-center">

                <!-- ── Panel Kiri: Branding ── -->
                <div class="col-12 col-lg-6 d-none d-lg-flex flex-center bg-body-tertiary min-vh-100">
                    <div class="text-center p-5">
                        <div class="mb-4">
                            <span data-feather="layers" style="width:80px;height:80px;" class="text-primary"></span>
                        </div>
                        <h2 class="fw-bold text-body-emphasis mb-2">ERP Textile</h2>
                        <p class="text-body-tertiary fs-8 mb-0">
                            Sistem Informasi Terpadu<br>
                            Dyeing &amp; Finishing Textile
                        </p>

                        <!-- Ilustrasi langkah -->
                        <div class="mt-5 d-flex flex-column gap-3 text-start">
                            <div class="d-flex align-items-start gap-3">
                                <span class="badge badge-phoenix badge-phoenix-primary p-2 mt-1">
                                    <span data-feather="message-circle" style="width:14px;height:14px;"></span>
                                </span>
                                <div>
                                    <p class="fs-9 fw-semibold text-body-emphasis mb-0">Hubungi Administrator</p>
                                    <p class="fs-10 text-body-secondary mb-0">Sampaikan username atau email akun Anda</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <span class="badge badge-phoenix badge-phoenix-warning p-2 mt-1">
                                    <span data-feather="user-check" style="width:14px;height:14px;"></span>
                                </span>
                                <div>
                                    <p class="fs-9 fw-semibold text-body-emphasis mb-0">Verifikasi Identitas</p>
                                    <p class="fs-10 text-body-secondary mb-0">Admin akan memverifikasi identitas Anda</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <span class="badge badge-phoenix badge-phoenix-success p-2 mt-1">
                                    <span data-feather="unlock" style="width:14px;height:14px;"></span>
                                </span>
                                <div>
                                    <p class="fs-9 fw-semibold text-body-emphasis mb-0">Password Direset</p>
                                    <p class="fs-10 text-body-secondary mb-0">Admin akan memberikan password baru untuk Anda</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Panel Kanan: Konten ── -->
                <div class="col-12 col-lg-6 d-flex flex-center min-vh-100">
                    <div class="w-100 px-4" style="max-width: 460px;">

                        <!-- Icon & Judul -->
                        <div class="text-center mb-3 mt-2">
                            <div class="mb-3">
                                <span class="badge badge-phoenix badge-phoenix-warning p-3"
                                    style="border-radius: 16px;">
                                    <span data-feather="lock" style="width:32px;height:32px;"></span>
                                </span>
                            </div>
                            <h3 class="fw-bold text-body-emphasis mb-2">Lupa Password?</h3>
                            <p class="text-body-tertiary fs-9 mb-0">
                                Reset password tidak dapat dilakukan sendiri.<br>
                                Hubungi Administrator sistem untuk bantuan.
                            </p>
                        </div>

                        <!-- Flash message -->
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <?= esc((string)session('error')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif ?>

                        <!-- Card Kontak Admin -->
                        <div class="card border-0 shadow-sm mb-2">
                            <div class="card-header bg-body-tertiary py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span data-feather="shield" style="width:16px;height:16px;" class="text-primary"></span>
                                    <h6 class="mb-0 fw-semibold">Hubungi Administrator</h6>
                                </div>
                            </div>
                            <div class="card-body py-4">

                                <!-- Info kontak -->
                                <div class="d-flex flex-column gap-3">

                                    <!-- WhatsApp -->
                                    <div class="d-flex align-items-center gap-3 p-3 bg-body-tertiary rounded-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-phoenix badge-phoenix-success p-2">
                                                <span data-feather="phone" style="width:16px;height:16px;"></span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fs-10 text-body-tertiary mb-0">WhatsApp / Telepon</p>
                                            <p class="fs-9 fw-semibold text-body-emphasis mb-0">
                                                <?= esc($adminPhone ?? '+62 xxx-xxxx-xxxx') ?>
                                            </p>
                                        </div>
                                        <?php if (!empty($adminPhone)): ?>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $adminPhone) ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-phoenix-success">
                                                Hubungi
                                            </a>
                                        <?php endif ?>
                                    </div>

                                    <!-- Email -->
                                    <div class="d-flex align-items-center gap-3 p-3 bg-body-tertiary rounded-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-phoenix badge-phoenix-primary p-2">
                                                <span data-feather="mail" style="width:16px;height:16px;"></span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fs-10 text-body-tertiary mb-0">Email</p>
                                            <p class="fs-9 fw-semibold text-body-emphasis mb-0">
                                                <?= esc($adminEmail ?? 'admin@erptextile.com') ?>
                                            </p>
                                        </div>
                                        <?php if (!empty($adminEmail)): ?>
                                            <a href="mailto:<?= esc($adminEmail) ?>"
                                                class="btn btn-sm btn-phoenix-primary">
                                                Kirim Email
                                            </a>
                                        <?php endif ?>
                                    </div>

                                    <!-- Lokasi / Ruangan -->
                                    <div class="d-flex align-items-center gap-3 p-3 bg-body-tertiary rounded-3">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-phoenix badge-phoenix-info p-2">
                                                <span data-feather="map-pin" style="width:16px;height:16px;"></span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fs-10 text-body-tertiary mb-0">Lokasi</p>
                                            <p class="fs-9 fw-semibold text-body-emphasis mb-0">
                                                <?= esc($adminLocation ?? 'Ruang IT / Kantor Administrasi') ?>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <!-- Pesan informasi -->
                                <div class="alert alert-phoenix-warning mt-4 mb-0 py-2 px-3 fs-9">
                                    <span data-feather="info" style="width:14px;height:14px;" class="me-1"></span>
                                    Siapkan <strong>username</strong> atau <strong>NIK karyawan</strong> Anda
                                    saat menghubungi administrator untuk mempercepat proses verifikasi.
                                </div>

                            </div>
                        </div>

                        <!-- Tombol kembali ke login -->
                        <div class="text-center">
                            <a href="<?= site_url('login') ?>"
                                class="btn btn-phoenix-secondary w-100">
                                <span data-feather="arrow-left" style="width:14px;height:14px;" class="me-2"></span>
                                Kembali ke Halaman Login
                            </a>
                        </div>

                        <!-- Footer halaman -->
                        <div class="text-center mt-4">
                            <p class="text-body-tertiary fs-10 mb-0">
                                ERP Textile Dyeing &amp; Finishing &copy; <?= date('Y') ?>
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendors/popper/popper.min.js') ?>"></script>
    <script src="<?= base_url('vendors/bootstrap/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('vendors/lodash/lodash.min.js') ?>"></script>
    <script src="<?= base_url('vendors/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/phoenix.js') ?>"></script>
</body>

</html>