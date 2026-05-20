<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('employe/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Mon profil</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Profil</div>
    </div>
  </div>

  <div class="content" style="max-width:600px">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="form-section">
      <h3>Informations personnelles</h3>
      <form method="post" action="<?= base_url('employe/profil/update') ?>">
        <?= csrf_field() ?>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input type="text" name="prenom" class="f-input" value="<?= esc($employe['prenom']) ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input type="text" name="nom" class="f-input" value="<?= esc($employe['nom']) ?>" required/>
          </div>
        </div>
        <div class="f-group">
          <label class="f-label">Email</label>
          <input type="email" class="f-input" value="<?= esc($employe['email']) ?>" disabled style="opacity:.6"/>
          <div class="f-hint">L'email ne peut pas être modifié.</div>
        </div>
        <div class="f-group">
          <label class="f-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
          <input type="password" name="password" class="f-input" placeholder="••••••••"/>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-forest"><i class="bi bi-floppy"></i> Enregistrer</button>
        </div>
      </form>
    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>
<?= $this->endSection() ?>