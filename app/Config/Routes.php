<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::doLogin');
$routes->get('logout', 'AuthController::logout');

// --- EMPLOYÉ ---
$routes->group('employe', ['filter' => 'auth:employe,rh,admin'], function ($routes) {
    $routes->get('dashboard', 'Employe\DashboardController::index');
    $routes->get('conges', 'Employe\CongesController::index');
    $routes->get('conges/create', 'Employe\CongesController::create');
    $routes->post('conges/store', 'Employe\CongesController::store');
    $routes->post('conges/cancel/(:num)', 'Employe\CongesController::cancel/$1');
    $routes->get('profil', 'Employe\ProfilController::index');
    $routes->post('profil/update', 'Employe\ProfilController::update');
});

// --- RH ---
$routes->group('rh', ['filter' => 'auth:rh,admin'], function ($routes) {
    $routes->get('dashboard', 'Rh\DashboardController::index');
    $routes->get('demandes', 'Rh\DemandesController::index');
    $routes->post('demandes/approuver/(:num)', 'Rh\DemandesController::approuver/$1');
    $routes->post('demandes/refuser/(:num)', 'Rh\DemandesController::refuser/$1');
    $routes->get('soldes', 'Rh\SoldesController::index');
});

// --- ADMIN ---
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('employes', 'Admin\EmployesController::index');
    $routes->post('employes/store', 'Admin\EmployesController::store');
    $routes->get('employes/edit/(:num)', 'Admin\EmployesController::edit/$1');
    $routes->post('employes/update/(:num)', 'Admin\EmployesController::update/$1');
    $routes->post('employes/toggle/(:num)', 'Admin\EmployesController::toggle/$1');
    $routes->get('departements', 'Admin\DepartementsController::index');
    $routes->post('departements/store', 'Admin\DepartementsController::store');
    $routes->post('departements/delete/(:num)', 'Admin\DepartementsController::delete/$1');
    $routes->get('types-conge', 'Admin\TypesCongeController::index');
    $routes->post('types-conge/store', 'Admin\TypesCongeController::store');
    $routes->get('soldes', 'Admin\SoldesController::index');
    $routes->post('soldes/update', 'Admin\SoldesController::update');
    $routes->get('demandes', 'Admin\DemandesController::index');
});