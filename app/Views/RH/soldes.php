<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('rh/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Soldes des employés</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes</div>
    </div>
  </div>

  <div class="content">

    <div class="data-card">
      <div class="data-card-head">
        <h3>Soldes (<?= (int)$annee ?>)</h3>
        <form method="get" action="<?= base_url('rh/soldes') ?>" style="display:flex;gap:8px;align-items:center">
          <select name="annee" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="this.form.submit()">
            <?php $y = (int)date('Y'); for ($yr=$y-1; $yr<=$y+1; $yr++): ?>
              <option value="<?= $yr ?>" <?= (int)$annee===$yr?'selected':'' ?>><?= $yr ?></option>
            <?php endfor; ?>
          </select>
        </form>
      </div>

      <?php if (empty($soldes)): ?>
      <div class="empty"><i class="bi bi-people"></i><p>Aucun solde.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Employé</th><th>Département</th><th>Type</th><th>Restant</th><th>Barre</th></tr></thead>
        <tbody>
        <?php foreach ($soldes as $s):
          $restant = (int)$s['jours_attribues'] - (int)$s['jours_pris'];
          $pct = ((int)$s['jours_attribues'] > 0) ? round(($restant / (int)$s['jours_attribues']) * 100) : 0;
          $barClass = soldeBarClass($restant, (int)$s['jours_attribues']);
        ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:7px">
                <div class="avatar <?= avatarClass($s['employe_id']) ?>" style="width:28px;height:28px;font-size:.62rem"><?= initiales($s['prenom'],$s['nom']) ?></div>
                <span class="td-name"><?= esc($s['prenom'].' '.$s['nom']) ?></span>
              </div>
            </td>
            <td class="td-muted"><?= esc($s['dept_nom'] ?? '—') ?></td>
            <td><?= typeBadge($s['type_libelle']) ?></td>
            <td class="td-mono"><strong><?= $restant ?></strong> / <?= (int)$s['jours_attribues'] ?> j</td>
            <td style="min-width:180px">
              <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div></div>
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
