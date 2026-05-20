<?php

namespace App\Controllers\Admin;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\DepartementModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $congeModel = new CongeModel();
        $empModel   = new EmployeModel();
        $soldeModel = new SoldeModel();
        $deptModel  = new DepartementModel();

        $stats     = $congeModel->countByStatut();
        $absents   = $empModel->getAbsentsAujourdhui();
        $critiques = $soldeModel->getSoldesCritiques();
        $recentes  = array_slice($congeModel->getAllWithDetails(), 0, 6);
        $nbEmployes = count($empModel->getActifsWithDept());
        $nbDepts    = count($deptModel->findAll());
        $statsMois  = $congeModel->getStatsByMonth(date('Y'));

        return view('admin/dashboard', [
            'stats'      => $stats,
            'absents'    => $absents,
            'critiques'  => $critiques,
            'recentes'   => $recentes,
            'nbEmployes' => $nbEmployes,
            'nbDepts'    => $nbDepts,
            'statsMois'  => $statsMois,
        ]);
    }
}