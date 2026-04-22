<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Letters::index');
$routes->get('letters', 'Letters::index');
$routes->get('letters/create', 'Letters::create');
$routes->post('letters', 'Letters::store');
$routes->post('letters/(:segment)/edit-access', 'Letters::authorizeEdit/$1');
$routes->get('letters/(:segment)/edit', 'Letters::edit/$1');
$routes->post('letters/(:segment)/update', 'Letters::update/$1');
$routes->post('letters/(:segment)/delete', 'Letters::delete/$1');
$routes->get('letters/(:segment)', 'Letters::show/$1');

$routes->get('admin/login', 'AdminLetters::login');
$routes->post('admin/login', 'AdminLetters::attemptLogin');
$routes->post('admin/logout', 'AdminLetters::logout');

$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'AdminLetters::index');
    $routes->get('letters/(:segment)/edit', 'AdminLetters::edit/$1');
    $routes->post('letters/(:segment)/update', 'AdminLetters::update/$1');
    $routes->post('letters/(:segment)/delete', 'AdminLetters::delete/$1');
});
