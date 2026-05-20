<?php
helper('rh');
$nom    = session()->get('nom');
$prenom = session()->get('prenom');
$initials = initiales($prenom ?? '', $nom ?? '');
$uri = service('uri');
$seg = $uri->getSegment(2);
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon"><i class="bi bi-shield-lock"></i></div>
    <div class="sidebar-brand-name">TechMada RH<span>Espace admin</span></div>
  </div>
  <div class="sidebar-section">Menu</div>
  <ul class="sidebar-nav">
    <li><a href="<?= base_url('admin/dashboard') ?>" class="<?= $seg==='dashboard'?'active':'' ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
    <li><a href="<?= base_url('admin/demandes') ?>" class="<?= $seg==='demandes'?'active':'' ?>"><i class="bi bi-inbox"></i> Demandes</a></li>
    <li><a href="<?= base_url('admin/employes') ?>" class="<?= $seg==='employes'?'active':'' ?>"><i class="bi bi-people"></i> Employés</a></li>
    <li><a href="<?= base_url('admin/departements') ?>" class="<?= $seg==='departements'?'active':'' ?>"><i class="bi bi-diagram-3"></i> Départements</a></li>
    <li><a href="<?= base_url('admin/types-conge') ?>" class="<?= $seg==='types-conge'?'active':'' ?>"><i class="bi bi-tags"></i> Types congé</a></li>
    <li><a href="<?= base_url('admin/soldes') ?>" class="<?= $seg==='soldes'?'active':'' ?>"><i class="bi bi-calculator"></i> Soldes</a></li>
  </ul>
  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar av-amber"><?= esc($initials) ?></div>
      <div>
        <div class="user-name"><?= esc($prenom . ' ' . $nom) ?></div>
        <div class="user-role">Administrateur</div>
      </div>
      <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</aside>
