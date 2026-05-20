<?php

namespace App\Controllers\Admin;

use App\Models\DepartementModel;
use CodeIgniter\Controller;

class DepartementsController extends Controller
{
    public function index()
    {
        $model = new DepartementModel();
        return view('admin/departements', ['depts' => $model->findAll()]);
    }

    public function store()
    {
        $model = new DepartementModel();
        $model->insert([
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);
        return redirect()->to('/admin/departements')->with('success', 'Département créé.');
    }

    public function delete(int $id)
    {
        $model = new DepartementModel();
        $model->delete($id);
        return redirect()->to('/admin/departements')->with('success', 'Département supprimé.');
    }
}