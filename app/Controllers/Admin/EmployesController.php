<?php

namespace App\Controllers\Admin;

use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use CodeIgniter\Controller;

class EmployesController extends Controller
{
    public function index()
    {
        $empModel  = new EmployeModel();
        $deptModel = new DepartementModel();

        $employes = $empModel->getAllWithDept();
        $depts    = $deptModel->findAll();

        return view('admin/employes', [
            'employes' => $employes,
            'depts'    => $depts,
        ]);
    }

    public function store()
    {
        $empModel   = new EmployeModel();
        $soldeModel = new SoldeModel();

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => $this->request->getPost('role'),
            'departement_id' => (int)$this->request->getPost('departement_id'),
            'date_embauche'  => $this->request->getPost('date_embauche'),
            'actif'          => 1,
        ];

        if ($empModel->findByEmail($data['email'])) {
            return redirect()->to('/admin/employes')->with('error', 'Cet email est déjà utilisé.');
        }

        $id = $empModel->insert($data);
        $soldeModel->initSoldesEmploye($id);

        return redirect()->to('/admin/employes')->with('success', 'Employé créé avec succès. Les soldes ont été initialisés.');
    }

    public function edit(int $id)
    {
        $empModel  = new EmployeModel();
        $deptModel = new DepartementModel();
        return view('admin/employe_edit', [
            'employe' => $empModel->find($id),
            'depts'   => $deptModel->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $empModel = new EmployeModel();
        $data = [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'role'           => $this->request->getPost('role'),
            'departement_id' => (int)$this->request->getPost('departement_id'),
            'date_embauche'  => $this->request->getPost('date_embauche'),
        ];
        $pwd = $this->request->getPost('password');
        if ($pwd) $data['password'] = password_hash($pwd, PASSWORD_DEFAULT);

        $empModel->update($id, $data);
        return redirect()->to('/admin/employes')->with('success', 'Employé mis à jour.');
    }

    public function toggle(int $id)
    {
        $empModel = new EmployeModel();
        $emp = $empModel->find($id);
        $empModel->update($id, ['actif' => $emp['actif'] ? 0 : 1]);
        $msg = $emp['actif'] ? 'Employé désactivé.' : 'Employé réactivé.';
        return redirect()->to('/admin/employes')->with('success', $msg);
    }
}