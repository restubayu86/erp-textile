<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

/**
 * ForgotPasswordController
 *
 * Reset password dilakukan oleh administrator.
 * Halaman ini hanya menampilkan informasi kontak admin.
 *
 * Routes:
 *   GET  /forgot-password → index()
 */
class ForgotPasswordController extends BaseController
{
    public function index()
    {
        // Jika sudah login, tidak perlu akses halaman ini
        if (auth()->loggedIn()) {
            return redirect()->to('dashboard');
        }

        return view('auth/forgot_password', [
            'title'         => 'Lupa Password',
            // Sesuaikan dengan kontak admin perusahaan Anda
            'adminPhone'    => '+62 822-1911-1646',
            'adminEmail'    => 'mbcregency.3a@gmail.com',
            'adminLocation' => 'Ruang Dyeing / 3A',
        ]);
    }
}
