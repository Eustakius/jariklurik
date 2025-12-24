<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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
    $routes->group('administrator', static function ($routes) {
        $routes->resource('user', ['controller' => 'Backend\Administrator\UserController', 'filter' => 'permission']);
        $routes->resource('role', ['controller' => 'Backend\Administrator\RoleController', 'filter' => 'permission']);
        $routes->resource('setting', ['controller' => 'Backend\SettingsController', 'filter' => 'permission']);
    });
    $routes->resource('my-profile', ['controller' => 'Backend\MyProfileController', 'filter' => 'permission']);
    $routes->resource('company', ['controller' => 'Backend\Application\CompanyController', 'filter' => 'permission']);
    $routes->get('job-vacancy/template-import', 'Backend\Application\JobVacancyController::templateImport', ['filter' => 'permission']);
    $routes->post('job-vacancy/import', 'Backend\Application\JobVacancyController::import', ['filter' => 'permission']);
    $routes->resource('job-vacancy', ['controller' => 'Backend\Application\JobVacancyController', 'filter' => 'permission']);
    $routes->put('applicant/mass-approve', 'Backend\Application\ApplicantController::massApprove', ['filter' => 'permission']);
    $routes->put('applicant/mass-process', 'Backend\Application\ApplicantController::massProcess', ['filter' => 'permission']);
    $routes->put('applicant/mass-reject', 'Backend\Application\ApplicantController::massReject', ['filter' => 'permission']);
    $routes->put('applicant/mass-revert', 'Backend\Application\ApplicantController::massRevert', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/process', 'Backend\Application\ApplicantController::process/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/approve', 'Backend\Application\ApplicantController::approve/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/reject', 'Backend\Application\ApplicantController::reject/$1', ['filter' => 'permission']);
    $routes->put('applicant/(:segment)/revert', 'Backend\Application\ApplicantController::revert/$1', ['filter' => 'permission']);

    $routes->resource('applicant', ['controller' => 'Backend\Application\ApplicantController', 'filter' => 'permission']);
    $routes->group('training', static function ($routes) {
        // Job Seeker Mass Actions
        $routes->put('job-seekers/mass-approve', 'Backend\Application\Training\JobSeekerController::massApprove', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-process', 'Backend\Application\Training\JobSeekerController::massProcess', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-reject', 'Backend\Application\Training\JobSeekerController::massReject', ['filter' => 'permission']);
        $routes->put('job-seekers/mass-revert', 'Backend\Application\Training\JobSeekerController::massRevert', ['filter' => 'permission']);
        
        $routes->put('job-seekers/(:segment)/approve', 'Backend\Application\Training\JobSeekerController::approve/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/reject', 'Backend\Application\Training\JobSeekerController::reject/$1', ['filter' => 'permission']);
        $routes->put('job-seekers/(:segment)/revert', 'Backend\Application\Training\JobSeekerController::revert/$1', ['filter' => 'permission']);
        $routes->resource('job-seekers', ['controller' => 'Backend\Application\Training\JobSeekerController', 'filter' => 'permission']);
        
        // Purna PMI Mass Actions
        $routes->put('purna-pmi/mass-approve', 'Backend\Application\Training\PurnaPmiController::massApprove', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-process', 'Backend\Application\Training\PurnaPmiController::massProcess', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-reject', 'Backend\Application\Training\PurnaPmiController::massReject', ['filter' => 'permission']);
        $routes->put('purna-pmi/mass-revert', 'Backend\Application\Training\PurnaPmiController::massRevert', ['filter' => 'permission']);

        $routes->put('purna-pmi/(:segment)/approve', 'Backend\Application\Training\PurnaPmiController::approve/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/reject', 'Backend\Application\Training\PurnaPmiController::reject/$1', ['filter' => 'permission']);
        $routes->put('purna-pmi/(:segment)/revert', 'Backend\Application\Training\PurnaPmiController::revert/$1', ['filter' => 'permission']);
        $routes->resource('purna-pmi', ['controller' => 'Backend\Application\Training\PurnaPmiController', 'filter' => 'permission']);
        
        $routes->put('training-type/mass-delete', 'Backend\Application\Training\TrainingTypeController::massDelete', ['filter' => 'permission']);
        $routes->resource('training-type', ['controller' => 'Backend\Application\Training\TrainingTypeController', 'filter' => 'permission']);
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
        });
    });
});
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    $routes->get('job-vacancy', 'Api\JobVacancyController::dataListFrontend');
    $routes->group('company', static function ($routes) {
        $routes->get('autocomplate', 'Api\CompanyController::autocomplate');
    });
    $routes->group('country', static function ($routes) {
        $routes->get('autocomplate', 'Api\CountryController::autocomplate');
    });
});
$routes->group('submit', ['filter' => 'post'], function ($routes) {
    $routes->post('job-seeker', 'Api\JobSeekerController::create');
    $routes->post('purna-pmi', 'Api\PurnaPmiController::create');
    $routes->post('applicant', 'Api\ApplicantController::create');
});
$routes->group('api', function ($routes) {
    $routes->get('corn-job-vacancy', 'Api\CronController::emailqueuejobvacancy');
});
$routes->get('cv/(:any)', 'FilePreviewController::CV/$1', ['filter' => 'auth']);
$routes->get('statement-letter/(:any)', 'FilePreviewController::statementLetter/$1', ['filter' => 'auth']);
$routes->get('stamp-passport-imigrasi/(:any)', 'FilePreviewController::stampPassportImigrasi/$1', ['filter' => 'auth']);
$routes->get('captcha', 'CaptchaController::index');
$routes->get('/(:any)', 'PageController::index/$1');
// $routes->get('image/(:any)', 'ImageController::show/$1');
