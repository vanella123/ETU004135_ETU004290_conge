<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table      = 'soldes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris',
    ];

    public function getSoldesEmploye(int $employeId, int $annee = null): array
    {
        $annee = $annee ?? date('Y');
        return $this->db->table('soldes s')
            ->select('s.*, tc.libelle, tc.deductible')
            ->join('types_conge tc', 'tc.id = s.type_conge_id')
            ->where('s.employe_id', $employeId)
            ->where('s.annee', $annee)
            ->get()->getResultArray();
    }

    public function getSolde(int $employeId, int $typeCongeId, int $annee = null): ?array
    {
        $annee = $annee ?? date('Y');
        return $this->where([
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'annee' => $annee,
        ])->first();
    }

    public function getRestant(int $employeId, int $typeCongeId, int $annee = null): int
    {
        $s = $this->getSolde($employeId, $typeCongeId, $annee);
        if (!$s) return 0;
        return $s['jours_attribues'] - $s['jours_pris'];
    }

    public function deduire(int $employeId, int $typeCongeId, int $nbJours, int $annee = null): bool
    {
        $annee = $annee ?? date('Y');
        return $this->db->table('soldes')
            ->where(['employe_id' => $employeId, 'type_conge_id' => $typeCongeId, 'annee' => $annee])
            ->update(['jours_pris' => "jours_pris + $nbJours"], null, null);
    }

    public function recréditer(int $employeId, int $typeCongeId, int $nbJours, int $annee = null): bool
    {
        $annee = $annee ?? date('Y');
        return $this->db->table('soldes')
            ->where(['employe_id' => $employeId, 'type_conge_id' => $typeCongeId, 'annee' => $annee])
            ->update(['jours_pris' => "jours_pris - $nbJours"], null, null);
    }

    public function getAllSoldesWithEmploye(int $annee = null): array
    {
        $annee = $annee ?? date('Y');
        return $this->db->table('soldes s')
            ->select('s.*, e.nom, e.prenom, d.nom as dept_nom, tc.libelle as type_libelle')
            ->join('employes e', 'e.id = s.employe_id')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->join('types_conge tc', 'tc.id = s.type_conge_id')
            ->where('s.annee', $annee)
            ->where('e.actif', 1)
            ->orderBy('e.nom')
            ->get()->getResultArray();
    }

    public function initSoldesEmploye(int $employeId, int $annee = null): void
    {
        $annee = $annee ?? date('Y');
        $types = $this->db->table('types_conge')->where('deductible', 1)->get()->getResultArray();
        foreach ($types as $type) {
            $exists = $this->getSolde($employeId, $type['id'], $annee);
            if (!$exists) {
                $this->insert([
                    'employe_id' => $employeId,
                    'type_conge_id' => $type['id'],
                    'annee' => $annee,
                    'jours_attribues' => $type['jours_annuels'],
                    'jours_pris' => 0,
                ]);
            }
        }
    }

    public function getSoldesCritiques(int $seuil = 2, int $annee = null): array
    {
        $annee = $annee ?? date('Y');
        return $this->db->table('soldes s')
            ->select('s.*, e.nom, e.prenom, tc.libelle')
            ->join('employes e', 'e.id = s.employe_id')
            ->join('types_conge tc', 'tc.id = s.type_conge_id')
            ->where('e.actif', 1)
            ->where('s.annee', $annee)
            ->where('(s.jours_attribues - s.jours_pris) <=', $seuil)
            ->where('s.jours_attribues >', 0)
            ->get()->getResultArray();
    }
}