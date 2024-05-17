<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    // show users list
    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->orderBy('id', 'DESC')->findAll();
        return view('user_view', $data);
    }
    // add user form
    public function create()
    {
        return view('add_user');
    }

    public function store()
    {
        $userModel = new UserModel();

        // Pengecekan apakah email sudah ada di database
        $existingUser = $userModel->where('email', $this->request->getVar('email'))->first();
        if ($existingUser) {
            // Jika email sudah ada, kembalikan pesan kesalahan
            return redirect()->to('/user-form')->with('errors', 'Email sudah digunakan. Silakan gunakan email lain.');
        }

        // Jika email belum ada, lanjutkan proses penyimpanan
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            'alamat'  => $this->request->getVar('alamat'),
            'no_hp'  => $this->request->getVar('no_hp'),
            'password' => $this->request->getVar('password'),
        ];

        $encrypted_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $encrypted_password;
        $userModel->insert($data);
        return redirect()->to('/loginForm')->with('success', 'Berhasil melakukan registrasi');
    }

    public function singleUser($id = null)
    {
        $userModel = new UserModel();
        $data['user_obj'] = $userModel->where('id', $id)->first();

        return view('edit_view', $data);
    }

    // update user data
    public function update()
    {
        $userModel = new UserModel();
        // Mendapatkan ID dari permintaan
        $id = $this->request->getVar('id');
        // Mengambil data pengguna berdasarkan ID
        $user = $userModel->find($id);
        // Periksa apakah pengguna ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
        }

        // Mengambil data yang diupdate dari permintaan
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            'alamat'  => $this->request->getVar('alamat'),
            'no_hp'  => $this->request->getVar('no_hp'),
        ];

        if ($user['email'] !== $data['email']) {
            $existingUser = $userModel->where('email', $this->request->getVar('email'))->first();
            if ($existingUser) {
                // Jika email sudah ada, kembalikan pesan kesalahan
                return redirect()->back()->with('error', 'Email sudah digunakan. Silakan gunakan email lain.');
            }
        }

        // Memperbarui data pengguna
        $userModel->update($id, $data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui');
    }

    // delete user
    public function delete($id = null)
    {
        $userModel = new UserModel();
        $data['user'] = $userModel->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('/home'));
    }

    public function loginForm()
    {
        return view('login_view');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        // Validasi email dan password
        if (!empty($email) && !empty($password)) {
            // Ambil data user dari database berdasarkan email
            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user === null) {
                return redirect()->back()->withInput()->with('error', 'User tidak ditemukan');
            }

            if (password_verify($password, $user['password'])) {
                // Login berhasil
                // Simpan data user ke dalam session
                $session = session();
                $session->set('user', $user);

                // Redirect ke halaman utama (atau ke halaman yang sesuai)
                return redirect()->to('/home')->with('success', 'Berhasil login ke dasboard');
            } else {
                // Login gagal, redirect kembali ke halaman login dengan pesan error
                return redirect()->back()->withInput()->with('error', 'Email atau password salah');
            }
        } else {
            // Jika email atau password kosong, redirect kembali ke halaman login dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Email dan password harus diisi');
        }
    }

    public function logout()
    {
        // Logout user, hapus data user dari session
        $session = session();
        $session->remove('user');

        // Redirect ke halaman login
        return redirect()->to('/')->withInput()->with('success', 'Berhasil logout');
    }

    public function profile()
    {
        // Mengambil data user dari session
        $session = session();
        $userSession = $session->get('user');
        if ($userSession === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('id', $userSession['id'])->first();

        $data = [
            'user' => $user,
        ];

        return view('profile_view', $data);
    }

    public function updatePassword()
    {
        // Memulai session
        $session = session();

        // Mengambil user dari session
        $user = $session->get('user');

        if ($user === null) {
            return redirect()->to('/loginForm')->with('error', 'Anda belum login');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'currentPassword' => 'required',
            'newPassword' => 'required|min_length[6]',
            'confirmNewPassword' => 'required|matches[newPassword]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Mendapatkan data input
        $currentPassword = (string) $this->request->getPost('currentPassword');
        $newPassword = (string) $this->request->getPost('newPassword');

        // Mengambil user dari database
        $userModel = new UserModel();
        $userData = $userModel->find($user['id']);

        // Memeriksa apakah password saat ini sesuai
        if (!password_verify($currentPassword, $userData['password'])) {
            return redirect()->back()->with('error', 'Password saat ini salah.');
        }

        // Mengupdate password baru
        $userModel->update($user['id'], ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);

        // Mengatur pesan sukses
        $session->setFlashdata('success', 'Password berhasil diubah.');

        // Redirect ke halaman profil atau halaman lainnya
        return redirect()->to('/profile');
    }
}
