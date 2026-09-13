<?php
declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

// Core classes
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Session.php';
require_once APP_PATH . '/Core/CSRF.php';
require_once APP_PATH . '/Core/View.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/Security.php';
require_once APP_PATH . '/Core/Upload.php';
require_once APP_PATH . '/Core/Router.php';
require_once APP_PATH . '/helpers.php';

// Models
require_once APP_PATH . '/Core/Audit.php';
require_once APP_PATH . '/Models/AdminLogModel.php';
require_once APP_PATH . '/Models/SettingModel.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Models/SliderModel.php';
require_once APP_PATH . '/Models/ServiceModel.php';
require_once APP_PATH . '/Models/ProjectModel.php';
require_once APP_PATH . '/Models/GalleryModel.php';
require_once APP_PATH . '/Models/TeamModel.php';
require_once APP_PATH . '/Models/TestimonialModel.php';
require_once APP_PATH . '/Models/PaintModel.php';
require_once APP_PATH . '/Models/MessageModel.php';
require_once APP_PATH . '/Models/SeoModel.php';
require_once APP_PATH . '/Models/PageModel.php';
require_once APP_PATH . '/Models/MenuModel.php';
require_once APP_PATH . '/Models/MediaModel.php';
require_once APP_PATH . '/Models/RoleModel.php';
require_once APP_PATH . '/Models/PageSectionModel.php';

// Public controllers
require_once APP_PATH . '/Controllers/HomeController.php';
require_once APP_PATH . '/Controllers/AboutController.php';
require_once APP_PATH . '/Controllers/ServicesController.php';
require_once APP_PATH . '/Controllers/ProjectsController.php';
require_once APP_PATH . '/Controllers/PaintsController.php';
require_once APP_PATH . '/Controllers/GalleryController.php';
require_once APP_PATH . '/Controllers/ContactController.php';
require_once APP_PATH . '/Controllers/PageController.php';
require_once APP_PATH . '/Controllers/SeoPublicController.php';

// Admin controllers
require_once APP_PATH . '/Controllers/Admin/AuthController.php';
require_once APP_PATH . '/Controllers/Admin/DashboardController.php';
require_once APP_PATH . '/Controllers/Admin/SlidersController.php';
require_once APP_PATH . '/Controllers/Admin/ServicesController.php';
require_once APP_PATH . '/Controllers/Admin/ProjectsController.php';
require_once APP_PATH . '/Controllers/Admin/GalleryController.php';
require_once APP_PATH . '/Controllers/Admin/TeamController.php';
require_once APP_PATH . '/Controllers/Admin/TestimonialsController.php';
require_once APP_PATH . '/Controllers/Admin/PaintsController.php';
require_once APP_PATH . '/Controllers/Admin/MessagesController.php';
require_once APP_PATH . '/Controllers/Admin/SettingsController.php';
require_once APP_PATH . '/Controllers/Admin/IdentityController.php';
require_once APP_PATH . '/Controllers/Admin/SecurityController.php';
require_once APP_PATH . '/Controllers/Admin/LogsController.php';
require_once APP_PATH . '/Controllers/Admin/SeoController.php';
require_once APP_PATH . '/Controllers/Admin/PagesController.php';
require_once APP_PATH . '/Controllers/Admin/MenusController.php';
require_once APP_PATH . '/Controllers/Admin/MediaController.php';
require_once APP_PATH . '/Controllers/Admin/UsersController.php';
require_once APP_PATH . '/Controllers/Admin/RolesController.php';
require_once APP_PATH . '/Controllers/Admin/SectionsController.php';
require_once APP_PATH . '/Controllers/Admin/ProfileController.php';

// Boot session
Session::start();
Security::boot();

if (setting('maintenance_mode') === '1' && !Security::isAdminRequest()) {
    http_response_code(503);
    header('Retry-After: 3600');
    include VIEWS_PATH . '/errors/maintenance.php';
    exit;
}

require_once ROOT_PATH . '/config/routes.php';
