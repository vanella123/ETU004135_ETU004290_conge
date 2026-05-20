<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table      = 'employes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom', 'prenom', 'email', 'password', 'role',
        'departement_id', 'date_embauche', 'actif',
    ];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function getAllWithDept()
    {
        return $this->db->table('employes e')
            ->select('e.*, d.nom as dept_nom')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->get()->getResultArray();
    }

    public function getActifsWithDept()
    {
        return $this->db->table('employes e')
            ->select('e.*, d.nom as dept_nom')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->where('e.actif', 1)
            ->get()->getResultArray();
    }

    public function getAbsentsAujourdhui()
    {
        $today = date('Y-m-d');
        return $this->db->table('employes e')
            ->select('e.id, e.nom, e.prenom, c.date_fin, tc.libelle as type_conge')
            ->join('conges c', 'c.employe_id = e.id')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->where('c.statut', 'approuvee')
            ->where('c.date_debut <=', $today)
            ->where('c.date_fin >=', $today)
            ->get()->getResultArray();
    }
}