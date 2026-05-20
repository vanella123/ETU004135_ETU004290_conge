<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table      = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin',
        'nb_jours', 'motif', 'statut', 'commentaire_rh', 'created_at', 'traite_par',
    ];

    public function getMesConges(int $employeId)
    {
        return $this->db->table('conges c')
            ->select('c.*, tc.libelle as type_libelle')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->where('c.employe_id', $employeId)
            ->orderBy('c.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getAllWithDetails(string $statut = null, int $deptId = null)
    {
        $builder = $this->db->table('conges c')
            ->select('c.*, e.nom, e.prenom, e.departement_id, d.nom as dept_nom, tc.libelle as type_libelle, tc.deductible')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->orderBy('c.created_at', 'DESC');

        if ($statut) $builder->where('c.statut', $statut);
        if ($deptId) $builder->where('e.departement_id', $deptId);

        return $builder->get()->getResultArray();
    }

    public function getEnAttente()
    {
        return $this->getAllWithDetails('en_attente');
    }

    public function calculerJours(string $debut, string $fin): int
    {
        // Tous les jours calendaires (simplification T0)
        $d1 = new \DateTime($debut);
        $d2 = new \DateTime($fin);
        return (int)$d1->diff($d2)->days + 1;
    }

    public function hasChevauche(int $employeId, string $debut, string $fin, int $excludeId = 0): bool
    {
        $builder = $this->db->table('conges')
            ->where('employe_id', $employeId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
                ->where('date_debut <=', $fin)
                ->where('date_fin >=', $debut)
            ->groupEnd();

        if ($excludeId) $builder->where('id !=', $excludeId);

        return $builder->countAllResults() > 0;
    }

    public function countByStatut(int $employeId = null): array
    {
        $builder = $this->db->table('conges');
        if ($employeId) $builder->where('employe_id', $employeId);
        $rows = $builder->select('statut, COUNT(*) as total')->groupBy('statut')->get()->getResultArray();
        $result = ['en_attente' => 0, 'approuvee' => 0, 'refusee' => 0, 'annulee' => 0];
        foreach ($rows as $r) $result[$r['statut']] = (int)$r['total'];
        return $result;
    }

    public function getStatsByMonth(int $annee): array
    {
        $rows = $this->db->table('conges')
            ->select("strftime('%m', date_debut) as mois, COUNT(*) as total")
            ->where("strftime('%Y', date_debut)", (string)$annee)
            ->where('statut', 'approuvee')
            ->groupBy("strftime('%m', date_debut)")
            ->get()->getResultArray();

        $stats = array_fill(1, 12, 0);
        foreach ($rows as $r) $stats[(int)$r['mois']] = (int)$r['total'];
        return $stats;
    }

    public function getStatsByTypeConge(int $employeId): array
    {
        return $this->db->table('conges c')
            ->select('tc.libelle, COUNT(*) as total, SUM(c.nb_jours) as jours_total')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->where('c.employe_id', $employeId)
            ->where('c.statut !=', 'annulee')
            ->groupBy('c.type_conge_id')
            ->get()->getResultArray();
    }
}