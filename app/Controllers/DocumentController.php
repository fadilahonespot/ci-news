<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\DocumentModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

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
        $categories = $categoryModel->where('user_id', $user['id'])->findAll();

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
            'path' => 'uploaded[path]|mime_in[path,image/jpg,image/jpeg,image/png,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.google-apps.document,application/vnd.google-apps.spreadsheet,application/vnd.google-apps.presentation,application/zip,application/x-zip-compressed,multipart/x-zip,application/x-rar-compressed,application/vnd.rar]|max_size[path,16024]',
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
            'permission' => $this->request->getPost('permission'),
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

    public function analyzeDocument($id)
    {
        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }

        // Memuat dokumen berdasarkan ID
        $documentModel = new DocumentModel();
        $document = $documentModel->where('id', $id)->first();
        if (!$document) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Dokumen tidak ditemukan.']);
        }

        // Membaca konten dokumen
        $filePath = FCPATH . 'uploads/' . $document['path'];
        $content = file_get_contents($filePath);

        // Validasi apakah konten sudah dalam format UTF-8
        if (!mb_check_encoding($content, 'UTF-8')) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Konten tidak dalam format UTF-8.']);
        }

        // Panggil API ChatGPT (Pseudo-code, ganti dengan panggilan API yang sesungguhnya)
        $apiUrl = 'https://api.openai.com/v1/engines/davinci-codex/completions';
        $apiKey = getenv('OPENAI_API_KEY'); // Retrieve from environment variable


        $response = $this->callChatgptApi($apiUrl, $apiKey, $content);

        // Validasi data JSON
        $jsonData = json_decode($response);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Gagal mem-parsing data JSON.']);
        }

        // Kirim hasil analisis kembali sebagai JSON
        return $this->response->setJSON(['analysis' => $jsonData]);
    }


    private function callChatgptApi($url, $apiKey, $content)
    {
        // Buat payload JSON sesuai dengan format yang diberikan
        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ["role" => "user", "content" => $content]
            ],
            "temperature" => 0.7
        ];

        // Konfigurasi opsi untuk permintaan HTTP
        $options = [
            "http" => [
                "header" => "Content-type: application/json\r\nAuthorization: Bearer " . $apiKey,
                "method" => "POST",
                "content" => json_encode($data)
            ]
        ];

        // Buat konteks aliran untuk permintaan HTTP
        $context  = stream_context_create($options);

        // Kirim permintaan HTTP ke URL yang ditentukan
        $result = file_get_contents($url, false, $context);

        // Periksa jika ada kesalahan dalam mengirim permintaan
        if ($result === FALSE) {
            return 'Error calling ChatGPT API.';
        }

        // Decode respons JSON
        $response = json_decode($result, true);

        // Kembalikan teks hasil dari respons
        return $response['choices'][0]['text'];
    }
}
