<?php

namespace App\Controllers\Rh;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $congeModel = new CongeModel();
        $empModel   = new EmployeModel();
        $soldeModel = new SoldeModel();

        $stats    = $congeModel->countByStatut();
        $absents  = $empModel->getAbsentsAujourdhui();
        $critiques = $soldeModel->getSoldesCritiques();
        $recentes = array_slice($congeModel->getAllWithDetails(), 0, 5);

        return view('rh/dashboard', [
            'stats'    => $stats,
            'absents'  => $absents,
            'critiques'=> $critiques,
            'recentes' => $recentes,
        ]);
    }
}