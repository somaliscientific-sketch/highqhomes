<?php
declare(strict_types=1);

$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'AboutController@index');
$router->get('/services', 'ServicesController@index');
$router->get('/services/{slug}', 'ServicesController@show');
$router->get('/projects', 'ProjectsController@index');
$router->get('/projects/{slug}', 'ProjectsController@show');
$router->get('/paints', 'PaintsController@index');
$router->get('/paints/{slug}', 'PaintsController@show');
$router->get('/gallery', 'GalleryController@index');
$router->get('/contact', 'ContactController@index');
$router->post('/contact', 'ContactController@submit');

// Admin authentication — secure login URL (legacy /admin/login is blocked)
$adminLogin = Security::adminLoginPath();
$router->get($adminLogin, 'Admin\AuthController@login');
$router->post($adminLogin, 'Admin\AuthController@authenticate');
$router->get('/admin/login', 'Admin\AuthController@blocked');
$router->post('/admin/login', 'Admin\AuthController@blocked');
$router->post('/admin/logout', 'Admin\AuthController@logout');

$router->get('/admin/profile', 'Admin\ProfileController@index');
$router->post('/admin/profile', 'Admin\ProfileController@update');
$router->post('/admin/profile/password', 'Admin\ProfileController@updatePassword');

// Admin dashboard
$router->get('/admin', 'Admin\DashboardController@index');
$router->get('/admin/dashboard', 'Admin\DashboardController@index');

// Admin content management
$router->get('/admin/sliders', 'Admin\SlidersController@index');
$router->get('/admin/sliders/create', 'Admin\SlidersController@create');
$router->post('/admin/sliders/create', 'Admin\SlidersController@store');
$router->get('/admin/sliders/{id}/edit', 'Admin\SlidersController@edit');
$router->post('/admin/sliders/{id}/edit', 'Admin\SlidersController@update');
$router->post('/admin/sliders/{id}/delete', 'Admin\SlidersController@destroy');
$router->post('/admin/sliders/{id}/toggle', 'Admin\SlidersController@toggle');
$router->post('/admin/sliders/settings', 'Admin\SlidersController@updateSettings');

$router->get('/admin/services', 'Admin\ServicesController@index');
$router->get('/admin/services/create', 'Admin\ServicesController@create');
$router->post('/admin/services/create', 'Admin\ServicesController@store');
$router->get('/admin/services/{id}/edit', 'Admin\ServicesController@edit');
$router->post('/admin/services/{id}/edit', 'Admin\ServicesController@update');
$router->post('/admin/services/{id}/delete', 'Admin\ServicesController@destroy');
$router->post('/admin/services/{id}/toggle', 'Admin\ServicesController@toggle');

$router->get('/admin/projects', 'Admin\ProjectsController@index');
$router->get('/admin/projects/create', 'Admin\ProjectsController@create');
$router->post('/admin/projects/create', 'Admin\ProjectsController@store');
$router->get('/admin/projects/{id}/edit', 'Admin\ProjectsController@edit');
$router->post('/admin/projects/{id}/edit', 'Admin\ProjectsController@update');
$router->post('/admin/projects/{id}/delete', 'Admin\ProjectsController@destroy');
$router->post('/admin/projects/{id}/toggle', 'Admin\ProjectsController@toggle');

$router->get('/admin/gallery', 'Admin\GalleryController@index');
$router->post('/admin/gallery/upload', 'Admin\GalleryController@upload');
$router->post('/admin/gallery/{id}/delete', 'Admin\GalleryController@destroy');
$router->post('/admin/gallery/{id}/toggle', 'Admin\GalleryController@toggle');

$router->get('/admin/team', 'Admin\TeamController@index');
$router->get('/admin/team/create', 'Admin\TeamController@create');
$router->post('/admin/team/create', 'Admin\TeamController@store');
$router->get('/admin/team/{id}/edit', 'Admin\TeamController@edit');
$router->post('/admin/team/{id}/edit', 'Admin\TeamController@update');
$router->post('/admin/team/{id}/delete', 'Admin\TeamController@destroy');

$router->get('/admin/testimonials', 'Admin\TestimonialsController@index');
$router->get('/admin/testimonials/create', 'Admin\TestimonialsController@create');
$router->post('/admin/testimonials/create', 'Admin\TestimonialsController@store');
$router->get('/admin/testimonials/{id}/edit', 'Admin\TestimonialsController@edit');
$router->post('/admin/testimonials/{id}/edit', 'Admin\TestimonialsController@update');
$router->post('/admin/testimonials/{id}/delete', 'Admin\TestimonialsController@destroy');

$router->get('/admin/paints', 'Admin\PaintsController@index');
$router->get('/admin/paints/create', 'Admin\PaintsController@create');
$router->post('/admin/paints/create', 'Admin\PaintsController@store');
$router->get('/admin/paints/{id}/edit', 'Admin\PaintsController@edit');
$router->post('/admin/paints/{id}/edit', 'Admin\PaintsController@update');
$router->post('/admin/paints/{id}/delete', 'Admin\PaintsController@destroy');

$router->get('/admin/messages', 'Admin\MessagesController@index');
$router->get('/admin/messages/{id}', 'Admin\MessagesController@show');
$router->post('/admin/messages/{id}/delete', 'Admin\MessagesController@destroy');
$router->post('/admin/messages/{id}/star', 'Admin\MessagesController@toggleStar');

$router->get('/admin/settings', 'Admin\SettingsController@index');
$router->post('/admin/settings', 'Admin\SettingsController@update');

$router->get('/admin/identity', 'Admin\IdentityController@index');
$router->post('/admin/identity', 'Admin\IdentityController@update');

$router->get('/admin/security', 'Admin\SecurityController@index');
$router->post('/admin/security', 'Admin\SecurityController@update');

$router->get('/admin/logs', 'Admin\LogsController@index');

$router->get('/admin/seo', 'Admin\SeoController@index');
$router->post('/admin/seo', 'Admin\SeoController@update');

$router->get('/admin/pages', 'Admin\PagesController@index');
$router->get('/admin/pages/create', 'Admin\PagesController@create');
$router->post('/admin/pages/create', 'Admin\PagesController@store');
$router->get('/admin/pages/{id}/edit', 'Admin\PagesController@edit');
$router->post('/admin/pages/{id}/edit', 'Admin\PagesController@update');
$router->post('/admin/pages/{id}/delete', 'Admin\PagesController@destroy');
$router->post('/admin/pages/{id}/toggle', 'Admin\PagesController@toggle');

$router->get('/admin/menus', 'Admin\MenusController@index');
$router->get('/admin/menus/create', 'Admin\MenusController@create');
$router->post('/admin/menus/create', 'Admin\MenusController@store');
$router->get('/admin/menus/{id}/edit', 'Admin\MenusController@edit');
$router->post('/admin/menus/{id}/edit', 'Admin\MenusController@update');
$router->post('/admin/menus/{id}/delete', 'Admin\MenusController@destroy');
$router->post('/admin/menus/{id}/toggle', 'Admin\MenusController@toggle');

$router->get('/admin/media/picker', 'Admin\MediaController@picker');
$router->get('/admin/media', 'Admin\MediaController@index');
$router->get('/admin/media/{id}', 'Admin\MediaController@show');
$router->post('/admin/media/upload', 'Admin\MediaController@upload');
$router->post('/admin/media/{id}/update', 'Admin\MediaController@update');
$router->post('/admin/media/{id}/replace', 'Admin\MediaController@replace');
$router->post('/admin/media/{id}/delete', 'Admin\MediaController@destroy');

$router->get('/admin/sections', 'Admin\SectionsController@index');
$router->get('/admin/sections/{id}/edit', 'Admin\SectionsController@edit');
$router->post('/admin/sections/{id}/edit', 'Admin\SectionsController@update');
$router->post('/admin/sections/{id}/toggle', 'Admin\SectionsController@toggle');

$router->get('/admin/roles', 'Admin\RolesController@index');
$router->post('/admin/roles/{id}/edit', 'Admin\RolesController@update');

$router->get('/admin/users', 'Admin\UsersController@index');
$router->post('/admin/users/create', 'Admin\UsersController@store');
$router->post('/admin/users/{id}/edit', 'Admin\UsersController@update');
$router->post('/admin/users/{id}/toggle', 'Admin\UsersController@toggle');
$router->post('/admin/users/{id}/delete', 'Admin\UsersController@destroy');

// Dynamic CMS pages stay last so they do not shadow fixed routes.
$router->get('/{slug}', 'PageController@show');

$router->dispatch();
