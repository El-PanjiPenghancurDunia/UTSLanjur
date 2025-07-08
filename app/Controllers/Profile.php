<?php

namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    /**
     * Menampilkan halaman untuk MENGEDIT profil pengguna.
     */
    public function index()
    {
        // Buat instance dari UserModel
        $userModel = new UserModel();

        // Ambil id pengguna dari session yang sedang login
        $userId = session()->get('id');

        // Siapkan data untuk dikirim ke view
        $data = [
            'title' => 'My Profile',
            'user'  => $userModel->find($userId) // Ambil data user berdasarkan id
        ];

        // Mengarahkan ke view 'app/Views/profile.php' (halaman edit)
        return view('profile', $data);
    }

    /**
     * =======================================================
     * METHOD BARU DITAMBAHKAN
     * =======================================================
     * Menampilkan halaman untuk HANYA MELIHAT profil pengguna.
     */
    public function view()
    {
        $userModel = new UserModel();
        $userId = session()->get('id');
        $data = [
            'title' => 'View Profile',
            'user'  => $userModel->find($userId)
        ];
        // Mengarahkan ke view 'app/Views/profile_view.php'
        return view('profile_view', $data);
    }


    /**
     * Memproses upload foto profil baru.
     */
    public function uploadPhoto()
    {
        // Aturan validasi untuk file yang diupload
        $rules = [
            'foto_profil' => [
                'rules' => 'uploaded[foto_profil]|max_size[foto_profil,2048]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih sebuah gambar untuk diupload.',
                    'max_size' => 'Ukuran gambar terlalu besar. Maksimal 2MB.',
                    'is_image' => 'File yang diupload bukan gambar.',
                    'mime_in'  => 'Hanya format JPG, JPEG, atau PNG yang diperbolehkan.'
                ]
            ]
        ];

        // Jalankan validasi
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali ke halaman profil dengan pesan error
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/profile');
        }

        // Ambil file yang diupload
        $imageFile = $this->request->getFile('foto_profil');

        // Jika file valid dan belum dipindahkan
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            // Ambil nama file lama untuk dihapus nanti
            $userModel = new UserModel();
            $userId = session()->get('id');
            $oldImageName = $userModel->find($userId)['foto_profil'];

            // Buat nama file baru yang acak untuk menghindari duplikasi
            $newImageName = $imageFile->getRandomName();

            // Pindahkan file ke folder public/NiceAdmin/assets/img/profiles/
            $imageFile->move(ROOTPATH . 'public/NiceAdmin/assets/img/profiles', $newImageName);

            // Update nama file di database
            $userModel->update($userId, ['foto_profil' => $newImageName]);

            // Hapus file foto lama jika bukan file default
            if ($oldImageName != 'default.jpg' && file_exists(ROOTPATH . 'public/NiceAdmin/assets/img/profiles/' . $oldImageName)) {
                unlink(ROOTPATH . 'public/NiceAdmin/assets/img/profiles/' . $oldImageName);
            }
            
            // Update session dengan nama foto baru
            session()->set('foto_profil', $newImageName);

            // Set pesan sukses
            session()->setFlashdata('success', 'Foto profil berhasil diubah.');
        }

        // Redirect kembali ke halaman profil
        return redirect()->to('/profile');
    }

    /**
     * Memproses perubahan password.
     */
    public function changePassword()
    {
        // Aturan validasi untuk form ganti password
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        // Jalankan validasi
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Gagal mengubah password. Periksa kembali input Anda.');
            return redirect()->to('/profile');
        }

        $userModel = new UserModel();
        $userId = session()->get('id');
        $user = $userModel->find($userId);

        // Ambil data dari form
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verifikasi password lama
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password Anda saat ini salah.');
            return redirect()->to('/profile');
        }

        // Hash password baru dan update ke database
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel->update($userId, ['password' => $hashedNewPassword]);

        session()->setFlashdata('success', 'Password berhasil diubah.');
        return redirect()->to('/profile');
    }
    
}
