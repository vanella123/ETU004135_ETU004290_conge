<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Soldes</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes</div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="data-card">
      <div class="data-card-head">
        <h3>Soldes employés</h3>
        <form method="get" action="<?= base_url('admin/soldes') ?>" style="display:flex;gap:8px;align-items:center">
          <select name="annee" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="this.form.submit()">
            <?php $y = (int)date('Y'); for ($yr=$y-1; $yr<=$y+1; $yr++): ?>
              <option value="<?= $yr ?>" <?= (int)$annee===$yr?'selected':'' ?>><?= $yr ?></option>
            <?php endfor; ?>
          </select>
        </form>
      </div>

      <?php if (empty($soldes)): ?>
      <div class="empty"><i class="bi bi-calculator"></i><p>Aucun solde pour cette année.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Employé</th><th>Département</th><th>Type</th><th>Restant</th><th>Barre</th><th>Édition</th></tr></thead>
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
            <td style="min-width:160px">
              <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div></div>
            </td>
            <td>
              <form method="post" action="<?= base_url('admin/soldes/update') ?>" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <input type="number" min="0" name="jours_attribues" class="f-input" value="<?= (int)$s['jours_attribues'] ?>" style="width:120px;padding:7px 10px;font-size:.82rem" title="Jours attribués">
                <input type="number" min="0" name="jours_pris" class="f-input" value="<?= (int)$s['jours_pris'] ?>" style="width:110px;padding:7px 10px;font-size:.82rem" title="Jours pris">
                <button class="btn-sm btn-edit" type="submit"><i class="bi bi-save"></i> OK</button>
              </form>
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
