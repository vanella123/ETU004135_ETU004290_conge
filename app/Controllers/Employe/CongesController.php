<?php

namespace App\Controllers\Employe;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use CodeIgniter\Controller;

class CongesController extends Controller
{
    public function index()
    {
        $employeId  = session()->get('employe_id');
        $congeModel = new CongeModel();
        $statut = $this->request->getGet('statut');

        $conges = $congeModel->getMesConges($employeId);
        if ($statut) {
            $conges = array_filter($conges, fn($c) => $c['statut'] === $statut);
        }

        $statsType = $congeModel->getStatsByTypeConge($employeId);

        return view('employe/conges', [
            'conges'    => $conges,
            'statut'    => $statut,
            'statsType' => $statsType,
        ]);
    }

    public function create()
    {
        $employeId  = session()->get('employe_id');
        $soldeModel = new SoldeModel();
        $typeModel  = new TypeCongeModel();

        $soldes = $soldeModel->getSoldesEmploye($employeId);
        $types  = $typeModel->findAll();

        return view('employe/create', [
            'soldes' => $soldes,
            'types'  => $types,
        ]);
    }

    public function store()
    {
        $employeId   = session()->get('employe_id');
        $congeModel  = new CongeModel();
        $soldeModel  = new SoldeModel();
        $typeModel   = new TypeCongeModel();

        $typeCongeId = (int)$this->request->getPost('type_conge_id');
        $dateDebut   = $this->request->getPost('date_debut');
        $dateFin     = $this->request->getPost('date_fin');
        $motif       = $this->request->getPost('motif');

        // Validations
        if (! $typeCongeId || ! $dateDebut || ! $dateFin) {
            return redirect()->to('/employe/conges/create')->with('error', 'Tous les champs obligatoires doivent être remplis.');
        }

        if ($dateDebut > $dateFin) {
            return redirect()->to('/employe/conges/create')->with('error', 'La date de fin doit être après la date de début.');
        }

        // Préavis 48h
        if (strtotime($dateDebut) < strtotime('+48 hours')) {
            return redirect()->to('/employe/conges/create')->with('error', 'Un préavis minimum de 48h est requis.');
        }

        $nbJours = $congeModel->calculerJours($dateDebut, $dateFin);

        // Vérif chevauchement
        if ($congeModel->hasChevauche($employeId, $dateDebut, $dateFin)) {
            return redirect()->to('/employe/conges/create')->with('error', 'Chevauchement détecté avec une demande existante.');
        }

        // Vérif solde
        $type = $typeModel->find($typeCongeId);
        if ($type && $type['deductible']) {
            $restant = $soldeModel->getRestant($employeId, $typeCongeId);
            if ($restant < $nbJours) {
                return redirect()->to('/employe/conges/create')->with('error', "Solde insuffisant : {$restant} jour(s) disponible(s), {$nbJours} demandé(s).");
            }
        }

        $congeModel->insert([
            'employe_id'    => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'nb_jours'      => $nbJours,
            'motif'         => $motif,
            'statut'        => 'en_attente',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/employe/dashboard')->with('success', 'Votre demande de congé a bien été soumise. Elle est en attente de validation.');
    }

    public function cancel(int $id)
    {
        $employeId  = session()->get('employe_id');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($id);
        if (! $conge || $conge['employe_id'] !== $employeId) {
            return redirect()->to('/employe/conges')->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] === 'approuvee') {
            // Recréditer si déjà approuvée
            $typeModel = new TypeCongeModel();
            $type = $typeModel->find($conge['type_conge_id']);
            if ($type && $type['deductible']) {
                $soldeModel->recréditer($employeId, $conge['type_conge_id'], $conge['nb_jours']);
            }
        } elseif ($conge['statut'] !== 'en_attente') {
            return redirect()->to('/employe/conges')->with('error', 'Cette demande ne peut pas être annulée.');
        }

        $congeModel->update($id, ['statut' => 'annulee', 'commentaire_rh' => "Annulé par l'employé"]);
        return redirect()->to('/employe/conges')->with('success', 'Demande annulée avec succès.');
    }
}