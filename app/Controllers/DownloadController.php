<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DocumentModel;

class DownloadController extends Controller
{
    public function index($fileName)
    {

        $documentModel = new DocumentModel();
        $documents = $documentModel->where('path', $fileName)->first();
        if ($documents == null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($documents['permission'] == '1') {
            $session = session();
            $user = $session->get('user');
    
            if ($user === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

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
