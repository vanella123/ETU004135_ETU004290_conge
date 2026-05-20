<?php

if (! function_exists('initiales')) {
    function initiales(string $prenom, string $nom): string
    {
        return strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
    }
}

if (! function_exists('avatarClass')) {
    function avatarClass(int $id): string
    {
        $classes = ['av-green', 'av-blue', 'av-amber'];
        return $classes[$id % count($classes)];
    }
}

if (! function_exists('statutBadge')) {
    function statutBadge(string $statut): string
    {
        return match ($statut) {
            'en_attente' => '<span class="statut s-attente">en attente</span>',
            'approuvee'  => '<span class="statut s-approuvee">approuvée</span>',
            'refusee'    => '<span class="statut s-refusee">refusée</span>',
            'annulee'    => '<span class="statut s-annulee">annulée</span>',
            default      => '<span class="statut">' . esc($statut) . '</span>',
        };
    }
}

if (! function_exists('typeBadge')) {
    function typeBadge(string $libelle): string
    {
        $lib = strtolower($libelle);
        if (str_contains($lib, 'annuel')) {
            return '<span class="type-badge t-annuel">' . esc($libelle) . '</span>';
        }
        if (str_contains($lib, 'maladie')) {
            return '<span class="type-badge t-maladie">' . esc($libelle) . '</span>';
        }
        if (str_contains($lib, 'spécial') || str_contains($lib, 'special')) {
            return '<span class="type-badge t-special">' . esc($libelle) . '</span>';
        }
        return '<span class="type-badge t-sans-solde">' . esc($libelle) . '</span>';
    }
}

if (! function_exists('soldeBarClass')) {
    function soldeBarClass(int $restant, int $total): string
    {
        if ($total === 0) {
            return '';
        }
        $pct = ($restant / $total) * 100;
        if ($pct <= 20) {
            return 'danger';
        }
        if ($pct <= 40) {
            return 'warn';
        }
        return '';
    }
}

if (! function_exists('formatDate')) {
    function formatDate(?string $date): string
    {
        if (! $date) {
            return '—';
        }
        $mois = ['', 'jan.', 'fév.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
        $t = strtotime($date);
        return date('d', $t) . ' ' . $mois[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}
