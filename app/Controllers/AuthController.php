<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model   = new EmployeModel();
        $employe = $model->findByEmail($email);

        if (! $employe || ! password_verify($password, $employe['password'])) {
            return redirect()->to('/login')->with('error', 'Identifiants incorrects. Veuillez réessayer.');
        }

        if (! $employe['actif']) {
            return redirect()->to('/login')->with('error', 'Votre compte est désactivé.');
        }

        session()->set([
            'logged_in'   => true,
            'employe_id'  => $employe['id'],
            'nom'         => $employe['nom'],
            'prenom'      => $employe['prenom'],
            'email'       => $employe['email'],
            'role'        => $employe['role'],
            'dept_id'     => $employe['departement_id'],
        ]);

        return $this->redirectByRole($employe['role']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Vous avez été déconnecté.');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'  => redirect()->to('/admin/dashboard'),
            'rh'     => redirect()->to('/rh/dashboard'),
            default  => redirect()->to('/employe/dashboard'),
        };
    }
}