<?php

namespace App\Controllers\Admin;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use CodeIgniter\Controller;

class DemandesController extends Controller
{
    public function index()
    {
        $congeModel = new CongeModel();
        $deptModel  = new DepartementModel();

        $statut = $this->request->getGet('statut');
        $deptId = (int)$this->request->getGet('dept');

        return view('admin/demandes', [
            'demandes'    => $congeModel->getAllWithDetails($statut ?: null, $deptId ?: null),
            'stats'       => $congeModel->countByStatut(),
            'departements'=> $deptModel->findAll(),
            'statut'      => $statut,
            'deptId'      => $deptId,
        ]);
    }
}