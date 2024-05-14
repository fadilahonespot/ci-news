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
            return redirect()->to('/')->with('error', 'Anda belum login');
        }

        $userId = $user['id'];

        // Mengambil jumlah user dari model
        $userModel = new UserModel();
        $totalUsers = $userModel->countAll();

        // Mengambil data jumlah category dan total dokumen dari model
        $categoryModel = new CategoryModel();
        $documentsModel = new DocumentModel();

        // Mengambil data jumlah category dan total dokumen berdasarkan user_id
        $categoriesData = $categoryModel->select('category_id, COUNT(document.id) AS total_documents')
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

        // Mengambil dokumen terakhir berdasarkan category_id dengan pagination
        $lastDocuments = $documentsModel->whereIn('category_id', $categoryIdsArray)
            ->orderBy('id', 'DESC')
            ->paginate(6); // Menentukan jumlah dokumen per halaman

        // Menghitung total dokumen
        $totalDocuments = 0;
        foreach ($categoriesData as $category) {
            $totalDocuments += $category['total_documents'];
        }

        // Menyusun data untuk ditampilkan di view
        $data = [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalCategories' => count($categoriesData),
            'totalDocuments' => $totalDocuments,
            'totalSize' => $totalSize,
            'lastDocuments' => $lastDocuments
        ];

        // Menampilkan view home dengan data jumlah user, category, dan dokumen
        return view('home', $data);
    }
}
