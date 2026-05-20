<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('employe/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Nouvelle demande de congé</div>
      <div class="topbar-breadcrumb">
        <a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
        <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
      </div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">

      <div>
        <div class="form-section">
          <h3>Détails de la demande</h3>
          <form method="post" action="<?= base_url('employe/conges/store') ?>" id="form-conge">
            <?= csrf_field() ?>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
              <select name="type_conge_id" class="f-select" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($types as $type): ?>
                <?php
                  $restant = 0;
                  foreach ($soldes as $s) {
                    if ($s['type_conge_id'] == $type['id']) { $restant = $s['jours_attribues'] - $s['jours_pris']; break; }
                  }
                ?>
                <option value="<?= $type['id'] ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                  <?= esc($type['libelle']) ?><?= $type['deductible'] ? " ({$restant} j restants)" : '' ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-grid-2" style="margin-bottom:1rem">
              <div class="f-group">
                <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                <input type="date" name="date_debut" id="date_debut" class="f-input" value="<?= old('date_debut') ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                <input type="date" name="date_fin" id="date_fin" class="f-input" value="<?= old('date_fin') ?>" required/>
              </div>
            </div>

            <div class="f-computed" id="computed-jours" style="display:none">
              <div class="f-computed-num" id="nb-jours">0</div>
              <div class="f-computed-label">jours calendaires calculés</div>
            </div>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Motif (optionnel)</label>
              <textarea name="motif" class="f-textarea" placeholder="Précisez le motif si nécessaire..."><?= old('motif') ?></textarea>
              <div class="f-hint">Le motif est visible par le responsable RH.</div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-forest"><i class="bi bi-send"></i> Soumettre la demande</button>
              <a href="<?= base_url('employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
            </div>
          </form>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:1rem">
        <div class="data-card" style="margin:0">
          <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
          <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
            <?php foreach ($soldes as $s):
              $restant = $s['jours_attribues'] - $s['jours_pris'];
              $pct = $s['jours_attribues'] > 0 ? round(($restant / $s['jours_attribues']) * 100) : 0;
              $barClass = soldeBarClass($restant, $s['jours_attribues']);
              $color = $barClass === 'warn' ? 'var(--warn)' : ($barClass === 'danger' ? 'var(--danger)' : 'var(--forest)');
            ?>
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                <span style="font-size:.8rem;color:var(--ink)"><?= esc($s['libelle']) ?></span>
                <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:<?= $color ?>;font-weight:500"><?= $restant ?> j</span>
              </div>
              <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="flash flash-info" style="margin:0">
          <i class="bi bi-info-circle-fill"></i>
          <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
        </div>
        <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
          <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles</div>
          <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
            <li>Préavis minimum : 48h avant la date de début</li>
            <li>Pas de chevauchement avec une demande en cours</li>
            <li>Solde insuffisant = demande refusée automatiquement</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>

<?= $this->section('scripts') ?>
<script>
function calcJours() {
  const d1 = document.getElementById('date_debut').value;
  const d2 = document.getElementById('date_fin').value;
  if (!d1 || !d2) { document.getElementById('computed-jours').style.display='none'; return; }
  const diff = Math.round((new Date(d2) - new Date(d1)) / 86400000) + 1;
  if (diff > 0) {
    document.getElementById('nb-jours').textContent = diff;
    document.getElementById('computed-jours').style.display = 'flex';
  } else {
    document.getElementById('computed-jours').style.display = 'none';
  }
}
document.getElementById('date_debut').addEventListener('change', calcJours);
document.getElementById('date_fin').addEventListener('change', calcJours);
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>