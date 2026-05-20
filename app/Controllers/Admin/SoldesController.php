<?php

namespace App\Controllers\Admin;

use App\Models\SoldeModel;
use CodeIgniter\Controller;

class SoldesController extends Controller
{
    public function index()
    {
        $model = new SoldeModel();
        $annee = (int)($this->request->getGet('annee') ?? date('Y'));
        return view('admin/soldes', [
            'soldes' => $model->getAllSoldesWithEmploye($annee),
            'annee'  => $annee,
        ]);
    }

    public function update()
    {
        $model = new SoldeModel();
        $id    = (int)$this->request->getPost('id');
        $model->update($id, [
            'jours_attribues' => (int)$this->request->getPost('jours_attribues'),
            'jours_pris'      => (int)$this->request->getPost('jours_pris'),
        ]);
        return redirect()->to('/admin/soldes')->with('success', 'Solde mis à jour.');
    }
}