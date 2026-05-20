<?= $this->extend('layouts/main') ?>
<?php helper('rh'); ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<style>
  .fc{font-family:'DM Sans',sans-serif}
  .fc .fc-toolbar-title{font-family:'Playfair Display',serif;color:var(--ink)}
  .fc .fc-button{background:var(--white);color:var(--muted);border:1.5px solid var(--border);border-radius:8px}
  .fc .fc-button:hover{border-color:var(--muted);color:var(--ink);background:var(--white)}
  .fc .fc-button-primary:not(:disabled).fc-button-active{background:var(--mint);border-color:var(--forest);color:var(--forest)}
  .fc .fc-today-button{background:var(--forest);border-color:var(--forest);color:var(--white)}
  .fc .fc-today-button:hover{background:var(--forest2);border-color:var(--forest2)}
  .fc .fc-daygrid-day.fc-day-today{background:var(--cream)}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

    <!-- Calendrier (FullCalendar) -->
    <div class="data-card" style="margin-bottom:1.5rem">
      <div class="data-card-head">
        <h3><i class="bi bi-calendar-week" style="color:var(--forest);margin-right:5px"></i>Calendrier</h3>
      </div>
      <div style="padding:1rem 1.25rem">
        <div id="calendar"></div>
      </div>
    </div>

  </div>
  <div class="footer-app"><i class="bi bi-c-circle"></i> <?= date('Y') ?> <span>TechMada RH</span></div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
const conges = <?= json_encode(array_map(fn($c) => [
  'id'     => $c['id'],
  'debut'  => $c['date_debut'],
  'fin'    => $c['date_fin'],
  'statut' => $c['statut'],
  'type'   => $c['type_libelle'],
], $conges ?? [])) ?>;

function addDays(ymd, days) {
  const d = new Date(ymd + 'T00:00:00');
  d.setDate(d.getDate() + days);
  return d.toISOString().slice(0, 10);
}

function colorsForStatut(statut) {
  if (statut === 'approuvee') return { bg: 'var(--success-bg)', border: 'var(--success)', text: 'var(--success)' };
  if (statut === 'en_attente') return { bg: 'var(--warn-bg)', border: 'var(--warn)', text: 'var(--warn)' };
  if (statut === 'refusee') return { bg: 'var(--danger-bg)', border: 'var(--danger)', text: 'var(--danger)' };
  return { bg: 'var(--cream)', border: 'var(--border)', text: 'var(--muted)' };
}

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;

  const events = conges.map(c => {
    const col = colorsForStatut(c.statut);
    return {
      id: String(c.id),
      title: c.type,
      start: c.debut,
      end: addDays(c.fin, 1),
      allDay: true,
      backgroundColor: col.bg,
      borderColor: col.border,
      textColor: col.text,
    };
  });

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'fr',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events,
  });

  calendar.render();
});
</script>
<?= $this->endSection() ?>