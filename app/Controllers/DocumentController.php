<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\DocumentModel;
use CodeIgniter\Controller;

class DocumentController extends Controller
{
    public function index($id)
    {
        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }

        $documentModel = new DocumentModel();
        $categoryModel = new CategoryModel();
        $categoriesyModel = new CategoryModel();

        // Tentukan jumlah item per halaman
        $perPage = 9;

        // Tentukan halaman saat ini
        $currentPage = $this->request->getVar('page') ?? 1;

        // Hitung offset
        $offset = ($currentPage - 1) * $perPage;

        // Mendapatkan data dokumen dengan paginasi berdasarkan category_id
        $documents = $documentModel->where('category_id', $id)->orderBy('created_at', 'DESC')->findAll($perPage, $offset);

        // Hitung total data
        $totalDocuments = $documentModel->where('category_id', $id)->countAllResults();

        // Mengambil data kategori dari model
        $categories = $categoriesyModel->where('user_id', $user['id'])->findAll();

        // Mendapatkan category
        $category = $categoryModel->where('id', $id)->first();

        // Hitung jumlah halaman yang diperlukan
        $totalPages = ceil($totalDocuments / $perPage);

        // Mengirim data ke view
        return view('document_view', [
            'documents' => $documents,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'categoryId' => $id,
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    public function uploadForm()
    {
        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }
        // Mengambil data kategori dari model
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('user_id', $user['id'])->findAll();

        // Mengambil data dokumen untuk ditampilkan
        $documentModel = new DocumentModel();
        $documents = $documentModel->orderBy('created_at', 'DESC')->paginate(10); // Mengambil 10 dokumen per halaman, diurutkan dari yang terbaru

        // Menampilkan view form upload dengan data kategori dan dokumen
        return view('upload_form', ['categories' => $categories, 'documents' => $documents, 'pager' => $documentModel->pager]);
    }

    public function upload()
    {
        // Validasi form upload
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'keterangan' => 'required',
            'path' => 'uploaded[path]|mime_in[path,image/jpg,image/jpeg,image/png,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[path,6024]',
            'categoryId' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembali ke form upload dengan pesan kesalahan
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Simpan file yang diupload
        $file = $this->request->getFile('path');
        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads', $newName);
        $date = date('Y-m-d H:i:s');

        // Simpan data dokumen ke dalam database
        $documentModel = new DocumentModel();
        $data = [
            'judul' => $this->request->getPost('judul'),
            'keterangan' => $this->request->getPost('keterangan'),
            'path' => $newName,
            'created_at' => $date,
            'category_id' => $this->request->getPost('categoryId'),
            'size' => $this->request->getPost('fileSize'),
        ];
        $documentModel->insert($data);

        // Ambil ID kategori untuk redirect
        $categoryId = $this->request->getPost('categoryId');

        $isHome = $this->request->getPost('home');
        if ($isHome == null) {
            // Redirect ke halaman upload dengan pesan sukses
            return redirect()->to('/category/' . $categoryId)->with('success', 'Document berhasil di upload.');
        }

        return redirect()->back()->with('success', 'Document berhasil di upload.');
    }

    public function updateDocument()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'keterangan' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $documentModel = new DocumentModel();
        $id = $this->request->getPost('id');

        // Cek apakah dokumen dengan ID yang diberikan ada di database
        $document = $documentModel->find($id);

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        // Update data dokumen
        $data = [
            'judul' => $this->request->getPost('judul'),
            'keterangan' => $this->request->getPost('keterangan'),
            'category_id' => $this->request->getPost('categoryId'),
        ];

        $documentModel->update($id, $data);

        return redirect()->back()->with('success', 'Document updated successfully.');
    }

    public function deleteDocument($id)
    {
        $documentModel = new DocumentModel();
        $document = $documentModel->find($id);

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        $documentModel->delete($id);

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
