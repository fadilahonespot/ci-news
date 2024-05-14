<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DownloadController extends Controller
{
    public function index($fileName)
    {
        // Lokasi penyimpanan file yang dapat diakses melalui web
        $file = FCPATH . 'uploads/' . $fileName;

        // Cek apakah file ada
        if (file_exists($file)) {
            // Set header untuk memulai unduhan
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        } else {
            // Jika file tidak ditemukan, tampilkan halaman 404
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
