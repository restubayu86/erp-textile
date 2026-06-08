<!DOCTYPE html>
<html lang="id" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="slim">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>Login — ERP Textile</title>

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
                        <!-- Logo / Ilustrasi -->
                        <div class="mb-4">
                            <span data-feather="layers" style="width:80px;height:80px;" class="text-primary"></span>
                        </div>
                        <h2 class="fw-bold text-body-emphasis mb-2">ERP Textile</h2>
                        <p class="text-body-tertiary fs-8 mb-0">
                            Sistem Informasi Terpadu<br>
                            Dyeing &amp; Finishing Textile
                        </p>
                        <!-- Fitur singkat -->
                        <div class="mt-5 d-flex flex-column gap-3 text-start">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge badge-phoenix badge-phoenix-success p-2">
                                    <span data-feather="users" style="width:14px;height:14px;"></span>
                                </span>
                                <span class="fs-9 text-body-secondary">Manajemen SDM &amp; Akses</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge badge-phoenix badge-phoenix-primary p-2">
                                    <span data-feather="activity" style="width:14px;height:14px;"></span>
                                </span>
                                <span class="fs-9 text-body-secondary">Monitoring Produksi Real-time</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge badge-phoenix badge-phoenix-warning p-2">
                                    <span data-feather="box" style="width:14px;height:14px;"></span>
                                </span>
                                <span class="fs-9 text-body-secondary">Kontrol Stok &amp; Formulasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Panel Kanan: Form Login ── -->
                <div class="col-12 col-lg-6 d-flex flex-center min-vh-100">
                    <div class="w-100 px-4" style="max-width: 420px;">

                        <!-- Header form -->
                        <div class="text-center mb-5">
                            <h3 class="fw-bold text-body-emphasis">Masuk ke Akun Anda</h3>
                            <p class="text-body-tertiary fs-9">Masukkan username dan password untuk melanjutkan</p>
                        </div>

                        <!-- Flash message: error umum -->
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <span data-feather="alert-circle" class="me-2"></span>
                                <?= esc((string)session('error')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Flash message: success (setelah logout) -->
                        <?php if (session()->has('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <span data-feather="check-circle" class="me-2"></span>
                                <?= esc((string)session('success')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form Login -->
                        <form action="<?= site_url('login') ?>" method="post" novalidate>
                            <?= csrf_field() ?>

                            <!-- Username -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="username">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <span data-feather="user" style="width:14px;height:14px;"></span>
                                    </span>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['username'])) ? 'is-invalid' : '' ?>"
                                        id="username"
                                        name="username"
                                        value="<?= old('username') ?>"
                                        placeholder="username Anda"
                                        autocomplete="username"
                                        autofocus>
                                    <?php if (session()->has('errors') && isset(session('errors')['username'])): ?>
                                        <div class="invalid-feedback"><?= esc((string)session('errors')['username']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="password">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <span data-feather="lock" style="width:14px;height:14px;"></span>
                                    </span>
                                    <input type="password"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['password'])) ? 'is-invalid' : '' ?>"
                                        id="password"
                                        name="password"
                                        placeholder="••••••••"
                                        autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                        title="Tampilkan/sembunyikan password">
                                        <span data-feather="eye" style="width:14px;height:14px;" id="eyeIcon"></span>
                                    </button>
                                    <?php if (session()->has('errors') && isset(session('errors')['password'])): ?>
                                        <div class="invalid-feedback"><?= esc((string)session('errors')['password']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Remember me + Forgot password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox"
                                        id="remember_me" name="remember_me" value="1">
                                    <label class="form-check-label fs-9" for="remember_me">
                                        Ingat saya
                                    </label>
                                </div>
                                <a href="<?= site_url('forgot-password') ?>" class="fs-9 text-decoration-none">
                                    Lupa password?
                                </a>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <span data-feather="log-in" class="me-2" style="width:14px;height:14px;"></span>
                                Masuk
                            </button>

                        </form>

                        <!-- Footer halaman login -->
                        <div class="text-center mt-4">
                            <p class="text-body-tertiary fs-10 mb-0">
                                ERP Textile Dyeing &amp; Finishing &copy; <?= date('Y') ?>
                            </p>
                        </div>

                    </div>
                </div>
                <!-- ── End Panel Kanan ── -->

            </div>
        </div>
    </main>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendors/popper/popper.min.js') ?>"></script>
    <script src="<?= base_url('vendors/bootstrap/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('vendors/lodash/lodash.min.js') ?>"></script>
    <script src="<?= base_url('vendors/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/phoenix.js') ?>"></script>

    <script>
        // Toggle show/hide password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-feather', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        });
    </script>
</body>

</html>