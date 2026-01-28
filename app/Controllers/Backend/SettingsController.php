<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use Myth\Auth\Password;

class SettingsController extends BaseController
{
    protected $auth;
    protected $config;
    protected $configApp;
    protected $model;
    protected $modelCompany;

    public function __construct()
    {
        $this->config = config('Backend');
        $this->configApp = config('App');
        $this->auth = service('authentication');
        $this->model = new SettingModel();
        $this->modelCompany = new CompanyModel();
    }
    public function index()
    {
        $this->model->getSettingSet();
        $settings = $this->model->findAll();
        
        return view('Backend/setting', [
            'config' => $this->config,
            'param' => [
                'action' => 'edit',
                'back' => false,
            ],
            'data' => json_decode(json_encode(array_column($settings, 'values', 'key'))),
            'form' => [
                'route' => $this->request->getPath(). "/0" ,
                'method' => 'PUT'
            ],
            'path' => $this->request->getPath(),
        ]);
    }

    /**
     * PUT/PATCH /user/(:num)
     */
    public function update($id = null)
    {
        $keys = [
            'site_name', 'company_email', 'company_phone', 'company_address',
            'meta_title', 'meta_keywords', 'meta_description',
            'og_title', 'og_type', 'og_description', 'canonical_url',
            'google_analytics_code', 'google_site_verification',
            'default_language', 'default_currency', 'default_timezone',
            'maintenance_message', 'backup_frequency',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_encryption', 'from_email', 'from_name',
            'password_min_length', 'session_timeout',
            // Checkboxes
            'maintenance_mode', 'auto_backup_enabled', 'require_password_strength', 'enable_mfa'
        ];
        
        // 1. Handle Standard Fields & Checkboxes
        foreach ($keys as $key) {
            // Special handling for checkboxes
            if (in_array($key, ['maintenance_mode', 'auto_backup_enabled', 'require_password_strength', 'enable_mfa'])) {
                $value = $this->request->getPost($key) ? '1' : '0';
            } else {
                $value = $this->request->getPost($key);
            }
            
            // Only update if key exists in DB (safety check, though model should handle it)
            $this->model->where('key', $key)->set(['values' => $value])->update();
        }

        // 2. Handle SMTP Password (only update if provided)
        $smtpPassword = $this->request->getPost('smtp_password');
        if (!empty($smtpPassword)) {
            $this->model->where('key', 'smtp_password')->set(['values' => $smtpPassword])->update();
        }

        // 3. Handle File Uploads
        $files = [
            'company_logo' => 'company_logo',
            'og_image_url' => 'og_image_url', 
            'file_statement_letter' => 'file_statement_letter'
        ];

        foreach ($files as $dbKey => $inputName) {
            $filePath = upload_file($inputName, 'file', $dbKey); // Assuming upload_file returns path or false/null
            if ($filePath) {
                $this->model->where('key', $dbKey)->set(['values' => $filePath])->update();
            }
        }

        return redirect()->to('/back-end/administrator/setting')->with('message-backend', 'Setting updated successfully');
    }
}
