<?php

namespace App\Controllers\Employe;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $employeId = session()->get('employe_id');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $stats    = $congeModel->countByStatut($employeId);
        $soldes   = $soldeModel->getSoldesEmploye($employeId);
        $derniers = array_slice($congeModel->getMesConges($employeId), 0, 5);

        return view('employe/dashboard', [
            'stats'   => $stats,
            'soldes'  => $soldes,
            'derniers' => $derniers,
        ]);
    }
}