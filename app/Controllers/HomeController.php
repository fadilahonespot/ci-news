<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\DocumentModel;
use CodeIgniter\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }

        $userId = $user['id'];

        // Mengambil jumlah user dari model
        $userModel = new UserModel();
        $totalUsers = $userModel->countAll();

        // Mengambil data jumlah category dan total dokumen dari model
        $categoryModel = new CategoryModel();
        $documentsModel = new DocumentModel();

        // Mengambil data jumlah category dan total dokumen berdasarkan user_id
        $categoriesData = $categoryModel->select('category.id as category_id, COUNT(document.id) AS total_documents')
            ->join('document', 'category.id = document.category_id', 'left')
            ->where('category.user_id', $userId)
            ->groupBy('category.id')
            ->findAll();

        // Menghitung total ukuran dokumen berdasarkan user_id
        $totalSize = 0;
        foreach ($categoriesData as $category) {
            $documents = $documentsModel->where('category_id', $category['category_id'])->findAll();
            foreach ($documents as $document) {
                // Menghapus "MB" dari string size dan mengonversi ke float
                $size = (float) str_replace('MB', '', $document['size']);
                // Menambahkan ke total ukuran
                $totalSize += $size;
            }
        }

        // Mengambil daftar category_id berdasarkan user_id
        $categoryIds = $categoryModel->select('id')->where('user_id', $userId)->findAll();

        // Mendapatkan array dari daftar category_id
        $categoryIdsArray = array_column($categoryIds, 'id');

        // Tentukan jumlah item per halaman
        $perPage = 9;

        // Tentukan halaman saat ini
        $currentPage = is_numeric($this->request->getVar('page')) ? (int)$this->request->getVar('page') : 1;

        // Hitung offset
        $offset = ($currentPage - 1) * $perPage;

        // Mengambil parameter pencarian
        $searchQuery = $this->request->getVar('q') ? trim($this->request->getVar('q')) : '';

        // Validasi jika categoryIdsArray tidak kosong
        if (!empty($categoryIdsArray)) {
            // Mengambil dokumen terakhir berdasarkan category_id dengan pagination dan pencarian
            $documentQuery = $documentsModel->select('document.*, category.id as category_id, category.nama as category_name')
                ->join('category', 'category.id = document.category_id')
                ->whereIn('category_id', $categoryIdsArray)
                ->orderBy('document.id', 'DESC');

            // Jika ada kata kunci pencarian, tambahkan kondisi pencarian
            if ($searchQuery) {
                $documentQuery->like('document.judul', $searchQuery);
            }

            $lastDocuments = $documentQuery->findAll($perPage, $offset);

            // Hitung total dokumen
            $totalDocuments = 0;
            foreach ($categoriesData as $category) {
                $totalDocuments += $category['total_documents'];
            }

            // Jika ada kata kunci pencarian, hitung ulang total dokumen yang sesuai dengan pencarian
            if ($searchQuery) {
                $totalDocuments = $documentsModel->selectCount('document.id')
                    ->join('category', 'category.id = document.category_id')
                    ->whereIn('category_id', $categoryIdsArray)
                    ->like('document.judul', $searchQuery)
                    ->countAllResults(false);
            } else {
                $totalDocuments = $documentsModel->selectCount('document.id')
                    ->join('category', 'category.id = document.category_id')
                    ->whereIn('category_id', $categoryIdsArray)
                    ->countAllResults(false);
            }
        } else {
            // Jika categoryIdsArray kosong, set lastDocuments dan totalDocuments ke nilai default
            $lastDocuments = [];
            $totalDocuments = 0;
        }

        // Mengambil data kategori dari model
        $categories = $categoryModel->where('user_id', $userId)->findAll();

        // Hitung jumlah halaman yang diperlukan
        $totalPages = ceil($totalDocuments / $perPage);

        // Menyusun data untuk ditampilkan di view
        $data = [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalCategories' => count($categoriesData),
            'totalDocuments' => $totalDocuments,
            'totalSize' => $totalSize,
            'lastDocuments' => $lastDocuments,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'searchQuery' => $searchQuery, // Tambahkan ini untuk menyimpan kata kunci pencarian di view
        ];

        // Menampilkan view home dengan data jumlah user, category, dan dokumen
        return view('home', $data);
    }
}
