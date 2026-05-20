<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('employe/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Tableau de bord</div>
      <div class="topbar-breadcrumb">Accueil</div>
    </div>
    <div class="topbar-actions">
      <a href="<?= base_url('employe/conges/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
        <i class="bi bi-plus-lg"></i> Nouvelle demande
      </a>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Métriques -->
    <div class="metrics">
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
        <div class="metric-val"><?= $stats['en_attente'] ?></div>
        <div class="metric-label">En attente</div>
      </div>
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
        <div class="metric-val"><?= $stats['approuvee'] ?></div>
        <div class="metric-label">Approuvées</div>
      </div>
      <?php
        $soldeAnnuel = null;
        foreach ($soldes as $s) {
            if (str_contains(strtolower($s['libelle']), 'annuel')) { $soldeAnnuel = $s; break; }
        }
        $restantAnnuel = $soldeAnnuel ? ($soldeAnnuel['jours_attribues'] - $soldeAnnuel['jours_pris']) : 0;
      ?>
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
        <div class="metric-val"><?= $restantAnnuel ?></div>
        <div class="metric-label">Jours restants</div>
        <?php if ($soldeAnnuel): ?>
        <div class="metric-sub">sur <?= $soldeAnnuel['jours_attribues'] ?> cette année</div>
        <?php endif; ?>
      </div>
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
        <div class="metric-val"><?= $stats['refusee'] ?></div>
        <div class="metric-label">Refusées</div>
      </div>
    </div>

    <!-- Soldes -->
    <div class="data-card">
      <div class="data-card-head"><h3>Mes soldes de congés — <?= date('Y') ?></h3></div>
      <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
        <?php foreach ($soldes as $s):
          $restant = $s['jours_attribues'] - $s['jours_pris'];
          $pct = $s['jours_attribues'] > 0 ? round(($restant / $s['jours_attribues']) * 100) : 0;
          $barClass = soldeBarClass($restant, $s['jours_attribues']);
        ?>
        <div class="solde-card" style="margin:0">
          <div class="solde-header">
            <span class="solde-type"><?= esc($s['libelle']) ?></span>
            <span class="solde-nums"><strong><?= $restant ?></strong> / <?= $s['jours_attribues'] ?> j</span>
          </div>
          <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div></div>
          <div class="solde-label"><?= $restant ?> jour<?= $restant > 1 ? 's' : '' ?> restant<?= $restant > 1 ? 's' : '' ?> · <?= $s['jours_pris'] ?> pris</div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Dernières demandes -->
    <div class="data-card">
      <div class="data-card-head">
        <h3>Mes dernières demandes</h3>
        <a href="<?= base_url('employe/conges') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
      </div>
      <?php if (empty($derniers)): ?>
      <div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande pour le moment.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($derniers as $c): ?>
          <tr>
            <td><?= typeBadge($c['type_libelle']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_debut']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_fin']) ?></td>
            <td class="td-mono"><?= $c['nb_jours'] ?> j</td>
            <td><?= statutBadge($c['statut']) ?></td>
            <td>
              <?php if ($c['statut'] === 'en_attente'): ?>
              <form method="post" action="<?= base_url('employe/conges/cancel/' . $c['id']) ?>" onsubmit="return confirm('Annuler cette demande ?')">
                <?= csrf_field() ?>
                <button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button>
              </form>
              <?php else: ?>
              <span class="td-muted" style="font-size:.75rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span> — Projet CodeIgniter 4</div>
</div>
</div>
<?= $this->endSection() ?>