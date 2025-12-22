<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Frontend extends BaseConfig
{
    public string $viewLayout = 'layout';

    public $sections = [
        'lowongan-kerja' => [
            [
                "id" =>  1,
                "name" => "main-banner"
            ],
            [
                "id" =>  2,
                "name" => "home-tabs"
            ],
            [
                "id" =>  3,
                "name" => "job-vacancy-list"
            ]
        ],
        'daftar-kepelatihan' => [
            [
                "id" =>  1,
                "name" => "main-banner"
            ],
            [
                "id" =>  2,
                "name" => "home-tabs"
            ],
            [
                "id" =>  3,
                "name" => "form-training-register-job-seeker",
                "master" => [
                    ['key' => 'training_type'],
                    ['key' => 'file_sample']
                ]
            ]
        ],
        'daftar-kepelatihan-pencari-kerja' => [
            [
                "id" =>  1,
                "name" => "main-banner"
            ],
            [
                "id" =>  2,
                "name" => "home-tabs"
            ],
            [
                "id" =>  3,
                "name" => "form-training-register-job-seeker",
                "master" => [
                    ['key' => 'training_type'],
                    ['key' => 'file_sample']
                ]
            ]
        ],
        'daftar-kepelatihan-purna-pmi' => [
            [
                "id" =>  1,
                "name" => "main-banner"
            ],
            [
                "id" =>  2,
                "name" => "home-tabs"
            ],
            [
                "id" =>  3,
                "name" => "form-training-register-purna-pmi",
                "master" => [
                    ['key' => 'training_type']
                ]
            ]
        ],
        'detail-job-vacancy' => [
            [
                "id" =>  1,
                "name" => "second-banner"
            ],
            [
                "id" =>  2,
                "name" => "content"
            ]
        ],
        'thank-you-registered' => [
            [
                "id" =>  1,
                "name" => "second-banner"
            ],
            [
                "id" =>  2,
                "name" => "notification",
                "data" => [
                    "wording" => [
                        "icon" => "/icon/jariklurik-mesage.png",
                        "button" => "Kembali"
                    ],       
                ]
            ]
        ]
    ];
}
