<?php
// app/Controllers/DiskonController.php
namespace App\Controllers;

use App\Models\DiskonModel;

class DiskonController extends BaseController
{
    protected $diskonModel;

    function __construct()
    {
        $this->diskonModel = new DiskonModel();
        helper('number');
    }

    public function index()
    {
        $data['diskon'] = $this->diskonModel->findAll();
        return view('v_diskon', $data);
    }

    public function create()
    {
        $this->diskonModel->insert([
            'nama_diskon'   => $this->request->getPost('nama_diskon'),
            'jumlah_diskon' => $this->request->getPost('jumlah_diskon'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
        ]);
        return redirect('diskon')->with('success', 'Data Diskon Berhasil Ditambah');
    }

    public function edit($id)
    {
        $this->diskonModel->update($id, [
            'nama_diskon'   => $this->request->getPost('nama_diskon'),
            'jumlah_diskon' => $this->request->getPost('jumlah_diskon'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
        ]);
        return redirect('diskon')->with('success', 'Data Diskon Berhasil Diubah');
    }

    public function delete($id)
    {
        $this->diskonModel->delete($id);
        return redirect('diskon')->with('success', 'Data Diskon Berhasil Dihapus');
    }
}