<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Types de congé</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Types de congé</div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="form-section">
      <h3><i class="bi bi-plus-circle" style="color:var(--forest);margin-right:6px"></i>Créer un type</h3>
      <form method="post" action="<?= base_url('admin/types-conge/store') ?>">
        <?= csrf_field() ?>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Libellé</label>
            <input name="libelle" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Jours annuels (attribution)</label>
            <input type="number" min="0" name="jours_annuels" class="f-input" value="0" required>
          </div>
          <div class="f-group">
            <label class="f-label">Déductible du solde</label>
            <select name="deductible" class="f-select" required>
              <option value="1">Oui</option>
              <option value="0">Non</option>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
        </div>
      </form>
    </div>

    <div class="data-card">
      <div class="data-card-head"><h3>Liste</h3></div>
      <?php if (empty($types)): ?>
      <div class="empty"><i class="bi bi-tags"></i><p>Aucun type.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th></tr></thead>
        <tbody>
        <?php foreach ($types as $t): ?>
          <tr>
            <td class="td-name"><?= esc($t['libelle']) ?></td>
            <td class="td-mono"><?= (int)$t['jours_annuels'] ?></td>
            <td>
              <?php if (!empty($t['deductible'])): ?>
                <span class="statut s-approuvee">Oui</span>
              <?php else: ?>
                <span class="statut s-annulee">Non</span>
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
