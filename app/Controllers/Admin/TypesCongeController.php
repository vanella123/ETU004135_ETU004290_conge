<?php

namespace App\Controllers\Admin;

use App\Models\TypeCongeModel;
use CodeIgniter\Controller;

class TypesCongeController extends Controller
{
    public function index()
    {
        $model = new TypeCongeModel();
        return view('admin/types_conge', ['types' => $model->findAll()]);
    }

    public function store()
    {
        $model = new TypeCongeModel();
        $model->insert([
            'libelle'       => $this->request->getPost('libelle'),
            'jours_annuels' => (int)$this->request->getPost('jours_annuels'),
            'deductible'    => (int)$this->request->getPost('deductible'),
        ]);
        return redirect()->to('/admin/types-conge')->with('success', 'Type de congé créé.');
    }
}