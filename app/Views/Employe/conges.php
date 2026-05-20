<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php helper('rh'); ?>

<div class="app-wrap">
<?= $this->include('employe/_sidebar') ?>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title">Mes demandes de congé</div>
      <div class="topbar-breadcrumb"><a href="<?= base_url('employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
    </div>
    <div class="topbar-actions">
      <a href="<?= base_url('employe/conges/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
    </div>
  </div>

  <div class="content">

    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Historique & statistiques par type -->
    <?php if (!empty($statsType)): ?>
    <div class="data-card" style="margin-bottom:1.25rem">
      <div class="data-card-head"><h3><i class="bi bi-bar-chart" style="color:var(--forest);margin-right:5px"></i>Historique &amp; statistiques</h3></div>
      <div style="padding:.85rem 1.25rem;display:flex;gap:1.5rem;flex-wrap:wrap">
        <?php foreach ($statsType as $st): ?>
        <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.75rem 1rem;min-width:140px">
          <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px"><?= esc($st['libelle']) ?></div>
          <div style="font-family:'DM Mono',monospace;font-size:1.1rem;font-weight:500;color:var(--ink)"><?= $st['total'] ?> demande<?= $st['total'] > 1 ? 's' : '' ?></div>
          <div style="font-size:.72rem;color:var(--muted)"><?= $st['jours_total'] ?> jours au total</div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="data-card">
      <div class="data-card-head">
        <h3>Toutes mes demandes</h3>
        <div style="display:flex;gap:6px">
          <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="window.location='?statut='+this.value">
            <option value="" <?= !$statut ? 'selected' : '' ?>>Tous les statuts</option>
            <option value="en_attente" <?= $statut==='en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="approuvee" <?= $statut==='approuvee' ? 'selected' : '' ?>>Approuvée</option>
            <option value="refusee" <?= $statut==='refusee' ? 'selected' : '' ?>>Refusée</option>
            <option value="annulee" <?= $statut==='annulee' ? 'selected' : '' ?>>Annulée</option>
          </select>
        </div>
      </div>
      <?php if (empty($conges)): ?>
      <div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande trouvée.</p></div>
      <?php else: ?>
      <table class="tbl">
        <thead><tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($conges as $c): ?>
          <tr>
            <td><?= typeBadge($c['type_libelle']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_debut']) ?></td>
            <td class="td-muted"><?= formatDate($c['date_fin']) ?></td>
            <td class="td-mono"><?= $c['nb_jours'] ?> j</td>
            <td><?= statutBadge($c['statut']) ?></td>
            <td style="font-size:.78rem;color:var(--muted)"><?= $c['commentaire_rh'] ? esc($c['commentaire_rh']) : '—' ?></td>
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

    <!-- Vue calendrier hebdomadaire -->
    <div class="data-card" style="margin-bottom:1.5rem">
      <div class="data-card-head">
        <h3><i class="bi bi-calendar-week" style="color:var(--forest);margin-right:5px"></i>Vue calendrier</h3>
        <div style="display:flex;gap:8px;align-items:center">
          <button id="prev-week" class="btn-secondary" style="padding:5px 10px"><i class="bi bi-chevron-left"></i></button>
          <span id="week-label" style="font-size:.82rem;color:var(--ink);font-weight:500;min-width:200px;text-align:center"></span>
          <button id="next-week" class="btn-secondary" style="padding:5px 10px"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>
      <div style="padding:1rem 1.25rem;overflow-x:auto">
        <div id="calendar-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;min-width:560px"></div>
      </div>
    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>

<?= $this->section('scripts') ?>
<script>
// Données des congés approuvés / en attente
const conges = <?= json_encode(array_map(fn($c) => [
  'debut'  => $c['date_debut'],
  'fin'    => $c['date_fin'],
  'statut' => $c['statut'],
  'type'   => $c['type_libelle'],
], array_filter($conges, fn($c) => in_array($c['statut'], ['approuvee','en_attente'])))) ?>;

const jours = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
let weekOffset = 0;

function getWeekStart(offset) {
  const d = new Date();
  const day = d.getDay() || 7;
  d.setDate(d.getDate() - day + 1 + offset * 7);
  d.setHours(0,0,0,0);
  return d;
}

function toYMD(d) { return d.toISOString().slice(0,10); }

function isInConge(dateStr) {
  return conges.find(c => dateStr >= c.debut && dateStr <= c.fin) || null;
}

function render() {
  const ws = getWeekStart(weekOffset);
  const we = new Date(ws); we.setDate(we.getDate()+6);
  const fmt = d => d.toLocaleDateString('fr-FR',{day:'numeric',month:'short'});
  document.getElementById('week-label').textContent = fmt(ws) + ' – ' + fmt(we);

  const grid = document.getElementById('calendar-grid');
  grid.innerHTML = '';
  jours.forEach((j,i) => {
    const d = new Date(ws); d.setDate(d.getDate()+i);
    const ymd = toYMD(d);
    const conge = isInConge(ymd);
    const isToday = toYMD(new Date()) === ymd;
    const isWe = i >= 5;
    let bg = isWe ? '#f8f6f1' : 'var(--white)';
    let border = isToday ? '2px solid var(--forest)' : '1px solid var(--border)';
    let content = '';
    if (conge) {
      const col = conge.statut === 'approuvee' ? 'var(--success-bg)' : 'var(--warn-bg)';
      const tcol = conge.statut === 'approuvee' ? 'var(--success)' : 'var(--warn)';
      bg = col;
      border = `1px solid ${tcol}`;
      content = `<div style="font-size:.62rem;color:${tcol};font-weight:500;margin-top:4px">${conge.type}</div>`;
    }
    grid.innerHTML += `<div style="background:${bg};border:${border};border-radius:8px;padding:.6rem .4rem;text-align:center;min-height:72px">
      <div style="font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em">${j}</div>
      <div style="font-family:'DM Mono',monospace;font-size:.9rem;font-weight:500;color:${isToday?'var(--forest)':'var(--ink)'}">${d.getDate()}</div>
      ${content}
    </div>`;
  });
}

document.getElementById('prev-week').onclick = () => { weekOffset--; render(); };
document.getElementById('next-week').onclick = () => { weekOffset++; render(); };
render();
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>