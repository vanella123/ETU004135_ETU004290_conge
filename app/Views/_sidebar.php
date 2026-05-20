<?php
helper('rh');
$role    = session()->get('role');
$nom     = session()->get('nom');
$prenom  = session()->get('prenom');
$initials = initiales($prenom ?? '', $nom ?? '');
$avClass  = avatarClass(session()->get('employe_id') ?? 1);

// Current URI for active detection
$uri = service('uri');
$seg = $uri->getSegment(2); // 'dashboard', 'conges', etc.
$prefix = $uri->getSegment(1); // 'employe'
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
    <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
  </div>
  <div class="sidebar-section">Menu</div>
  <ul class="sidebar-nav">
    <li><a href="<?= base_url('employe/dashboard') ?>" class="<?= $seg === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
    <li><a href="<?= base_url('employe/conges/create') ?>" class="<?= ($seg === 'conges' && $uri->getSegment(3) === 'create') ? 'active' : '' ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
    <li><a href="<?= base_url('employe/conges') ?>" class="<?= ($seg === 'conges' && $uri->getSegment(3) !== 'create') ? 'active' : '' ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
    <li><a href="<?= base_url('employe/profil') ?>" class="<?= $seg === 'profil' ? 'active' : '' ?>"><i class="bi bi-person"></i> Mon profil</a></li>
  </ul>
  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar <?= $avClass ?>"><?= esc($initials) ?></div>
      <div>
        <div class="user-name"><?= esc($prenom . ' ' . $nom) ?></div>
        <div class="user-role">Employé</div>
      </div>
      <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</aside>