<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('rh/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Demandes à traiter</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

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
        <thead><tr><th>Employé</th><th>Département</th><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Motif</th><th>Action</th></tr></thead>
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
            <td style="font-size:.78rem;color:var(--muted)"><?= $c['motif'] ? esc($c['motif']) : '—' ?></td>
            <td>
              <?php if ($c['statut'] === 'en_attente'): ?>
                <form method="post" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                  <?= csrf_field() ?>
                  <input type="hidden" name="nom_employe" value="<?= esc($c['prenom'].' '.$c['nom']) ?>">
                  <input name="commentaire" class="f-input" placeholder="Commentaire" style="width:160px;padding:7px 10px;font-size:.82rem" />
                  <button class="btn-sm btn-approve" type="submit" formaction="<?= base_url('rh/demandes/approuver/' . $c['id']) ?>" onclick="return confirm('Approuver cette demande ?')"><i class="bi bi-check"></i> Approuver</button>
                  <button class="btn-sm btn-refuse" type="submit" formaction="<?= base_url('rh/demandes/refuser/' . $c['id']) ?>" onclick="return confirm('Refuser cette demande ?')"><i class="bi bi-x"></i> Refuser</button>
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
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>
<?= $this->endSection() ?>
