<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * ErrorController
 * Menangani halaman error custom dengan Phoenix Admin theme.
 *
 * Dipanggil dari:
 *  - Routes.php → $routes->set404Override('App\Controllers\ErrorController::notFound')
 *  - Routes.php → $routes->get('errors/403', ...)
 *  - Routes.php → $routes->get('errors/500', ...)
 *  - app/Config/Exceptions.php → untuk exception handler
 */
class ErrorController extends Controller
{
    protected $request;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
    }

    /**
     * 404 — Halaman tidak ditemukan
     * Dipanggil otomatis oleh set404Override
     */
    public function notFound(): ResponseInterface
    {
        return $this->response
            ->setStatusCode(404)
            ->setBody(view('errors/error_404', [
                'title'   => '404 — Halaman Tidak Ditemukan',
                'code'    => 404,
                'message' => 'Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.',
            ]));
    }

    /**
     * 403 — Akses ditolak
     */
    public function forbidden(): ResponseInterface
    {
        return $this->response
            ->setStatusCode(403)
            ->setBody(view('errors/error_403', [
                'title'   => '403 — Akses Ditolak',
                'code'    => 403,
                'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
            ]));
    }

    /**
     * 500 — Server error
     */
    public function serverError(): ResponseInterface
    {
        return $this->response
            ->setStatusCode(500)
            ->setBody(view('errors/error_500', [
                'title'   => '500 — Server Error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
            ]));
    }
}
