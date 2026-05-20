<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Demandes de congé</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
    </div>
  </div>

  <div class="content">

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
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
        <div class="metric-val"><?= $stats['refusee'] ?></div>
        <div class="metric-label">Refusées</div>
      </div>
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-slash-circle"></i></div></div>
        <div class="metric-val"><?= $stats['annulee'] ?></div>
        <div class="metric-label">Annulées</div>
      </div>
    </div>

    <div class="data-card">
      <div class="data-card-head">
        <h3>Toutes les demandes</h3>
        <div style="display:flex;gap:6px;flex-wrap:wrap">
          <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="window.location='?statut='+this.value+'&dept=<?= (int)($deptId ?? 0) ?>'">
            <option value="" <?= empty($statut) ? 'selected' : '' ?>>Tous les statuts</option>
            <option value="en_attente" <?= ($statut==='en_attente') ? 'selected' : '' ?>>En attente</option>
            <option value="approuvee" <?= ($statut==='approuvee') ? 'selected' : '' ?>>Approuvée</option>
            <option value="refusee" <?= ($statut==='refusee') ? 'selected' : '' ?>>Refusée</option>
            <option value="annulee" <?= ($statut==='annulee') ? 'selected' : '' ?>>Annulée</option>
          </select>
          <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="window.location='?dept='+this.value+'&statut=<?= esc($statut ?? '') ?>'">
            <option value="" <?= empty($deptId) ? 'selected' : '' ?>>Tous les départements</option>
            <?php foreach (($departements ?? []) as $d): ?>
              <option value="<?= $d['id'] ?>" <?= ((int)$deptId === (int)$d['id']) ? 'selected' : '' ?>><?= esc($d['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if (empty($demandes)): ?>
      <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Employé</th><th>Département</th><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th></tr></thead>
        <tbody>
        <?php foreach ($demandes as $c): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:7px">
                <div class="avatar <?= avatarClass($c['employe_id']) ?>" style="width:28px;height:28px;font-size:.62rem"><?= initiales($c['prenom'],$c['nom']) ?></div>
                <span class="td-name"><?= esc($c['prenom'].' '.$c['nom']) ?></span>
              </div>
            </td>
            <td class="td-muted"><?= esc($c['dept_nom'] ?? '—') ?></td>
            <td><?= typeBadge($c['type_libelle']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_debut']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_fin']) ?></td>
            <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
            <td><?= statutBadge($c['statut']) ?></td>
            <td style="font-size:.78rem;color:var(--muted)"><?= $c['commentaire_rh'] ? esc($c['commentaire_rh']) : '—' ?></td>
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
