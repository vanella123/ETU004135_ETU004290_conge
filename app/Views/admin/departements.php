<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Départements</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Départements</div>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="form-section">
      <h3><i class="bi bi-plus-circle" style="color:var(--forest);margin-right:6px"></i>Créer un département</h3>
      <form method="post" action="<?= base_url('admin/departements/store') ?>">
        <?= csrf_field() ?>
        <div class="form-grid-2">
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input name="nom" class="f-input" required>
          </div>
          <div class="f-group">
            <label class="f-label">Description</label>
            <input name="description" class="f-input">
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
        </div>
      </form>
    </div>

    <div class="data-card">
      <div class="data-card-head"><h3>Liste</h3></div>
      <?php if (empty($depts)): ?>
      <div class="empty"><i class="bi bi-diagram-3"></i><p>Aucun département.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Nom</th><th>Description</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($depts as $d): ?>
          <tr>
            <td class="td-name"><?= esc($d['nom']) ?></td>
            <td class="td-muted"><?= $d['description'] ? esc($d['description']) : '—' ?></td>
            <td>
              <div class="action-btns">
                <form method="post" action="<?= base_url('admin/departements/delete/' . $d['id']) ?>" onsubmit="return confirm('Supprimer ce département ?')">
                  <?= csrf_field() ?>
                  <button class="btn-sm btn-del" type="submit"><i class="bi bi-trash"></i> Supprimer</button>
                </form>
              </div>
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
