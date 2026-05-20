<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Départements
        $depts = [
            ['nom' => 'IT', 'description' => 'Département informatique'],
            ['nom' => 'Finance', 'description' => 'Comptabilité & finances'],
            ['nom' => 'Marketing', 'description' => 'Communication & marketing'],
            ['nom' => 'RH', 'description' => 'Ressources humaines'],
        ];
        $this->db->table('departements')->insertBatch($depts);

        // Types de congé
        $types = [
            ['libelle' => 'Congé annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Congé maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['libelle' => 'Congé spécial', 'jours_annuels' => 5, 'deductible' => 1],
            ['libelle' => 'Sans solde', 'jours_annuels' => 0, 'deductible' => 0],
        ];
        $this->db->table('types_conge')->insertBatch($types);

        // Employés
        $employes = [
            [
                'nom' => 'Admin', 'prenom' => 'Système',
                'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin', 'departement_id' => 4,
                'date_embauche' => '2019-01-01', 'actif' => 1,
            ],
            [
                'nom' => 'Rabe', 'prenom' => 'Marie',
                'email' => 'rh@techmada.mg',
                'password' => password_hash('rh123', PASSWORD_DEFAULT),
                'role' => 'rh', 'departement_id' => 4,
                'date_embauche' => '2020-01-15', 'actif' => 1,
            ],
            [
                'nom' => 'Rakoto', 'prenom' => 'Soa',
                'email' => 'employe@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe', 'departement_id' => 1,
                'date_embauche' => '2022-03-01', 'actif' => 1,
            ],
            [
                'nom' => 'Fidy', 'prenom' => 'Tsiry',
                'email' => 'tsiry@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe', 'departement_id' => 2,
                'date_embauche' => '2019-07-10', 'actif' => 0,
            ],
            [
                'nom' => 'Andria', 'prenom' => 'Haja',
                'email' => 'haja@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe', 'departement_id' => 3,
                'date_embauche' => '2021-05-20', 'actif' => 1,
            ],
        ];
        $this->db->table('employes')->insertBatch($employes);

        $annee = date('Y');

        // Soldes pour chaque employé actif
        $employeIds = [1, 2, 3, 5]; // admin, rh, soa, haja
        $soldes = [];
        foreach ($employeIds as $eid) {
            $soldes[] = ['employe_id' => $eid, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => ($eid === 3 ? 12 : ($eid === 5 ? 8 : 5))];
            $soldes[] = ['employe_id' => $eid, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 10, 'jours_pris' => ($eid === 3 ? 2 : 0)];
            $soldes[] = ['employe_id' => $eid, 'type_conge_id' => 3, 'annee' => $annee, 'jours_attribues' => 5, 'jours_pris' => ($eid === 3 ? 4 : 0)];
        }
        // Tsiry (id=4) with insufficient balance
        $soldes[] = ['employe_id' => 4, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 0];
        $soldes[] = ['employe_id' => 4, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 10, 'jours_pris' => 9];
        $soldes[] = ['employe_id' => 4, 'type_conge_id' => 3, 'annee' => $annee, 'jours_attribues' => 5, 'jours_pris' => 0];

        $this->db->table('soldes')->insertBatch($soldes);

        // Demandes de congés
        $conges = [
            [
                'employe_id' => 3, 'type_conge_id' => 1,
                'date_debut' => $annee . '-06-23', 'date_fin' => $annee . '-06-27',
                'nb_jours' => 5, 'motif' => 'Vacances familiales',
                'statut' => 'en_attente', 'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'employe_id' => 3, 'type_conge_id' => 2,
                'date_debut' => $annee . '-06-02', 'date_fin' => $annee . '-06-03',
                'nb_jours' => 2, 'motif' => null,
                'statut' => 'approuvee', 'commentaire_rh' => 'Validé',
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'traite_par' => 2,
            ],
            [
                'employe_id' => 3, 'type_conge_id' => 1,
                'date_debut' => $annee . '-05-12', 'date_fin' => $annee . '-05-16',
                'nb_jours' => 5, 'motif' => null,
                'statut' => 'approuvee', 'commentaire_rh' => 'OK',
                'created_at' => date('Y-m-d H:i:s', strtotime('-38 days')),
                'traite_par' => 2,
            ],
            [
                'employe_id' => 3, 'type_conge_id' => 3,
                'date_debut' => $annee . '-04-05', 'date_fin' => $annee . '-04-05',
                'nb_jours' => 1, 'motif' => 'Événement personnel',
                'statut' => 'refusee', 'commentaire_rh' => 'Chevauchement détecté',
                'created_at' => date('Y-m-d H:i:s', strtotime('-75 days')),
                'traite_par' => 2,
            ],
            [
                'employe_id' => 3, 'type_conge_id' => 4,
                'date_debut' => $annee . '-03-10', 'date_fin' => $annee . '-03-12',
                'nb_jours' => 3, 'motif' => null,
                'statut' => 'annulee', 'commentaire_rh' => 'Annulé par l\'employé',
                'created_at' => date('Y-m-d H:i:s', strtotime('-98 days')),
            ],
            [
                'employe_id' => 4, 'type_conge_id' => 2,
                'date_debut' => $annee . '-06-18', 'date_fin' => $annee . '-06-19',
                'nb_jours' => 2, 'motif' => 'Maladie',
                'statut' => 'en_attente', 'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'employe_id' => 5, 'type_conge_id' => 1,
                'date_debut' => $annee . '-06-30', 'date_fin' => $annee . '-07-04',
                'nb_jours' => 5, 'motif' => 'Vacances',
                'statut' => 'en_attente', 'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('conges')->insertBatch($conges);
    }
}