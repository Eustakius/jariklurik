<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Backend extends BaseConfig
{
    public string $viewLayout = 'Backend/layout';

    public $menus = [
        [
            'id' => 0,
            'parent_id' => null,
            'name' => 'Dashboard',
            'icon' => 'solar:home-smile-angle-outline',
            'is_group' => false,
            'url' => '/',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
            ]
        ],
        [
            'id' => 1,
            'parent_id' => null,
            'name' => 'Company',
            'icon' => 'mage:building-b',
            'is_group' => false,
            'url' => 'company',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 2,
            'parent_id' => null,
            'name' => 'Job Vacancy',
            'icon' => 'mage:stack',
            'is_group' => false,
            'url' => 'job-vacancy',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Import',
                    'name' => 'import'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
                [
                    'label' => 'Send WhatsApp',
                    'name' => 'send_whatsapp'
                ],
            ]
        ],
        [
            'id' => 3,
            'parent_id' => null,
            'name' => 'Training',
            'icon' => 'mage:star-moving',
            'is_group' => false,
            'url' => null,
            'type' => 'sidebar',
            'permissions' => []
        ],
        [
            'id' => 4,
            'parent_id' => 3,
            'name' => 'Job Seekers',
            'icon' => 'mage:scan-user',
            'is_group' => false,
            'url' => 'training/job-seekers',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Approve',
                    'name' => 'approve'
                ],
                [
                    'label' => 'Reject',
                    'name' => 'reject'
                ],
                [
                    'label' => 'Revert',
                    'name' => 'revert'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 5,
            'parent_id' => 3,
            'name' => 'Purna PMI',
            'icon' => 'mage:scan-user',
            'is_group' => false,
            'url' => 'training/purna-pmi',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Approve',
                    'name' => 'approve'
                ],
                [
                    'label' => 'Reject',
                    'name' => 'reject'
                ],
                [
                    'label' => 'Revert',
                    'name' => 'revert'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 6,
            'parent_id' => 3,
            'name' => 'Training Type',
            'icon' => 'material-symbols-light:model-training',
            'is_group' => false,
            'url' => 'training/training-type',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 7,
            'parent_id' => null,
            'name' => 'Applicant',
            'icon' => 'mage:scan-user',
            'is_group' => false,
            'url' => 'applicant',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Process',
                    'name' => 'process'
                ],
                [
                    'label' => 'Approve',
                    'name' => 'approve'
                ],
                [
                    'label' => 'Reject',
                    'name' => 'reject'
                ],
                [
                    'label' => 'Revert',
                    'name' => 'revert'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 99,
            'parent_id' => null,
            'name' => 'Administrator',
            'icon' => null,
            'is_group' => true,
            'url' => null,
            'type' => 'sidebar',
            'permissions' => []
        ],
        [
            'id' => 91,
            'parent_id' => 99,
            'name' => 'User',
            'icon' => 'mage:user',
            'is_group' => false,
            'url' => 'administrator/user',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 92,
            'parent_id' => 99,
            'name' => 'Role',
            'icon' => 'mage:checklist',
            'is_group' => false,
            'url' => 'administrator/role',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 93,
            'parent_id' => 99,
            'name' => 'Setting',
            'icon' => 'mage:settings',
            'is_group' => false,
            'url' => 'administrator/setting',
            'type' => 'sidebar',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Create',
                    'name' => 'create'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
                [
                    'label' => 'Delete',
                    'name' => 'delete'
                ],
                [
                    'label' => 'Restore',
                    'name' => 'restore'
                ],
            ]
        ],
        [
            'id' => 100,
            'parent_id' => null,
            'name' => 'My Profile',
            'icon' => 'solar:user-linear',
            'is_group' => false,
            'url' => '/my-profile',
            'type' => 'top',
            'permissions' => [
                [
                    'label' => 'View',
                    'name' => 'view'
                ],
                [
                    'label' => 'Detail',
                    'name' => 'detail'
                ],
                [
                    'label' => 'Update',
                    'name' => 'update'
                ],
            ]
        ]
    ];

    public $settings = [
        // Global Site Configurations - Company Information
        [
            'key' => "site_name",
            'name' => "Site Name",
            'type' => "text"
        ],
        [
            'key' => "company_logo",
            'name' => "Company Logo",
            'type' => "file"
        ],
        [
            'key' => "company_email",
            'name' => "Company Email",
            'type' => "email"
        ],
        [
            'key' => "company_phone",
            'name' => "Company Phone",
            'type' => "text"
        ],
        [
            'key' => "company_address",
            'name' => "Company Address",
            'type' => "textarea"
        ],

        // SEO & Metadata
        [
            'key' => "meta_title",
            'name' => "Meta Title",
            'type' => "text"
        ],
        [
            'key' => "meta_description",
            'name' => "Meta Description",
            'type' => "textarea"
        ],
        [
            'key' => "meta_keywords",
            'name' => "Meta Keywords",
            'type' => "text"
        ],
        [
            'key' => "og_title",
            'name' => "OG Title",
            'type' => "text"
        ],
        [
            'key' => "og_description",
            'name' => "OG Description",
            'type' => "textarea"
        ],
        [
            'key' => "og_image_url",
            'name' => "OG Image URL",
            'type' => "file"
        ],
        [
            'key' => "og_type",
            'name' => "OG Type",
            'type' => "text"
        ],
        [
            'key' => "canonical_url",
            'name' => "Canonical URL",
            'type' => "text"
        ],
        [
            'key' => "google_analytics_code",
            'name' => "Google Analytics Code",
            'type' => "textarea"
        ],
        [
            'key' => "google_site_verification",
            'name' => "Google Site Verification",
            'type' => "text"
        ],

        // Localization
        [
            'key' => "default_language",
            'name' => "Default Language",
            'type' => "select"
        ],
        [
            'key' => "default_currency",
            'name' => "Default Currency",
            'type' => "text"
        ],
        [
            'key' => "default_timezone",
            'name' => "Default Timezone",
            'type' => "select"
        ],

        // System & Maintenance
        [
            'key' => "maintenance_mode",
            'name' => "Maintenance Mode",
            'type' => "toggle"
        ],
        [
            'key' => "maintenance_message",
            'name' => "Maintenance Message",
            'type' => "textarea"
        ],

        // Email Server (SMTP)
        [
            'key' => "smtp_host",
            'name' => "SMTP Host",
            'type' => "text"
        ],
        [
            'key' => "smtp_port",
            'name' => "SMTP Port",
            'type' => "number"
        ],
        [
            'key' => "smtp_username",
            'name' => "SMTP Username",
            'type' => "text"
        ],
        [
            'key' => "smtp_password",
            'name' => "SMTP Password",
            'type' => "password"
        ],
        [
            'key' => "smtp_encryption",
            'name' => "SMTP Encryption",
            'type' => "select"
        ],
        [
            'key' => "from_email",
            'name' => "From Email Address",
            'type' => "email"
        ],
        [
            'key' => "from_name",
            'name' => "From Name",
            'type' => "text"
        ],

        // Security & Authentication
        [
            'key' => "require_password_strength",
            'name' => "Require Strong Passwords",
            'type' => "toggle"
        ],
        [
            'key' => "password_min_length",
            'name' => "Minimum Password Length",
            'type' => "number"
        ],
        [
            'key' => "enable_mfa",
            'name' => "Enable Multi-Factor Authentication",
            'type' => "toggle"
        ],
        [
            'key' => "session_timeout",
            'name' => "Session Timeout (minutes)",
            'type' => "number"
        ],

        // Backup & Export
        [
            'key' => "auto_backup_enabled",
            'name' => "Enable Automatic Backups",
            'type' => "toggle"
        ],
        [
            'key' => "backup_frequency",
            'name' => "Backup Frequency",
            'type' => "select"
        ],

        // Legacy/Additional
        [
            'key' => "file_statement_letter",
            'name' => "File Statement Letter",
            'type' => "file"
        ],

        // WhatsApp Gateway
        [
            'key' => "whatsapp_token",
            'name' => "WhatsApp Token",
            'type' => "password",
            'values' => "EAAxdPVo72WkBQQNGZBx6YoRIjh5gCp017yX9RdFuOwCfgzEiuMlAZC0VZA2DGxjRWFMuxod344wvmMAd9wb4Npb0cfScZAdtgUBPh0fULILQsIR1qZCvdTq20tPWMwTIBpc667i0AQ1BFnqr1keTinaJ0P1JTvGXW1YxupvEdHdGj6YqrjssBb470j3TJJAZDZD"
        ],
        [
            'key' => "whatsapp_phone_number_id",
            'name' => "WhatsApp Phone Number ID",
            'type' => "text",
            'values' => "6281353199745"
        ],
        [
            'key' => "whatsapp_business_account_id",
            'name' => "WhatsApp Business Account ID",
            'type' => "text"
        ]
    ];

    public $permissions = [
        [
            'label' => 'View',
            'name' => 'view'
        ],
        [
            'label' => 'Create',
            'name' => 'create'
        ],
        [
            'label' => 'Detail',
            'name' => 'detail'
        ],
        [
            'label' => 'Update',
            'name' => 'update'
        ],
        [
            'label' => 'Delete',
            'name' => 'delete'
        ],
        [
            'label' => 'Approve',
            'name' => 'approve'
        ],
        [
            'label' => 'Reject',
            'name' => 'reject'
        ],
        [
            'label' => 'Revert',
            'name' => 'revert'
        ],
        [
            'label' => 'Restore',
            'name' => 'restore'
        ],
    ];

    public $educationLevel = [
        [
            'label' => 'SD',
            'value' => 'SD'
        ],
        [
            'label' => 'SMP',
            'value' => 'SMP'
        ],
        [
            'label' => 'SMA',
            'value' => 'SMA'
        ],
        [
            'label' => 'SMK',
            'value' => 'SMK'
        ],
        [
            'label' => 'Diploma 1',
            'value' => 'D1'
        ],
        [
            'label' => 'Diploma 2',
            'value' => 'D2'
        ],
        [
            'label' => 'Diploma 3',
            'value' => 'D3'
        ],
        [
            'label' => 'Diploma 4',
            'value' => 'D4'
        ],
        [
            'label' => 'Sarjana (S1)',
            'value' => 'S1'
        ],
        [
            'label' => 'Magister (S2)',
            'value' => 'S2'
        ],
        [
            'label' => 'Doktor (S3)',
            'value' => 'S3'
        ],
    ];
    
    public $status = [
        [
            'label' => 1,
            'value' => 'Active'
        ],
        [
            'label' => 0,
            'value' => 'Inactive'
        ],
        [
            'label' => -1,
            'value' => 'Delete'
        ],
        [
            'label' => 9,
            'value' => 'Request'
        ],
    ];
}
