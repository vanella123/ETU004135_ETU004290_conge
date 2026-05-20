<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('admin/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Tableau de bord Admin</div>
      <div class="topbar-breadcrumb">Accueil</div>
    </div>
    <div class="topbar-actions">
      <a href="<?= base_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
        <i class="bi bi-person-plus"></i> Gérer les employés
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
        <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-people"></i></div></div>
        <div class="metric-val"><?= $nbEmployes ?></div>
        <div class="metric-label">Employés actifs</div>
      </div>
      <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-diagram-3"></i></div></div>
        <div class="metric-val"><?= $nbDepts ?></div>
        <div class="metric-label">Départements</div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem">

      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3><i class="bi bi-bar-chart" style="color:var(--forest);margin-right:5px"></i>Congés approuvés par mois (<?= date('Y') ?>)</h3>
          </div>
          <div style="padding:1rem 1.25rem">
            <canvas id="salesChart" height="110"></canvas>
          </div>
        </div>

        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="<?= base_url('admin/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
          </div>
          <?php if (empty($recentes)): ?>
          <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande.</p></div>
          <?php else: ?>
          <table class="tbl">
            <thead><tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($recentes as $c): ?>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:7px">
                    <div class="avatar <?= avatarClass($c['employe_id']) ?>" style="width:28px;height:28px;font-size:.62rem"><?= initiales($c['prenom'],$c['nom']) ?></div>
                    <span class="td-name" style="font-size:.84rem"><?= esc($c['prenom'].' '.$c['nom']) ?></span>
                  </div>
                </td>
                <td><?= typeBadge($c['type_libelle']) ?></td>
                <td class="td-mono"><?= $c['nb_jours'] ?> j</td>
                <td><?= statutBadge($c['statut']) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:1rem">
        <div class="data-card" style="margin:0">
          <div class="data-card-head"><h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3></div>
          <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
            <?php if (empty($absents)): ?>
            <p style="font-size:.82rem;color:var(--muted);margin:0">Aucun absent aujourd'hui.</p>
            <?php else: foreach ($absents as $a): ?>
            <div style="display:flex;align-items:center;gap:8px">
              <div class="avatar <?= avatarClass($a['id']) ?>" style="width:30px;height:30px;font-size:.65rem"><?= initiales($a['prenom'],$a['nom']) ?></div>
              <div>
                <div style="font-size:.83rem;font-weight:500;color:var(--ink)"><?= esc($a['prenom'].' '.$a['nom']) ?></div>
                <div style="font-size:.72rem;color:var(--muted)"><?= esc($a['type_conge']) ?> · retour <?= formatDate($a['date_fin']) ?></div>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

        <?php if (!empty($critiques)): ?>
        <div class="flash flash-warn" style="margin:0">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span style="font-size:.8rem"><?= count($critiques) ?> employé(s) ont un solde critique (≤ 2 jours). <a href="<?= base_url('admin/soldes') ?>" style="color:var(--warn);font-weight:500">Voir les soldes →</a></span>
        </div>
        <?php endif; ?>
      </div>

    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const el = document.getElementById('salesChart');
  if (!el) return;

  const css = getComputedStyle(document.documentElement);
  const cForest = css.getPropertyValue('--forest').trim() || '#2d5a3d';
  const cLeaf   = css.getPropertyValue('--leaf').trim() || '#5fa876';
  const cWarn   = css.getPropertyValue('--warn').trim() || '#b8750a';
  const cInfo   = css.getPropertyValue('--info').trim() || '#1a4f7a';
  const cDanger = css.getPropertyValue('--danger').trim() || '#c0392b';

  const labels = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
  const data = <?= json_encode(array_values($statsMois ?? array_fill(1,12,0))) ?>;

  new Chart(el, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Congés approuvés',
        data,
        backgroundColor: [
          cForest, cLeaf, cWarn, cInfo, cLeaf, cForest,
          cInfo, cWarn, cForest, cLeaf, cDanger, cInfo
        ],
        borderWidth: 0,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true }
      },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });
})();
</script>
<?= $this->endSection() ?>
