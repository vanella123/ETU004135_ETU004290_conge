<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Employés</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="form-section">
      <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Créer un employé</h3>
      <form method="post" action="<?= base_url('admin/employes/store') ?>">
        <?= csrf_field() ?>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input name="nom" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input name="prenom" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Email</label>
            <input type="email" name="email" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Mot de passe</label>
            <input type="password" name="password" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Rôle</label>
            <select name="role" class="f-select" required>
              <option value="employe">Employé</option>
              <option value="rh">Responsable RH</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Département</label>
            <select name="departement_id" class="f-select" required>
              <?php foreach (($depts ?? []) as $d): ?>
                <option value="<?= $d['id'] ?>"><?= esc($d['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Date d'embauche</label>
            <input type="date" name="date_embauche" class="f-input" required>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
        </div>
      </form>
    </div>

    <div class="data-card">
      <div class="data-card-head"><h3>Liste des employés</h3></div>
      <?php if (empty($employes)): ?>
      <div class="empty"><i class="bi bi-people"></i><p>Aucun employé.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Employé</th><th>Email</th><th>Rôle</th><th>Département</th><th>Embauche</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($employes as $e): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:7px">
                <div class="avatar <?= avatarClass($e['id']) ?>" style="width:28px;height:28px;font-size:.62rem"><?= initiales($e['prenom'],$e['nom']) ?></div>
                <span class="td-name"><?= esc($e['prenom'].' '.$e['nom']) ?></span>
              </div>
            </td>
            <td class="td-muted"><?= esc($e['email']) ?></td>
            <td class="td-muted" style="text-transform:uppercase;font-size:.72rem;letter-spacing:.06em"><?= esc($e['role']) ?></td>
            <td class="td-muted"><?= esc($e['dept_nom'] ?? '—') ?></td>
            <td class="td-muted"><?= $e['date_embauche'] ? formatDate($e['date_embauche']) : '—' ?></td>
            <td>
              <?php if (!empty($e['actif'])): ?>
                <span class="statut s-approuvee">Actif</span>
              <?php else: ?>
                <span class="statut s-annulee">Inactif</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="action-btns">
                <a class="btn-sm btn-edit" href="<?= base_url('admin/employes/edit/' . $e['id']) ?>"><i class="bi bi-pencil"></i> Modifier</a>
                <form method="post" action="<?= base_url('admin/employes/toggle/' . $e['id']) ?>" onsubmit="return confirm('Changer le statut de cet employé ?')">
                  <?= csrf_field() ?>
                  <button class="btn-sm btn-del" type="submit"><i class="bi bi-power"></i> <?= !empty($e['actif']) ? 'Désactiver' : 'Réactiver' ?></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>
<?= $this->endSection() ?>
