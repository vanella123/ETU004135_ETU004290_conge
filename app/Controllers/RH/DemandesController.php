<?php

namespace App\Controllers\Rh;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\DepartementModel;
use CodeIgniter\Controller;

class DemandesController extends Controller
{
    public function index()
    {
        $congeModel = new CongeModel();
        $deptModel  = new DepartementModel();

        $statut  = $this->request->getGet('statut');
        $deptId  = (int)$this->request->getGet('dept');

        $demandes    = $congeModel->getAllWithDetails($statut ?: null, $deptId ?: null);
        $stats       = $congeModel->countByStatut();
        $departements = $deptModel->findAll();

        return view('rh/demandes', [
            'demandes'    => $demandes,
            'stats'       => $stats,
            'departements'=> $departements,
            'statut'      => $statut,
            'deptId'      => $deptId,
        ]);
    }

    public function approuver(int $id)
    {
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        $typeModel  = new TypeCongeModel();

        $conge = $congeModel->find($id);
        if (! $conge || $conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh/demandes')->with('error', 'Demande introuvable ou déjà traitée.');
        }

        $type = $typeModel->find($conge['type_conge_id']);

        if ($type && $type['deductible']) {
            $restant = $soldeModel->getRestant($conge['employe_id'], $conge['type_conge_id']);
            if ($restant < $conge['nb_jours']) {
                return redirect()->to('/rh/demandes')->with('error', "Solde insuffisant pour approuver cette demande ({$restant} j disponibles, {$conge['nb_jours']} j demandés).");
            }
            $soldeModel->deduire($conge['employe_id'], $conge['type_conge_id'], $conge['nb_jours']);
        }

        $congeModel->update($id, [
            'statut'    => 'approuvee',
            'traite_par' => session()->get('employe_id'),
            'commentaire_rh' => $this->request->getPost('commentaire') ?: 'Validé',
        ]);

        $nomEmp = $this->request->getPost('nom_employe') ?? 'l\'employé';
        return redirect()->to('/rh/demandes')->with('success', "Demande approuvée. Le solde a été mis à jour automatiquement.");
    }

    public function refuser(int $id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);
        if (! $conge || $conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh/demandes')->with('error', 'Demande introuvable ou déjà traitée.');
        }

        $congeModel->update($id, [
            'statut'         => 'refusee',
            'traite_par'     => session()->get('employe_id'),
            'commentaire_rh' => $this->request->getPost('commentaire') ?: 'Refusé par le RH',
        ]);

        return redirect()->to('/rh/demandes')->with('success', 'Demande refusée.');
    }
}