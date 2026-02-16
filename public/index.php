<?php

require_once __DIR__ . '/../app/Core/App.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Database.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize application
$app = new \App\Core\App();
$router = $app->getRouter();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/services', 'HomeController@services');
$router->get('/services/{slug}', 'HomeController@serviceDetail');
$router->get('/about', 'HomeController@about');
$router->get('/reviews', 'HomeController@reviews');
$router->get('/contact', 'HomeController@contact');

// Contact form submission
$router->post('/contact/submit', 'ContactController@submit');
$router->post('/review/submit', 'ContactController@submitReview');

// Admin routes
$router->get('/admin/login', 'AdminController@login');
$router->post('/admin/auth', 'AdminController@authenticate');
$router->get('/admin/logout', 'AdminController@logout');
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin', 'AdminController@dashboard');

// Admin - Services
$router->get('/admin/services', 'AdminController@services');
$router->get('/admin/services/create', 'AdminController@serviceCreate');
$router->post('/admin/services/create', 'AdminController@serviceCreate');
$router->get('/admin/services/edit/{id}', 'AdminController@serviceEdit');
$router->post('/admin/services/edit/{id}', 'AdminController@serviceEdit');
$router->get('/admin/services/delete/{id}', 'AdminController@serviceDelete');

// Admin - Reviews
$router->get('/admin/reviews', 'AdminController@reviews');
$router->get('/admin/reviews/approve/{id}', 'AdminController@reviewApprove');
$router->get('/admin/reviews/featured/{id}', 'AdminController@reviewToggleFeatured');
$router->get('/admin/reviews/delete/{id}', 'AdminController@reviewDelete');

// Admin - Contacts
$router->get('/admin/contacts', 'AdminController@contacts');
$router->get('/admin/contacts/view/{id}', 'AdminController@contactView');
$router->post('/admin/contacts/view/{id}', 'AdminController@contactView');
$router->get('/admin/contacts/delete/{id}', 'AdminController@contactDelete');

// Admin - Pages
$router->get('/admin/pages', 'AdminController@pages');
$router->get('/admin/pages/edit/{id}', 'AdminController@pageEdit');
$router->post('/admin/pages/edit/{id}', 'AdminController@pageEdit');

// Admin - Settings
$router->get('/admin/settings', 'AdminController@settings');
$router->post('/admin/settings', 'AdminController@settings');

// Run application
$app->run();
