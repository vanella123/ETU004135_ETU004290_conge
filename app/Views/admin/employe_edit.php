<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Modifier un employé</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/employes') ?>">Employés</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Modifier</div>
    </div>
  </div>

  <div class="content">

    <?php if (empty($employe)): ?>
      <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i>Employé introuvable.</div>
    <?php else: ?>

    <div class="form-section">
      <h3><i class="bi bi-pencil" style="color:var(--info);margin-right:6px"></i>Informations</h3>
      <form method="post" action="<?= base_url('admin/employes/update/' . $employe['id']) ?>">
        <?= csrf_field() ?>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input name="nom" class="f-input" value="<?= esc($employe['nom']) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input name="prenom" class="f-input" value="<?= esc($employe['prenom']) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label">Email</label>
            <input type="email" name="email" class="f-input" value="<?= esc($employe['email']) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label">Nouveau mot de passe (optionnel)</label>
            <input type="password" name="password" class="f-input" placeholder="Laisser vide pour ne pas changer">
          </div>
          <div class="f-group">
            <label class="f-label">Rôle</label>
            <select name="role" class="f-select" required>
              <option value="employe" <?= $employe['role']==='employe'?'selected':'' ?>>Employé</option>
              <option value="rh" <?= $employe['role']==='rh'?'selected':'' ?>>Responsable RH</option>
              <option value="admin" <?= $employe['role']==='admin'?'selected':'' ?>>Admin</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Département</label>
            <select name="departement_id" class="f-select" required>
              <?php foreach (($depts ?? []) as $d): ?>
                <option value="<?= $d['id'] ?>" <?= (int)$employe['departement_id']===(int)$d['id']?'selected':'' ?>><?= esc($d['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Date d'embauche</label>
            <input type="date" name="date_embauche" class="f-input" value="<?= esc($employe['date_embauche']) ?>" required>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Mettre à jour</button>
          <a class="btn-secondary" href="<?= base_url('admin/employes') ?>"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
      </form>
    </div>

    <?php endif; ?>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>
<?= $this->endSection() ?>
