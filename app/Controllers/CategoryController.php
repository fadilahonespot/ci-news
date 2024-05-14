<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/')->with('error', 'Anda belum login');
        }
        
        // Mengambil data kategori dari model
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('user_id', $user['id'])->findAll();

        // Menyusun data untuk ditampilkan di view
        $data = [
            'categories' => $categories
        ];

        // Menampilkan view categoryView dengan data kategori
        return view('category_view', $data);
    }

    public function save()
    {
        $categoryModel = new CategoryModel();

        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'user_id' => $user["id"],
        ];

        // Simpan data kategori
        $categoryModel->insert($data);

        // Simpan pesan sukses ke dalam session flash data
        session()->setFlashdata('success', 'Kategori berhasil ditambahkan');

        // Kembali ke halaman sebelumnya
        return redirect()->back();
    }

    public function delete($id)
    {
        // Instance model
        $categoryModel = new CategoryModel();

        // Cek apakah kategori tersedia
        $category = $categoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Kategori tidak ditemukan.');
            return redirect()->back();
        }

        // Hapus kategori
        $categoryModel->delete($id);

        // Redirect kembali ke halaman kategori
        session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->back();
    }

    public function update()
    {
        // Instance model
        $categoryModel = new CategoryModel();

        // Mengambil data user dari session
        $session = session();
        $user = $session->get('user');

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'user_id' => $user["id"],
        ];

        $id = $this->request->getPost('id');

        // Update data kategori
        $categoryModel->update($id, $data);

        // Redirect kembali ke halaman daftar kategori
        session()->setFlashdata('success', 'Kategori berhasil diperbarui.');
        return redirect()->back();
    }
}
