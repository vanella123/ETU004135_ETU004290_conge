<?php

namespace App\Controllers\Employe;

use App\Models\EmployeModel;
use CodeIgniter\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $employeId = session()->get('employe_id');
        $model = new EmployeModel();
        $employe = $model->find($employeId);
        return view('employe/profil', ['employe' => $employe]);
    }

    public function update()
    {
        $employeId = session()->get('employe_id');
        $model = new EmployeModel();

        $data = ['nom' => $this->request->getPost('nom'), 'prenom' => $this->request->getPost('prenom')];
        $newPwd = $this->request->getPost('password');
        if ($newPwd) {
            $data['password'] = password_hash($newPwd, PASSWORD_DEFAULT);
        }

        $model->update($employeId, $data);
        session()->set('nom', $data['nom']);
        session()->set('prenom', $data['prenom']);

        return redirect()->to('/employe/profil')->with('success', 'Profil mis à jour avec succès.');
    }
}