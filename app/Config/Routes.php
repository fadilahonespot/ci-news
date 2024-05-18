<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// CRUD RESTful Routes
$routes->get('/user-view', 'UserController::index');
$routes->get('user-form', 'UserController::create');
$routes->post('submit-form', 'UserController::store');
$routes->get('edit-view/(:num)', 'UserController::singleUser/$1');
$routes->post('update', 'UserController::update');
$routes->get('delete/(:num)', 'UserController::delete/$1');
$routes->post('login', 'UserController::login');
$routes->get('/loginForm', 'UserController::loginForm');
$routes->get('category', 'CategoryController::index');
$routes->get('home', 'HomeController::index');
$routes->get('logout', 'UserController::logout');
$routes->post('upload', 'DocumentController::upload');
$routes->get('uploadForm', 'DocumentController::uploadForm');
$routes->get('downloads/(:segment)', 'DownloadController::index/$1'); 
$routes->post('save-category', 'CategoryController::save');
$routes->get('delete-category/(:num)', 'CategoryController::delete/$1');
$routes->post('update-category', 'CategoryController::update');
$routes->get('category/(:num)', 'DocumentController::index/$1');
$routes->post('update-document', 'DocumentController::updateDocument');
$routes->get('delete-document/(:num)', 'DocumentController::deleteDocument/$1');
$routes->get('preview/(:num)', 'PreviewController::index/$1');
$routes->get('profile', 'UserController::profile');
$routes->get('/', 'IndexController::index');
$routes->post('/change-password', 'UserController::updatePassword');
$routes->get('analyze-document/(:num)', 'DocumentController::analyzeDocument/$1');


