<?php

namespace App\Controllers\Rh;

use App\Models\SoldeModel;
use CodeIgniter\Controller;

class SoldesController extends Controller
{
    public function index()
    {
        $soldeModel = new SoldeModel();
        $annee = (int)($this->request->getGet('annee') ?? date('Y'));
        $soldes = $soldeModel->getAllSoldesWithEmploye($annee);

        return view('rh/soldes', [
            'soldes' => $soldes,
            'annee'  => $annee,
        ]);
    }
}