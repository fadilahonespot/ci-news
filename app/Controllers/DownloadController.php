<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;
use App\Models\DocumentModel;

class DownloadController extends Controller
{
    public function index($fileName)
    {

        $documentModel = new DocumentModel();
        $document = $documentModel->where('path', $fileName)->first();
        if ($document == null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($document['permission'] == '1' || $document['permission'] == '2') {
            $session = session();
            $user = $session->get('user');
    
            if ($user === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            if ($document['permission'] == '1') {
                $categoryModel = new CategoryModel();
                $category = $categoryModel->where('id', $document['category_id'])->first();

                if ($category === null || $user['id'] !== $category['user_id']) {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
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
