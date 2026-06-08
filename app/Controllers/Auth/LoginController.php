<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;

/**
 * LoginController
 *
 * Custom login controller yang menggantikan route default Shield.
 * Menggunakan Shield sebagai backend auth tapi view-nya Phoenix Admin.
 *
 * Routes:
 *   GET  /login  → index()   (tampilkan form login)
 *   POST /login  → attempt() (proses autentikasi)
 *   POST /logout → logout()
 */
class LoginController extends BaseController
{
    /**
     * Tampilkan halaman login
     */
    public function index()
    {
        // Jika sudah login, langsung ke dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('dashboard');
        }

        return view('auth/login', [
            'title'      => 'Login',
            'validation' => $this->validator,
        ]);
    }

    /**
     * Proses login
     */
    public function attempt()
    {
        if (auth()->loggedIn()) {
            return redirect()->to('dashboard');
        }

        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required|min_length[3]'],
            'password' => ['label' => 'Password', 'rules' => 'required|min_length[6]'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $credentials = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
        ];

        $remember = (bool) $this->request->getPost('remember_me');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $result = $authenticator->remember($remember)->attempt($credentials);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah. Silakan coba lagi.');
        }

        $user = auth()->user();
        if (! $user->active) {
            auth()->logout();
            return redirect()->to('login')
                ->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }
        session()->setTempdata('login_success', true, 10);
        session()->setTempdata('user_name', $user->username ?? 'User', 10);
        $redirectUrl = session()->getTempdata('beforeLoginUrl') ?? 'dashboard';
        return redirect()->to($redirectUrl)
            ->with('success', 'Selamat datang, ' . ($user->username ?? 'User') . '!');
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();

        return redirect()->to('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
