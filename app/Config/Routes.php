<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Short URL redirect route (must be before other routes)
$routes->get('s/(:any)', 'ShortUrlController::redirect/$1');

$routes->group('back-end', static function ($routes) {
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
});

// Explicit 2FA Routes (Top Level Priority)
$routes->get('back-end/2fa/setup', '\App\Controllers\Backend\TwoFactorController::setup', ['filter' => 'auth']);
$routes->post('back-end/2fa/enable', '\App\Controllers\Backend\TwoFactorController::enable', ['filter' => 'auth']);
$routes->get('back-end/2fa/reset', '\App\Controllers\Backend\TwoFactorController::resetSetup', ['filter' => 'auth']);
$routes->get('back-end/2fa/login', '\App\Controllers\Backend\TwoFactorController::login', ['filter' => 'auth']);
$routes->post('back-end/2fa/verify', '\App\Controllers\Backend\TwoFactorController::verify', ['filter' => 'auth']);

$routes->group('back-end', ['filter' => 'auth'], static function ($routes) {
    $routes->get('logout', 'AuthController::logout', ['as' => 'logout']);
});

$routes->group('back-end', ['filter' => ['auth', '2fa']], static function ($routes) {
    $routes->get('', 'Backend\DashboardController::index');
    $routes->get('/', 'Backend\DashboardController::index');
    $routes->get('dashboard', 'Backend\DashboardController::index');
    $routes->get('dashboard', 'Backend\DashboardController::index');
    
    // Security Dashboard
    $routes->get('security', 'Backend\SecurityDashboardController::index');
    
    $routes->group('administrator', static function ($routes) {
        // User Routes
        $routes->get('user', 'Backend\Administrator\UserController::index', ['filter' => 'permission']);
        $routes->get('user/new', 'Backend\Administrator\UserController::new', ['filter' => 'permission']);
        $routes->post('user', 'Backend\Administrator\UserController::create', ['filter' => 'permission']);
        $routes->get('user/(:segment)/edit', 'Backend\Administrator\UserController::edit/$1', ['filter' => 'permission']);
        $routes->get('user/(:segment)', 'Backend\Administrator\UserController::show/$1', ['filter' => 'permission']);
        $routes->put('user/(:segment)', 'Backend\Administrator\UserController::update/$1', ['filter' => 'permission']);
        $routes->patch('user/(:segment)', 'Backend\Administrator\UserController::update/$1', ['filter' => 'permission']);
        $routes->delete('user/(:segment)', 'Backend\Administrator\UserController::delete/$1', ['filter' => 'permission']);

        // Role Routes
        $routes->get('role', 'Backend\Administrator\RoleController::index', ['filter' => 'permission']);
        $routes->get('role/new', 'Backend\Administrator\RoleController::new', ['filter' => 'permission']);
        $routes->post('role', 'Backend\Administrator\RoleController::create', ['filter' => 'permission']);
        $routes->get('role/(:segment)/edit', 'Backend\Administrator\RoleController::edit/$1', ['filter' => 'permission']);
        $routes->get('role/(:segment)', 'Backend\Administrator\RoleController::show/$1', ['filter' => 'permission']);
        $routes->put('role/(:segment)', 'Backend\Administrator\RoleController::update/$1', ['filter' => 'permission']);
        $routes->patch('role/(:segment)', 'Backend\Administrator\RoleController::update/$1', ['filter' => 'permission']);
        $routes->delete('role/(:segment)', 'Backend\Administrator\RoleController::delete/$1', ['filter' => 'permission']);

        // Setting Routes
        $routes->get('setting', 'Backend\SettingsController::index', ['filter' => 'permission']);
        $routes->get('setting/new', 'Backend\SettingsController::new', ['filter' => 'permission']);
        $routes->post('setting', 'Backend\SettingsController::create', ['filter' => 'permission']);
        $routes->get('setting/(:segment)/edit', 'Backend\SettingsController::edit/$1', ['filter' => 'permission']);
        $routes->get('setting/(:segment)', 'Backend\SettingsController::show/$1', ['filter' => 'permission']);
        $routes->put('setting/(:segment)', 'Backend\SettingsController::update/$1', ['filter' => 'permission']);
        $routes->patch('setting/(:segment)', 'Backend\SettingsController::update/$1', ['filter' => 'permission']);
        $routes->delete('setting/(:segment)', 'Backend\SettingsController::delete/$1', ['filter' => 'permission']);
    });
    // My Profile Routes
    // My Profile Routes
    $routes->get('my-profile', 'Backend\MyProfileController::index');
    $routes->get('my-profile/new', 'Backend\MyProfileController::new');
    $routes->post('my-profile', 'Backend\MyProfileController::create');
    $routes->get('my-profile/(:segment)/edit', 'Backend\MyProfileController::edit/$1');
    $routes->get('my-profile/(:segment)', 'Backend\MyProfileController::show/$1');
    $routes->put('my-profile/(:segment)', 'Backend\MyProfileController::update/$1');
    $routes->patch('my-profile/(:segment)', 'Backend\MyProfileController::update/$1');
    $routes->delete('my-profile/(:segment)', 'Backend\MyProfileController::delete/$1');

    // Company Routes
    $routes->get('company', 'Backend\Application\CompanyController::index', ['filter' => 'permission']);
    $routes->get('company/new', 'Backend\Application\CompanyController::new', ['filter' => 'permission']);
    $routes->post('company', 'Backend\Application\CompanyController::create', ['filter' => 'permission']);
    $routes->get('company/(:segment)/edit', 'Backend\Application\CompanyController::edit/$1', ['filter' => 'permission']);
    $routes->get('company/(:segment)', 'Backend\Application\CompanyController::show/$1', ['filter' => 'permission']);
    $routes->put('company/(:segment)', 'Backend\Application\CompanyController::update/$1', ['filter' => 'permission']);
    $routes->patch('company/(:segment)', 'Backend\Application\CompanyController::update/$1', ['filter' => 'permission']);
    $routes->delete('company/(:segment)', 'Backend\Application\CompanyController::delete/$1', ['filter' => 'permission']);

    // Job Vacancy Routes
    $routes->get('job-vacancy/template-import', 'Backend\Application\JobVacancyController::templateImport', ['filter' => 'permission']);
    $routes->post('job-vacancy/import', 'Backend\Application\JobVacancyController::import', ['filter' => 'permission']);
    $routes->post('job-vacancy/send-whatsapp', 'Backend\Application\JobVacancyController::sendWhatsapp', ['filter' => 'auth']);
    
    $routes->get('job-vacancy', 'Backend\Application\JobVacancyController::index', ['filter' => 'permission']);
    $routes->get('job-vacancy/new', 'Backend\Application\JobVacancyController::new', ['filter' => 'permission']);
    $routes->post('job-vacancy', 'Backend\Application\JobVacancyController::create', ['filter' => 'permission']);
    $routes->get('job-vacancy/(:segment)/edit', 'Backend\Application\JobVacancyController::edit/$1', ['filter' => 'permission']);
    $routes->get('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::show/$1', ['filter' => 'permission']);
    $routes->put('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::update/$1', ['filter' => 'permission']);
    $routes->patch('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::update/$1', ['filter' => 'permission']);
    $routes->delete('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::delete/$1', ['filter' => 'permission']);
    $routes->put('applicant/mass-approve', 'Backend\Application\ApplicantController::massApprove', ['filter' => 'permission']);
    $routes->put('applicant/mass-process', 'Backend\Application\ApplicantController::massProcess', ['filter' => 'permission']);
    $routes->put('applicant/mass-reject', 'Backend\Application\ApplicantController::massReject', ['filter' => 'permission']);
    $routes->put('applicant/mass-revert', 'Backend\Application\ApplicantController::massRevert', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/process', 'Backend\Application\ApplicantController::process/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/approve', 'Backend\Application\ApplicantController::approve/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/reject', 'Backend\Application\ApplicantController::reject/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/revert', 'Backend\Application\ApplicantController::revert/$1', ['filter' => 'permission']);

    // Applicant Routes
    $routes->get('applicant', 'Backend\Application\ApplicantController::index', ['filter' => 'permission']);
    $routes->get('applicant/new', 'Backend\Application\ApplicantController::new', ['filter' => 'permission']);
    $routes->post('applicant', 'Backend\Application\ApplicantController::create', ['filter' => 'permission']);
    $routes->get('applicant/(:segment)/edit', 'Backend\Application\ApplicantController::edit/$1', ['filter' => 'permission']);
    $routes->get('applicant/(:segment)', 'Backend\Application\ApplicantController::show/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)', 'Backend\Application\ApplicantController::update/$1', ['filter' => 'permission']);
    $routes->patch('applicant/(:segment)', 'Backend\Application\ApplicantController::update/$1', ['filter' => 'permission']);
    $routes->delete('applicant/(:segment)', 'Backend\Application\ApplicantController::delete/$1', ['filter' => 'permission']);
    $routes->group('training', static function ($routes) {
        // Job Seeker Mass Actions
        $routes->put('job-seekers/mass-approve', 'Backend\Application\Training\JobSeekerController::massApprove', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-process', 'Backend\Application\Training\JobSeekerController::massProcess', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-reject', 'Backend\Application\Training\JobSeekerController::massReject', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-revert', 'Backend\Application\Training\JobSeekerController::massRevert', ['filter' => 'permission']);
        
        $routes->put('job-seekers/(:segment)/approve', 'Backend\Application\Training\JobSeekerController::approve/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/reject', 'Backend\Application\Training\JobSeekerController::reject/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/revert', 'Backend\Application\Training\JobSeekerController::revert/$1', ['filter' => 'permission']);
        // Job Seekers Routes
        $routes->get('job-seekers', 'Backend\Application\Training\JobSeekerController::index', ['filter' => 'permission']);
        $routes->get('job-seekers/new', 'Backend\Application\Training\JobSeekerController::new', ['filter' => 'permission']);
        $routes->post('job-seekers', 'Backend\Application\Training\JobSeekerController::create', ['filter' => 'permission']);
        $routes->get('job-seekers/(:segment)/edit', 'Backend\Application\Training\JobSeekerController::edit/$1', ['filter' => 'permission']);
        $routes->get('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::show/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::update/$1', ['filter' => 'permission']);
        $routes->patch('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::update/$1', ['filter' => 'permission']);
        $routes->delete('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::delete/$1', ['filter' => 'permission']);
        
        // Purna PMI Mass Actions
        $routes->put('purna-pmi/mass-approve', 'Backend\Application\Training\PurnaPmiController::massApprove', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-process', 'Backend\Application\Training\PurnaPmiController::massProcess', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-reject', 'Backend\Application\Training\PurnaPmiController::massReject', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-revert', 'Backend\Application\Training\PurnaPmiController::massRevert', ['filter' => 'permission']);

        $routes->put('purna-pmi/(:segment)/approve', 'Backend\Application\Training\PurnaPmiController::approve/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/reject', 'Backend\Application\Training\PurnaPmiController::reject/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/revert', 'Backend\Application\Training\PurnaPmiController::revert/$1', ['filter' => 'permission']);
        
        // Purna PMI Routes
        $routes->get('purna-pmi', 'Backend\Application\Training\PurnaPmiController::index', ['filter' => 'permission']);
        $routes->get('purna-pmi/new', 'Backend\Application\Training\PurnaPmiController::new', ['filter' => 'permission']);
        $routes->post('purna-pmi', 'Backend\Application\Training\PurnaPmiController::create', ['filter' => 'permission']);
        $routes->get('purna-pmi/(:segment)/edit', 'Backend\Application\Training\PurnaPmiController::edit/$1', ['filter' => 'permission']);
        $routes->get('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::show/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::update/$1', ['filter' => 'permission']);
        $routes->patch('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::update/$1', ['filter' => 'permission']);
        $routes->delete('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::delete/$1', ['filter' => 'permission']);
        
        $routes->put('training-type/mass-delete', 'Backend\Application\Training\TrainingTypeController::massDelete', ['filter' => 'permission']);
        
        // Training Type Routes
        $routes->get('training-type', 'Backend\Application\Training\TrainingTypeController::index', ['filter' => 'permission']);
        $routes->get('training-type/new', 'Backend\Application\Training\TrainingTypeController::new', ['filter' => 'permission']);
        $routes->post('training-type', 'Backend\Application\Training\TrainingTypeController::create', ['filter' => 'permission']);
        $routes->get('training-type/(:segment)/edit', 'Backend\Application\Training\TrainingTypeController::edit/$1', ['filter' => 'permission']);
        $routes->get('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::show/$1', ['filter' => 'permission']);
        $routes->put('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::update/$1', ['filter' => 'permission']);
        $routes->patch('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::update/$1', ['filter' => 'permission']);
        $routes->delete('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::delete/$1', ['filter' => 'permission']);
    });
    $routes->group('api', ['filter' => 'jwt'], function ($routes) {
        $routes->group('user', static function ($routes) {
            $routes->get('data-table', 'Api\UserController::dataTable');
        });
        $routes->group('role', static function ($routes) {
            $routes->get('data-table', 'Api\RoleController::dataTable');
        });
        $routes->group('company', static function ($routes) {
            $routes->get('data-table', 'Api\CompanyController::dataTable');
            $routes->get('select', 'Api\CompanyController::select2');
        });
        $routes->group('country', static function ($routes) {
            $routes->get('select', 'Api\CountryController::select2');
        });
        $routes->group('job-vacancy', static function ($routes) {
            $routes->get('data-table', 'Api\JobVacancyController::dataTable');
            $routes->put('data-table-update', 'Api\JobVacancyController::dataTableUpdate');
            $routes->get('select', 'Api\JobVacancyController::select2');
            $routes->get('(:num)', 'Api\JobVacancyController::show/$1');
        });
        $routes->group('applicant', static function ($routes) {
            $routes->get('data-table-new', 'Api\ApplicantController::dataTableNew');
            $routes->get('data-table-processed', 'Api\ApplicantController::dataTableProcessed');
            $routes->get('data-table-approved', 'Api\ApplicantController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\ApplicantController::dataTableRejected');
        });
        $routes->group('job-seeker', static function ($routes) {
            $routes->get('data-table-new', 'Api\JobSeekerController::dataTableNew');
            $routes->get('data-table-approved', 'Api\JobSeekerController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\JobSeekerController::dataTableRejected');
        });
        $routes->group('purna-pmi', static function ($routes) {
            $routes->get('data-table-new', 'Api\PurnaPmiController::dataTableNew');
            $routes->get('data-table-approved', 'Api\PurnaPmiController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\PurnaPmiController::dataTableRejected');
        });
        $routes->group('training-type', static function ($routes) {
            $routes->get('data-table', 'Api\TrainingTypeController::dataTable');
            $routes->get('select', 'Api\TrainingTypeController::select2');
            $routes->get('(:num)', 'Api\TrainingTypeController::show/$1');
        });
    });
});
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    // Protected API routes (if any remain)
});

// Public API Routes (No JWT required)
$routes->group('api', function ($routes) {
    $routes->get('job-vacancy', 'Api\JobVacancyController::dataListFrontend');
    $routes->group('company', static function ($routes) {
        $routes->get('autocomplate', 'Api\CompanyController::autocomplate');
    });
    $routes->group('country', static function ($routes) {
        $routes->get('autocomplate', 'Api\CountryController::autocomplate');
    });

    
    // Security Dashboard
    $routes->get('security', 'Backend\SecurityDashboardController::index');
    
    $routes->group('administrator', static function ($routes) {
        // User Routes
        $routes->get('user', 'Backend\Administrator\UserController::index', ['filter' => 'permission']);
        $routes->get('user/new', 'Backend\Administrator\UserController::new', ['filter' => 'permission']);
        $routes->post('user', 'Backend\Administrator\UserController::create', ['filter' => 'permission']);
        $routes->get('user/(:segment)/edit', 'Backend\Administrator\UserController::edit/$1', ['filter' => 'permission']);
        $routes->get('user/(:segment)', 'Backend\Administrator\UserController::show/$1', ['filter' => 'permission']);
        $routes->put('user/(:segment)', 'Backend\Administrator\UserController::update/$1', ['filter' => 'permission']);
        $routes->patch('user/(:segment)', 'Backend\Administrator\UserController::update/$1', ['filter' => 'permission']);
        $routes->delete('user/(:segment)', 'Backend\Administrator\UserController::delete/$1', ['filter' => 'permission']);

        // Role Routes
        $routes->get('role', 'Backend\Administrator\RoleController::index', ['filter' => 'permission']);
        $routes->get('role/new', 'Backend\Administrator\RoleController::new', ['filter' => 'permission']);
        $routes->post('role', 'Backend\Administrator\RoleController::create', ['filter' => 'permission']);
        $routes->get('role/(:segment)/edit', 'Backend\Administrator\RoleController::edit/$1', ['filter' => 'permission']);
        $routes->get('role/(:segment)', 'Backend\Administrator\RoleController::show/$1', ['filter' => 'permission']);
        $routes->put('role/(:segment)', 'Backend\Administrator\RoleController::update/$1', ['filter' => 'permission']);
        $routes->patch('role/(:segment)', 'Backend\Administrator\RoleController::update/$1', ['filter' => 'permission']);
        $routes->delete('role/(:segment)', 'Backend\Administrator\RoleController::delete/$1', ['filter' => 'permission']);

        // Setting Routes
        $routes->get('setting', 'Backend\SettingsController::index', ['filter' => 'permission']);
        $routes->get('setting/new', 'Backend\SettingsController::new', ['filter' => 'permission']);
        $routes->post('setting', 'Backend\SettingsController::create', ['filter' => 'permission']);
        $routes->get('setting/(:segment)/edit', 'Backend\SettingsController::edit/$1', ['filter' => 'permission']);
        $routes->get('setting/(:segment)', 'Backend\SettingsController::show/$1', ['filter' => 'permission']);
        $routes->put('setting/(:segment)', 'Backend\SettingsController::update/$1', ['filter' => 'permission']);
        $routes->patch('setting/(:segment)', 'Backend\SettingsController::update/$1', ['filter' => 'permission']);
        $routes->delete('setting/(:segment)', 'Backend\SettingsController::delete/$1', ['filter' => 'permission']);
    });
    // My Profile Routes
    // My Profile Routes
    $routes->get('my-profile', 'Backend\MyProfileController::index');
    $routes->get('my-profile/new', 'Backend\MyProfileController::new');
    $routes->post('my-profile', 'Backend\MyProfileController::create');
    $routes->get('my-profile/(:segment)/edit', 'Backend\MyProfileController::edit/$1');
    $routes->get('my-profile/(:segment)', 'Backend\MyProfileController::show/$1');
    $routes->put('my-profile/(:segment)', 'Backend\MyProfileController::update/$1');
    $routes->patch('my-profile/(:segment)', 'Backend\MyProfileController::update/$1');
    $routes->delete('my-profile/(:segment)', 'Backend\MyProfileController::delete/$1');

    // Company Routes
    $routes->get('company', 'Backend\Application\CompanyController::index', ['filter' => 'permission']);
    $routes->get('company/new', 'Backend\Application\CompanyController::new', ['filter' => 'permission']);
    $routes->post('company', 'Backend\Application\CompanyController::create', ['filter' => 'permission']);
    $routes->get('company/(:segment)/edit', 'Backend\Application\CompanyController::edit/$1', ['filter' => 'permission']);
    $routes->get('company/(:segment)', 'Backend\Application\CompanyController::show/$1', ['filter' => 'permission']);
    $routes->put('company/(:segment)', 'Backend\Application\CompanyController::update/$1', ['filter' => 'permission']);
    $routes->patch('company/(:segment)', 'Backend\Application\CompanyController::update/$1', ['filter' => 'permission']);
    $routes->delete('company/(:segment)', 'Backend\Application\CompanyController::delete/$1', ['filter' => 'permission']);

    // Job Vacancy Routes
    $routes->get('job-vacancy/template-import', 'Backend\Application\JobVacancyController::templateImport', ['filter' => 'permission']);
    $routes->post('job-vacancy/import', 'Backend\Application\JobVacancyController::import', ['filter' => 'permission']);
    $routes->post('job-vacancy/send-whatsapp', 'Backend\Application\JobVacancyController::sendWhatsapp', ['filter' => 'auth']);
    
    $routes->get('job-vacancy', 'Backend\Application\JobVacancyController::index', ['filter' => 'permission']);
    $routes->get('job-vacancy/new', 'Backend\Application\JobVacancyController::new', ['filter' => 'permission']);
    $routes->post('job-vacancy', 'Backend\Application\JobVacancyController::create', ['filter' => 'permission']);
    $routes->get('job-vacancy/(:segment)/edit', 'Backend\Application\JobVacancyController::edit/$1', ['filter' => 'permission']);
    $routes->get('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::show/$1', ['filter' => 'permission']);
    $routes->put('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::update/$1', ['filter' => 'permission']);
    $routes->patch('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::update/$1', ['filter' => 'permission']);
    $routes->delete('job-vacancy/(:segment)', 'Backend\Application\JobVacancyController::delete/$1', ['filter' => 'permission']);
    $routes->put('applicant/mass-approve', 'Backend\Application\ApplicantController::massApprove', ['filter' => 'permission']);
    $routes->put('applicant/mass-process', 'Backend\Application\ApplicantController::massProcess', ['filter' => 'permission']);
    $routes->put('applicant/mass-reject', 'Backend\Application\ApplicantController::massReject', ['filter' => 'permission']);
    $routes->put('applicant/mass-revert', 'Backend\Application\ApplicantController::massRevert', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/process', 'Backend\Application\ApplicantController::process/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/approve', 'Backend\Application\ApplicantController::approve/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/reject', 'Backend\Application\ApplicantController::reject/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/revert', 'Backend\Application\ApplicantController::revert/$1', ['filter' => 'permission']);

    // Applicant Routes
    $routes->get('applicant', 'Backend\Application\ApplicantController::index', ['filter' => 'permission']);
    $routes->get('applicant/new', 'Backend\Application\ApplicantController::new', ['filter' => 'permission']);
    $routes->post('applicant', 'Backend\Application\ApplicantController::create', ['filter' => 'permission']);
    $routes->get('applicant/(:segment)/edit', 'Backend\Application\ApplicantController::edit/$1', ['filter' => 'permission']);
    $routes->get('applicant/(:segment)', 'Backend\Application\ApplicantController::show/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)', 'Backend\Application\ApplicantController::update/$1', ['filter' => 'permission']);
    $routes->patch('applicant/(:segment)', 'Backend\Application\ApplicantController::update/$1', ['filter' => 'permission']);
    $routes->delete('applicant/(:segment)', 'Backend\Application\ApplicantController::delete/$1', ['filter' => 'permission']);
    $routes->group('training', static function ($routes) {
        // Job Seeker Mass Actions
        $routes->put('job-seekers/mass-approve', 'Backend\Application\Training\JobSeekerController::massApprove', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-process', 'Backend\Application\Training\JobSeekerController::massProcess', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-reject', 'Backend\Application\Training\JobSeekerController::massReject', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-revert', 'Backend\Application\Training\JobSeekerController::massRevert', ['filter' => 'permission']);
        
        $routes->put('job-seekers/(:segment)/approve', 'Backend\Application\Training\JobSeekerController::approve/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/reject', 'Backend\Application\Training\JobSeekerController::reject/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/revert', 'Backend\Application\Training\JobSeekerController::revert/$1', ['filter' => 'permission']);
        // Job Seekers Routes
        $routes->get('job-seekers', 'Backend\Application\Training\JobSeekerController::index', ['filter' => 'permission']);
        $routes->get('job-seekers/new', 'Backend\Application\Training\JobSeekerController::new', ['filter' => 'permission']);
        $routes->post('job-seekers', 'Backend\Application\Training\JobSeekerController::create', ['filter' => 'permission']);
        $routes->get('job-seekers/(:segment)/edit', 'Backend\Application\Training\JobSeekerController::edit/$1', ['filter' => 'permission']);
        $routes->get('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::show/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::update/$1', ['filter' => 'permission']);
        $routes->patch('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::update/$1', ['filter' => 'permission']);
        $routes->delete('job-seekers/(:segment)', 'Backend\Application\Training\JobSeekerController::delete/$1', ['filter' => 'permission']);
        
        // Purna PMI Mass Actions
        $routes->put('purna-pmi/mass-approve', 'Backend\Application\Training\PurnaPmiController::massApprove', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-process', 'Backend\Application\Training\PurnaPmiController::massProcess', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-reject', 'Backend\Application\Training\PurnaPmiController::massReject', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-revert', 'Backend\Application\Training\PurnaPmiController::massRevert', ['filter' => 'permission']);

        $routes->put('purna-pmi/(:segment)/approve', 'Backend\Application\Training\PurnaPmiController::approve/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/reject', 'Backend\Application\Training\PurnaPmiController::reject/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/revert', 'Backend\Application\Training\PurnaPmiController::revert/$1', ['filter' => 'permission']);
        
        // Purna PMI Routes
        $routes->get('purna-pmi', 'Backend\Application\Training\PurnaPmiController::index', ['filter' => 'permission']);
        $routes->get('purna-pmi/new', 'Backend\Application\Training\PurnaPmiController::new', ['filter' => 'permission']);
        $routes->post('purna-pmi', 'Backend\Application\Training\PurnaPmiController::create', ['filter' => 'permission']);
        $routes->get('purna-pmi/(:segment)/edit', 'Backend\Application\Training\PurnaPmiController::edit/$1', ['filter' => 'permission']);
        $routes->get('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::show/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::update/$1', ['filter' => 'permission']);
        $routes->patch('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::update/$1', ['filter' => 'permission']);
        $routes->delete('purna-pmi/(:segment)', 'Backend\Application\Training\PurnaPmiController::delete/$1', ['filter' => 'permission']);
        
        $routes->put('training-type/mass-delete', 'Backend\Application\Training\TrainingTypeController::massDelete', ['filter' => 'permission']);
        
        // Training Type Routes
        $routes->get('training-type', 'Backend\Application\Training\TrainingTypeController::index', ['filter' => 'permission']);
        $routes->get('training-type/new', 'Backend\Application\Training\TrainingTypeController::new', ['filter' => 'permission']);
        $routes->post('training-type', 'Backend\Application\Training\TrainingTypeController::create', ['filter' => 'permission']);
        $routes->get('training-type/(:segment)/edit', 'Backend\Application\Training\TrainingTypeController::edit/$1', ['filter' => 'permission']);
        $routes->get('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::show/$1', ['filter' => 'permission']);
        $routes->put('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::update/$1', ['filter' => 'permission']);
        $routes->patch('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::update/$1', ['filter' => 'permission']);
        $routes->delete('training-type/(:segment)', 'Backend\Application\Training\TrainingTypeController::delete/$1', ['filter' => 'permission']);
    });
    $routes->group('api', ['filter' => 'jwt'], function ($routes) {
        $routes->group('user', static function ($routes) {
            $routes->get('data-table', 'Api\UserController::dataTable');
        });
        $routes->group('role', static function ($routes) {
            $routes->get('data-table', 'Api\RoleController::dataTable');
        });
        $routes->group('company', static function ($routes) {
            $routes->get('data-table', 'Api\CompanyController::dataTable');
            $routes->get('select', 'Api\CompanyController::select2');
        });
        $routes->group('country', static function ($routes) {
            $routes->get('select', 'Api\CountryController::select2');
        });
        $routes->group('job-vacancy', static function ($routes) {
            $routes->get('data-table', 'Api\JobVacancyController::dataTable');
            $routes->put('data-table-update', 'Api\JobVacancyController::dataTableUpdate');
            $routes->get('select', 'Api\JobVacancyController::select2');
            $routes->get('(:num)', 'Api\JobVacancyController::show/$1');
        });
        $routes->group('applicant', static function ($routes) {
            $routes->get('data-table-new', 'Api\ApplicantController::dataTableNew');
            $routes->get('data-table-processed', 'Api\ApplicantController::dataTableProcessed');
            $routes->get('data-table-approved', 'Api\ApplicantController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\ApplicantController::dataTableRejected');
        });
        $routes->group('job-seeker', static function ($routes) {
            $routes->get('data-table-new', 'Api\JobSeekerController::dataTableNew');
            $routes->get('data-table-approved', 'Api\JobSeekerController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\JobSeekerController::dataTableRejected');
        });
        $routes->group('purna-pmi', static function ($routes) {
            $routes->get('data-table-new', 'Api\PurnaPmiController::dataTableNew');
            $routes->get('data-table-approved', 'Api\PurnaPmiController::dataTableApproved');
            $routes->get('data-table-rejected', 'Api\PurnaPmiController::dataTableRejected');
        });
        $routes->group('training-type', static function ($routes) {
            $routes->get('data-table', 'Api\TrainingTypeController::dataTable');
            $routes->get('select', 'Api\TrainingTypeController::select2');
            $routes->get('(:num)', 'Api\TrainingTypeController::show/$1');
        });
    });
});
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    // Protected API routes (if any remain)
});

// Public API Routes (No JWT required)
$routes->group('api', function ($routes) {
    $routes->get('job-vacancy', 'Api\JobVacancyController::dataListFrontend');
    $routes->group('company', static function ($routes) {
        $routes->get('autocomplate', 'Api\CompanyController::autocomplate');
    });
    $routes->group('country', static function ($routes) {
        $routes->get('autocomplate', 'Api\CountryController::autocomplate');
    });
    
    // Security Dashboard API
    $routes->group('security', static function ($routes) {
        $routes->get('stats', 'Api\SecurityController::stats');
        $routes->get('logs', 'Api\SecurityController::logs');
        $routes->get('incidents', 'Api\SecurityController::incidents');
        $routes->post('forensics', 'Api\ForensicsController::collect');
        $routes->get('forensics', 'Api\ForensicsController::index');
        $routes->post('quick-scan', 'Api\SecurityController::quickScan');
        $routes->get('check-blacklist', 'Api\SecurityController::checkBlacklist');
        $routes->get('system-health', 'Api\SecurityController::systemHealth');
        $routes->get('public-activity', 'Api\SecurityController::publicActivity');
        $routes->get('blocked-ips', 'Api\SecurityController::blockedIps');
        $routes->delete('blocked-ips/(:num)', 'Api\SecurityController::unblockIp/$1');
    });
});
$routes->get('thank-you-registered', 'PageController::thankYou');


$routes->get('/', 'PageController::index');
$routes->get('captcha', 'CaptchaController::index');
$routes->get('(:any)', 'PageController::index/$1');
// $routes->get('image/(:any)', 'ImageController::show/$1');
